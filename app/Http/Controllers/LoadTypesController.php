<?php

namespace App\Http\Controllers;

use App\Models\LoadType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoadTypesController extends Controller
{
    public function index()
    {
        $loadTypes = LoadType::all();


        return view('pages.load-types', compact('loadTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
            'icon'  => 'required',
        ]);

        $icon = $request->icon;
        $icon->storeAs('', $icon->getClientOriginalName(), 'public');

//        dd($icon->getClientOriginalName());


        $loadType        = new LoadType();
        $loadType->name  = $request->name;
        $loadType->price = $request->price;
        $loadType->icon  = $icon->getClientOriginalName();
        $loadType->save();


        return back();
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'    => 'required|exists:load_types,id',
            'name'  => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
        ]);

        $type        = LoadType::findOrFail($request->id);
        $type->name  = $request->name;
        $type->price = $request->price;
        if ($request->hasFile('icon')) {
            $fileName = $type->icon;
            Storage::disk('public')->delete($fileName);

            $icon = $request->icon;
            $icon->storeAs('', $icon->getClientOriginalName(), 'public');

            $type->icon = $icon->getClientOriginalName();
        }
        $type->save();

        return back();
    }

    public function destroy(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|exists:load_types,id',
        ]);

        $type = LoadType::findOrFail($request->id);

        $fileName = $type->icon;
        Storage::disk('public')->delete($fileName);

        $type->delete();

        return back();
    }
}
