<?php
use Viber\Client;
class ControllerMocoMsViber extends Controller {    
    
    private $apiKey ='48778ec81f27d1c0-d12e345d3177352c-40705c26d27a97bc';
    private $webhookUrl = 'https://www.xn--d1abkindgji.xn--j1amh/index.php?route=moco/ms/viber'; // <- PLACE-YOU-HTTPS-URL
    
    public function setup() {
        
        $url = 'https://chatapi.viber.com/pa/set_webhook';
        
        $webhookUrl = 'https://viber.hcbogdan.com/bot.php'; // <- PLACE-YOU-HTTPS-URL
        try {
            $client = new Client([ 'token' => $this->apiKey ]);
            $result = $client->setWebhook($this->webhookUrl);
            echo "Success!\n";
        } catch (Exception $e) {
            echo "Error: ". $e->getError() ."\n";
        }
    }
    
    public function index(){
        
    }
    
}
