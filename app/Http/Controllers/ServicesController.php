<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(){

        return view('pages.sitesSettings.services', compact('sliders'));

    }

    public function store(Request $request){

    }

    public function update(Request $request){

    }

    public function delete(Request $request){

    }
}
