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
     */
    public function handleUpload(Request $request)
    {
        if ($request->hasFile('doc')) {
            $doc = $request->doc;
            $user_id = Auth::user()->id;

            // Check if the user had uploaded a file to check
            $filename = DB::table('user_file_result')->where('user_id', $user_id)->pluck('original_file_name')->first();
            if ($filename == '' || $filename == null) 
            {
                // If it doesn't, just insert
                DB::insert('INSERT INTO user_file_result (user_id, original_file_name) VALUES (?, ?)', [$user_id, $doc->getClientOriginalName()]);
            } else
            {
                // If it does, update and delete the old file
                DB::update('UPDATE user_file_result SET original_file_name = ?, result = NULL WHERE user_id = ?', [$doc->getClientOriginalName(), $user_id]);
                File::delete('upload/' . $user_id . '.' . $filename);
            }

            $doc->move('upload', $user_id . '.' . $doc->getClientOriginalName());

            $filename = DB::select('SELECT original_file_name FROM user_file_result WHERE user_id = ?', [$user_id]);

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