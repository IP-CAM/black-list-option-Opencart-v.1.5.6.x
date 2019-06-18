<?php
//https://api.telegram.org/bot$token/getupdates
class ControllerModuleMocoMsTelegram extends Controller {
    
    private $token = TELEGRAM_TOKEN;
    private $chat_ids = ["-396766706"];   


    public function index() {}
    
    public function push_phone($data) {
        
        $data["text"] = "📞Клієнт запитує консультацію!!! 😱👇"
                . "\n\n"
                . "телефон: {$data["telephone"]}"
                . "\n\n";
                
        $data["text"] .= "[".$data["title"]."](".$data["url"].")";
        
        if($data["ip_countryName"] == "Ukraine"){
            $data["ip_countryName"] .= "🇺🇦";
        }
                
        $data["text"] .= "\n\n"
                . "Додаткова інформація визначається по IP(можливі неточності)"
                . "\n"
                . "Країна: {$data["ip_countryName"]}"
                . "\n"
                . "Регіон: {$data["ip_regionName"]}"
                . "\n"
                . "Місто: {$data["ip_city"]}"
                . "\n";

        return $this->push($data["text"]);
        
    }
    
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
