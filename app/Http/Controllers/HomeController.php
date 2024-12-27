<?php


namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides   = Slider::with('media')->where('is_active', 1)->get();
        $services = Service::with('media')->where('is_active', 1)->get();

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

        $announcement = Announcement::where('is_active', 1)->first();

        return view('frontend.pages.home', compact('slides', 'services', 'announcement', 'tr'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // ====== Pages ======

    public function about(Request $request) {

        $settings=Setting::where('key', 'about')->first();

//        dd($settings);

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



        return view('frontend.pages.about', compact('settings','tr'));

    }

    public function terms(Request $request) {

        $settings=Setting::where('key', 'terms')->first();

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

        $terms=Setting::where('key', 'terms')->first();


        if(Session::get('locale') === 'en'){

            if (Cache::has('translatedTermsEN')) {
                $translated = Cache::get('translatedTermsEN');
            } else {
                Cache::put('translatedTermsEN', $tr->translate($terms->value) );
                $translated = Cache::get('translatedTermsEN');
            }
        }

        if(Session::get('locale') === 'ru'){

            if (Cache::has('translatedTermsRU')) {
                $translated = Cache::get('translatedTermsRU');
            } else {
                Cache::put('translatedTermsRU', $tr->translate($terms->value) );
                $translated = Cache::get('translatedTermsRU');
            }
        }


        return view('frontend.pages.terms', compact('translated', 'settings','tr'));

    }

}
