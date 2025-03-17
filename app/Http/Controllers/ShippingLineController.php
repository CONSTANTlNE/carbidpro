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
        ]);

        ShippingLine::create([
            'name' => $request->name,
        ]);


        return back();
    }

    public function update(Request $request)
    {
        $line = ShippingLine::find($request->id);

        $request->validate([
            'name' => 'required|max:255',
        ]);

        if ($line) {
            $line->name = $request->name;
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
}
