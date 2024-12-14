<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncmentController;
use App\Http\Controllers\ArrivedController;
use App\Http\Controllers\AuctionsController;
use App\Http\Controllers\calculatorController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LoadTypesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\PortEmailController;
use App\Http\Controllers\PortsController;
use App\Http\Controllers\ShippingPricesController;
use App\Http\Controllers\UserController;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Stichoza\GoogleTranslate\GoogleTranslate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//

Route::get('/lang/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
})->name('set-locale');



// FRONTEND ROUTES No Auth

Route::get('/', [HomeController::class, 'index']);
Route::get('/announcements', [AnnouncmentController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/about', function (SettingsService $settings) {
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
    return view('frontend.pages.about', compact('tr', 'settings'));
})->name('about');
Route::get('/terms-and-conditions', function (SettingsService $settings) {
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
    return view('frontend.pages.terms', compact('tr', 'settings'));
})->name('terms');
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
        'key' => 'privacy',
        'label' => 'Privacy',
        'value' => null,
        'type' => 'textarea',
    ]);


})->name('st');
Route::get('/calculator', [calculatorController::class, 'index']);
Route::post('/calculator', [calculatorController::class, 'calculate'])->name('calculate');
Route::post('/send-email', [CustomerController::class, 'sendEmail'])->name('sendEmail');

Route::prefix('customer')->group(function () {
    Route::get('/login', [CustomerController::class, 'showLoginForm'])->name('customer.login.get');
    Route::post('/login', [CustomerController::class, 'login'])->name('customer.login.post');
    Route::get('/register', [CustomerController::class, 'showRegistrationForm'])->name('customer.register.get');
    Route::post('/register', [CustomerController::class, 'register'])->name('customer.register.post');
    Route::get('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
});

Route::get('/search', [CustomerController::class, 'searchResult'])
    ->name('customer.searchResult');

Route::get('/download-images/{vin}', [CustomerController::class, 'download'])
    ->name('customer.download_images');








// ====  ADMIN ROUTES  ====

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    // Users
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/dashboard/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/dashboard/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Cars
    Route::get('/dashboard/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/dashboard/car/create', [CarController::class, 'create'])->name(name: 'car.create');
    Route::post('/dashboard/car/create', [CarController::class, 'store'])->name(name: 'car.store');
    Route::get('/dashboard/car/edit/{id}', [CarController::class, 'edit'])->name(name: 'car.edit');
    Route::post('/dashboard/car/edit/{id}', [CarController::class, 'update'])->name(name: 'car.update');
    Route::post('/dashboard/car/list-update/{id}', [CarController::class, 'listUpdate'])->name(name: 'car.listupdate');
    Route::post('/dashboard/car/update', [CarController::class, 'listUpdate'])->name(name: 'car.updateByid');
    Route::get('/dashboard/cars/status/{slug}', [CarController::class, 'showStatus'])->name(name: 'car.showStatus');
    Route::delete('/dashboard/car/{user}', [CarController::class, 'destroy'])->name('car.destroy');


    Route::post('/calculate-shipping-cost', [CarController::class, 'calculateShippingCost'])->name('calculate.shipping.cost');
    Route::post('/fetch-locations', [CarController::class, 'fetchLocations'])->name('fetch.locations');
    Route::post('/fetch-ports', [CarController::class, 'fetchPorts'])->name('fetch.ports');
    Route::post('/fetch-from-states', [CarController::class, 'fetchFromStates'])->name('fetch.from.states');
    Route::post('/upload-images', [ImageUploadController::class, 'store'])->name('upload.images.spatie');


    // Containers
    Route::post('/dashboard/container/group-create', [ContainerController::class, 'groupSelectedCars'])->name(name: 'container.selected');
    Route::post('/dashboard/container/create', [ContainerController::class, 'store'])->name(name: 'container.store');
    Route::get('/dashboard/container/edit/{id}', [ContainerController::class, 'edit'])->name(name: 'container.edit');
    Route::post('/dashboard/container/edit/{id}', [ContainerController::class, 'update'])->name(name: 'container.update');
    Route::post('/dashboard/container/list-update', [ContainerController::class, 'listUpdate'])->name(name: 'container.listupdate');
    Route::get('/dashboard/containers/status/{slug}', [ContainerController::class, 'showStatus'])->name(name: 'container.showStatus');
    Route::post('/dashboard/containers/update/group', [ContainerController::class, 'updateGroup'])->name(name: 'container.updateGroup');
    Route::post('/dashboard/containers/send-email', [ContainerController::class, 'sendEmail'])->name(name: 'container.sendEmail');
    Route::post('/dashboard/containers/get-avaliable-cars', [ContainerController::class, 'availableCars'])->name(name: 'container.availableCars');
    Route::post('/dashboard/containers/replace-car', [ContainerController::class, 'replaceCar'])->name(name: 'container.replaceCar');
    Route::post('/dashboard/containers/remove-from-list', [ContainerController::class, 'removeFromList'])->name(name: 'container.removeFromList');
    Route::post('/dashboard/containers/add-car-to-group', [ContainerController::class, 'addCarToGroup'])->name(name: 'container.addCarToGroup');
    Route::post('/dashboard/containers/filter', [ContainerController::class, 'addCarToGroup'])->name(name: 'container.filter');

    // Arrived
    Route::get('/dashboard/arrived/index', [ArrivedController::class, 'index'])->name(name: 'arrived.index');
    Route::post('/dashboard/arrived/container/{id}/save', [ArrivedController::class, 'update'])->name(name: 'arrived.update');
    Route::post('/dashboard/arrived/car/{id}/update', [ArrivedController::class, 'updateCar'])->name(name: 'arrived.car-update');
    Route::get('/dashboard/arrived/car/{id}/show-image/', [ArrivedController::class, 'showImages'])->name(name: 'arrived.showImages');

    // Port Emails
    Route::get('/dashboard/portemails', [PortEmailController::class, 'index'])->name('portemail.index');
    Route::post('/dashboard/portemails', [PortEmailController::class, 'store'])->name('portemail.store');
    Route::post('/dashboard/portemails/{user}', [PortEmailController::class, 'update'])->name('portemail.update');
    Route::delete('/dashboard/portemails/{user}', [PortEmailController::class, 'destroy'])->name('portemail.destroy');

    // Payment Reports
    Route::get('/dashboard/payment-reports', [PaymentReportController::class, 'index'])->name('paymentreports.index');
    Route::post('/dashboard/payment-reports', [PaymentReportController::class, 'store'])->name('paymentreports.store');
    Route::post('/dashboard/payment-reports/{id}', [PaymentReportController::class, 'update'])->name('paymentreports.update');
    Route::delete('/dashboard/payment-reports/{id}', [PaymentReportController::class, 'destroy'])->name('paymentreports.destroy');

    // Auctions
    Route::controller(AuctionsController::class)->group(function() {
        Route::get('/dashboard/auctions',  'index')->name('auctions.index');
        Route::post('/dashboard/auctions/store', 'store')->name('auctions.store');
        Route::post('/dashboard/auctions/destroy',  'destroy')->name('auctions.destroy');
        Route::post('/dashboard/auctions/update',  'update')->name('auctions.update');
    });

    // Load Types
    Route::controller(LocationsController::class)->group(function () {
        Route::get('/dashboard/locations',  'index')->name('locations.index');
        Route::post('/dashboard/locations/store', 'store')->name('locations.store');
        Route::post('/dashboard/locations/destroy',  'destroy')->name('locations.destroy');
        Route::post('/dashboard/locations/update',  'update')->name('locations.update');
    });

    // Shipping Prices
    Route::controller(ShippingPricesController::class)->group(function () {
        Route::get('/dashboard/shipping-prices',  'index')->name('shipping-prices.index');
        Route::post('/dashboard/shipping-prices/store', 'store')->name('shipping-prices.store');
        Route::post('/dashboard/shipping-prices/destroy',  'destroy')->name('shipping-prices.destroy');
        Route::get('/dashboard/shipping-prices/locations/htmx','htmxLocations')->name('htmx.locations');
        Route::post('/dashboard/shipping-prices/update',  'update')->name('shipping-prices.update');
    });

    // Load Types
    Route::controller(LoadTypesController::class)->group(function () {
        Route::get('/dashboard/load-types',  'index')->name('load-types.index');
        Route::post('/dashboard/load-types/store', 'store')->name('load-types.store');
        Route::post('/dashboard/load-types/destroy',  'destroy')->name('load-types.destroy');
        Route::post('/dashboard/load-types/update',  'update')->name('load-types.update');
    });

    // Ports
    Route::controller(PortsController::class)->group(function () {
        Route::get('/dashboard/ports',  'index')->name('ports.index');
        Route::post('/dashboard/ports/store', 'store')->name('ports.store');
        Route::post('/dashboard/ports/destroy',  'destroy')->name('ports.destroy');
        Route::post('/dashboard/ports/update',  'update')->name('ports.update');
    });

    // Customers
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard/customers',  'customerIndex')->name('customers.index');
        Route::post('/dashboard/customers/store', 'store')->name('customers.store');
        Route::post('/dashboard/customers/activate', 'customerActivate')->name('customer.activate');
        Route::post('/dashboard/customers/destroy',  'destroy')->name('customers.destroy');
        Route::post('/dashboard/customers/update',  'update')->name('customers.update');
    });
});





// ====  Dealer  ROUTES  ====

Route::prefix('dealer')->middleware(['auth:customer'])->group(function () {

    Route::get('/record/{id}/table', [CustomerController::class, 'getTableData'])->name('record.table');

    Route::get('/main', [CustomerController::class, 'showDashboard'])
        ->name('customer.dashboard');

    Route::get('/archived-cars', [CustomerController::class, 'showDashboard'])
        ->name('customer.archivedcars');

    Route::get('/car-info/{vin}', [CustomerController::class, 'showCar'])
        ->name('customer.car-info');



    Route::post('/save-release', [CustomerController::class, 'saveRelease'])->name('saveRelease');

    Route::get('/payment-registration', [CustomerController::class, 'showPaymentRequest'])
        ->name('customer.payment_registration');

    Route::post('/payment-registration', [CustomerController::class, 'registrPaymentRequest'])
        ->name('customer.payment_registration_submit');

    Route::get('/payment-history', [CustomerController::class, 'paymentHistory'])
        ->name('customer.payment_history');

    Route::post('/generate-invoice', [CustomerController::class, 'generateInvoice'])
        ->name('customer.generate_invoice');

    Route::post('/set-car-amount', [CustomerController::class, 'setCarAmount'])
        ->name('customer.set_amount');


    Route::get('/team-list', [CustomerController::class, 'teamList'])
        ->name('customer.teamList');

    Route::get('/add-team', [CustomerController::class, 'addTeam'])
        ->name('customer.addTeam');

    Route::get('/team-edit/{id}', [CustomerController::class, 'teamEdit'])
        ->name('customer.teamEdit');

    Route::post('/team-update/{id}', [CustomerController::class, 'teamUpdate'])
        ->name('customer.teamUpdate');

    Route::post('/team-remove/{id}', [CustomerController::class, 'removeTeam'])
        ->name('customer.removeTeam');

    Route::post('/car-assing-team', [CustomerController::class, 'addTeamToCar'])
        ->name('customer.addTeamToCar');

    Route::post('/add-invoice-price', [CustomerController::class, 'addInvoicePrice'])
        ->name('customer.addInvoicePrice');
});

require __DIR__ . '/auth.php';
