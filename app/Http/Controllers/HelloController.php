<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class HelloController extends Controller
{
    public function welcome(){
        // return "hello, this is from the hellocontroller";
        return view('Home.hello', [
            'name' => 'Ethan',
            'surname' => 'Amour'
        ]);
    }
}
