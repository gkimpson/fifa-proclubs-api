<?php

namespace App\Console\Commands;

use App\Http\Controllers\StatsController;
use App\Models\Club;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetClubsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubsapi:clubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Clubs data from the API';

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
        $this->info('Collecting club data from the EA API');
        $response = $controller->clubsInfo($request);
        $clubs = Club::formatData($response);
        // dd($clubs);

        // $u = User::find(1);
        // $props = [
        //     'clubId' => '1741008',
        //     'platform' => 'ps4'
        // ];
        // $u->properties = json_encode($props);
        // $u->save();
        // dd($u);
        // $inserted = Club::insertUniqueClub($clubs);
        // $this->info("{$inserted} unique clubs into the database");        
        return 0;
    }
}
