<?php

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
