<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // This points to resources/views/home/index.blade.php
        return view('home.index');
    }
}