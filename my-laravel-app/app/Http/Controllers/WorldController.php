<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WorldController extends Controller
{
    public function index()
    {
        return view('world');
    }
}
