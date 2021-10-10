<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MyDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // do a quick refresh on the results...
        // Artisan::call('proclubsapi:matches n'); // if param 1 is 'y' then we show output
        
        $data = [
            'results' => Result::getResults($user->properties),
            'myClubId' => (int)$user->properties['clubId']
        ];

        $a = $data['results'][0];
        // dump($a->match_data[310718]->mom);
        // dump($a->properties);
        // dump($a->properties[]);   
        // dump($data['results'][0]->toArray());
        return view('dashboard', $data);
    }

}
