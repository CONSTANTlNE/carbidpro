<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionsController extends Controller
{
    public function index()
    {
        $auctions = Auction::all();

        return view('pages.auctions', compact('auctions'));
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string|max:100'
        ]);

         Auction::create(['name'=>$validated['name']]);

        return back()->with('success','Auction added successfully');
    }

    public function destroy(Request $request)
    {
       $validated=$request->validate([
           'id' => 'required|exists:auctions,id'
       ]);

        $auction = Auction::findOrFail($validated['id']);
        $auction->delete();

        return back()->with('success','Auction Deleted successfully');
    }

    public function update(Request $request)
    {
        $validated=$request->validate([
            'id' => 'required|exists:auctions,id',
            'name'=>'required|string|max:100'
        ]);

        $auction = Auction::findOrFail($validated['id']);
        if ($request->status!=='on'){
            $auction->is_active=false;
        } else {
            $auction->is_active=true;
        }
        $auction->name=$validated['name'];
        $auction->save();


        return back()->with('success','Auction added successfully');
    }

}
