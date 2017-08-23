<?php

use Anchu\Ftp\Facades\Ftp;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test/ftp', function () {
    // return view('welcome');


            $ftp = FTP::connection()->getDirListing();

            // $status = FTP::connection()->makeDir('./inv');
            $status = FTP::connection()->makeDir('/in');
            $status = FTP::connection()->makeDir('/in/2');
            $status = FTP::connection()->makeDir('/in/2/3');
            echo $status;
});


Route::get('test/pdf_lpo', function () {

	$lpo   = App\Models\LPOModels\Lpo::findOrFail(128);

    $data = array(
            'lpo'   => $lpo
        );

    return view('pdf/lpo',$data);
});



Route::get('test/pdf_mobile_payment', function () {

	$mp   = App\Models\MobilePaymentModels\MobilePayment::findOrFail(128);

    $data = array(
            'mobile_payment'   => $mp
        );

    return view('pdf/mobile_payment',$data);
});



Route::get('test/email_lpo', function () {

        $lpo = App\Models\LPOModels\LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoice',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail(128);

        foreach ($lpo->approvals as $key => $value) {
            $lpo->approvals[$key]['approver'] = App\Models\StaffModels\Staff::findOrFail($lpo->approvals[$key]['approver_id']);
        }

    $data = array(
            'lpo'   => $lpo,
            'addressed_to' => $lpo->requested_by,
            'js_url' => "j",
        );

    return view('emails/notify_lpo',$data);
});
