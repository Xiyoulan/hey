<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StaticPageController extends Controller {

    public function home() {
         $feed_items = [];
        if(Auth::check()){
         $feed_items=Auth::user()->feed()->paginate(10);  
        }
        return view('static_pages.home', compact('feed_items'));
    }

    public function about() {
        return view('static_pages.about');
    }

    public function help() {
        return view('static_pages.help');
    }

}
