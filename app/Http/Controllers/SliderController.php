<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders=Slider::with('media')->get();

        return view('pages.sitesSettings.sliders', compact('sliders'));
    }

    public function store(Request $request)
    {
        $sliders=Slider::with('media')->get();

        return back();
    }

    public function update(Request $request)
    {
        $sliders=Slider::with('media')->get();

        return back();
    }

    public function delete(Request $request)
    {
        $sliders=Slider::with('media')->get();

        return back();
    }

}
