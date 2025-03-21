<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function index(Request $request)
    {
        $perpage  = $request->input('perpage', 10);
        $auctions = Auction::all();

        if ($request->has('auction') && $request->auction === 'all') {
            return to_route('locations.index');
        }

        if ($request->has('search')) {
            $search    = $request->input('search');
            $locations = Location::with('auction')
                ->where('name', 'like', '%'.$search.'%')
                ->paginate($perpage)
                ->withQueryString();

            $count = $locations->total();

            return view('pages.locations', compact('locations', 'auctions', 'count'));
        }

        if ($request->has('auction')) {
            $auctionId = $request->input('auction');
            $locations = Location::with('auction')
                ->where('auction_id', $auctionId)
                ->paginate($perpage)
                ->withQueryString();

            $count = $locations->total();

            return view('pages.locations', compact('locations', 'auctions', 'count'));
        }

        $locations = Location::with('auction')->paginate($perpage)->withQueryString();
        $count     = $locations->total();

        return view('pages.locations', compact('locations', 'auctions', 'count'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'auction_id' => 'required|exists:auctions,id',
        ]);

        Location::create(
            [
                'name' => $request->name,
                'auction_id'=>$request->auction_id,
                'is_active' => 1,
            ]
        );

        return back()->with('success', 'Location created successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'         => 'required|exists:locations,id',
            'auction_id' => 'required|exists:auctions,id',
            'name'       => 'required|string|max:100',
        ]);

        $location = Location::findOrFail($validated['id']);

        if ($request->status !== 'on') {
            $location->is_active = false;
        } else {
            $location->is_active = true;
        }
        $location->name       = $validated['name'];
        $location->auction_id = $validated['auction_id'];
        $location->save();


        return back()->with('success', 'Auction added successfully');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:locations,id',
        ]);

        $location = Location::findOrFail($validated['id']);
        $location->delete();

        return back()->with('success', 'Auction Deleted successfully');
    }

}