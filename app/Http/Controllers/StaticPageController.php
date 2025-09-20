<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function snk(){
        return view('static.snk');
    }
    public function faq(){
        return view('static.faq');
    }
    public function kebijakanPrivasi(){
        return view('static.kebijakan-privasi');
    }
    public function disclaimer(){
        return view('static.disclaimer');
    }
    public function tentangKami(){
        return view('static.tentang-kami');
    }
}
