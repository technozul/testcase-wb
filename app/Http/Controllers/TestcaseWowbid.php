<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestcaseWowbid extends Controller
{
    // page home
    public function index()
    {
        return view('home');
    }

    // page test case #1
    public function testcase_1()
    {
        return view('testcase_1');
    }

    // page test case #2
    public function testcase_2()
    {
        return view('testcase_2');
    }

    // page about
    public function about()
    {
        return view('about');
    }
}
