<?php
class ControllerMocoMoco extends Controller {  
    
    
    private $info;
    
    public function __construct($registry) {
        parent::__construct($registry);
        
        $Info_IP = $this->getInfo_IP();
        
        if(isset($Info_IP->geoplugin_request)){            
            $data["ip"] = $Info_IP->geoplugin_request;
        } else {
            $data["ip"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_countryName)){            
            $data["ip_countryName"] = $Info_IP->geoplugin_countryName;
        } else {
            $data["ip_countryName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_regionName)){
            $data["ip_regionName"] = $Info_IP->geoplugin_regionName;
        } else {
            $data["ip_regionName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_city)){
            $data["ip_city"] = $Info_IP->geoplugin_city;
        } else {
            $data["ip_city"] = "---";
        }
        $this->info = $data;        
    }
    
    
    
    public function index() {}
    
    public function NewReviews($data){
        
        $data = array_merge($this->info,$data);
        
        $this->load->controller('moco/ms/telegram/NewReviews',$data);
        
    }
    
    public function push_phoneA15() {
        
    }
    
    public function push_phone() {
        
        $this->load->language('moco/moco');
        
        $data = [];
        
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
        
        $data = array_merge($this->info,$data);
        
        $statys = $this->load->controller('moco/ms/telegram/pushPhone',$data);
        var_dump($statys);
        if ($statys = 'ok') {
            
            $this->load->model('moco/moco');
            $this->model_moco_moco->addQuery($data);
            
            echo $this->language->get('text_push_phone_mess_ok');
        }else{
            echo $this->language->get('text_push_phone_mess_erorr');
        }
        
    }
    
    public function push_QueryOrder() {
        
        $this->load->model('common/cart_wix');
        
        $data["images"] = [];
        
        if (isset($this->request->post['url'])) {
            $data["url"] = $this->request->post['url'];
        }else{
            $data["url"] = "";
        }
        
        if (isset($this->request->post['title'])) {
            $data["title"] = $this->request->post['title'];
        }else{
            die;
        }
        
        if (isset($this->request->post["product_id"])) {
            $data["product_id"] = $this->request->post["product_id"];
            
            $this->load->model('db_allegro/product');
            
            $product_info = $this->model_db_allegro_product->getProduct($data["product_id"]);
            
            $data["images"] = $product_info["images"];
            
        }else{
            $data["product_id"] = "";
        }
        
        
        if (isset($this->request->post['quantity'])) {
            $data["quantity"] = $this->request->post['quantity'];
        }else{
            $data["quantity"] = "1";
        }
        
        
        if (isset($this->request->post['name'])) {
            $data["name"] = $this->request->post['name'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['phone'])) {
            $data["phone"] = $this->request->post['phone'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['email'])) {
            $data["email"] = $this->request->post['email'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['city'])) {
            $data["city"] = $this->request->post['city'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['sum_insured'])) {
            $data["sum"] = $this->request->post['sum_insured'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['delivery'])) {
            $data["delivery"] = $this->request->post['delivery'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['delivery_n'])) {
            $data["delivery_n"] = $this->request->post['delivery_n'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['full_name'])) {
            $data["full_name"] = $this->request->post['full_name'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['tara'])) {
            $data["tara"] = $this->request->post['tara'];
        }else{
            echo '';
        }
        
        if (isset($this->request->post['discription'])) {
            $data["discription"] = $this->request->post['discription'];
        }else{
            echo 'not discription<br>';
            $data["discription"] = "";
        }

        $data = array_merge($this->info,$data);
        
        $data["order_namber"] = $this->model_common_cart_wix->addOrder($data);
        
        $this->load->controller('moco/ms/telegram/pushQueryOrder',$data);
        $this->load->controller('moco/mail/send',$data);
        
        
    }
    
    public function view_phone() {
        
    }
    
    public function view_QueryOrder() {
        
        $this->load->language('moco/QueryOrder');
        
        $data['fieldsets'] = [];
        /*
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_'),
                'id' => '',
                'name' => '',
                'placeholder' => $this->language->get('text_placeholder_'),
                'required' => TRUE,// TRUE/FALSE
                'value' => '',
                'type' => '',
                'tabindex' => '',//integer
                'maxlength' => '',//integer
                'autocomplete' => '',// on/off
                'pattern' => '',
                'icon' => '',
            ];
        */
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_full_name'),
                'id' => 'name',
                'name' => 'name',
                'placeholder' => $this->language->get('text_placeholder_full_name'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'text',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'name',
                'pattern' => '',
                'icon' => 'user',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_phone'),
                'id' => 'phone',
                'name' => 'phone',
                'placeholder' => $this->language->get('text_placeholder_phone'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'tel',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'mobile tel',
                'pattern' => '',
                'icon' => 'earphone',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_email'),
                'id' => 'email',
                'name' => 'email',
                'placeholder' => $this->language->get('text_placeholder_email'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'email',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'email',
                'pattern' => '',
                'icon' => 'envelope',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_SETTLEMENT'),
                'id' => 'SETTLEMENT',
                'name' => 'SETTLEMENT',
                'placeholder' => $this->language->get('text_placeholder_SETTLEMENT'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'text',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'SETTLEMENT',
                'pattern' => '',
                'icon' => 'home',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_sum_insured'),
                'id' => 'sum_insured',
                'name' => 'sum_insured',
                'placeholder' => $this->language->get('text_placeholder_sum_insured'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'number',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'off',
                'pattern' => '',
                'icon' => 'usd',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_country-name'),
                'id' => 'country-name',
                'name' => 'country-name',
                'placeholder' => $this->language->get('text_placeholder_country-name'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'radio',
                'select' => [
                    'value' => 'UA',
                    'text' => 'UA',
                ],
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'off',
                'pattern' => '',
                'icon' => 'usd',
            ];
        
        $data['fieldsets'][] = [
                'label' => $this->language->get('text_label_carrier'),
                'id' => 'carrier',
                'name' => 'carrier',
                'placeholder' => $this->language->get('text_placeholder_carrier'),
                'required' => 'TRUE',
                'value' => '',
                'type' => 'sele',
                //'tabindex' => '',
                'maxlength' => '',
                'autocomplete' => 'off',
                'pattern' => '',
                'icon' => 'usd',
            ];
        
                
        
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('moco/QueryOrder', $data));
        
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