<?php

namespace App\Http\Controllers;

use App\Mail\CarGroupEmail;
use App\Models\CarStatus;
use App\Models\ContainerGroup;
use App\Models\ContainerStatus;
use DB;
use Illuminate\Http\Request;
use App\Models\Car;
use Mail;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::with(relations: ['dispatch', 'customer', 'state', 'CarStatus', 'auction', 'loadType'])->paginate(50); // Keep eager loading for relationships
        $container_status = ContainerStatus::withCount('cars')->get();

        return view('pages.containers.index', compact('cars', 'container_status'));
    }

    public function showStatus($slug, Request $request)
    {
        $containerStatus = ContainerStatus::where('slug', $slug)->first();

        if (auth()->user()->hasRole('Admin')) {

            $container_status = ContainerStatus::withCount('cars','containerStatus')->get();
        } else {
            $container_status = ContainerStatus::withCount(['containerStatus',
                'cars' => function ($query) {
                    $query->where('dispatch_id', auth()->id());
                }
            ])->get();
        }

        $groups = '';
        $cars = '';
        if ($slug == 'for-load') {
            if (auth()->user()->hasRole('Admin')) {
                $cars = Car::with(['dispatch', 'customer', 'state', 'Auction', 'loadType', 'port'])
                    ->where('container_status', 1)->paginate(50);

            } else {

                $cars = Car::with(relations: ['state', 'dispatch', 'customer', 'auction', 'loadType'])
                    ->where(
                        'container_status',
                        $containerStatus->id
                    )->where('dispatch_id', auth()->user()->id)
                    ->paginate(50);
            }
        } elseif ($slug == 'loading-pending') {
            $groups = ContainerGroup::with('cars')->get();
        }

 
        return view('pages.containers.index', compact('cars', 'groups', 'container_status'));

    }


    public function groupSelectedCars(Request $request)
    {
        // Validate the selected car IDs
        $validated = $request->validate([
            'car_ids' => 'required|array',
            'car_ids.*' => 'exists:cars,id',
        ]);


        // Create a new car group dynamically
        $group = ContainerGroup::create([
            'group_name' => 'Group ' . now()->timestamp,  // Example group name
        ]);

        // Attach selected cars to this group

        $carIds = $validated['car_ids']; // Ensure this is an array of IDs

        $car_ids_array = explode(",", $carIds[0]);

        Car::whereIn('id', $car_ids_array)->increment('container_status', 1);
        $availableCars = Car::whereNotIn('id', $car_ids_array)->get();

        $group->cars()->attach($car_ids_array); // Laravel will create separate rows for each car ID



        return redirect()->back()->with(['message' => 'Cars grouped successfully', 'group' => $group]);
    }

    public function availableCars(Request $request)
    {

        $car_ids = DB::table('container_group_container')
            ->where('container_group_id', $request->container_id)
            ->pluck('car_id')
            ->toArray();


        $availableCars = Car::whereNotIn('id', $car_ids)->get(); // Or apply filtering logic if necessary

        // Return the cars in a JSON format
        return response()->json([
            'cars' => $availableCars
        ]);
    }

    public function replaceCar(Request $request)
    {
        // Validate the request
        $request->validate([
            'original_car_id' => 'required|exists:cars,id',
            'new_car_id' => 'required|exists:cars,id',
        ]);

        // Fetch the original car
        $originalCar = ContainerGroup::findOrFail($request->container_id);
        $original_car_id = Car::where('id', $request->original_car_id)->first();

        // Detach the old car from the group
        $originalCar->cars()->detach($original_car_id->id);

        // Attach the new car to the group
        $originalCar->cars()->attach($request->new_car_id);

        return response()->json(['message' => 'Car replaced successfully']);
    }

    public function listUpdate(Request $request)
    {
        $car = Car::with('statusRelation')->findOrFail($request->id);

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
        }

        if ($request->has('container_cost')) {
            $container->cost = $request->container_cost;
        }

        if ($request->has('booking_id')) {
            $container->booking_id = $request->booking_id;
        }

        if ($request->hasFile('bol_photo')) {
            // Get the uploaded file (single file)
            $photo = $request->file('bol_photo');

            // Store the file in 'public/payment_photos' directory
            $path = $photo->store('bol_photo', 'public');
            // Save the path in the database
            $container->photo = $path;
        }


        $container->save();

        // Optionally, you can redirect or return a response
        return redirect()->back()->with('success', 'Container updated successfully.');



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



        Mail::to('kvachakhiadimitri@gmail.com')->send(new CarGroupEmail($cars));


        // Return a response for AJAX
        return response()->json(['message' => 'Email sent successfully!']);
    }
}
