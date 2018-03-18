<?php

namespace RefChecker\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class RefChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        define('PATH_TO_JAVA_EXEC', 'D:\Information Extraction\RefChecker\pdfExtract.jar');

        $filename = $this->user->id . '.' . DB::table('user_file_result')->where('user_id', $this->user->id)->pluck('original_file_name')->first();
        $filepath = base_path() . '\public\upload\\' . $filename;

        $array_output = array();
        $return_val = -20;
        $result = exec('java -jar "' . PATH_TO_JAVA_EXEC . '" "' . $filepath . '"', $array_output, $return_val);    // Extra " for path with whitespaces
        
        $fp = fopen('vardump.txt', 'w');
        fwrite($fp, serialize($array_output[5]));
        fclose($fp);

        $result = $filepath . ' as a result';
        DB::update('UPDATE user_file_result SET result = ? WHERE user_id = ?', [$result, $this->user->id]);
    }
}
