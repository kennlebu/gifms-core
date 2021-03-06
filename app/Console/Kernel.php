<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\MigrateFiles::class,
        Commands\MigratePasswords::class,
        Commands\GenerateTransactionDocuments::class,
        Commands\ReformatPhoneNumbers::class,
        Commands\TestSeed::class,
        Commands\MigrateMobilePaymentDates::class,
        Commands\CopyActivityObjectives::class,
        Commands\GenerateRequisitionDocuments::class,
        Commands\RefractorBudgets::class,
        Commands\CreateBudgeetExpenditures::class,
        Commands\PopuateVatPercentage::class,
        Commands\RefractorSupplyCategories::class,
        Commands\RemoveVoucherDuplicates::class,
        Commands\RestoreVoucherNumbers::class,
        Commands\RefractorSupplierServices::class,
        Commands\GenerateOldPVs::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
