<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Country;
use App\Models\Customer;
use App\Models\LoadType;
use App\Models\Location;
use App\Models\Port;
use App\Models\PortCity;
use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class calculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::all();
        $loadtypes = LoadType::all();
        $countries = Country::all();
        return view('frontend.pages.calculator', compact('loadtypes', 'auctions', 'countries'));
    }

    public function calculate(Request $request)
    {
        $auctionId = $request->get('auction_id');
        $locationId = $request->get('location_id');
        $portId = $request->get('port_id');

        $result = "";
        if ($auctionId && !$request->get('location_id')) {
            $result = Location::where('auction_id', $auctionId)->where('is_active', 1)->orderBy('name')->get();
            if (empty($result['items'])) {
                $difflocation = Location::where('name', trim($request->get('location_name')))->where('id', '!=', $auctionId)->where('is_active', 1)->orderBy('name')->first();
            }
        }

        if ($request->get('port')) {

            $from_location_ids = ShippingPrice::where('from_location_id', $auctionId)->pluck('to_port_id')->toArray();
            if (empty($from_location_ids) && isset($difflocation)) {
                $from_location_ids = ShippingPrice::where('from_location_id', $difflocation->id)->pluck('to_port_id')->toArray();
            }
            $result = Port::whereIn('id', $from_location_ids)->get();

        }

        if ($request->get('country')) {
            $result = PortCity::where('country_id', $request->get('country'))->get();
        }


        if ($auctionId && $locationId && $portId) {
            $result = ShippingPrice::where('from_location_id', $locationId)->where('to_port_id', $portId)->whereJsonContains('auction_id', $auctionId)->first();
        }

        if ($auctionId && $locationId && $portId && $request->get('country_port')) {
            $total_inside = ShippingPrice::where('from_location_id', $locationId)->where('to_port_id', $portId)->whereJsonContains('auction_id', $auctionId)->first();
            if (empty($total_inside)) {
                $difflocation = Location::where('name', trim($request->get('location_name')))->where('id', '!=', $locationId)->where('is_active', 1)->first();
                $total_inside = ShippingPrice::where('from_location_id', $difflocation->id)->where('to_port_id', $portId)->whereJsonContains('auction_id', $auctionId)->first();
            }

            $to_port = Port::where('id', $portId)->first();
            // $to_port = PortCity::where('id', $request->get('country_port'))->first();



            $extra_for_team = session()->get('auth')->extra_for_team;

            if (session()->get('auth')->extra_for_team && session()->get('auth')->parent_of != 0) {
                $parent_dealer = Customer::where('id', session()->get('auth')->parent_of)->first();

                $parent_extra = $parent_dealer->extra_for_team + $extra_for_team;


            } else {
                $parent_extra = session()->get('auth')->extra_for_team;
            }

            $result = $total_inside->price +
                $to_port->price +
                $request->get('loadtype') +
                $parent_extra;
            // $result = isset($total_inside->price) ? $total_inside->price : 0 + $to_port->price + $request->get('loadtype') + session()->get('auth')->extra_for_team;



            
            return response()->json([
                'ground_rate' => $total_inside->price + $parent_extra,
                // 'ground_rate' => isset($total_inside->price) ? $total_inside->price + session()->get('auth')->extra_for_team : 0,
                'total' => $result,
            ]);

        }

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}