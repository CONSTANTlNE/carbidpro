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



    // ====== Pages ======

    public function about(Request $request) {




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

        $about=Setting::where('key', 'about')->first();

        if(Session::get('locale') === 'en'){

            if (Cache::has('translatedAboutEN')) {
                $translated = Cache::get('translatedAboutEN');
            } else {
                Cache::forever('translatedAboutEN', $tr->translate($about->value) );
                $translated = Cache::get('translatedAboutEN');
            }
        }

        if(Session::get('locale') === 'ru'){

            if (Cache::has('translatedAboutRU')) {
                $translated = Cache::get('translatedAboutRU');
            } else {
                Cache::forever('translatedAboutRU', $tr->translate($about->value) );
                $translated = Cache::get('translatedAboutRU');
            }
        }



        return view('frontend.pages.about', compact('tr','translated'));

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
                Cache::forever('translatedTermsEN', $tr->translate($terms->value) );
                $translated = Cache::get('translatedTermsEN');
            }
        }

        if(Session::get('locale') === 'ru'){

            if (Cache::has('translatedTermsRU')) {
                $translated = Cache::get('translatedTermsRU');
            } else {
                Cache::forever('translatedTermsRU', $tr->translate($terms->value) );
                $translated = Cache::get('translatedTermsRU');
            }
        }


        return view('frontend.pages.terms', compact('translated', 'settings','tr'));

    }

    public function contact(Request $request)
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



        $settings = Setting::all();


        return view('frontend.pages.contact', compact( 'settings', 'tr'));
    }

    public function announcements(Request $request)
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


        $translated = array();

        if(!Cache::get('translatedAnnouncements'.Session::get('locale'))){
            $announcements = Announcement::where('is_active', 1)->get();

            foreach($announcements as $announcement){

                $translated[$announcement->id] = [
                    'title' => $tr->translate($announcement->title),
                    'content' => $tr->translate($announcement->content),
                    'date' => $announcement->date
                ];
            }
            Cache::forever('translatedAnnouncements'.Session::get('locale'), $translated);
        }


        return view('frontend.pages.announcement', compact(  'tr'));
    }

}
