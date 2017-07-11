<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'invoices'              =>      'App\Models\InvoicesModels\Invoice',
            'advances'              =>      'App\Models\AdvancesModels\Advance',
            'mobile_payments'       =>      'App\Models\MobilePaymentModels\MobilePayment',
            'claims'                =>      'App\Models\ClaimsModels\Claim',
            'lpos'                  =>      'App\Models\LPOModels\Lpo',
            'payments'              =>      'App\Models\PaymentModels\Payment',
            'deliveries'            =>      'App\Models\DeliveriesModels\Delivery',
            ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
        }
    }
}
