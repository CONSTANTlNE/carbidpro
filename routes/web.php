<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\AnnouncmentController;
use App\Http\Controllers\ArrivedController;
use App\Http\Controllers\AuctionsController;
use App\Http\Controllers\calculatorController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LoadTypesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\CustomerBalanceController;
use App\Http\Controllers\PortEmailController;
use App\Http\Controllers\PortsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShippingPricesController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Models\Car;
use App\Models\Credit;
use App\Models\CustomerBalance;
use App\Models\Setting;
use App\Models\State;
use App\Services\SettingsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Stichoza\GoogleTranslate\GoogleTranslate;


//Route::get('/', function () {
//    return view('welcome');
//});
//

Route::get('/lang/{locale}', function ($locale) {
    Session::put('locale', $locale);

    return redirect()->back();
})->name('set-locale');


// FRONTEND ROUTES No Auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about']);
Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
Route::get('/announcements', [AnnouncmentController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);


Route::get('/privacy-and-policy', function (SettingsService $settings) {
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

    return view('frontend.pages.privacy', compact('tr', 'settings'));
})->name('privacy');
Route::get('/create-setting', function () {
    Setting::create([
        'key'   => 'privacy',
        'label' => 'Privacy',
        'value' => null,
        'type'  => 'textarea',
    ]);
})->name('st');

Route::get('/calculator', [calculatorController::class, 'index']);
Route::post('/calculator', [calculatorController::class, 'calculate'])->name('calculate');
Route::post('/send-email', [CustomerController::class, 'sendEmail'])->name('sendEmail');


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
    });
});


// =======  ADMIN ROUTES  ========

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard/users', 'index')->name('users.index');
        Route::post('/dashboard/users', 'store')->name('users.store');
        Route::post('/dashboard/users/{user}', 'update')->name('users.update');
        Route::delete('/dashboard/users/{user}', 'destroy')->name('users.destroy');
    });

    // Cars
    Route::controller(CarController::class)->group(function () {
        Route::get('/dashboard/cars', 'index')->name('cars.index');
        Route::get('/dashboard/car/create', 'create')->name(name: 'car.create');
        Route::post('/dashboard/car/create', 'store')->name(name: 'car.store');
        Route::get('/dashboard/car/edit/{id}', 'edit')->name(name: 'car.edit');
        Route::post('/dashboard/car/edit/{id}', 'update')->name(name: 'car.update');
        Route::post('/dashboard/car/list-update/{id}', 'listUpdate')->name(name: 'car.listupdate');
        Route::post('/dashboard/car/update', 'listUpdate')->name(name: 'car.updateByid');
        Route::get('/dashboard/cars/status/{slug}', 'showStatus')->name(name: 'car.showStatus');
//        Route::delete('/dashboard/car/{user}', 'destroy')->name('car.destroy');
        Route::post('/dashboard/car/delete', 'destroy')->name('car.destroy');
        Route::post('/calculate-shipping-cost', 'calculateShippingCost')->name('calculate.shipping.cost');
        Route::post('/fetch-locations', 'fetchLocations')->name('fetch.locations');
        Route::post('/fetch-ports', 'fetchPorts')->name('fetch.ports');
        Route::post('/fetch-from-states', 'fetchFromStates')->name('fetch.from.states');
    });

    Route::post('/upload-images', [ImageUploadController::class, 'store'])->name('upload.images.spatie');

    // Containers
    Route::controller(ContainerController::class)->group(function () {
        Route::post('/dashboard/container/group-create', 'groupSelectedCars')->name(name: 'container.selected');
        Route::post('/dashboard/container/create', 'store')->name(name: 'container.store');
        Route::get('/dashboard/container/edit/{id}', 'edit')->name(name: 'container.edit');
        Route::post('/dashboard/container/edit/{id}', 'update')->name(name: 'container.update');
        Route::post('/dashboard/container/list-update', 'listUpdate')->name(name: 'container.listupdate');
        Route::get('/dashboard/containers/status/{slug}', 'showStatus')->name(name: 'container.showStatus');
        Route::post('/dashboard/containers/update/group', 'updateGroup')->name(name: 'container.updateGroup');
        Route::post('/dashboard/containers/send-email', 'sendEmail')->name(name: 'container.sendEmail');
        Route::post('/dashboard/containers/get-avaliable-cars', 'availableCars')->name(name: 'container.availableCars');
        Route::post('/dashboard/containers/replace-car', 'replaceCar')->name(name: 'container.replaceCar');
        Route::post('/dashboard/containers/remove-from-list', 'removeFromList')->name(name: 'container.removeFromList');
        Route::post('/dashboard/containers/add-car-to-group', 'addCarToGroup')->name(name: 'container.addCarToGroup');
        Route::post('/dashboard/containers/filter', 'addCarToGroup')->name(name: 'container.filter');
    });

    // Arrived ?? what this does ??
    Route::controller(ArrivedController::class)->group(function () {
        Route::get('/dashboard/arrived/index', 'index')->name(name: 'arrived.index');
        Route::post('/dashboard/arrived/container/{id}/save', 'update')->name(name: 'arrived.update');
        Route::post('/dashboard/arrived/car/{id}/update', 'updateCar')->name(name: 'arrived.car-update');
        Route::get('/dashboard/arrived/car/{id}/show-image/', 'showImages')->name(name: 'arrived.showImages');
    });

    // Port Emails
    Route::controller(PortEmailController::class)->group(function () {
        Route::get('/dashboard/portemails', 'index')->name('portemail.index');
        Route::post('/dashboard/portemails', 'store')->name('portemail.store');
        Route::post('/dashboard/portemails/{user}', 'update')->name('portemail.update');
        Route::delete('/dashboard/portemails/{user}', 'destroy')->name('portemail.destroy');
    });

    // Balance
    Route::controller(CustomerBalanceController::class)->group(function () {
        // Balance Fills
        Route::get('/dashboard/balances', 'index')->name('customer.balance.index');
        Route::post('/dashboard/balances/aprove', 'approveBalance')->name('customer.balance.approve');
        Route::post('/dashboard/balances/store', 'storeBalance')->name('customer.balance.store');
        Route::post('/dashboard/balances/update', 'updateBalance')->name('customer.balance.update');
        Route::post('/dashboard/balances/delete', 'deleteBalance')->name('customer.balance.delete');
        Route::get('/dashboard/balances/search/customer/htmx', 'searchCustomerHtmx')->name('customer.search.htmx');

        // Car Payments
        Route::get('/dashboard/car-payments', 'carPaymentIndex')->name('carpayment.index');
        Route::post('/dashboard/car-payments/store', 'carPaymentStore')->name('carpayment.store');
        Route::post('/dashboard/car-payments/update', 'carPaymentUpdate')->name('carpayment.update');
        Route::post('/dashboard/car-payments/delete', 'carPaymentDelete')->name('carpayment.delete');
        Route::get('/dashboard/balances/search/car/htmx', 'carSearchHtmx')->name('car.search.htmx');

        Route::get('/dashboard/calculate/percenttilldate',
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
        Route::get('/dashboard/locations', 'index')->name('locations.index');
        Route::post('/dashboard/locations/store', 'store')->name('locations.store');
        Route::post('/dashboard/locations/destroy', 'destroy')->name('locations.destroy');
        Route::post('/dashboard/locations/update', 'update')->name('locations.update');
    });

    // Shipping Prices
    Route::controller(ShippingPricesController::class)->group(function () {
        Route::get('/dashboard/shipping-prices', 'index')->name('shipping-prices.index');
        Route::post('/dashboard/shipping-prices/store', 'store')->name('shipping-prices.store');
        Route::post('/dashboard/shipping-prices/destroy', 'destroy')->name('shipping-prices.destroy');
        Route::get('/dashboard/shipping-prices/locations/htmx', 'htmxLocations')->name('htmx.locations');
        Route::post('/dashboard/shipping-prices/update', 'update')->name('shipping-prices.update');
    });

    // Load Types
    Route::controller(LoadTypesController::class)->group(function () {
        Route::get('/dashboard/load-types', 'index')->name('load-types.index');
        Route::post('/dashboard/load-types/store', 'store')->name('load-types.store');
        Route::post('/dashboard/load-types/destroy', 'destroy')->name('load-types.destroy');
        Route::post('/dashboard/load-types/update', 'update')->name('load-types.update');
    });

    // Ports
    Route::controller(PortsController::class)->group(function () {
        Route::get('/dashboard/ports', 'index')->name('ports.index');
        Route::post('/dashboard/ports/store', 'store')->name('ports.store');
        Route::post('/dashboard/ports/destroy', 'destroy')->name('ports.destroy');
        Route::post('/dashboard/ports/update', 'update')->name('ports.update');
    });

    // Customers
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard/customers', 'customerIndex')->name('customers.index');
        Route::post('/dashboard/customers/store', 'store')->name('customers.store');
        Route::post('/dashboard/customers/activate', 'customerActivate')->name('customer.activate');
        Route::post('/dashboard/customers/destroy', 'destroy')->name('customers.destroy');
        Route::post('/dashboard/customers/update', 'update')->name('customers.update');
    });

    // Credit
    Route::controller(CreditController::class)->group(function () {
        Route::post('/give.credit', 'giveCredit')->name('give.credit');
    });


//    ======= Website Management Routes =======
    Route::controller(SliderController::class)->group(function () {
        Route::get('/sliders', 'index')->name('sliders.index');
        Route::post('/sliders/store', 'store')->name('sliders.store');
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
        Route::post('/services/update', 'update')->name('services.update');
        Route::post('/services/delete', 'delete')->name('services.delete');
    });

    Route::controller(AnnouncementsController::class)->group(function () {
        Route::get('/announcements', 'index')->name('announcements.index');
        Route::post('/announcements/store', 'store')->name('announcements.store');
        Route::post('/announcements/update', 'update')->name('announcements.update');
        Route::post('/announcements/delete', 'delete')->name('announcements.delete');
    });
});


// SEED STATES

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


// ====  Dealer dashboard  ROUTES  ====

Route::prefix('dealer')->middleware(['auth:customer'])->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/record/{id}/table', 'getTableData')->name('record.table');

        Route::get('/main', 'showDashboard')->name('customer.dashboard');

        Route::get('/archived-cars', 'showDashboard')->name('customer.archivedcars');

        Route::get('/car-info/{vin}', 'showCar')->name('customer.car-info');

        Route::post('/save-release', 'saveRelease')->name('saveRelease');

        Route::get('/payment-registration', 'showPaymentRequest')->name('customer.payment_registration');

        Route::get('/payment-history', 'paymentHistory')->name('customer.payment_history');

        Route::post('/generate-invoice', 'generateInvoice')->name('customer.generate_invoice');

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
});

// TEST ROUTEs

Route::get('/cache', function () {
//    if(Cache::has('translated')) {
//        Cache::forget('translated');
//
//        return 'Cache cleared';
//    }


    $gg = Cache::get('headerStatics'.session()->get('locale'))['Logout'];

    return $gg;
});


route::get('/logout', function () {
    Auth::guard('web')->logout();

    session()->invalidate();

    session()->regenerateToken();
});

route::get('/credit', function () {


    $car_id      = 4;
    $customer_id = 1;

    // first update old credit record
    $oldrecord=Credit::where('car_id', $car_id)
        ->where('customer_balance_id', 54)->first();
    $oldrecord->paid_amount=500;
    $oldrecord->issue_or_payment_date="2024-11-10";
    $oldrecord->save();

    // Update balance payment also
    $balance2 = CustomerBalance::where('id', $oldrecord->customer_balance_id)->first();
    $balance2->carpayment_date = $oldrecord->issue_or_payment_date;
    $balance2->amount = -$oldrecord->paid_amount;
    $balance2->save();



    $credit = Credit::where('car_id', $car_id)
        ->where('customer_id', $customer_id)
        ->get();


// ცალკე ვინახავ ძველ მონაცემს გარდა გააფდეითებულისა , ვუმატებ გააფდეითებულს ,
// ვსორტავ issue_or_payment_date ით ,
// ვშლი ყველა ძველ მონაცემს და ვატარებ შენახულ მონაცემებს ბაზაში



    $sortedCredit = $credit->sortBy('issue_or_payment_date')->values();

    // sorting needed if payment date is changed
    $creditAmount = $sortedCredit->first()['credit_amount'];

    // Recalculate interests for each record
    foreach ($sortedCredit as $index => $cr) {

        if ($index >= 1) {

            $paymentDate = Carbon::parse($cr['issue_or_payment_date']);
            $creditDays = $paymentDate->diffInDays(Carbon::parse($sortedCredit[$index - 1]['issue_or_payment_date']));

            $accruedPercent = $creditAmount * ($cr['monthly_percent'] * 12 / 365) * $creditDays;
            $creditAmount += $accruedPercent - $cr['paid_amount'];


            $cr->credit_amount = $creditAmount;
            $cr->accrued_percent = $accruedPercent;
            $cr->save();
        }
    }

});

require __DIR__.'/auth.php';
