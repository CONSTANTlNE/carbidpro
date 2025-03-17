<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\CustomerBalance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\View;
use Illuminate\View\View as BaseView;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventAccessingMissingAttributes();
        Model::unguard();


        Paginator::defaultView('vendor.pagination.bootstrap-4');







        $balancecomposers = CustomerBalance::where('type', 'fill')
            ->with('customer')
            ->where('is_approved', 0)->get();
        $deposits         = $balancecomposers->count();


        $newcustomers   = Customer::where('is_active', 0)->get(['id','contact_name']);
        $customerscount = $newcustomers->count();
        $archivedCount  = Car::onlyTrashed()->count();


        View::share([
            'balancecomposers' => $balancecomposers,
            'deposits' => $deposits,
            'newcustomers' => $newcustomers,
            'customerscount' => $customerscount,
            'archivedCount' => $archivedCount,
        ]);


    }
}
