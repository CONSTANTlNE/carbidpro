<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Location;
use App\Models\Port;
use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class ShippingPricesController extends Controller
{
    public function index(Request $request)
    {
        $perpage=$request->input('perpage', 10);
        $auctions = Auction::all();
        $locations=Location::with('auction')->get();
        $ports=Port::all();


        if (($request->has('auction') && $request->auction === 'all') || ($request->has('port') && $request->port === 'all')) {
            return to_route('shipping-prices.index');
        }

        // Filter by port and auction
        if ($request->hasAny(['port', 'auction','minprice','maxprice','location'])) {
            $query = ShippingPrice::with(['location', 'port', 'auction'])->orderBy('created_at', 'desc');

            // Apply the filter for `port` if present
            if ($request->filled('port')) {
                $portId = $request->input('port');
                $query->where('to_port_id', $portId);
            }

            // Apply the filter for `auction` if present
            if ($request->filled('auction')) {
                $auctionId = $request->input('auction');
                $query->whereJsonContains('auction_id', $auctionId);
            }

            if ($request->filled('location')) {
                $locationId = $request->input('location');
                $query->where('from_location_id', $locationId);
            }

            if ($request->filled('minprice') && $request->filled('maxprice')) {
                $minPrice = (int) $request->input('minprice');
                $maxPrice = (int) $request->input('maxprice');
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }

            // Paginate and retrieve the data
            $shipping_prices = $query->paginate($perpage)->withQueryString();

            $count = $shipping_prices->total();

            // Get the current filters for use in the view

            return view('pages.shipping_prices', compact('shipping_prices', 'auctions', 'count', 'ports', 'locations'));
        }


        $shipping_prices = ShippingPrice::with(['location', 'port', 'auction'])
            ->orderBy('created_at', 'desc')
            ->paginate($perpage)->withQueryString();
        $count=$shipping_prices->total();

        return view('pages.shipping_prices', compact('shipping_prices','count','auctions','ports','locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required:exists:locations,id',
            'auction_id' => 'required|array',
            'auction_id.*' => 'exists:auctions,id',
            'port_id' => 'required|exists:ports,id',
            'price' => 'required|numeric'
        ]);

        $new=new ShippingPrice();
        $new->from_location_id=$request->location_id;
        $new->to_port_id=$request->port_id;
        $new->auction_id=$request->auction_id;
        $new->price=$request->price;
        $new->save();

        return back()->with('success', 'Location created successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:shipping_prices,id',
            'location_id' => 'required:exists:locations,id',
            'auction_id' => 'required|array',
            'auction_id.*' => 'exists:auctions,id',
            'port_id' => 'required|exists:ports,id',
            'price' => 'required|numeric'
        ]);


        $shippingprice=ShippingPrice::findOrFail($request->id);

        $shippingprice->update([
            'from_location_id' => $request->location_id,
            'to_port_id' => $request->port_id,
            'price' => $request->price,
            'auction_id'=>$request->auction_id,
        ]);

        return back()->with('success','Auction added successfully');
    }

    public function htmxLocations(Request $request){

        $search = $request->input('search');
        $index=$request->input('index');
        $locations = Location::with('auction')
            ->where('name', 'like', '%' . $search . '%')
            ->get();

        return view('pages.htmx.htmxLocations',compact('locations','search','index'));
    }

    public function destroy(Request $request)
    {
        $validated=$request->validate([
            'id' => 'required|exists:shipping_prices,id'
        ]);

        $auction = ShippingPrice::findOrFail($validated['id']);
        $auction->delete();

        return back()->with('success','Auction Deleted successfully');
    }

}
