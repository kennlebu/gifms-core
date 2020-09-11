<?php

use Anchu\Ftp\Facades\Ftp;
use App\Http\Controllers\Controller;
use App\Mail\NotifyActivityCreation;
use App\Models\ActivityModels\Activity;
use Illuminate\Support\Facades\Mail;

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

Route::get('storage/app/staff/{filename}', function ($filename) 
{
   $path = storage_path("app/staff/".$filename);

   if (!File::exists($path)) {
      abort(404);
   }

   $file = File::get($path);
   $type = File::mimeType($path);

   $response = Response::make($file, 200);
   $response->header("Content-Type", $type);

   return $response;
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

    $lpo   = App\Models\LPOModels\Lpo::with('allocations')->findOrFail(2201);
    $controller = new Controller();
    $unique_approvals = $controller->unique_multidim_array($lpo->approvals, 'approver_id');

    $data = array(
            'lpo'   => $lpo,
            'unique_approvals' => $unique_approvals
        );

    return view('pdf/lpo',$data);
});

Route::get('test/pdf_lso', function () {

    $lpo   = App\Models\LPOModels\Lpo::with('allocations')->findOrFail(2201);
    $controller = new Controller();
    $unique_approvals = $controller->unique_multidim_array($lpo->approvals, 'approver_id');

    $data = array(
            'lpo'   => $lpo,
            'unique_approvals' => $unique_approvals
        );

    return view('pdf/lso',$data);
});

Route::get('test/pdf_invoice_voucher', function () {

    $inv   = App\Models\InvoicesModels\Invoice::findOrFail(128);

    $data = array(
            'invoice'   => $inv
        );

    return view('pdf/invoice_payment_voucher',$data);
});
Route::get('test/pdf_voucher', function () {

    $payment_voucher   = App\Models\PaymentModels\PaymentVoucher::findOrFail(12);

            //load signatures

            foreach ($payment_voucher->vouchable->approvals as $key => $approval) {
                try {
                    
                    echo $signature = $approval->approver->signature_url;
                } catch (Exception $e) {
                    
                }

                # code...
            }
            // die;

    $data = array(
            'payment_voucher'   => $payment_voucher
        );

    return view('pdf/payment_voucher',$data);
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

Route::get('test/email', function () {
    $data = [
        'title' => 'TEST TITLE',
        'paragraphs' => ['Hello','How are you?'],
        'signature' => 'Ken Lebu',
        'js_url' => 'http://localhost:4200'
    ];
    Mail::send('emails.generic', $data, function ($message) {
        $message->from('kefinance@clintonhealthaccess.org', 'KEFINANCE');
        $message->sender('kefinance@clintonhealthaccess.org', 'KEFINANCE');
        $message->to('kenlebu@gmail.com', 'John Doe');
        $message->cc('kennlebu@live.com', 'John Doe');
        $message->bcc('bkennville@yahoo.com', 'John Doe');
        $message->replyTo('kenlebu@gmail.com', 'John Doe');
        $message->subject('TEST GIFMS EMAIL');
        $message->priority(3);
    });;
});
Route::get('test/NotifyActivity', function () {
    $act = Activity::with('program','program_manager')->find('44');
    Mail::queue(new NotifyActivityCreation($act));
});

Route::get('/event/register/{url}', 'MeetingApi@registerIndex');
Route::get('/event/register/{url}/b', 'MeetingApi@registerIndexBanking');
Route::post('/event/register', 'MeetingApi@register')->name('register');
Route::post('/event/register-attendee', 'MeetingApi@registerAttendee')->name('register-attendee');
