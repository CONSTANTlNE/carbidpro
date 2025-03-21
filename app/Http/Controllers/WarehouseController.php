<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index(){

        $warehouses=Warehouse::all();

        return view('pages.warehouses',compact('warehouses'));
    }

    public function create(Request $request){

        $request->validate([
           'name'=>'required|string|max:255'
        ]);

        $warehouse=new Warehouse();
        $warehouse->name=$request->name;
        $warehouse->save();

        return back();

    }

    public function update(Request $request){

        $warehouse=Warehouse::find($request->id);

        if (!$warehouse){
            return back()->with('error','warehouse not found');
        }

        $request->validate([
            'name'=>'required|string|max:255'
        ]);

        $warehouse->name=$request->name;
        $warehouse->save();

        return back();

    }

    public function delete(Request $request){

        $warehouse=Warehouse::find($request->id);

        if (!$warehouse){
            return back()->with('error','warehouse not found');
        }

        $warehouse->delete();

        return back();
    }

    public function activate(Request $request){

        $warehouse=Warehouse::find($request->id);

        if (!$warehouse){
            return back()->with('error','warehouse not found');
        }


        if ($warehouse->is_active==1) {
            $warehouse->is_active=0;
            $warehouse->save();
        } else {
            $warehouse->is_active=1;
            $warehouse->save();
        }

        return back();



    }

}
