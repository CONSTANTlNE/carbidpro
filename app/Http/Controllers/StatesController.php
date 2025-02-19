<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    public function index(){

        $states=State::all();

        return view('pages.states',compact('states'));
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required|string|max:2'
        ]);

        State::create([
            'name'=>$request->name,
            'is_active'=>true
        ]);

        return back();

    }

    public function update(Request $request){
        $request->validate([
            'name'=>'required|string|max:2'
        ]);


        $state = State::findOrFail($request->id);
        if ($request->status!=='on'){
            $state->is_active=false;
        } else {
            $state->is_active=true;
        }
        $state->name=$request->name;
        $state->save();
        return back();

    }

    public function delete(Request $request){
        $request->validate([
            'id'=>'required|exists:states,id'
        ]);

        State::find($request->id)->delete();

        return back();

    }
}
