<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function adminIndex()
    {
        return view('admin');
    }
}
