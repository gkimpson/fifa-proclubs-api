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

        // var_dump($data['results'][0]->properties);  // no error
        // echo '<br>';
        // var_dump(json_decode($data['results'][0]->properties)); // no error
        // dd( json_decode($data['results'][0]) );
        // var_dump(json_decode($data['results'][0]->properties)->clubs[1]->name);
        // dd($x->clubs);
        // exit;
        // $r = json_decode($data['results'][0]->properties)->clubs;
        // dump($r);
        return view('dashboard', $data);
    }

}
