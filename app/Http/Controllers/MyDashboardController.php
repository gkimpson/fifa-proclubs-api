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
        Artisan::call('proclubsapi:matches '); // if param 1 is 'y' then we show output

        $data = [
            'results' => Result::getResults($user->properties)
        ];

        return view('dashboard', $data);
    }

}
