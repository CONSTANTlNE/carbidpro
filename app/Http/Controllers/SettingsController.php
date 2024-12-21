<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();


        return view('pages.sitesSettings.settings', compact('settings'));
    }

    public function store(Request $request)
    {
//        dd($request);

        $setting=new Setting();
        $setting->key=$request->key;
        $setting->label=$request->label;
        $setting->value=$request->setting_value;
        $setting->save();

        Cache::forget('settings');
        Cache::forever('settings', Setting::all());

         return back();
    }

    public function updateHtmx(Request $request) {

        $setting = Setting::where('key', $request->key)->first();
        $key = $request->key;

        return view('pages.htmx.htmxSettings', compact('setting', 'key'));
    }

    public function update(Request $request) {


        $setting = Setting::where('key', $request->key)->first();
        $setting->key=$request->new_key;
        $setting->label=$request->new_label;
        $setting->value=$request->setting_value;
        $setting->save();

        Cache::forget('settings');
        Cache::forever('settings', Setting::all());

        return back();
    }

    public function delete(Request $request) {}
}
