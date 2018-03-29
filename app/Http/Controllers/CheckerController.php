<?php

namespace RefChecker\Http\Controllers;

use RefChecker\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use RefChecker\Ticket;
use RefChecker\Jobs\RefChecker;
use DB;
use Storage;
use Auth;
use File;

/**
* 
*/
class CheckerController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checker() {
        return view('checker');
    }

    /**
     * Handle upload
     * Uploaded file is stored in the server
     * @TODO: remove unused worker
     */
    public function handleUpload(Request $request)
    {
        $user_id = Auth::user()->id;

        // $original_file_name: file name as it is uploaded
        // $file_name: file name on disk. Syntax: user_id.original_file_name
        
        // File input
        if ($request->hasFile('doc')) 
        {
            $doc = $request->doc;

            // Check if the user had uploaded a file to correct
            $original_file_name = DB::table('user_file_result')->where('user_id', $user_id)->pluck('original_file_name')->first();
            if ($original_file_name == '' || $original_file_name == null) 
            {
                // If it doesn't, just insert
                DB::insert('INSERT INTO user_file_result (user_id, original_file_name) VALUES (?, ?)', [$user_id, $doc->getClientOriginalName()]);
            } else 
            {
                // If it does, update and delete the old file
                DB::update('UPDATE user_file_result SET original_file_name = ?, result = NULL WHERE user_id = ?', [$doc->getClientOriginalName(), $user_id]);
                File::delete('upload/' . $user_id . '.' . $original_file_name);
            }

            $file_name = $user_id . '.' . $doc->getClientOriginalName();
            $doc->move('upload', $file_name);

            $original_file_name = DB::select('SELECT original_file_name FROM user_file_result WHERE user_id = ?', [$user_id]);

            dispatch(new RefChecker(Auth::user()));

            return redirect('results');
        }

        // Text input
        if ($request->has('list-references')) 
        {
            $content = $request->input('list-references');
            // The input text is stored inside a file for reference processing
            // Just pretend that the original file name is the user id
            $file_name = $user_id . '.' . $user_id;
            file_put_contents($file_name, $content);

            // Check if the user had typed a list of reference to correct
            $original_file_name = DB::table('user_file_result')->where('user_id', $user_id)->pluck('original_file_name')->first();
            if ($original_file_name == '' || $original_file_name == null) 
            {
                // If it doesn't, just insert
                DB::insert('INSERT INTO user_file_result (user_id, original_file_name) VALUES (?, ?)', [$user_id, $user_id]);
            } else 
            {
                // If it does, update and delete the old file
                DB::update('UPDATE user_file_result SET original_file_name = ?, result = NULL WHERE user_id = ?', [$user_id, $user_id]);
                File::delete('upload/' . $file_name);
            }

            // I hate php
            rename($file_name, 'upload/' . $file_name);

            dispatch(new RefChecker(Auth::user()));

            return redirect('results');
        }
    }

    public function testGet()
    {
        return Storage::download('test.rtf');
    }

    // public function testPost(Request $request)
    // {
    //     $this->validate($request, )
    // }

    public function testID($name)
    {
        return Auth::user()->id;
    }
}