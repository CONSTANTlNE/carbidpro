<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Session;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ContactController extends Controller
{
    public function index(SettingsService $settings)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }
        return view('frontend.pages.contact', compact('tr', 'settings'));
    }
}
