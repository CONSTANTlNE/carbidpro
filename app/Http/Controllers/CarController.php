<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Car;
use App\Models\CarStatus;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\CustomerBalance;
use App\Models\Extraexpence;
use App\Models\LoadType;
use App\Models\Location;
use App\Models\Port;
use App\Models\PortCity;
use App\Models\ShippingPrice;
use App\Models\User;
use App\Services\CreditService;
use App\Services\smsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortColumn    = $request->get('sort', 'customers.contact_name'); // Default sort by customer
        $sortDirection = $request->get('direction', 'asc'); // Default sorting direction


        if ($request->has('archive')) {
            $cars = Car::onlyTrashed()->with([
                'dispatch', 'customer', 'state', 'CarStatus', 'firstCredit', 'Auction', 'groups',
                'credit' => function ($query) {
                    $query->orderBy('issue_or_payment_date', 'asc');
                }, 'latestCredit',
            ]);
        } else {
            $cars = Car::with([
                'dispatch', 'customer', 'state', 'CarStatus', 'firstCredit', 'Auction', 'groups',
                'credit' => function ($query) {
                    $query->orderBy('issue_or_payment_date', 'asc');
                }, 'latestCredit',
            ]);
        }
        // Base query with eager loading


        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            // Search across columns in the cars table and related models
            $cars = $cars->where(function ($query) use ($searchTerm) {
                $query
                    ->Where('make_model_year', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('customer', function ($q) use ($searchTerm) {
                        $q->where('contact_name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('dispatch', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhere('vin', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('gate_or_member', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('lot', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply sorting based on the requested column
        if (isset($_GET['sort'])) {
            if ($sortColumn == 'customers.contact_name') {
                $cars = $cars
                    ->join('customers', 'cars.customer_id', '=', 'customers.id')
                    ->select('cars.*', 'customers.contact_name') // Select the customer name for sorting
                    ->orderBy('customers.contact_name', $sortDirection);
            } elseif ($sortColumn == 'dispatcher.name') {
                $cars = $cars
                    ->join('users as dispatcher', 'cars.dispatch_id', '=', 'dispatcher.id')
                    ->select('cars.*', 'dispatcher.name as dispatcher_name') // Select the dispatcher name for sorting
                    ->orderBy('dispatcher.name', $sortDirection);
            } elseif ($sortColumn == 'car_statuses.name') {
                $cars = $cars
                    ->join('car_statuses', 'cars.car_status_id', '=', 'car_statuses.id')
                    ->select('cars.*', 'car_statuses.name as status_name') // Select the status name for sorting
                    ->orderBy('car_statuses.name', $sortDirection);
            } else {
                // Default sort by created_at if no special sort column is requested
                $cars = $cars->orderBy('cars.created_at', $sortDirection);
            }
        } else {
            $cars = $cars->orderBy('cars.created_at', 'desc');
        }

        // Select cars columns only to avoid ambiguity
        $cars = $cars->paginate(50);

        $car_status = CarStatus::with('cars')->withCount('cars')->get();

        return view('pages.cars.index', compact('cars', 'car_status'));
    }

    public function calculateShippingCost(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'load_type'  => 'required|integer',
            'from_state' => 'required|integer',
            'to_port_id' => 'required|integer',
        ]);

        // Fetch values
        $loadTypePrice = LoadType::where('id', $request->load_type)->pluck('price')->first();

        $shippingPrice = ShippingPrice::where('from_location_id', $request->from_state)
            ->where('to_port_id', $request->to_port_id)
            ->first();

        $port = Port::where('id', $request->to_port_id)->first();


        // Example additional costs (for demo purposes)
        $customerExtra = 0; // Some extra cost, replace as per your logic

        // Calculate the total shipping cost
        $shippingCost = isset($shippingPrice->price)
            ? $shippingPrice->price + $port->price + $customerExtra + $loadTypePrice
            : 0;

        // Return the calculated shipping cost
        return response()->json(['shipping_cost' => $shippingCost]);
    }

    public function fetchPorts(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'from_state_id' => 'required|integer',
        ]);

        // Fetch to_port_ids based on the selected from_state_id (location_id)
        $fromLocationIds = ShippingPrice::where('from_location_id', $request->from_state_id)
            ->pluck('to_port_id')
            ->toArray();

        // Fetch the port names where the IDs match the to_port_ids
        $ports = Port::whereIn('id', $fromLocationIds)->pluck('name', 'id');

        // Return the ports as a JSON response
        return response()->json(['ports' => $ports]);
    }

    public function fetchLocations(Request $request)
    {
        // Validate the auction_id in the request
        $request->validate([
            'auction_id' => 'required|integer',
        ]);

        // Fetch locations based on auction_id
        $locations = Location::where('auction_id',
            $request->auction_id)->get(); // Adjust the query as per your relationships

        // Return the locations as a JSON response
        return response()->json(['locations' => $locations]);
    }

    public function fetchFromStates(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'auction_id' => 'required|integer',
        ]);

        // Fetch locations (from_state) based on the auction_id
        $states = Location::where('auction_id', $request->auction_id)->pluck('name', 'id');

        // Return the locations (from_state) as a JSON response
        return response()->json(['states' => $states]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auctions        = Auction::all();
        $load_types      = LoadType::all();
        $ports           = Port::all();
        $locations       = Location::all();
        $shipping_prices = ShippingPrice::all();
        $customers       = Customer::get();
        $dispatchers     = User::role('Dispatch')->get();
        $extra_expenses  = Extraexpence::all();
        $car_status      = CarStatus::with('cars')->get();

        return view(
            'pages.cars.create',
            compact(
                'auctions',
                'dispatchers',
                'load_types',
                'car_status',
                'ports',
                'locations',
                'customers',
                'shipping_prices',
                'extra_expenses',
            ),
        );
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'vin'             => 'required|unique:cars,vin',
            'make_model_year' => 'required',
            'status'          => 'required',
        ]);

//        dd($request->images2);

        // Create new car
        $car = new Car();

        // Assign fields from the request to the Car model attributes
        $car->customer_id        = $request->input('customer_id');
        $car->make_model_year    = $request->input('make_model_year');
        $car->dispatch_id        = $request->input('dispatch_id');
        $car->lot                = $request->input('lot');
        $car->vin                = $request->input('vin');
        $car->gate_or_member     = $request->input('gate_or_member');
        $car->title              = $request->input('title');
        $car->is_dispatch        = $request->input('is_dispatch');
        $car->auction            = $request->input('auction');
        $car->load_type          = $request->input('load_type');
        $car->from_state         = $request->input('from_state');
        $car->to_port_id         = $request->input('to_port_id');
        $car->zip_code           = $request->input('zip_code');
        $car->type_of_fuel       = $request->input('type_of_fuel');
        $car->vehicle_owner_name = $request->input('vehicle_owner_name');
        $car->owner_id_number    = $request->input('owner_id_number');
        $car->owner_phone_number = $request->input('owner_phone_number');
        $car->container_number   = $request->input('container_number');
        $car->warehouse          = $request->input('warehouse');
        $car->comment            = $request->input('comment');
        $car->record_color       = $request->input('record_color');
//      $car->balance = $request->input('balance');  ?? whyyy????

        // Debit changed to total_cost
//        $car->total_cost    = $request->input('total_cost');
        $car->car_status_id = $request->input('status');


//        if ($request->input('payed')) {
//            // At this point percent on credit (if any) is not included
//            $car->amount_due = $request->input('total_cost') - $request->input('payed');
//        } else {
//            $car->amount_due = $request->input('total_cost');
//        }


        // Add Extra expense (which is granted to customer)
        $balanceAccounting = $request->input('balance_accounting');
        $extraexpenses     = Extraexpence::all();

        foreach ($extraexpenses as $extraexpense) {
            if ($request->has($extraexpense->name) && $request->input($extraexpense->name) > 0) {
                $balanceAccounting[] = [
                    'name'  => $extraexpense->name,
                    'value' => $request->input($extraexpense->name),
                    'date'  => now()->format('Y-m-d'),
                ];
            }
        }
        $balanceAccounting = json_encode($balanceAccounting);
        // Handle balance_accounting array
        if ($request->has('balance_accounting')) {
            // Assuming balance_accounting is a JSON field in the database
            $car->balance_accounting = $balanceAccounting;
        }


        $sum             = array_sum(array_map(fn($item) => $item->value, json_decode($balanceAccounting)));
        $car->total_cost = $sum;
        $car->amount_due = $sum;

        $car->save();

        // Change customer Extra expenses also if change during car addition ????
//        $extraexpenses = Extraexpence::all();
//        $extraExpenseArray = [];
//        $customer = Customer::find($request->customer_id);
//        foreach ($extraexpenses as $extraexpense) {
//            if ($request->has($extraexpense->name)) {
//                $extraExpenseArray[$extraexpense->name] = $request->input($extraexpense->name);
//            }
//        }
//        $customer->extra_expenses = json_encode($extraExpenseArray);
//        $customer->save();


        // Handle images array
        if ($request->has('images2')) {
            // Assuming images is a JSON field in the database
            $car->images = json_encode($request->input('images2'));

            foreach ($request->file('images2') as $image) {
                $car->addMedia($image)->toMediaCollection('images');
            }
        }

        if ($request->input('percent') > 0) {
            $credit = Credit::create([
                'customer_id'           => $request->customer_id,
                'credit_amount'         => $car->total_cost,
                'car_id'                => $car->id,
                'monthly_percent'       => $request->input('percent') / 100,
                'issue_or_payment_date' => Carbon::now(),
            ]);
        }


        // if payed amount is indicated during the creation, also create relevant record in customer_balance
        if ($request->input('payed') > 0) {
            // at the same time create balance fill and deduct record and deduction record

            $balance                  = new CustomerBalance();
            $balance->customer_id     = $request->input('customer_id');
            $balance->amount          = -$request->input('payed');
            $balance->type            = 'car_payment';
            $balance->is_approved     = true;
            $balance->date            = Carbon::now();
            $balance->carpayment_date = Carbon::now();
            $balance->car_id          = $car->id;
            $balance->save();
        }

        $phone = Customer::where('id', $request->input('customer_id'))->first()->phone;

        (new smsService())->newCarAdded($phone, $car);

        // Redirect or return a response after saving
        return redirect()->route('car.edit', $car->id)->with('success', 'Car updated successfully.');
    }

    public function showStatus($slug, Request $request)
    {
        $carStatus = CarStatus::where('slug', $slug)->first();

        // $car_status = CarStatus::withCount('cars')->get();
        if (auth()->user()->hasRole('Admin|Developer')) {
            $car_status = CarStatus::with('cars')->withCount('cars')->get();
        }

        if (auth()->user()->hasRole('Dispatch')) {
            $car_status = CarStatus::with('cars')->withCount([
                'cars' => function ($query) {
                    $query->where('dispatch_id', auth()->user()->id);
                },
            ])->get();
        }

        if (auth()->user()->hasRole('Admin|Developer')) {
            $sortColumn    = $request->get('sort', 'customers.contact_name'); // Default sort by customer
            $sortDirection = $request->get('direction', 'asc'); // Default sorting direction

            // Base query with eager loading
            $cars = Car::with(['dispatch', 'customer', 'state', 'Auction', 'groups'])
                ->where('car_status_id', $carStatus->id);

            // Check if there is a search query
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');

                // Search across columns in the cars table and related models
                $cars = $cars->where(function ($query) use ($searchTerm) {
                    $query
                        ->Where('make_model_year', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('customer', function ($q) use ($searchTerm) {
                            $q->where('contact_name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orWhereHas('dispatch', function ($q) use ($searchTerm) {
                            $q->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orWhere('vin', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('gate_or_member', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('lot', 'LIKE', "%{$searchTerm}%");
                });
            }

            if (isset($_GET['sort'])) {
                if ($sortColumn == 'customers.contact_name') {
                    $cars = $cars
                        ->join('customers', 'cars.customer_id', '=', 'customers.id')
                        ->orderBy('customers.contact_name', $sortDirection);
                } elseif ($sortColumn == 'dispatcher.name') {
                    $cars = $cars
                        ->join('users as dispatcher', 'cars.dispatch_id', '=', 'dispatcher.id')
                        ->orderBy('dispatcher.name', $sortDirection);
                } else {
                    $cars = $cars->orderBy('cars.created_at', 'desc');
                }
            } else {
                $cars = $cars->orderBy('cars.created_at', 'desc');
            }


            // Select cars columns only to avoid ambiguity
            $cars = $cars->select('cars.*')->paginate(50);
        } else {
            $sortColumn    = $request->get('sort', 'dispatcher.name'); // Default sort by customer
            $sortDirection = $request->get('direction', 'asc'); // Default sorting direction

            $cars = Car::with(['state', 'dispatch', 'customer', 'Auction', 'groups'])
                ->where('car_status_id', $carStatus->id)
                ->where('dispatch_id', auth()->user()->id);


            // Check if there is a search query
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');

                // Search across columns in the cars table and related models
                $cars = $cars->where(function ($query) use ($searchTerm) {
                    $query
                        ->Where('make_model_year', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('customer', function ($q) use ($searchTerm) {
                            $q->where('contact_name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orWhereHas('dispatch', function ($q) use ($searchTerm) {
                            $q->where('name', 'LIKE', "%{$searchTerm}%");
                        })
                        ->orWhere('vin', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('gate_or_member', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('lot', 'LIKE', "%{$searchTerm}%");
                });
            }

            if (isset($_GET['sort'])) {
                if ($sortColumn == 'customers.contact_name') {
                    $cars = $cars
                        ->join('customers', 'cars.customer_id', '=', 'customers.id')
                        ->orderBy('customers.contact_name', $sortDirection);
                } elseif ($sortColumn == 'dispatcher.name') {
                    $cars = $cars
                        ->join('users as dispatcher', 'cars.dispatch_id', '=', 'dispatcher.id')
                        ->orderBy('dispatcher.name', $sortDirection);
                } else {
                    $cars = $cars->orderBy('cars.created_at', 'desc');
                }
            } else {
                $cars = $cars->orderBy('cars.created_at', 'desc');
            }

            // Select cars columns only to avoid ambiguity
            $cars = $cars->select('cars.*')->paginate(50);
        }

        $statuses = CarStatus::all();


        return view('pages.cars.index', compact('cars', 'car_status', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $car        = Car::findOrFail($request->id);
        $car_status = CarStatus::get();
        $container_id=$car->groups->first()?->container_id;


        $extra_expenses    = Extraexpence::all();
        $auctions          = Auction::all();
        $load_types        = LoadType::all();
        $ports             = Port::all();
        $locations         = Location::all();
        $shipping_prices   = ShippingPrice::all();
        $balanceAccounting = json_decode($car->balance_accounting, true); // Decode the JSON
        $customers         = Customer::get();
        $dispatchers       = User::where('role', 'Dispatch')->get();
        $selectedcustomer  = Customer::find($car->customer_id);

        return view(
            'pages.cars.edit',
            compact(
                'car',
                'balanceAccounting',
                'auctions',
                'dispatchers',
                'car_status',
                'load_types',
                'ports',
                'locations',
                'customers',
                'shipping_prices',
                'extra_expenses',
                'selectedcustomer',
                'container_id'
            ),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $car = Car::findOrFail($request->id);

//       dd($request->all());

        //  if new costs were added recalculate credit
        if ($request->has('balance_accounting')) {
            $costs = $request->input('balance_accounting');
            (new CreditService())->reCalculateOnDeleteOrAdd($car, null, $costs);
        }


        $car->balance_accounting = json_encode($request->input('balance_accounting'));
        $car->customer_id        = $request->input('customer_id');
        $car->make_model_year    = $request->input('make_model_year');
        $car->dispatch_id        = $request->input('dispatch_id');
        $car->lot                = $request->input('lot');
        $car->vin                = $request->input('vin');
        $car->gate_or_member     = $request->input('gate_or_member');
        $car->title              = $request->input('title');
        $car->is_dispatch        = $request->input('is_dispatch');
        $car->auction            = $request->input('auction');
        $car->load_type          = $request->input('load_type');
        $car->from_state         = $request->input('from_state');
        $car->to_port_id         = $request->input('to_port_id');
        $car->zip_code           = $request->input('zip_code');
        $car->type_of_fuel       = $request->input('type_of_fuel');
        $car->vehicle_owner_name = $request->input('vehicle_owner_name');
        $car->owner_id_number    = $request->input('owner_id_number');
        $car->owner_phone_number = $request->input('owner_phone_number');
        $car->container_number   = $request->input('container_number');
        $car->warehouse          = $request->input('warehouse');
        $car->comment            = $request->input('comment');
        $car->balance            = $request->input('balance');
        $car->record_color       = $request->input('record_color');


        if ($request->filled('payed')) {
            $car->payed = $request->input('payed');
        }

        $car->total_cost    = $request->input('total_cost');
        $car->car_status_id = $request->input('status');


        // in payments may be also Interest payments , so total payments might be greater that total cost
        $totalpayed = -CustomerBalance::where('car_id', $car->id)
            ->where('type', 'car_payment')
            ->sum('amount');

        if ($request->input('total_cost') - $totalpayed > 0) {
            $car->amount_due = $request->input('total_cost') - $totalpayed;
        } else {
            $car->amount_due = 0;
        }

        $car->save();


        return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
    }

    public function listUpdate(Request $request)
    {
        $car = Car::with('CarStatus')->findOrFail($request->id);

        if ($request->has('warehouse')) {
            $car->warehouse = $request->warehouse;
        }

        if ($request->has('internal_shipping')) {
            $car->internal_shipping = $request->internal_shipping;
        }

        // internal shipping carrier company name
        if ($request->has('company_name')) {
            $car->company_name = $request->company_name;
        }

        // internal shipping carier contact
        if ($request->has('contact_info')) {
            $car->contact_info = $request->contact_info;
        }

        if ($request->has('pickup_dates')) {
            $car->pickup_dates = $request->pickup_dates;
        }


        if ($request->has('title_delivery')) {
            $car->title_delivery = $request->title_delivery;
        }

        if ($request->has('payment_method')) {
            $car->payment_method = $request->payment_method;
        }

        if ($request->has('payment_address')) {
            $car->payment_address = $request->payment_address;
        }

        if ($request->has(key: 'payment_company')) {
            $car->payment_company = $request->payment_company;
        }


        if ($request->hasFile('payment_photo')) {
            //  if changed file by user , delete previous file from storage
            if ($car->payment_photo !== null) {
                $filepath = 'public/'.$car->payment_photo;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $photo = $request->file('payment_photo');

            // Store the file in 'public/payment_photos' directory
            $path = $photo->store('payment_photos', 'public');

            // Save the path in the database
            $car->payment_photo = $path;

            // Don't forget to save the $car model after updating
            $car->save();
        }


        if ($request->onlytitl_delivery) {
            $car->save();

            return true;
        }


        $car->car_status_id = $car->car_status_id + 1;

        if ($car->car_status_id == 7) {
            $car->container_status = 1;
        }

        $car->save();

        if ($request->has('storage')) {
            $car->storage = $request->storage;
            $car->save();
        }

        if ($request->has('cost') && $request->cost > 0) {
            $array = json_decode($car->balance_accounting, true);

            $storageValue = $request->cost;
            $newElement   = [
                "name"  => "Storage",
                "value" => $storageValue,
                'date'  => now()->format('Y-m-d'),
            ];

            // Append the new element to the array
            $array[] = $newElement;

            // Encode the array back into JSON
            $updatedJsonString = json_encode($array);

            // Output the updated JSON
            $car->balance_accounting = $updatedJsonString;
            $car->storage_cost       = $request->cost;
            $car->amount_due         = $car->amount_due + $request->cost;
            $car->total_cost         = $car->total_cost + $request->cost;
            $car->save();
            //  add storage amount to credit amount (applied only if credit is granted)
            $newCredit = (new CreditService())->addNewAmountToCredit($car, $request->cost);

            (new CreditService())->reCalculateOnDeleteOrAdd($car);
        }


        return redirect()->back()->with('success', 'Car updated successfully.');
        // return redirect()->route('car.showStatus', $carstatus->slug)->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request)
    {
        $car = Car::withTrashed()->find($request->id);

        if ($car) {
            if ($car->trashed()) {
                $car->forceDelete();
            } else {
                $car->delete();
            }

            return back();
        }

        return back()->with('error', 'Car not found');
    }

    public function readyForPickup(Request $request)
    {
        $car      = Car::findOrFail($request->car_id);
        $number   = $car->customer->phone;
        $key      = $request->key;
        $carindex = $request->carindex;

//        dd($car);

        if ($car->ready_for_pickup == 0) {
            $car->ready_for_pickup = 1;
            (new smsService())->readyForPickup($number, $car);
        } else {
            $car->ready_for_pickup = 0;
        }
        $car->save();

        return view('pages.htmx.htmxCarPickup', compact('car', 'key', 'carindex'));
    }

    public function restoreTrashed($id)
    {
        $car = Car::onlyTrashed()->find($id);
        if ($car) {
            $car->restore();

            return back();
        }

        return back()->with('error', 'Car not found');
    }

    public function deleteImage(Request $request)
    {
        $image = Media::where('id', $request->image_id)->first();
        $image->delete();

        return response()->noContent();
    }

    public function deletePaymentImage($id)

    {
        $car = Car::where('id', $id)->first();

        if ($car) {
            $filename = $car->payment_photo;
            $filepath = 'public/'.$filename;

            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
                $car->payment_photo = null;
                $car->save();
                return back()->with('success', 'Image deleted successfully.');
            } else {
                return back()->with('error', 'File not found.');
            }
        }
        return back()->with('error', 'Group not found.');
    }

    public function changeCarStatus(Request $request)

    {
        $car = Car::where('id', $request->car_id)->first();
        if ($car) {
            $car->car_status_id = $request->status;
            $car->save();

            return back()->with('success', 'Car status changed successfully.');
        }

        return back()->with('error', 'Car not found.');
    }

}
