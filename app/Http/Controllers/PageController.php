<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function hello()
    {
        $shopName = 'WAVE SHOP';

        return view('hello', ['shopName' => $shopName]);
    }
}