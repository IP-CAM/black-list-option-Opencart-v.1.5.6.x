<?php
//https://api.telegram.org/bot$token/getupdates
class ControllerMocoMsTelegram extends Controller {
    
    private $token = "871357307:AAFvlqdJzBKSvHLO7IHk9FY9Qrsw3miHx_M";
    private $chat_ids = [
                    "-396766706"
            ];
    


    public function index() {}
    
    public function pushPhone($data) {
        
        $data["text"] = "📞Клієнт запитує дзвінок?😱👇"
                . "\n"
                . "І'мя: {$data["name"]}"
                . "\n"
                . "телефон: {$data["phone"]}"
                . "\n";
        
        $data["text"] = urlencode($data["text"]);
        
        $data["text"] .= "[".$data["title"]."](".urlencode($data["url"]).")";
        
        if($data["ip_countryName"] == "Ukraine"){
            $data["ip_countryName"] .= "🇺🇦";
        }
                
        $info = "\n"
                . "Додаткова інформація визначається по IP(можливі неточності)"
                . "\n"
                . "Країна: {$data["ip_countryName"]}"
                . "\n"
                . "Регіон: {$data["ip_regionName"]}"
                . "\n"
                . "Місто: {$data["ip_city"]}"
                . "\n";
                
        $info = urlencode($info);
        
        $data["text"] .= $info;
        
        return $this->push($data);
        
    }
    
    public function pushQueryOrder($data) {
        
        $data["text"] = ""
                . "Запит клієнта на товар!!!"
                . "\n"
                . "Номер заказа: {$data["order_namber"]}"
                . "\n"
                . "Ім'я: {$data["name"]}"
                . "\n"
                . "lot: {$data["product_id"]}"
                . "\n"
                . "телефон: {$data["phone"]}"
                . "\n"
                . "email: {$data["email"]}"
                . "\n"
                . "Кількість: {$data["quantity"]}"
                . "\n"
                . "Страхова сума: {$data["sum"]}"
                . "\n"
                . "Місто: {$data["city"]}"
                . "\n"
                . "Перевізник: {$data["delivery"]}"
                . "\n"
                . "№ складу: {$data["delivery_n"]}"
                . "\n"
                . "П.І.Б. одержувача: {$data["full_name"]}"
                . "\n"
                . "Тара: {$data["tara"]}"
                . "\n"
                . "Опис:"
                . "\n"
                . "{$data["discription"]}"
                . "\n";

        $data["text"] = urlencode($data["text"]);
        
        $data["text"] .= "[".$data["title"]."](".urlencode($data["url"]).")";
        
        if($data["ip_countryName"] == "Ukraine"){
            $data["ip_countryName"] .= "🇺🇦";
        }
                
        $info = "\n"
                . "Додаткова інформація визначається по IP(можливі неточності)"
                . "\n"
                . "Країна: {$data["ip_countryName"]}"
                . "\n"
                . "Регіон: {$data["ip_regionName"]}"
                . "\n"
                . "Місто: {$data["ip_city"]}"
                . "\n";
                
        $info = urlencode($info);
        
        $data["text"] .= $info;
        
        return $this->push($data);
        
    }
    
    public function NewReviews($data){

        $text = $data["text"];
        $data["text"] = "Відгук!!!\n";
        
        if($data['title'])
            $data["text"]  .= "1 * {$data["title"]} * ";
        
        if($data['rating']){
            for ($i = 1; $i <= $data["rating"]; $i++) {
                $data["text"]  .= "⭐️";
            }
        }
        
        $data["text"]  .= "\n";
        
        if($data['name']) $data["text"]  .= "* {$data["name"]} * \n";
        if($data['city']) $data["text"]  .= "* {$data["city"]} * \n";
        if($text) $data["text"]  .= "{$text} \n";
        
        
        

        $data["text"] = urlencode($data["text"]);
        
        if($data["ip_countryName"] == "Ukraine"){
            $data["ip_countryName"] .= "🇺🇦";
        }
                
        $info = "\n"
                . "Додаткова інформація визначається по IP(можливі неточності)"
                . "\n"
                . "Країна: {$data["ip_countryName"]}"
                . "\n"
                . "Регіон: {$data["ip_regionName"]}"
                . "\n"
                . "Місто: {$data["ip_city"]}"
                . "\n";
                
        $info = urlencode($info);
        
        $data["text"] .= $info;
        
        $this->push($data);
        
    }
    
    
    
    
    
    
    public function push($data) {
        try {
            foreach ($this->chat_ids as $chat_id) {
            
                file_get_contents("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$chat_id}&parse_mode=Markdown&text=".($data["text"]));            
            }
            return "ok";
        } catch (Exception $exc) {
            return "error";
        }
    }
    
}