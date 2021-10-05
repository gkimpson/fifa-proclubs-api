<?php

namespace App\Console\Commands;

use App\Http\Controllers\StatsController;
use App\Models\Result;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetMatchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:matches {output=y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest matches from the API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        try {
            $showOutput = ($this->argument('output') === 'y') ? true : false;
            $controller = new StatsController();
            $results = [];
            $properties = User::pluck('properties')->unique();

            // $spinner = $this->spinner($properties->count());
            // $spinner->setMessage('Loading...');
            // $spinner->start();

            $this->info("{$properties->count()} user clubId/platform combinations found");

            $x = 0;
            foreach ($properties as $property) {
                $this->info("Collecting matches data for - {$property['platform']}/{$property['clubId']} : [{$x}]");
                
                $params = [
                    'matchType' => 'gameType13',
                    'platform' => $property['platform'],
                    'clubIds' => $property['clubId'] 
                ];
    
                $response = $controller->matchStats($request, $params);
                $results_1 = Result::formatData($response);
                $params = [
                    'matchType' => 'gameType9',
                    'platform' => $property['platform'],
                    'clubIds' => $property['clubId']
                ];
    
                $response = $controller->matchStats($request, $params);
                $results_2 = Result::formatData($response);
                $results = array_merge($results_1->toArray(), $results_2->toArray());
                $total = count($results);
                $this->info("Total matches found : {$total}");
                $inserted = Result::insertUniqueMatches($results, $property['platform'], $showOutput);
                $this->info("{$inserted} unique results into the database");
                // $spinner->advance();
                $x++;
            }
    
            // $spinner->finish();
            return 0;
        } catch (\Exception $e) {
            // do some logging...
            return false;
        }
    }
}
