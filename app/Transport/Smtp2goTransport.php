<?php
namespace App\Transport;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Log;
use Swift_Mime_Message;

class Smtp2goTransport extends Transport{
    protected $client;
    protected $key;
    protected $domain;
    protected $url;

    public function __construct(Client $client, $key, $domain)
    {
        $this->client = $client;
        $this->key = $key;
        $this->domain = $domain;
    }

    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        try{
            $this->beforeSendPerformed($message);

            $to_email = [];
            $cc = [];
            $bcc = [];

            $from = $message->getFrom();
            $fromAddress = key($from);
            foreach ($message->getTo() as $email => $name) {
                $to_email[] = $email;
            }
            if($message->getCc()){
                foreach ($message->getCc() as $email => $name) {
                    $cc[] = $email;
                }
            }
            
            if($message->getBcc()){
                foreach ($message->getBcc() as $email => $name) {
                    $bcc[] = $email;
                }
            }

            $options = [
                'api_key' => $this->key,
                'sender' => $fromAddress,
                'to' => $to_email,
                'subject' => $message->getSubject(),
                'html_body'  => $message->getBody()
            ];

            $response = $this->client->post('https://api.smtp2go.com/v3/email/send', 
                [RequestOptions::JSON => $options]
            );
            return $response;
        }
        catch(Exception $e){
            Log::error($e->getMessage()); 
            Log::error($e->getTraceAsString());
        }
    }
}
?>