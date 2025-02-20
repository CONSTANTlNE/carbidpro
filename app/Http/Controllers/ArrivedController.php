<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ContainerGroup;
use App\Models\LoadType;
use App\Models\Port;
use App\Services\smsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArrivedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!empty($request->input('search')) && $request->has('search')) {
//
//            $groups = ContainerGroup::with('cars.credit','cars.latestCredit')
//                ->where('container_id', $request->input('search'))
//                ->whereHas('cars', function ($query) {
//                    $query->where('container_status', 3);
//                })
//                ->get();

            $groups = ContainerGroup::with('cars.credit', 'cars.latestCredit')
                ->where(function ($query) use ($request) {
                    // Search by container_id
                    $query
                        ->where('container_id', $request->input('search'))
                        ->orWhereHas('cars', function ($query) use ($request) {
                            $searchTerm = $request->input('search');

                            // Search for cars by relevant fields (car model, make, etc.)
                            $query
                                ->where('container_status', 3)
                                ->where(function ($q) use ($searchTerm) {
                                    $q->where('vin', 'like', '%'.$searchTerm.'%');
                                });
                        });
                })
                ->get();
        } else {
            $groups = ContainerGroup::with('cars.credit', 'cars.latestCredit')
                ->whereHas('cars', function ($query) {
                    $query->where('container_status', 3);  // Filter cars where container_status is 2
                })
                ->get();
        }

        $cars      = '';
        $ports     = Port::all();
        $loadtypes = LoadType::all();

        return view(
            'pages.arrived.index',
            compact(
                'cars',
                'groups',
                'ports',
                'loadtypes',
            ),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $container = ContainerGroup::with('cars.customer')
            ->findOrFail($id);

        if ($request->has('title_in_office')) {
            $container->title_in_office = $request->title_in_office;
        }

        if ($request->has('arrival_time')) {
            $container->arrival_time = $request->arrival_time;
        }

        if ($request->has('trt_payed')) {
            $container->trt_payed = $request->trt_payed;
        }

        if ($request->has('thc_payed')) {
            $container->thc_payed = $request->thc_payed;
        }

        if ($request->has('is_green')) {
            $container->is_green = $request->is_green;
        }


        if ($request->has('terminal')) {
            $container->terminal = $request->terminal;
        }


        if ($request->has('cont_status')) {
            $container->cont_status = $request->cont_status;
        }


        if ($request->has('est_open_date')) {
            $container->est_open_date = $request->est_open_date;
        }


        if ($request->has('opened')) {
            $container->opened = $request->opened;
            // Send sms for dealers
            foreach ($container->cars as $car) {
                (new smsService())->containerOpenedd($car->customer->phone, $car);
            }
        }

        if ($request->has('open_payed')) {
            $container->open_payed = $request->open_payed;
        }

        if ($request->has('remark')) {
            $container->remark = $request->remark;
        }

        $container->save();

        return redirect()->back()->with(['message' => 'Container updated successfully']);
    }

    public function updateCar(Request $request, string $id)
    {
        $car = Car::findOrFail($request->car_id);

        if ($request->has('remark')) {
            $car->remark = $request->remark;
        }

        if ($request->has('title_take')) {
            $car->title_take = $request->title_take;
        }

        if ($request->hasFile('bill_of_loading')) {

            //  if changed file by user , delete previous file from storage
            if ($car->bill_of_loading!==null){
                $filepath='public/'.$car->bill_of_loading;
                if (Storage::exists($filepath)) {
                    Storage::delete($filepath);
                }
            }

            // Get the uploaded file (single file)
            $photo = $request->file('bill_of_loading');

            // Store the file in 'public/payment_photos' directory
            $path = $photo->store('bill_of_loading', 'public');

            // Save the path in the database
            $car->bill_of_loading = $path;
        }

        if ($request->hasFile('container_images')) {
            foreach ($request->file('container_images') as $image) {
                // Add each image to the media collection
                $car->addMedia($image)
                    ->toMediaCollection('container_images');
            }
        }
        // dd($request->all());

        $car->save();

        return response()->json(['message' => 'Car data updated successfully!']);
    }

    public function showImages(string $id)
    {
        $car    = Car::where('id', $id)->first();
        $images = $car->getMedia('container_images');

        return view('pages.arrived.gallery', compact('images'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function deleteImage(Request $request) {
        $image=Media::where('id', $request->image_id)->first();
        $image->delete();

        return back();
    }

    public function deleteBolImage($id)
    {
        $car=Car::where('id', $id)->first();

        if ($car){
            $filename = $car->bill_of_loading;
            $filepath='public/'.$filename;

            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
                $car->bill_of_loading = null;
                $car->save();
                return back()->with('success', 'Image deleted successfully.');
            } else {
                return back()->with('error', 'File not found.');
            }
        }
        return back()->with('error', 'Group not found.');
    }
}
