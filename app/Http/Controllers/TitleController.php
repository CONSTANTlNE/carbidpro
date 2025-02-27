<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TitleController extends Controller
{
    public function index(Request $request){
        $perpage=$request->input('perpage', 50);

        if(cache::get('titles_count')){
            $titles_count=cache::get('titles_count');
        }else{
            $titles_count=Title::count();
            cache::forever('titles_count', $titles_count);
        }


        if ($request->has('search')) {
            $search = $request->input('search');
            $titles = Title::where('name', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->paginate($perpage)
                ->withQueryString();

            return view('pages.titles', compact('titles','titles_count'));
        }

            $titles = Title::paginate($perpage)->withQueryString();



        return view('pages.titles', compact('titles','titles_count'));
    }

    public function active(Request $request){

        $title=Title::find($request->id);

        if ($title->is_active==1) {
            $title->is_active=0;
            $title->save();
        } else {
            $title->is_active=1;
            $title->save();
        }

        return back();

    }

    public function store(Request $request){

        $title=new Title();
        $title->name=$request->name;
        $title->status=$request->status;
        $title->save();

        return back();
    }

    public function update(Request $request){

        $title=Title::find($request->id);
        if ($title){
            $title->name=$request->name;
            $title->status=$request->status;
            $title->save();
            return back();
        }

        return back()->with('error', 'Title not found');

    }

    public function delete(Request $request){

        $title=Title::find($request->id);
        if ($title){
            $title->delete();
            return back();
        }

        return back()->with('error', 'Title not found');

    }
}
