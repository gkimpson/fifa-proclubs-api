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
    protected $signature = 'proclubsapi:matches';

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
            $controller = new StatsController();

            $results = [];
            $properties = User::pluck('properties')->unique();
            $this->info("{$properties->count()} user clubId/platform combinations found");
            
            $x = 0;
            foreach ($properties as $property) {
                $this->info("Collecting matches data for - {$property->platform}/{$property->clubId} : [{$x}]");
                
                $params = [
                    'matchType' => 'gameType13',
                    'platform' => $property->platform,
                    'clubIds' => $property->clubId 
                ];
    
                $response = $controller->matchStats($request, $params);
                $results_1 = Result::formatData($response);
                $params = [
                    'matchType' => 'gameType9',
                    'platform' => $property->platform,
                    'clubIds' => $property->clubId 
                ];
    
                $response = $controller->matchStats($request, $params);
                $results_2 = Result::formatData($response);
    
                $results = array_merge($results_1->toArray(), $results_2->toArray());
                $inserted = Result::insertUniqueMatches($results, $property->platform);
                $this->info("{$inserted} unique results into the database");
                $x++;
            }
    
            return 0;
        } catch (\Exception $e) {
            // do some logging...
            return false;
        }
    }

    public function OLDhandle(Request $request)
    {
        $controller = new StatsController();
        $this->info('Collecting matches data from the EA API');
        $response = $controller->matchStats($request);
        $results = Result::formatData($response);
        $inserted = Result::insertUniqueMatches($results);

        $this->info("{$inserted} unique results into the database");
        return 0;
    }    
}
