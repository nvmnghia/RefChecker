<?php

namespace RefChecker\Http\Controllers;

use RefChecker\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use RefChecker\Ticket;
use DB;
use Storage;
use Auth;
use File;

/**
* 
*/
class ResultController extends Controller
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

    public function viewResults() {
    	return view('result');
    }

    public function getResult() {
        $result = DB::table('user_file_result')->where('user_id', Auth::user()->id)->pluck('result')->first();
        echo $result;
        
        // Test JSON
     //    $json = '{"original":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"APA":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"BibTeX":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"MLA":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"IEEE":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"ACM":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"Chicago":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"],"CSE":["a","jkadf sdfjk \n skdjbv","fjasn ajdfk"]}';

    	// // Result
     //    echo $json;
    }
}