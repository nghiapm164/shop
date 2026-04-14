<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Display the main shop page with filtering component.
     */
    public function index(): View
    {
        return view('shop.index');
    }
}
