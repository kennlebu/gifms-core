<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Events\StatementPrepared;

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
            'users'                 =>      'App\Models\StaffModels\User',
            'staff'                 =>      'App\Models\StaffModels\Staff',
            'allocations'           =>      'App\Models\AllocationModels\Allocation',
            ]);
        // $dispatcher = new Dispatcher;
        // $dispatcher->listen(Illuminate\Database\Events\StatementPrepared::class, function ($event) {
        //     $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        // });
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
