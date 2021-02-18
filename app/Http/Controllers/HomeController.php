<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function log(Request $request)
    {
        $request->session('verify', $request->session()->get('verify'));
        return view('log', ['page' => 'Log', 'verify' => $request->session()->get('verify')]);
    }

    public function test(Request $request)
    {
        $request->session('verify', $request->session()->get('verify'));
        return view('test', ['page' => 'Test', 'verify' => $request->session()->get('verify')]);
    }
}
