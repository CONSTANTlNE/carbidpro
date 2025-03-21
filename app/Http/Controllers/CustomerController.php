<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\RegisterMail;
use App\Mail\SampleMail;
use App\Mail\teamRegisterMail;
use App\Models\ContainerGroup;
use App\Models\Extraexpence;
use App\Models\Insurance;
use Illuminate\Support\Facades\Mail;

use App\Models\Car;
use App\Models\CustomerBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Spatie\MediaLibrary\Support\MediaStream;

class CustomerController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian

        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }

        return view('frontend.pages.customer.login', compact('tr'));
    }

    // RecordController.php
    public function getTableData($id)
    {
        // Fetch the data from the database based on the record ID
        $data = CarPayment::where('car_id', $id)->get();

        // Return the data as JSON
        return response()->json($data);
    }

    public function login(Request $request)
    {
        if (isset($request->tempo_user_id) && !empty($request->tempo_user_id)) {
            if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Editor')) {
                $customer = Customer::find($request->tempo_user_id);
                Auth::guard('customer')->login($customer);
                Session::put('auth', $customer);

                return redirect('/dashboard/main');
            }
        }

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Customer::where('email', $credentials['email'])->first();

            Session::put('auth', $customer);


            if (!$customer->is_active) {
                Auth::guard('customer')->logout();

                return redirect()->back()->withErrors(['email' => 'Account is not active']);
            }

            return redirect(route('customer.dashboard'));
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        Session::forget('auth');

        return to_route('home');
    }

    public function saveRelease(Request $request)
    {
//        dd($request->all());
        // Validate the incoming request data before proceeding
        $request->validate([
            'car_id' => 'required', // Ensure the car_id exists in the database

        ]);

        // Find the car by its ID
        $car = Car::findOrFail($request->car_id);

        // Update the car attributes
        $car->vehicle_owner_name = $request->vehicle_owner_name;
        $car->owner_id_number    = $request->owner_id_number;
        $car->owner_phone_number = $request->owner_phone_number;

        // Save the changes to the database
        $car->save();

        // Optionally, you can return a response or redirect the user after updating the car
        return back()->with('success', 'Car details updated successfully.');
    }

    public function showCar(Request $request, $vin = null)
    {
        if ($request->search) {
            $vin = $request->search;
        }


        $car = Car::with(['state', 'toPort', 'loadType', 'auction', 'media'])->where('vin', $vin)
            ->where('is_active', 1)->first();


        if (!$car) {
            return back()->with('error', 'Car not found');
        }


//        if (Session::has('locale')) {
//            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
//            $tr->setSource('en'); // Translate from English
//            $tr->setSource(); // Detect language automatically
//            $tr->setTarget(Session::get('locale')); // Translate to Georgian
//
//        } else {
//            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
//            $tr->setSource('en'); // Translate from English
//            Session::put('locale', 'en');
//        }

        return view('frontend.pages.customer.car-info', compact('car'));
    }

    public function download($vin)
    {
        $car = Car::where('vin', $vin)->first();

        // Let's get some media.
        $downloads = $car->getMedia('images');

        // Download the files associated with the media in a streamed way.
        // No prob if your files are very large.
        return MediaStream::create($vin.'.zip')->addMedia($downloads);
    }

    public function searchResult(Request $request)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }


        if (strlen($request->search) >= 17) {
            $car = Car::where('vin', $request->search)->first();

            if (!$car) {
                return redirect(route('customer.dashboard'));
            }


            return view('frontend.pages.search-result', compact('tr', 'car'));
        } else {
            $container = isset($_GET['search']) ? $_GET['search'] : '';

            return Redirect::away('https://parcelsapp.com/en/tracking/'.$container)->withHeaders([
                'target' => '_blank',
            ]);
            // return view('pages.search-result', compact('tr', 'container'));
        }
    }

    public function addInvoicePrice(Request $request)
    {
        // Validate the request if needed
        $validatedData = $request->validate([
            'invoice_debit'    => 'nullable|numeric',
            'invoice_received' => 'nullable|numeric',
            'invoice_balance'  => 'nullable|numeric',
        ]);

        // Find the car by ID
        $car = Car::find($request->car_id);

        // Update the car's invoice details
        if ($car) {
            $car->invoice_debit    = filled($validatedData['invoice_debit']) ? (int) $validatedData['invoice_debit'] : 0;
            $car->invoice_received = filled($validatedData['invoice_received']) ? $validatedData['invoice_received'] : 0;
            $car->invoice_balance  = filled($validatedData['invoice_balance']) ? (int) $validatedData['invoice_balance'] : 0;
            $car->save();
        } else {
            // Handle case where car is not found
            return redirect()->back()->with('error', 'Car not found.');
        }

        // Redirect back with success message or to handle further actions
        return redirect()->back()->with('success', 'Invoice details updated successfully.');
    }

    public function teamList(Request $request)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }

        // Data preparation for dashboard view
        $teams = Customer::where('child_of', auth()->user()->id)->get();

        return view('frontend.pages.customer.team', compact('tr', 'teams'));
    }

    public function addTeam(Request $request)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }


        // Additional authorization checks (optional)
        if (!auth()->user()->is_active) {
            return redirect('/')->with('error', 'Your account is not active.');
        }

        // Data preparation for dashboard view
        $customer = auth()->user();


        return view('frontend.pages.customer.add_team', compact('tr', 'customer'));
    }

    public function teamEdit($id)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }

        $team = Customer::where('id', $id)->first();


        return view('frontend.pages.customer.edit_team', compact('tr', 'team'));
    }

    public function teamUpdate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_name' => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $customer                 = Customer::find($id);
        $customer->contact_name   = $request->input('contact_name');
        $customer->company_name   = "";
        $customer->phone          = $request->input('phone');
        $customer->email          = $request->input('email');
        $customer->password       = Hash::make($request->input('password'));
        $customer->is_active      = $request->is_active; // Set account as active by default
        $customer->extra_for_team = isset($request->extra_for_team) ? $request->extra_for_team : 0; // Set account as active by default

        $customer->save();


        return redirect(route('customer.teamEdit', $id))->with('success', 'Your Team member Updated.');
    }

    public function removeTeam($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return redirect()->back();
    }

    public function addTeamToCar(Request $request)
    {
        $car = Car::find($request->car_id);

        if ($request->team_id === 'remove_team') {
            $car->team_id = 0;
            $car->save();

            return back();
        }
        $car->team_id = $request->team_id;
        $car->save();

        return back();
    }

    public function showDashboard(Request $request, Builder $builder)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }

        // Additional authorization checks (optional)
        if (!auth()->user()->is_active) {
            return redirect('/')->with('error', 'Your account is not active.');
        }

        // Data preparation for dashboard view
        if (auth()->user()->child_of > 0) {
            $whereLabel = 'team_id';
            $customer   = auth()->user();
        } else {
            $whereLabel = 'customer_id';
            $customer   = auth()->user();
        }


        if (route('customer.archivedcars') == url()->current()) {
            $cars = Car::with([
                'state', 'toPort', 'payments', 'latestCredit', 'firstCredit', 'groups.line', 'media',
                'credit' => function ($query) {
                    $query->orderBy('issue_or_payment_date', 'asc');
                },
            ])
                ->where('is_active', 1)
                ->where('amount_due', 0)
                ->where($whereLabel,
                    $customer->id)
                ->paginate(50)
                ->withQueryString();
        } elseif (auth()->user()->hasRole('portmanager')) {
            $cars = Car::with([
                'state', 'toPort', 'payments', 'latestCredit', 'firstCredit', 'groups.line', 'media',
                'credit' => function ($query) {
                    $query->orderBy('issue_or_payment_date', 'asc');
                },
            ])
                ->where('is_active', 1)
                ->paginate(50)
                ->withQueryString();
        } else {
            $cars = Car::with([
                'state', 'toPort', 'payments', 'latestCredit', 'firstCredit', 'groups.line', 'media',
                'credit' => function ($query) {
                    $query->orderBy('issue_or_payment_date', 'asc');
                },
            ])
                ->where('is_active', 1)
//                ->where('balance_accounting', '!=', null)
                ->where('amount_due', '>', 0)
                ->where($whereLabel,
                    $customer->id)
                ->paginate(50)
                ->withQueryString();
        }

        $balance = CustomerBalance::where('customer_id', $customer->id)
            ->where('is_approved', 1)
            ->sum('amount');

        $pending = CustomerBalance::where('customer_id', $customer->id)
            ->where('is_approved', 0)
            ->sum('amount');

        $teams = Customer::where('child_of', $customer->id)->get();

//        $totalAmountDue=Car::

        return view('frontend.pages.customer.dashboard2',
            compact('tr', 'customer', 'teams', 'cars', 'balance', 'pending'));
    }

    public function showPaymentRequest(Request $request, Builder $builder)
    {
        if (auth()->user()->hasRole('portmanager')) {
            return redirect()->back();
        }

        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }


        // Additional authorization checks (optional)
        if (!auth()->user()->is_active) {
            return redirect('/')->with('error', 'Your account is not active.');
        }

        // check if auth user is Sub Dealer or not and retrieve relevant data

        if (auth()->user()->child_of > 0) {
            $whereLabel = 'team_id';
            $customer   = auth()->user();
        } else {
            $whereLabel = 'customer_id';
            $customer   = auth()->user();
        }

//        $cars = Car::with(['state', 'toPort', 'payments'])->where('is_active', 1)->where('balance', '!=',
//        0)->where('color', '!=', '#82f98261')->where($whereLabel, $customer->id)->orderBy('created_at',
//        'DESC')->get();

        $cars = Car::with(['state', 'toPort', 'payments'])->where('is_active', 1)
            ->where('total_cost', '!=', 0)
            ->where($whereLabel, $customer->id)
            ->orderBy('created_at',
                'DESC')->get();


        $paymentRequest = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)
            ->sum('amount');

        $carBalance = Car::where('customer_id', auth()->user()->id)
            ->where('total_cost', '<', 0)
            ->sum('total_cost');


        $payment_report = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)->sum('amount');


        $balance = $paymentRequest - $payment_report - $carBalance;
//        dd($balance);

//        $total_amount_due = Car::where('customer_id', auth()->user()->id)->where('balance', '!=', 0)->where('color',
//            '!=', '#82f98261')->where('is_active', 1)->sum('balance');

        $total_amount_due = Car::where('customer_id', auth()->user()->id)
            ->where('amount_due', '!=', 0)
            ->where('is_active', 1)
            ->sum('amount_due');

        $pending = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 0)
            ->sum('amount');


        return view('frontend.pages.customer.payment_request',
            compact('tr', 'customer', 'total_amount_due', 'pending', 'cars', 'balance', 'payment_report'));
    }

    public function paymentHistory(Request $request)
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }


        // Additional authorization checks (optional)
        if (!auth()->user()->is_active) {
            return redirect('/')->with('error', 'Your account is not active.');
        }

        // Data preparation for dashboard view
        $customer = auth()->user();


        $payment_report = CustomerBalance::with('car')
            ->where('customer_id', auth()->user()->id)
//            ->where('is_approved',1)
            ->orderBy('date', 'desc')
            ->get();
//        dd($payment_report);

        $balance = CustomerBalance::where('customer_id', $customer->id)
            ->where('is_approved', 1)
            ->sum('amount');

        $pending = CustomerBalance::where('customer_id', $customer->id)
            ->where('is_approved', 0)
            ->sum('amount');


        return view('frontend.pages.customer.payment_history',
            compact('tr', 'customer', 'payment_report', 'balance', 'pending'));
    }

    public function sendEmail(Request $request)
    {
        if (!empty($request->required)) {
            exit('Spam detected');
        }


        $content = [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'msg'   => $request->message,
        ];


        Mail::to(config('carbiddata.email'))->send(new ContactMail($content));

        return redirect()->back()->with('success', 'Email sent');
    }

    public function showRegistrationForm()
    {
        if (Session::has('locale')) {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            $tr->setSource(); // Detect language automatically
            $tr->setTarget(Session::get('locale')); // Translate to Georgian
        } else {
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            $tr->setSource('en'); // Translate from English
            Session::put('locale', 'en');
        }

        return view('frontend.pages.customer.register', compact('tr'));
    }

    // Dealer Registration and also Dealers can register their Sub Dealers adding their profit as 'extra_for_team'

    public function register(Request $request)
    {
        if ($request->filled('must_fill')) {
            return response()->json(['message' => 'Success'], 200);
        }


        $current       = session()->get('auth');
        $extraexpenses = Extraexpence::all();


        if (isset($request->team)) {
            $validator = Validator::make($request->all(), [
                'contact_name' => 'required|string|max:255',
                'email'        => 'required|email|unique:customers',
                'phone'        => 'required',
                'password'     => 'required|string|min:8|confirmed',
            ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $customer                    = new Customer;
            $customer->contact_name      = $request->input('contact_name');
            $customer->company_name      = "";
            $customer->child_of          = $current->id;
            $customer->phone             = $request->input('phone');
            $customer->email             = $request->input('email');
            $customer->password          = Hash::make($request->input('password'));
            $customer->unhashed_password = $request->input('password');
            $customer->is_active         = 1; // Set account as active by default
            $customer->child_of          = $current->id; // assign main dealer ID as parent if subdealer is registered by a main dealer
            // Extra is a Dealer profit added for subdealer transactions or we can set manually and give either discount or different price for particular dealers
            $customer->extra_for_team     = isset($request->extra_for_team) ? $request->extra_for_team : 0;
            $customer->comment            = $request->input('comment');
            $customer->newwebsitecustomer = 1;
            $customer->save();

            $customer->save();

            $content = [
                'contact_name'  => $customer->contact_name,
                'email'         => $customer->email,
                'current_name'  => $current->contact_name,
                'current_email' => $current->email,
            ];

            Mail::to(config('carbiddata.email'))->send(new teamRegisterMail($content));
        } else {
            $validator = $request->validate([
                'number_of_cars' => 'required', // Add more validation rules as needed
                'company_name'   => 'required',
                'contact_name'   => 'required',
                'phone'          => 'required',
                'email'          => 'required|email|unique:customers',
                'password'       => 'required|min:8|confirmed',
            ]);


            $customer                    = new Customer;
            $customer->company_name      = $request->input('company_name');
            $customer->contact_name      = $request->input('contact_name');
            $customer->personal_number   = $request->input('personal_number');
            $customer->unhashed_password = $request->input('password');
            $customer->extra_for_team    = isset($request->extra_for_team) ? $request->extra_for_team : 0;
            $customer->phone             = $request->input('phone');
            $customer->email             = $request->input('email');
            $customer->password          = Hash::make($request->input('password'));
            $customer->number_of_cars    = $request->input('number_of_cars');
            $customer->is_active         = 0; // Set account as active by default


            $extraExpenseArray   = [];
            $extraExpenseArray[] = ['name' => '', 'value' => 0, 'date' => ''];
            foreach ($extraexpenses as $extraexpense) {
                if ($request->has($extraexpense->name)) {
                    $request->validate([
                        $extraexpense->name => 'nullable|numeric',
                    ]);
                    if ($request->input($extraexpense->name) > 0) {
                        $extraExpenseArray[] = [
                            'name'  => $extraexpense->name,
                            'value' => $request->input($extraexpense->name),
                        ];
                    }
                }
            }
            $customer->extra_expenses = json_encode($extraExpenseArray);

            $customer->save();

            $content = [
                'contact_name' => $request->input('contact_name'),
                'company_name' => $request->input('company_name'),
                'phone'        => $request->input('phone'),
                'email'        => $request->input('email'),
            ];

            if (!isset($request->admin)) {
                Mail::to(config('carbiddata.email'))->send(new RegisterMail($content));
            }
        }

        if (isset($request->admin)) {
            return back();
        }


        if (isset($request->team)) {
            return redirect(route('customer.addTeam'))->with('success', 'Your Team member added.');
        }

        return redirect(route('customer.login.get'))->with('success', 'Your account will be activated.');
    }

    // for main page search by container
    public function trackContainer(Request $request)
    {
        if (empty($request->container)) {
            return back()->with('container_error', 'Please enter Container Number');
        }

        $container = ContainerGroup::where('container_id', $request->container)->first();

        if (!$container) {
            return back()->with('container_error', 'Container not found');
        }

//        $url = match ($container->thc_agent) {
//            'MAERSK' => 'https://www.maersk.com/tracking/'.$container->container_id,
//            'Hapag-Eisa' => 'https://www.hapag-lloyd.com/en/online-business/track/track-by-container-solution.html?container='.$container->container_id,
//            'COSCO' => 'https://elines.coscoshipping.com/ebusiness/cargoTracking?trackingType=BILLOFLADING&number='.$container->container_id,
//            'Turkon-DTS' => 'https://my.turkon.com/container-tracking',
//            'One net-Wilhelmsen' => 'https://ecomm.one-line.com/one-ecom/manage-shipment/cargo-tracking',
//        };

        $url= $container->line->tracking_url.'/'.$container->container_id;


        return redirect($url);
    }

    public function terms()
    {
        $user = Auth::user();
        $insurance=Insurance::first();

        return view('frontend.pages.customer.customer_info', compact('user','insurance'));
    }

    public function Insurance()
    {

        $insurance=Insurance::first();

        return view('frontend.pages.customer.insurance', compact('insurance'));
    }


}
