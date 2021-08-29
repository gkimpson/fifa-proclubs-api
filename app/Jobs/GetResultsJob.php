<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GetResultsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all the users in the db that have a valid properties with a clubId & platform
        $properties = User::all()->pluck('properties')->unique();
        $properties->each(function ($item, $key) {
            
        });
        
        dd('--');

        // only get UNIQUE clubId/platform combinations

        // loop through each one to grab the matches data

        // insert this match into the db
    }
}
