<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::all();

        return view('pages.Data.countries', compact('countries'));
    }

    public function store(Request $request)
    {
        $country       = new Country();
        $country->name = $request->name;
        $country->save();

        return back();
    }

    public function update(Request $request)
    {
        $country       = Country::find($request->id);
        $country->name = $request->name;
        $country->save();

        return back();
    }

    public function delete(Request $request)
    {
        $country = Country::find($request->id);
        $country->delete();

        return back();
    }

    public function activate(Request $request)
    {
        $country = Country::find($request->id);

        if($country->is_active==1){
            $country->is_active=0;
        } else {
            $country->is_active=1;
        }
        $country->save();

        return back();
    }

}
