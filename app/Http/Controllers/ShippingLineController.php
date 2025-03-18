<?php

namespace App\Http\Controllers;

use App\Models\ShippingLine;
use Illuminate\Http\Request;

class ShippingLineController extends Controller
{
    public function index()
    {
        $shippingLines = ShippingLine::all();

        return view('pages.shipping_lines', compact('shippingLines'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tracking_url'=>'nullable|string',

        ]);

        ShippingLine::create([
            'name' => $request->name,
            'tracking_url' => $request->tracking_url,
        ]);


        return back();
    }

    public function update(Request $request)
    {
        $line = ShippingLine::find($request->id);

        $request->validate([
            'name' => 'required|max:255',
            'tracking_url'=>'nullable|string',
        ]);

        if ($line) {
            $line->name = $request->name;
            $line->tracking_url = $request->tracking_url;
            $line->save();

            return back();
        }

        return back()->with('error', 'Line not found');
    }

    public function delete(Request $request)
    {
        $line = ShippingLine::find($request->id);

        if ($line) {
            $line->delete();

            return back();
        }
        return back()->with('error', 'Line not found');
    }


    public function activate(Request $request){

        $line=ShippingLine::find($request->id);

        if(!$line){
            return back()->with('error', 'Title not found');
        }

        if ($line->is_active==1) {
            $line->is_active=0;
            $line->save();
        } else {
            $line->is_active=1;
            $line->save();
        }

        return back();
    }
}
