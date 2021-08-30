<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class MyDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'results' => Result::getResults($user->properties)
        ];

        return view('dashboard', $data);
    }

}
