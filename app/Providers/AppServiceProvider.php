<?php

namespace App\Providers;

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
        Paginator::defaultView('vendor.pagination.bootstrap-4');

        // Bind the composer to a specific view
        View::composer(['partials.aside', 'partials.header'], function (BaseView $view) {

            $balancecomposers=CustomerBalance::where('type', 'fill')->where('is_approved', 0)->get();
            $deposits=$balancecomposers->count();
//            dd($deposits);

            $view->with('balancecomposers', $balancecomposers)->with('deposits', $deposits);

        });
    }
}
