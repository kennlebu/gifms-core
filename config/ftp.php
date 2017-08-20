<?php
return array(

    /*
	|--------------------------------------------------------------------------
	| Default FTP Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the FTP connections below you wish
	| to use as your default connection for all ftp work.
	|
	*/

    'default' => env('FTP_DEFAULT_CONNECTION', "connection1"),

    /*
    |--------------------------------------------------------------------------
    | FTP Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the FTP connections setup for your application.
    |
    */

    'connections' => array(

        'connection1' => array(
            'host'   => env('FTP_HOST', "localhost"),
            'port'  => env('FTP_PORT', 21),
            'username' => env('FTP_USERNAME', "root"),
            'password'   => env('FTP_PASSWORD', "root"),
            'passive'   => env('FTP_PASSIVE', false),
        ),
        'connection_migration' => array(
            'host'   => env('FTP_HOST_MIG', "localhost"),
            'port'  => env('FTP_PORT_MIG', 21),
            'username' => env('FTP_USERNAME_MIG', "root"),
            'password'   => env('FTP_PASSWORD_MIG', "root"),
            'passive'   => env('FTP_PASSIVE_MIG', false),
        ),
    ),


    'folders' => array(

        'lpos'                  => "lpos",
        'invoices'              => "invoices",
        'allowances'            => "allowances",
        'bank_csv'              => "bank_csv",
        'bank_dump'             => "bank_dump",
        'bank_stmt'             => "bank_stmt",
        'claims'                => "claims",
        'signature'             => "signature",
        'invoices'              => "invoices",
        'expenses'              => "expenses",
        'mpesa'                 => "mpesa",
        'payments'              => "payments",
        'quotations'            => "quotations",
        'signsheets'            => "signsheets",
        'suppliers'             => "suppliers",
        'tickets'               => "tickets",

    ),
);