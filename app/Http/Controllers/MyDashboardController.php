<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MyDashboardController extends BaseController
{
    public function __construct()
    {
        
        
    }

    public function index()
    {
        $user = auth()->user();
        // do a quick refresh on the results...
        // Artisan::call('proclubsapi:matches n'); // if param 1 is 'y' then we show output
        
        $data = [
            'results' => Result::getResults($user->properties),
            'myClubId' => (int)$user->properties['clubId']
        ];

        // dd($row);
        // dd($data['myClubId']);
        // dump($data['results'][0]->properties['clubs'][0]['name']);
        return view('dashboard', $data);
    }

    public function cup()
    {
        $user = auth()->user();
        $data = [];        
        return view('matches', $data);
    }

    public function league()
    {
        $user = auth()->user();
        $data = [];        
        return view('matches', $data);
    }    

    public function squad()
    {
        $user = auth()->user();
        $data = [];
        return view('squad', $data);
    }

    public function club(Request $request)
    {
        $user = auth()->user();
        $controller = new StatsController();

        $data = [
            'myClubId' => (int)$user->properties['clubId'],
            'club' => $controller->clubsInfo($request),
            'seasonStats' => $controller->seasonStats($request)
        ];
        
        // dump($data);
        return view('club', $data);
    }       

    public function form()
    {
        $user = auth()->user();
        $data = [];
        return view('form', $data);
    } 
    
    public function rank()
    {
        $user = auth()->user();
        $data = [];
        return view('rank', $data);
    }     

    public function media()
    {
        $user = auth()->user();
        $platform = $user->properties['platform'];
        $clubId = $user->properties['clubId'];
        $media = Result::getMedia($platform, $clubId);
        
        $data = [
            'media' => $media['pagination'],
            'formatted' => $media['formatted']
        ];

        return view('media', $data);
    }         

}
