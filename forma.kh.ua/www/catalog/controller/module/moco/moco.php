<?php
class ControllerMocoMoco extends Controller {  
    
    
    private $data = [];
    
    public function __construct($registry) {
        parent::__construct($registry);
        
        $Info_IP = $this->getInfo_IP();
        
        if(isset($Info_IP->geoplugin_request)){            
            $this->data["ip"] = $Info_IP->geoplugin_request;
        } else {
            $this->data["ip"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_countryName)){            
            $this->data["ip_countryName"] = $Info_IP->geoplugin_countryName;
        } else {
            $this->data["ip_countryName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_regionName)){
            $this->data["ip_regionName"] = $Info_IP->geoplugin_regionName;
        } else {
            $this->data["ip_regionName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_city)){
            $this->data["ip_city"] = $Info_IP->geoplugin_city;
        } else {
            $this->data["ip_city"] = "---";
        }     
    }
    
    public function index() {}

    
    public function push_phone() {
        
        $this->load->language('moco/moco');
        
        $data =$this->data;
        
        if (isset($this->request->post['url'])) {
            $data["url"] = $this->request->post['url'];
        }else{
            echo 'not url';
            die;
        }
        
        if (isset($this->request->post['title'])) {
            $data["title"] = $this->request->post['title'];
        }else{
            echo 'not title';
            die;
        }
        
        if (isset($this->request->post['phone'])) {
            $data["phone"] = $this->request->post['phone'];
        }else{
            echo 'not phone';
            die;
        }
        
        if (isset($this->request->post['name'])) {
            $data["name"] = $this->request->post['name'];
        }else{
            $data["name"] = "---";
        }
        $data['text'] = '';
        
        $data = array_merge($this->info,$data);
        
        $statys = $this->load->controller('moco/ms/telegram/pushPhone',$data);
        
        if ($statys = 'ok') {
            
            $this->load->model('moco/moco');
            $this->model_moco_moco->addQuery($data);
            
            echo $this->language->get('text_push_phone_mess_ok');
        }else{
            echo $this->language->get('text_push_phone_mess_erorr');
        }
        
    }    
    
    public function getInfo_IP() {
    
        $ip = "";
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
        else $ip = $remote;
        
        return @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        
    }
}