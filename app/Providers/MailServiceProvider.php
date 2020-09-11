<?php
namespace App\Providers;

use App\Transport\Smtp2goTransportManager;
use Illuminate\Mail\MailServiceProvider as MailProvider;

class MailServiceProvider extends MailProvider
{
    protected function registerSwiftTransport()
    {
        $this->app->singleton('swift.transport', function ($app) {
            return new Smtp2goTransportManager($app);
        });
    }

}
?>