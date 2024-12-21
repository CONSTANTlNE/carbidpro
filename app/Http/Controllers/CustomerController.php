<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\RegisterMail;
use App\Mail\SampleMail;
use App\Mail\teamRegisterMail;
use App\Models\CarPayment;
use Illuminate\Support\Facades\Mail;

use App\Models\car;
use App\Models\PaymentReport;
use App\Models\CustomerBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Facades\Invoice;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
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

        $request->session()->invalidate();

        $request->session()->regenerateToken();

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

    public function showCar($vin)
    {
        $customer = auth()->user();

        $car = Car::with(['state', 'toPort', 'loadType', 'auction'])->where('vin', $vin)->where('is_active',
            1)->where('customer_id', $customer->id)->first();

        if (!$car) {
            return redirect(route('customer.dashboard'));
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

        return view('frontend.pages.customer.car-info', compact('tr', 'car'));
    }

    public function download($vin)
    {
        $car = Car::where('vin', $vin)->first();

        // Let's get some media.
        $downloads = $car->getMedia();


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
            $car = car::where('vin', $request->search)->first();

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

        $customer = Customer::find($id);
        $customer->assignRole($request->role);
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
        $car          = car::find($request->car_id);

        if($request->team_id==='remove_team'){
            $car->team_id =0;
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
            $cars = car::with(['state', 'toPort', 'payments'])
                ->where('is_active', 1)
                ->where('balance', 0)
                ->where($whereLabel,
                    $customer->id)
                ->paginate(50)
                ->withQueryString();

        } elseif (auth()->user()->hasRole('portmanager')) {
            $cars = car::with(['state', 'toPort', 'payments'])
                ->where('is_active', 1)
                ->paginate(50)
                ->withQueryString();
        } else {
            $cars = car::with(['state', 'toPort', 'payments'])
                ->where('is_active', 1)
                ->where('balance_accounting', '!=', null)
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

//        $cars = car::with(['state', 'toPort', 'payments'])->where('is_active', 1)->where('balance', '!=',
//        0)->where('color', '!=', '#82f98261')->where($whereLabel, $customer->id)->orderBy('created_at',
//        'DESC')->get();

        $cars = car::with(['state', 'toPort', 'payments'])->where('is_active', 1)
            ->where('total_cost', '!=', 0)
            ->where($whereLabel, $customer->id)
            ->orderBy('created_at',
                'DESC')->get();


        $paymentRequest = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)
            ->sum('amount');

        $carBalance = car::where('customer_id', auth()->user()->id)
            ->where('total_cost', '<', 0)
            ->sum('total_cost');


        $payment_report = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)->sum('amount');


        $balance = $paymentRequest - $payment_report - $carBalance;
//        dd($balance);

//        $total_amount_due = car::where('customer_id', auth()->user()->id)->where('balance', '!=', 0)->where('color',
//            '!=', '#82f98261')->where('is_active', 1)->sum('balance');

        $total_amount_due = car::where('customer_id', auth()->user()->id)
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
            ->where('is_approved',
                1)->get();

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

    public function generateInvoice(Request $request)
    {
        $car = car::with('customer')->where('id', $request->car_id)->first();

        $customer = new Buyer([
            'name'          => $car->customer->company_name.' N '.$car->customer->personal_number,
            'custom_fields' => [
                'email' => $car->customer->email,
            ],
        ]);

        $car_name = $car->make.' '.$car->model.' '.$car->year.' '.$car->vin;

        if (session()->get('auth')->child_of <= 0 && !empty($car->balance_accounting[0]['name'])) {
            $items = [];
            // InvoiceItem::make($car_name)->pricePerUnit(session()->get('auth')->parent_of <= 0 ? $car->debit : $car->invoice_debit),

            if (!empty($car->balance_accounting)) {
                foreach ($car->balance_accounting as $balance_accounting) {
                    $key_value = $balance_accounting['name'];
                    if ($balance_accounting['name'] == 'Vehicle cost') {
                        $key_value = $balance_accounting['name']." VIN: ".$car->vin;
                    }

                    $item = InvoiceItem::make(empty($key_value) ? 'undefined' : $key_value)->pricePerUnit($balance_accounting['value']);

                    array_push($items, $item);
                }
            }


            $originalBalance  = $car->debit - $car->recived;
            $createdDate      = Carbon::parse($car->created_at);
            $currentDate      = Carbon::now();
            $differenceInDays = $createdDate->diffInDays($currentDate);
            $percent          = $car->percent; // This should be in decimal form, i.e., 5% should be 0.05.

            // Calculate the percentage amount.
            if (isset($car->extra_price) && $car->extra_price > 0) {
                $percentAmount = (int) $car->extra_price * ($percent / 100);
            } else {
                $percentAmount = (int) $originalBalance * ($percent / 100);
            }

            // Assuming 30 days for simplicity.
            $days = 30;

            // Calculate the daily charge.
            $dailyCharge = (int) number_format($percentAmount, 2, '.', '') / $days;

            // Calculate the new amount due based on the car's percentage
            $newAmountDue = $dailyCharge * $differenceInDays;


            $newTax = InvoiceItem::make('Financed fee')->pricePerUnit($newAmountDue);

            array_push($items, $newTax);

            $notes = [
                'Amount due <strong>$'.$car->balance.'<strong>',
            ];
            $notes = implode("<br>", $notes);

            if ($car->balance > 0) {
                $invoice = Invoice::make()
                    ->status(__('invoices::invoice.due'))
                    ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                    ->logo(public_path('assets/logo.png'))
                    ->buyer($customer)
                    ->currencySymbol('$')
                    ->currencyCode('USD')
                    ->notes($notes)
                    ->payUntilDays(0)
                    ->addItems($items);
            } else {
                $invoice = Invoice::make()
                    ->status(__('invoices::invoice.paid'))
                    ->logo(public_path('assets/logo.png'))
                    ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                    ->buyer($customer)
                    ->currencySymbol('$')
                    ->currencyCode('USD')
                    ->payUntilDays(0)
                    ->addItems($items);
            }
        } else {
            $car_name = $car->make.' '.$car->model.' '.$car->year.' '.$car->vin;
            $item     = InvoiceItem::make($car_name)->pricePerUnit(session()->get('auth')->child_of <= 0 ? $car->debit : $car->invoice_debit);
            $invoice  = Invoice::make()
                ->buyer($customer)
                ->currencySymbol('$')
                ->currencyCode('USD')
                ->payUntilDays(0)
                ->addItem($item);
        }


        return $invoice->stream();
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
        $current = session()->get('auth');


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

            $customer               = new Customer;
            $customer->contact_name = $request->input('contact_name');
            $customer->company_name = "";
            $customer->child_of     = $current->id;
            $customer->phone        = $request->input('phone');
            $customer->email        = $request->input('email');
            $customer->password     = Hash::make($request->input('password'));
            $customer->is_active    = 1; // Set account as active by default
            $customer->child_of     = $current->id; // assign main dealer ID as parent if subdealer is registered by a main dealer
            // Extra is a Dealer profit added for subdealer transactions or we can set manually and give either discount or different price for particular dealers
            $customer->extra_for_team = isset($request->extra_for_team) ? $request->extra_for_team : 0;
            $customer->save();

            $customer->save();

            $customer->assignRole($request->role);

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

            $customer->phone          = $request->input('phone');
            $customer->email          = $request->input('email');
            $customer->password       = Hash::make($request->input('password'));
            $customer->number_of_cars = $request->input('number_of_cars');
            $customer->is_active      = 0; // Set account as active by default

            $customer->save();

            $content = [
                'contact_name' => $request->input('contact_name'),
                'company_name' => $request->input('company_name'),
                'phone'        => $request->input('phone'),
                'email'        => $request->input('email'),
            ];

            Mail::to(config('carbiddata.email'))->send(new RegisterMail($content));

            $customer->assignRole('dealer');
        }


        if (isset($request->team)) {
            return redirect(route('customer.addTeam'))->with('success', 'Your Team member added.');
        }

        return redirect(route('customer.login.get'))->with('success', 'Your account will be activated.');
    }
}
