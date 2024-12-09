<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;

class PortsController extends Controller
{
    public function index(){

        $ports = Port::all();
        return view('pages.ports', compact('ports'));
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
        ]);

        $port = new Port();
        $port->name=$request->name;
        $port->price=$request->price;
        $port->save();

        return back();
    }

    public function update(Request $request){

        $validated = $request->validate([
            'id'=>'required|exists:load_types,id',
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
        ]);

        $port=Port::findOrFail($request->id);
        $port->name=$request->name;
        $port->price=$request->price;
        $port->save();

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
