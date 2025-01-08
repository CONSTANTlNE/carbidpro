<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::with('media')->get();

        return view('pages.sitesSettings.services', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'required',
        ]);

        $service = Service::create([
            'title'        => $request->title,
            'button_title' => $request->button_title,
            'button_url'   => $request->button_url,
            'is_active'    => 1,
        ]);

        $service->addMedia($request->file('image'))->toMediaCollection('services');

        Cache::forget('servicesTranslateen');
        Cache::forget('servicesTranslateru');

        return back();
    }

    public function activate(Request $request)
    {
        $service = Service::findOrFail($request->id);

        if($service->is_active == 1){
            $service->is_active = 0;
        }else{
            $service->is_active = 1;
        }
        $service->save();


        Cache::forget('servicesTranslateen');
        Cache::forget('servicesTranslateru');

        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string',

        ]);

        $service = Service::findOrFail($request->id);

        $service->title        = $request->title;
        $service->button_title = $request->button_title;
        $service->button_url   = $request->button_url;
        $service->save();


        if ($request->hasFile('image')) {
            $service->media()->delete();
            $service->addMedia($request->file('image'))->toMediaCollection('services');
        }

        Cache::forget('servicesTranslateen');
        Cache::forget('servicesTranslateru');

        return back();
    }

    public function delete(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->media()->delete();
        $service->delete();

        Cache::forget('servicesTranslateen');
        Cache::forget('servicesTranslateru');

        return back();
    }
}
