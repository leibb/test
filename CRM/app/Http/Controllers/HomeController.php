<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('home.dashboard');
    }
}
