<?php
namespace App\Transport;
use Illuminate\Mail\TransportManager;
// use Smtp2goTransport;

class Smtp2goTransportManager extends TransportManager{
    protected function createSmtp2goDriver()
    {
        $config = $this->app['config']->get('services.smtp2go', []);

        return new Smtp2goTransport(
            $this->guzzle($config),
            $config['api_key'], $config['domain']
        );
    }
}
?>