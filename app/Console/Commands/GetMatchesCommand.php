<?php

namespace App\Console\Commands;

use App\Http\Controllers\StatsController;
use App\Models\Result;
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
    protected $signature = 'matches:get';

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
        $controller = new StatsController();
        $this->info('Collecting matches data from the EA API');
        $response = $controller->matchStats($request);
        $results = Result::formatData($response);
        $inserted = Result::insertUniqueMatches($results);
        $this->info("{$inserted} results into the database");
        return 0;
    }
}
