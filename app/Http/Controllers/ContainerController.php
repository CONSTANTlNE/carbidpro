<?php

namespace App\Http\Controllers;

use App\Mail\CarGroupEmail;
use App\Models\CarStatus;
use App\Models\ContainerGroup;
use App\Models\ContainerStatus;
use App\Models\LoadType;
use App\Models\Port;
use App\Models\PortCity;
use App\Models\PortEmail;
use App\Services\smsService;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars             = Car::with(relations: [
            'dispatch', 'customer', 'state', 'CarStatus', 'auction', 'loadType',
        ])->paginate(50); // Keep eager loading for relationships
        $container_status = ContainerStatus::withCount('cars')->get();


        return view('pages.containers.index', compact('cars', 'container_status'));
    }

    public function showStatus($slug, Request $request)
    {
        $containerStatus = ContainerStatus::where('slug', $slug)->first();

        if (auth()->user()->hasRole('Admin')) {
            $container_status = ContainerStatus::withCount('cars')->get();
        } else {
            $container_status = ContainerStatus::withCount([
                'cars' => function ($query) {
                    $query->where('dispatch_id', auth()->id());
                },
            ])->get();
        }


        $groups = '';
        $cars   = '';

//  CONTAINER STATUS IS CHANGED ON CARS and thetn ContainerGroup is filtered according to cars which have cotainer_status
        if ($slug == 'for-load') {
            if (isset($_GET)) {
                $query = Car::with(['dispatch', 'customer', 'state', 'Auction', 'loadType', 'port', 'groups.port'])
                    ->where('container_status_id', 1);

                // Apply filters based on GET parameters if they exist
                if ($request->has('to_port_id') && !empty($request->to_port_id)) {
                    $query->where('to_port_id', $request->to_port_id);
                }

                if ($request->has('load_type') && !empty($request->load_type)) {
                    $query->where('load_type', $request->load_type);
                }

                if ($request->has('type_of_fuel') && !empty($request->type_of_fuel)) {
                    $query->where('type_of_fuel', $request->type_of_fuel);
                }

                if ($request->has('title') && !is_null($request->title)) {
                    $query->where('title', $request->title); // Assuming 1 = Yes, 0 = No
                }

                // Text search filter
                if ($request->has('search') && !empty($request->search)) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q
                            ->where('vin', 'like', "%{$search}%")
                            ->orWhere('make_model_year', 'like', "%{$search}%") // Add more fields as needed
                            ->orWhere('lot', 'like', "%{$search}%"); // Example field
                    });
                }

                $cars = $query->paginate(50);
            } else {
                $cars = Car::with(['dispatch', 'customer', 'state', 'Auction', 'loadType', 'port' , 'groups.port'])
                    ->where('container_status_id', 1)->paginate(50);
            }
        } elseif ($slug == 'loading-pending') {
            $groupsQuery = ContainerGroup::with('cars.customer', 'cars.Auction', 'cars.loadType', 'cars.port', 'port')
                ->whereHas('cars', function ($query) {
                    $query->where('container_status_id', 2); // Filter cars where container_status is 2
                });

            // Apply search filter if $request->search is provided
            if ($request->has('search') && !empty($request->search)) {
                $searchValue = $request->search;
                $groupsQuery->where(function ($query) use ($searchValue) {
                    $query
                        ->where('booking_id', $searchValue)
                        ->orWhere('container_id', $searchValue)
                        ->orWhereHas('cars', function ($carQuery) use ($searchValue) {
                            $carQuery
                                ->where('lot', 'LIKE', '%'.$searchValue.'%')
                                ->orWhere('vin', 'LIKE', '%'.$searchValue.'%')
                                ->orWhere('make_model_year', 'LIKE', '%'.$searchValue.'%');
                        });
                });
            }
            $groups = $groupsQuery->get();
        } // LOADED PAYMENTS ,
        else {
            $groupsQuery = ContainerGroup::with('cars.customer', 'cars.Auction', 'cars.loadType', 'cars.port', 'port')
                ->whereHas('cars', function ($query) {
                    $query->where('container_status_id', 3); // Filter cars where container_status is 3
                });

            // Apply search filter if $request->search is provided
            if ($request->has('search') && !empty($request->search)) {
                $searchValue = $request->search;
                $groupsQuery->where(function ($query) use ($searchValue) {
                    $query
                        ->where('booking_id', $searchValue)
                        ->orWhere('container_id', $searchValue)
                        ->orWhereHas('cars', function ($carQuery) use ($searchValue) {
                            $carQuery
                                ->where('lot', 'LIKE', '%'.$searchValue.'%')
                                ->orWhere('vin', 'LIKE', '%'.$searchValue.'%')
                                ->orWhere('make_model_year', 'LIKE', '%'.$searchValue.'%');
                        });
                });
            }
            $groups = $groupsQuery->get();
        }


        $ports     = Port::all();
        $loadtypes = LoadType::all();


        // C O U N T S
        $pendingCount = $groupsQuery = ContainerGroup::with('cars.customer', 'cars.Auction', 'cars.loadType',
            'cars.port', 'port')
            ->whereHas('cars', function ($query) {
                $query->where('container_status_id', 2); // Filter cars where container_status is 2
            })->count();

        $loadingCount = $groupsQuery = ContainerGroup::with('cars.customer', 'cars.Auction', 'cars.loadType',
            'cars.port', 'port')
            ->whereHas('cars', function ($query) {
                $query->where('container_status_id', 3); // Filter cars where container_status is 2
            })->count();


        $forLoadCount = Car::with(['dispatch', 'customer', 'state', 'Auction', 'loadType', 'port'])
            ->where('container_status_id', 1)->count();


        return view(
            'pages.containers.index',
            compact(
                'cars',
                'groups',
                'container_status',
                'ports',
                'loadtypes',
                'forLoadCount',
                'pendingCount',
                'loadingCount',
            ),
        );
    }

    /**
     *  Create ContainerGroup record
     */
    public function groupSelectedCars(Request $request)
    {
        // Validate the selected car IDs
        $validated = $request->validate([
            'car_ids'   => 'required|array',
            'car_ids.*' => 'exists:cars,id',
        ]);


        // Attach selected cars to this group

        $carIds        = $validated['car_ids']; // Ensure this is an array of IDs
        $car_ids_array = explode(",", $carIds[0]);
        $cars          = Car::whereIn('id', $car_ids_array)->get(['to_port_id', 'title', 'warehouse']);

        // Check if all `to_port_id` are the same
        if ($cars->pluck('to_port_id')->unique()->count() !== 1 || $cars->pluck('warehouse')->unique()->count() !== 1) {
            // Return error if `to_port_id` values are not the same
            return redirect()->back()->with(['message' => 'Cars Port and TRT need same', 'alert-type' => 'error']);
        }

        if ($cars->pluck('title')->every(function ($title) {
                return strtolower($title) === 'yes';
            }) === false) {
            // Return error if any title is not 'YES'
            return redirect()->back()->with(['message' => 'All car titles must be YES', 'alert-type' => 'error']);
        }


        // Group and Container group is same as Container , like group of cars for a particular container
        // At this point CONTAINER number is not know...
        $group = ContainerGroup::create([
            'group_name' => 'Group '.now()->timestamp,

        ]);

        $group->update(['to_port_id' => $cars[0]->to_port_id]);
        // trt and warehouse have same values and names should be same also ...but we have what we have...
        $group->update(['trt' => $cars[0]->warehouse]);


        Car::whereIn('id', $car_ids_array)->increment('container_status_id', 1);
        $availableCars = Car::whereNotIn('id', $car_ids_array)->get();

        $group->cars()->attach($car_ids_array); // Laravel will create separate rows for each car ID


        return redirect()->route('container.showStatus', 'for-load')->with([
            'message' => 'Cars grouped successfully', 'group' => $group,
        ]);
    }

    public function updateGroup(Request $request)
    {
        $container = ContainerGroup::findOrFail($request->group_id);

        $car_ids = DB::table('container_group_container')
            ->where('container_group_id', $request->group_id)
            ->pluck('car_id')
            ->toArray();

        if ($request->has('container')) {
            $container->container_id = $request->container;
            Car::whereIn('id', $car_ids)->update(['container_number' => $request->container]);
            foreach ($car_ids as $car_id) {
                $customer = Car::where('id', $car_id)->first()->customer;
                if ($customer) {
                    (new smsService())->carLoaded($customer->phone, Car::where('id', $car_id)->first(),
                        $request->container);
                }
            }
        }

        if ($request->has('container_cost')) {
            $container->cost = $request->container_cost;
        }

        if ($request->has('booking_id')) {
            $container->booking_id = $request->booking_id;
        }


        if ($request->has('arrival_time')) {
            $container->arrival_time = $request->arrival_time;
            Car::whereIn('id', $car_ids)->update(['arrival_time' => $request->arrival_time]);
        }

        if ($request->hasFile('bol_photo')) {

            //  if changed file by user , delete previous file from storage
            if ($container->photo!==null){
                $filepath='public/'.$container->photo;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $photo = $request->file('bol_photo');

            // Store the file in 'public/payment_photos' directory
            $path = $photo->store('bol_photo', 'public');
            // Save the path in the database
            $container->photo = $path;
        }

        if ($request->hasFile('invoice_file')) {

            //  if changed file by user , delete previous file from storage
            if ($container->invoice_file!==null){
                $filepath='public/'.$container->invoice_file;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $invoice_file = $request->file('invoice_file');

            // Store the file in 'public/payment_invoice_files' directory
            $path = $invoice_file->store('invoice_file', 'public');
            // Save the path in the database
            $container->invoice_file = $path;

        }

        if ($request->hasFile('payment_file_1')) {


            //  if changed file by user , delete previous file from storage
            if ($container->payment_file_1!==null){
                $filepath='public/'.$container->payment_file_1;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $payment_file_1 = $request->file('payment_file_1');

            // Store the file in 'public/payment_invoice_files' directory
            $path = $payment_file_1->store('payment_file_1', 'public');

            // Save the path in the database
            $container->payment_file_1 = $path;
        }

        if ($request->hasFile('payment_file_2')) {

            //  if changed file by user , delete previous file from storage
            if ($container->payment_file_2!==null){
                $filepath='public/'.$container->payment_file_2;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }


            // Get the uploaded file (single file)
            $payment_file_2 = $request->file('payment_file_2');

            // Store the file in 'public/payment_invoice_files' directory
            $path = $payment_file_2->store('payment_file_2', 'public');
            // Save the path in the database
            $container->payment_file_2 = $path;
        }

        if ($request->hasFile('thc_invoice')) {

            //  if changed file by user , delete previous file from storage
            if ($container->thc_invoice!==null){
                $filepath='public/'.$container->thc_invoice;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $thc_invoice = $request->file('thc_invoice');

            // Store the file in 'public/payment_thc_invoices' directory
            $path = $thc_invoice->store('thc_invoice', 'public');
            // Save the path in the database
            $container->thc_invoice = $path;
        }


        if ($request->has('thc_cost')) {
            $container->thc_cost = $request->thc_cost;
        }

        if ($request->has('thc_agent')) {
            $container->thc_agent = $request->thc_agent;
        }

        if ($request->has('is_green')) {
            $container->is_green = $request->is_green;
        }


        $container->save();

        Car::whereIn('id', $car_ids)->update(['container_status_id' => 3]);


        return redirect()->route('container.showStatus',
            'loaded-payments')->with(['message' => 'Container updated successfully']);
    }

    public function availableCars(Request $request)
    {
        $car_ids = DB::table('container_group_container')
            ->where('container_group_id', $request->container_id)
            ->pluck('car_id')
            ->toArray();


        if ($request->has('to_port_id')) {
            $availableCars = Car::with('loadType')->whereNotIn('id', $car_ids)->where('title',
                'yes')->where('to_port_id', $request->to_port_id)->get(); // Or apply filtering logic if necessary
        } else {
            $availableCars = Car::with('loadType')->whereNotIn('id', $car_ids)->where('title',
                'yes')->where('to_port_id', $request->to_port_id)->get(); // Or apply filtering logic if necessary
        }


        // Return the cars in a JSON format
        return response()->json([
            'cars' => $availableCars,
        ]);
    }

    public function replaceCar(Request $request)
    {
        // Validate the request
        $request->validate([
            'oldcar_id' => 'required|exists:cars,id',
        ]);

        $key      = $request->key;
        $newcarid = $request->input('new_car_id'.$key);

        // Fetch the original car
        $container                = ContainerGroup::findOrFail($request->container_id);
        $oldcar                   = Car::where('id', $request->oldcar_id)->first();
        $oldcar->container_status_id = 1;
        $oldcar->save();

        $newcar                   = Car::where('id', $newcarid)->first();
        $newcar->container_status_id = 2;
        $newcar->save();

        // Detach the old car from the group
        $container->cars()->detach($oldcar->id);

        // Attach the new car to the group
        $container->cars()->attach($newcarid);

        return response()->json(['message' => 'Car replaced successfully']);
    }

    public function addCarToGroup(Request $request)
    {
        // Fetch the original car
        $containerGroup        = ContainerGroup::findOrFail($request->container_id);
        $key                   = $request->key;
        $carid                 = $request->input('car_id'.$key);
        $car                   = Car::find($carid);
        $car->container_status_id = 2;
        $car->save();

        $containerGroup->cars()->attach($carid);
        $containerGroup->is_email_sent = 0;
        $containerGroup->save();

        return redirect()->back()->with('success', 'Container updated successfully.');
    }

    public function removeFromList(Request $request)
    {
        // Fetch the original car
        $originalCar = ContainerGroup::findOrFail($request->container_id);
        $car         = Car::where('id', $request->carId)->first();
        // Detach the old car from the group
        $originalCar->cars()->detach($car->id);
        $car->container_status_id      = 1;
        $originalCar->is_email_sent = 0;
        $originalCar->save();
        $car->save();

        return response()->json(['message' => 'Car Removed From List']);
    }

    public function listUpdate(Request $request)
    {
//        $car = Car::with('statusRelation')->findOrFail($request->carid);
        $car = Car::findOrFail($request->carid);

        if ($request->has('title')) {
            $car->title = $request->title;
        }


        // Disable automatic updating of `updated_at`
        $car->timestamps = false;

        // Save the model without changing the `updated_at` field
        $car->save();

        // Optionally, you can enable the timestamps back if needed later
        $car->timestamps = true;

        return redirect()->back()->with('success', 'Car updated successfully.');

        // $carstatus = CarStatus::where('id', $car->status)->first();


        // return redirect()->route('car.showStatus', $carstatus->slug)->with('success', 'Car updated successfully.');
    }

    public function sendEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'container_id' => 'required|integer',
        ]);

        $container_id = $request->input('container_id');

        $car_ids = DB::table('container_group_container')
            ->where('container_group_id', $container_id)
            ->pluck('car_id')
            ->toArray();

        $cars = Car::whereIn('id', $car_ids)->get();

        // there might be several , but this updates 1
        $container                  = ContainerGroup::findOrFail($request->container_id);
        $container->is_email_sent   = 1;
        $container->email_sent_date = Carbon::now();
        $container->save();

        $con      = ContainerGroup::where('id', $request->container_id)->first();
        $getEmail = PortEmail::where('port_id', $con->to_port_id)->first();

        Mail::to($getEmail?->email)->send(new CarGroupEmail($cars));

        // Return a response for AJAX
        return view('pages.htmx.htmxSendEmailContainers', compact('container'));
    }

    public function htmxSelectCar(Request $request)
    {
        $key         = $request->key;
        $carstoadd   = Car::where('to_port_id', $request->to_port_id)
            ->where('warehouse', $request->trt)
            ->where('container_status_id', 1)
            ->get();
        $containerid = $request->container_id;

//dd($carstoadd);
        return view('pages.htmx.htmxAddCarToContainer', compact('carstoadd', 'key', 'containerid'));
    }

    public function htmxSelectForReplaceCar(Request $request)
    {
        $key = $request->key;
//dd($request->all());
        $carstoadd = Car::where('to_port_id', $request->to_port_id)
            ->where('warehouse', $request->trt)
            ->where('container_status_id', 1)
            ->get();

        $containerid = $request->container_id;
        $oldcar_id   = $request->oldcar_id;


        return view('pages.htmx.htmxReplaceCarFromContainer', compact('carstoadd', 'key', 'containerid', 'oldcar_id'));
    }

    public function deleteImages($id, $image_type)
    {

        $group=ContainerGroup::where('id', $id)->first();

        if ($group){
            $filename = $group->$image_type;
            $filepath='public/'.$filename;

            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
                $group->$image_type = null;
                $group->save();
                return back()->with('success', 'Image deleted successfully.');
            } else {
                return back()->with('error', 'File not found.');
            }
        }

        return back()->with('error', 'Group not found.');

    }
}
