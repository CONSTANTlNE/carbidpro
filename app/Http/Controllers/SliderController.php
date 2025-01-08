<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SliderController extends Controller
{
    public function index()
    {
        $sliders=Slider::with('media')->get();

        return view('pages.sitesSettings.sliders', compact('sliders'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title'=>'required',
            'image'=>'required',
        ]);

        $slider=Slider::create([
            'title'=>$request->title,
            'is_active'=>1
        ]);

        $slider->addMediaFromRequest('image')->toMediaCollection('slider');

        Cache::forget('sliderTranslateru');
        Cache::forget('sliderTranslateen');

        return back();
    }

    public function activate(Request $request)
    {
        $slider=Slider::where('id', $request->id)->first();

        if($slider->is_active==1){
            $slider->is_active=0;
        }else{
            $slider->is_active=1;

        }
        $slider->save();

        Cache::forget('sliderTranslateru');
        Cache::forget('sliderTranslateen');

        return back();
    }

    public function update(Request $request)
    {

        $slider=Slider::where('id', $request->id)->first();

        $slider->title=$request->title;
        $slider->save();


        if ($request->hasFile('image')) {
            $slider->media()->delete();
            $slider->addMedia($request->file('image'))->toMediaCollection('slider');
        }


        Cache::forget('sliderTranslateru');
        Cache::forget('sliderTranslateen');
        return back();
    }

    public function delete(Request $request)
    {

        $slider=Slider::where('id', $request->id)->first();
        $slider->media()->delete();
        $slider->delete();

        Cache::forget('sliderTranslateru');
        Cache::forget('sliderTranslateen');
        return back();
    }

}
