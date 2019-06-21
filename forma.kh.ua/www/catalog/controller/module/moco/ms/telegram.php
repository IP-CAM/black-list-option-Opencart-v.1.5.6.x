<?php
//https://api.telegram.org/bot$token/getupdates
class ControllerModuleMocoMsTelegram extends Controller {
    
    private $token = TELEGRAM_TOKEN;
    private $chat_ids = ["-396766706"];   


    public function index() {}
        
    public function push($text) {
        
        try {
            foreach ($this->chat_ids as $chat_id) {            
                file_get_contents("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$chat_id}&parse_mode=Markdown&text=".urlencode($text));            
            }
            return true;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return false;
        }
    }
    
}
