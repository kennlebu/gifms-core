<?php

namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\PaymentModels\PaymentStatus;
use App\Models\PaymentModels\Payment;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Constructor
    */
    public function __construct()
    {


    }












    


    public function current_user(){
    	return JWTAuth::parseToken()->authenticate();
    }












    




    public function get_mime_type($filename) {

        $idx = explode( '.', $filename);
        $count_explode = count($idx);
        $idx = strtolower($idx[$count_explode-1]);

        $mimet = array( 
            'txt' => 'text/plain',
            'csv' => 'text/x-csv',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

        // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

        // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

        // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

        // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


        // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );

        if (isset( $mimet[$idx] )) {
           return $mimet[$idx];
        } else {
           return 'application/octet-stream';
        }
   }

   

    protected function generateRandomString($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }













    protected function generate_payable_payment($payable){

        $status = PaymentStatus::where('default_status','1')->first();
        $default_status = $status->id;


        $payment = new Payment;

        $payment->payable_type              =   $payable['payable_type'];
        $payment->payable_id                =   $payable['payable_id'];
        $payment->debit_bank_account_id     =   $payable['debit_bank_account_id'];
        $payment->currency_id               =   $payable['currency_id'];
        $payment->payment_desc              =   $payable['payment_desc'];
        $payment->paid_to_name              =   $payable['paid_to_name'];
        $payment->paid_to_mobile_phone_no   =   $payable['paid_to_mobile_phone_no'];
        $payment->paid_to_bank_account_no   =   $payable['paid_to_bank_account_no'];
        $payment->paid_to_bank_id           =   $payable['paid_to_bank_id'];
        $payment->paid_to_bank_branch_id    =   $payable['paid_to_bank_branch_id'];
        $payment->payment_mode_id           =   $payable['payment_mode_id'];
        $payment->amount                    =   $payable['amount'];
        $payment->payment_batch_id          =   $payable['payment_batch_id'];
        $payment->bank_charges              =   $payable['bank_charges'];

        $payment->status_id                 =   $default_status;

        if($payment->save()) {

            $payment->ref                        = "CHAI/PYMT/#$payment->id/".date_format($payment->created_at,"Y/m/d");
            $payment->save();
            
            // return Response()->json(array('success' => 'Payable Added','payment' => $payment), 200);
        }
    }
}
