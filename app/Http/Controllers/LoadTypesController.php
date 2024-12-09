<?php

namespace App\Http\Controllers;

use App\Models\LoadType;
use Illuminate\Http\Request;

class LoadTypesController extends Controller
{
    public function index(){

        $loadTypes = LoadType::all();
     return view('pages.load-types', compact('loadTypes'));
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
        ]);

        $loadType = new LoadType();
        $loadType->name=$request->name;
        $loadType->price=$request->price;
        $loadType->save();

        return back();
    }

    public function update(Request $request){

        $validated = $request->validate([
            'id'=>'required|exists:load_types,id',
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
        ]);

        $type=LoadType::findOrFail($request->id);
        $type->name=$request->name;
        $type->price=$request->price;
        $type->save();

        return back();

    }

    public function destroy(Request $request){

        $validate=$request->validate([
            'id'=>'required|exists:load_types,id',
        ]);

        $type=LoadType::findOrFail($request->id);
        $type->delete();

        return back();
    }
}
