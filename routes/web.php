<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\ArrivedController;
use App\Http\Controllers\AuctionsController;
use App\Http\Controllers\calculatorController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExtraexpenceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoadTypesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\CustomerBalanceController;
use App\Http\Controllers\PortCitiesController;
use App\Http\Controllers\PortEmailController;
use App\Http\Controllers\PortsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShippingPricesController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\UserController;
use App\Models\Customer;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;



Route::get('/oldversionlogin', function ( Illuminate\Http\Request $request) {

    $token = $request->get('token');
    $signature = $request->get('signature');

    $sharedSecret = env('CARBID_SECRET');
    $expectedSignature = hash_hmac('sha256', $token, $sharedSecret);

    if (!hash_equals($expectedSignature, $signature)) {
        return  response()->json(['error' => 'Invalid signature']);
    }

    $payload = json_decode(base64_decode($token), true);

    if (isset($payload['id'] ,$payload['timestamp'])) {
        $user = Customer::where('id', $payload['id'])
            ->first();

        if ($user) {
            Auth::guard('customer')->login($user);
            Session::put('auth', $user);
            return redirect('/dashboard/main');
        }
    }

    return  response()->json(['error' => 'User Not Found']);

});


Route::get('/lang/{locale}', function ($locale) {
    Session::put('locale', $locale);

    return redirect()->back();
})->name('set-locale');


// FRONTEND Website ROUTES No Auth
Route::controller(HomeController::class)->group(function () {
    Route::get('/',  'index')->name('home');
    Route::get('/about',  'about');
    Route::get('/terms-and-conditions',  'terms')->name('terms');
    Route::get('/announcements',  'announcements');
    Route::get('/contact', 'contact');
});

// Customers / Dealers Login-registration
Route::prefix('dealer')->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('customer.login.get');
        Route::post('/login', 'login')->name('customer.login.post');
        Route::get('/register', 'showRegistrationForm')->name('customer.register.get');
        Route::post('/register', 'register')->name('customer.register.post');
        Route::get('/logout', 'logout')->name('customer.logout');
        Route::get('/search', 'searchResult')->name('customer.searchResult');
        Route::get('/download-images/{vin}', 'download')->name('customer.download_images');
        Route::post('/send-email',  'sendEmail')->name('sendEmail');
    });
});

// =======  ADMIN ROUTES  ========
Route::prefix('dashboard') ->middleware(['auth', 'verified'])->group(function () {

    // Dasboard Analitics
    Route::controller(AdminController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('dashboard');
    });

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::post('/users/store', 'store')->name('users.store');
        Route::post('/users/update', 'update')->name('users.update');
        Route::post('/users/delete', 'delete')->name('users.destroy');
    });

    // Cars
    Route::controller(CarController::class)->group(function () {
        Route::get('/cars', 'index')->name('cars.index');
        Route::get('/cars/archive', 'index')->name('cars.index.trashed');
        Route::get('/car/create', 'create')->name(name: 'car.create');
        Route::post('/car/create', 'store')->name(name: 'car.store');
        Route::get('/car/edit/{id}', 'edit')->name(name: 'car.edit');
        Route::post('/car/edit/{id}', 'update')->name(name: 'car.update');
        Route::post('/car/list-update/{id}', 'listUpdate')->name(name: 'car.listupdate');
        Route::post('/car/update', 'listUpdate')->name(name: 'car.updateByid');
        Route::get('/cars/status/{slug}', 'showStatus')->name(name: 'car.showStatus');
//        Route::delete('/dashboard/car/{user}', 'destroy')->name('car.destroy');
        Route::post('/car/delete', 'destroy')->name('car.destroy');
        Route::post('/car/delete/permanently', 'destroyPermanently')->name('car.destroy.permanently');
        Route::post('/calculate-shipping-cost', 'calculateShippingCost')->name('calculate.shipping.cost');
        Route::post('/fetch-locations', 'fetchLocations')->name('fetch.locations');
        Route::post('/fetch-ports', 'fetchPorts')->name('fetch.ports');
        Route::post('/fetch-from-states', 'fetchFromStates')->name('fetch.from.states');
        Route::get('/ready-for-pickup', 'readyForPickup')->name('car.readyforpickup');
        Route::get('/cars/archive/restore/{id}', 'restoreTrashed')->name('car.trashed.restore');

    });

    Route::post('/upload-images', [ImageUploadController::class, 'store'])->name('upload.images.spatie');

    // Containers
    Route::controller(ContainerController::class)->group(function () {
        // Creates ContainerGroup
        Route::post('/container/group-create', 'groupSelectedCars')->name(name: 'container.selected');
        Route::post('/container/create', 'store')->name(name: 'container.store');
        Route::get('/container/edit/{id}', 'edit')->name(name: 'container.edit');
        Route::post('/container/edit/{id}', 'update')->name(name: 'container.update');
        Route::post('/container/list-update', 'listUpdate')->name(name: 'container.listupdate');
        Route::get('/containers/status/{slug}', 'showStatus')->name(name: 'container.showStatus');
        Route::post('/containers/update/group', 'updateGroup')->name(name: 'container.updateGroup');
        Route::post('/containers/send-email', 'sendEmail')->name(name: 'container.sendEmail');
        Route::post('/containers/get-avaliable-cars', 'availableCars')->name(name: 'container.availableCars');
        Route::post('/containers/replace-car', 'replaceCar')->name(name: 'container.replaceCar');
        Route::post('/containers/remove-from-list', 'removeFromList')->name(name: 'container.removeFromList');
        Route::post('/containers/add-car-to-group', 'addCarToGroup')->name(name: 'container.addCarToGroup');
        Route::post('/containers/filter', 'addCarToGroup')->name(name: 'container.filter');
    });

    Route::controller(ArrivedController::class)->group(function () {
        Route::get('/arrived/index', 'index')->name(name: 'arrived.index');
        Route::post('/arrived/container/{id}/save', 'update')->name(name: 'arrived.update');
        Route::post('/arrived/car/{id}/update', 'updateCar')->name(name: 'arrived.car-update');
        Route::get('/arrived/car/{id}/show-image/', 'showImages')->name(name: 'arrived.showImages');
    });

    // Port Emails
    Route::controller(PortEmailController::class)->group(function () {
        Route::get('/portemails', 'index')->name('portemail.index');
        Route::post('/portemails', 'store')->name('portemail.store');
        Route::post('/portemails/{user}', 'update')->name('portemail.update');
        Route::delete('/portemails/{user}', 'destroy')->name('portemail.destroy');
    });

    // Balance
    Route::controller(CustomerBalanceController::class)->group(function () {
        // Balance Fills
        Route::get('/deposits', 'index')->name('customer.balance.index');
        Route::post('/deposits/aprove', 'approveBalance')->name('customer.balance.approve');
        Route::post('/deposits/store', 'storeBalance')->name('customer.balance.store');
        Route::post('/deposits/update', 'updateBalance')->name('customer.balance.update');
        Route::post('/deposits/delete', 'deleteBalance')->name('customer.balance.delete');
        Route::get('/deposits/search/customer/htmx', 'searchCustomerHtmx')->name('customer.search.htmx');

        // Car Payments
        Route::get('/car-payments', 'carPaymentIndex')->name('carpayment.index');
        Route::post('/car-payments/store', 'carPaymentStore')->name('carpayment.store');
        Route::post('/car-payments/update', 'carPaymentUpdate')->name('carpayment.update');
        Route::post('/car-payments/delete', 'carPaymentDelete')->name('carpayment.delete');
        Route::get('/balances/search/car/htmx', 'carSearchHtmx')->name('car.search.htmx');

        Route::get('/calculate/percenttilldate',
            'percentTillDateHtmx')->name('car.calculate.percenttilldate');
    });

    // Auctions
    Route::controller(AuctionsController::class)->group(function () {
        Route::get('/dashboard/auctions', 'index')->name('auctions.index');
        Route::post('/dashboard/auctions/store', 'store')->name('auctions.store');
        Route::post('/dashboard/auctions/destroy', 'destroy')->name('auctions.destroy');
        Route::post('/dashboard/auctions/update', 'update')->name('auctions.update');
    });

    // Load Types (locations)
    Route::controller(LocationsController::class)->group(function () {
        Route::get('/locations', 'index')->name('locations.index');
        Route::post('/locations/store', 'store')->name('locations.store');
        Route::post('/locations/destroy', 'destroy')->name('locations.destroy');
        Route::post('/locations/update', 'update')->name('locations.update');
    });

    // Shipping Prices
    Route::controller(ShippingPricesController::class)->group(function () {
        Route::get('/shipping-prices', 'index')->name('shipping-prices.index');
        Route::post('/shipping-prices/store', 'store')->name('shipping-prices.store');
        Route::post('/shipping-prices/destroy', 'destroy')->name('shipping-prices.destroy');
        Route::get('/shipping-prices/locations/htmx', 'htmxLocations')->name('htmx.locations');
        Route::post('/shipping-prices/update', 'update')->name('shipping-prices.update');
    });

    // Load Types
    Route::controller(LoadTypesController::class)->group(function () {
        Route::get('/load-types', 'index')->name('load-types.index');
        Route::post('/load-types/store', 'store')->name('load-types.store');
        Route::post('/load-types/destroy', 'destroy')->name('load-types.destroy');
        Route::post('/load-types/update', 'update')->name('load-types.update');
    });

    // Ports
    Route::controller(PortsController::class)->group(function () {
        Route::get('/ports', 'index')->name('ports.index');
        Route::post('/ports/store', 'store')->name('ports.store');
        Route::post('/ports/destroy', 'destroy')->name('ports.destroy');
        Route::post('/ports/update', 'update')->name('ports.update');
    });

    // Countries
    Route::controller(CountriesController::class)->group(function () {
        Route::get('/countries', 'index')->name('countries.index');
        Route::post('/countries/store', 'store')->name('countries.store');
        Route::post('/countries/destroy', 'delete')->name('countries.delete');
        Route::post('/countries/update', 'update')->name('countries.update');
        Route::post('/countries/activate', 'activate')->name('countries.activate');
    });

    // PortCities
    Route::controller(PortCitiesController::class)->group(function () {
        Route::get('/portcities', 'index')->name('portcities.index');
        Route::post('/portcities/store', 'store')->name('portcities.store');
        Route::post('/portcities/destroy', 'delete')->name('portcities.delete');
        Route::post('/portcities/update', 'update')->name('portcities.update');

    });

    // Customers
    Route::controller(AdminController::class)->group(function () {
        Route::get('/customers', 'customerIndex')->name('customers.index');
        Route::get('/archived-customers', 'customerIndex')->name('customers.archived');
        Route::get('/archived-customers/restore/{id}', 'restore')->name('customers.restore');
//        Route::post('/customers/store', 'store')->name('customers.store');
        Route::post('/customers/activate', 'customerActivate')->name('customer.activate');
        Route::post('/customers/destroy', 'delete')->name('customers.delete');
        Route::post('/customers/update', 'update')->name('customers.update');
        Route::post('/customers/auto/login', 'autoLogin')->name('customers.autologin');
    });

    // Credit
    Route::controller(CreditController::class)->group(function () {
        Route::post('/give.credit', 'giveCredit')->name('give.credit');
    });

    // SMS
    Route::controller(SmsController::Class)->group(function (){

        Route::get('/sms/drafts','drafts')->name('sms.drafts');
        Route::get('/sms/drafts/save','storeDraft')->name('sms.drafts.store');
        Route::get('/sms/drafts/activate','activateDraft')->name('sms.drafts.activate');
        Route::get('/sms/drafts/update','updateDraft')->name('sms.drafts.update');
        Route::get('/sms/drafts/delete','deleteDraft')->name('sms.drafts.delete');
        Route::get('/sms/invalids/clear','clearInvalids')->name('sms.invalid.clear');

        Route::get('/sms/all','allsms')->name('sms.all');
        Route::post('/sms/all/send','sendAll')->name('sms.send.all');
        Route::post('/sms/recipient/send','sendRecipient')->name('sms.send.recipient');
        Route::get('/sms/selected','selectedsms')->name('sms.selected');
        Route::post('/sms/selected/send','sendSelected')->name('sms.send.selected');

        Route::post('/sms/newdeposit/number/update','updateDepositNumber')->name('sms.deposit.number.update');

    });

    //    ======= Website Management Routes =======
    Route::controller(SliderController::class)->group(function () {
        Route::get('/sliders', 'index')->name('sliders.index');
        Route::post('/sliders/store', 'store')->name('sliders.store');
        Route::post('/sliders/activate', 'activate')->name('sliders.activate');
        Route::post('/sliders/update', 'update')->name('sliders.update');
        Route::post('/sliders/delete', 'delete')->name('sliders.delete');
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index');
        Route::post('/settings/store', 'store')->name('settings.store');
        Route::get('/settings/update/htmx', 'updateHtmx')->name('settings.update.htmx');
        Route::post('/settings/update', 'update')->name('settings.update');
        Route::post('/settings/delete', 'delete')->name('settings.delete');
    });

    Route::controller(ServicesController::class)->group(function () {
        Route::get('/services', 'index')->name('services.index');
        Route::post('/services/store', 'store')->name('services.store');
        Route::post('/services/activate', 'activate')->name('services.activate');
        Route::post('/services/update', 'update')->name('services.update');
        Route::post('/services/delete', 'delete')->name('services.delete');
    });

    Route::controller(AnnouncementsController::class)->group(function () {
        Route::get('/announcements', 'index')->name('announcements.index');
        Route::post('/announcements/store', 'store')->name('announcements.store');
        Route::post('/announcements/activate', 'activate')->name('announcements.activate');
        Route::get('/announcements/update/htmx', 'updateHtmx')->name('announcements.update.htmx');
        Route::post('/announcements/update', 'update')->name('announcements.update');
        Route::post('/announcements/delete', 'delete')->name('announcements.delete');
    });

    Route::controller(ExtraexpenceController::class)->group(function () {
        route::get('/htmx/get/expenses', 'htmxGetExtraExpense')->name('htmx.get.extraexpense');
    });

    // Manual upload of customers and users OF/From old app
    route::get('/uploadolddata', function () {

        $csrfExpiration = config('session.lifetime') * 60;
        return view('uploadcustomers',compact('csrfExpiration'));

    });
    route::post('/uploadcustomers', function (Request $request) {

        $storedFile = $request->file->store('public');
        $filePath = storage_path('app/' . $storedFile);
        $jsonContents = file_get_contents($filePath);
        $data = json_decode($jsonContents, true); // Use `true` to get an associative array



    foreach ($data[2]['data'] as $customer){

        Customer::create([
            'id'=>$customer['id'],
            'company_name'=>$customer['company_name'],
            'contact_name'=>$customer['contact_name'],
            'email'=>$customer['email'],
            'phone'=>$customer['phone'],
            'is_active'=>$customer['is_active'],
            'number_of_cars'=>$customer['number_of_cars'],
            'password'=>$customer['password'],
            'child_of'=>$customer['parent_of'],
            'personal_number'=>$customer['personal_number'],
            'extra_for_team'=>$customer['extra_for_team'],
            'username'=>$customer['username'],
            'created_at'=>$customer['created_at'],
            'updated_at'=>$customer['updated_at'],
            'deleted_at'=>$customer['deleted_at'],
            'image'=>$customer['image'],
        ]);
    }



        return view('testfile',compact('data'));

    })->name('customer.upload');
    route::post('/uploadusers', function (Request $request) {

        $storedFile = $request->file->store('public');
        $filePath = storage_path('app/' . $storedFile);
        $jsonContents = file_get_contents($filePath);
        $data = json_decode($jsonContents, true); // Use `true` to get an associative array

        dd($data[2]['data']);

//
//    foreach ($data[2]['data'] as $customer){
//
//        Customer::create([
//            'id'=>$customer['id'],
//            'company_name'=>$customer['company_name'],
//            'contact_name'=>$customer['contact_name'],
//            'email'=>$customer['email'],
//            'phone'=>$customer['phone'],
//            'is_active'=>$customer['is_active'],
//            'number_of_cars'=>$customer['number_of_cars'],
//            'password'=>$customer['password'],
//            'child_of'=>$customer['parent_of'],
//            'personal_number'=>$customer['personal_number'],
//            'extra_for_team'=>$customer['extra_for_team'],
//            'username'=>$customer['username'],
//            'created_at'=>$customer['created_at'],
//            'updated_at'=>$customer['updated_at'],
//            'deleted_at'=>$customer['deleted_at'],
//            'image'=>$customer['image'],
//        ]);
//    }

        unlink($filePath);

        return view('testfile',compact('data'));

    })->name('user.upload');

});

// ====  Dealer dashboard  ROUTES  ====
Route::prefix('dealer')->middleware(['auth:customer'])->group(function () {

    Route::get('/calculator', [calculatorController::class, 'index'])->name('calculator.index');
    Route::post('/calculator', [calculatorController::class, 'calculate'])->name('calculate');

    Route::controller(CustomerController::class)->group(function () {

        Route::get('/record/{id}/table', 'getTableData')->name('record.table');

        Route::get('/main', 'showDashboard')->name('customer.dashboard');

        Route::get('/archived-cars', 'showDashboard')->name('customer.archivedcars');

        Route::get('/car-info/{vin}', 'showCar')->name('customer.car-info');

        Route::post('/save-release', 'saveRelease')->name('saveRelease');

        Route::get('/payment-registration', 'showPaymentRequest')->name('customer.payment_registration');

        Route::get('/payment-history', 'paymentHistory')->name('customer.payment_history');

        Route::get('/team-list', 'teamList')->name('customer.teamList');

        Route::get('/add-team', 'addTeam')->name('customer.addTeam');

        Route::get('/team-edit/{id}', 'teamEdit')->name('customer.teamEdit');

        Route::post('/team-update/{id}', 'teamUpdate')->name('customer.teamUpdate');

        Route::post('/team-remove/{id}', 'removeTeam')->name('customer.removeTeam');

        Route::post('/car-assing-team', 'addTeamToCar')->name('customer.addTeamToCar');

        Route::post('/add-invoice-price', 'addInvoicePrice')->name('customer.addInvoicePrice');
    });

    Route::controller(CustomerBalanceController::class)->group(function () {
        Route::post('/payment-registration', 'registrPaymentRequest')->name('customer.payment_registration_submit');
        // Dealer pays for a particular car from General balance
        Route::post('/set-car-amount', 'setCarAmount')->name('customer.set_amount');
    });

    route::controller(InvoiceController::Class)->group(function (){
        Route::get('/generate-invoice', 'generateInvoice')->name('customer.generate_invoice');
    });

});

// Old Website Authorization

Route::middleware('auth')->group(function () {
    Route::get('/generate-link', function (Request $request) {
        if ($request->has('customer_id')){
            $user = $request->get('customer_id');
        } else{
            $user = auth()->user()->id;
        }

        // Generate token
        $payload = [
            'id' => $user,
            'timestamp' => now()->timestamp
        ];

        $sharedSecret = env('CARBID_SECRET'); // A shared key between both apps
        $token = base64_encode(json_encode($payload)); // Base64 encode for easy transmission
        $signature = hash_hmac('sha256', $token, $sharedSecret); // Sign the token

        $oldSite = 'https://carbidpro.com/oldversionlogin?token=' . urlencode($token) . '&signature=' . urlencode($signature);

        return  redirect()->to($oldSite);

    })->name('generate.link');
});





// SEED STATES needed before DB:seed
Route::get('/savelocations', function () {
    $state_api = Illuminate\Support\Facades\Http::get('https://gist.githubusercontent.com/mshafrir/2646763/raw/8b0dbb93521f5d6889502305335104218454c2bf/states_hash.json');

    $states = $state_api->json();


    foreach (array_keys($states) as $state) {
        State::create([
            'name' => $state,
        ]);
    }

    // foreach ($locations as $location) {
    //     Location::create([
    //         'name' => $location['name'],
    //         'auction_id' => 3,
    //         'is_active' => 1,
    //     ]);
    // }

})->name('st');



// TEST ROUTES

route::get('/logout', function () {

    Auth::guard('web')->logout();

    session()->invalidate();

    session()->regenerateToken();
});




require __DIR__.'/auth.php';
