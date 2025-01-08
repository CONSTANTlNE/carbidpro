<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\State;
use Illuminate\Http\Request;

class PortsController extends Controller
{
    public function index(){

        $ports = Port::all();
        $states=State::all();
        return view('pages.ports', compact('ports', 'states'));
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
            'state_id' => 'required|exists:states,id',
        ]);

        $port = new Port();
        $port->name=$request->name;
        $port->price=$request->price;
        $port->state_id=$request->state_id;
        $port->save();

        return back();
    }

    public function update(Request $request){

        $validated = $request->validate([
            'id'=>'required|exists:ports,id',
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
            'state_id' => 'required|exists:states,id',
        ]);

        $port=Port::findOrFail($request->id);
        $port->name=$request->name;
        $port->price=$request->price;
        $port->state_id=$request->state_id;
        $port->save();

        return back();

    }

    public function destroy(Request $request){

        $validate=$request->validate([
            'id'=>'required|exists:ports,id',
        ]);

        $type=Port::findOrFail($request->id);
        $type->delete();

        return back();
    }
}
