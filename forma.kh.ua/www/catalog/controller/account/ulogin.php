<?php 
class ControllerAccountUlogin extends Controller { 
    public function index() {
            
            $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
            $user = json_decode($s, true);

        if (!isset($user['identity'])) {
            die('Ошибка');
        }
        
        if (isset($user['first_name']) && $user['first_name']) {
            $firstname = $user['first_name'];
        } else {
            $firstname = '';
        }
        
        if (isset($user['last_name']) && $user['last_name']) {
            $lastname = $user['last_name'];
        } else {
            $lastname = '';
        }
        
        if (isset($user['email']) && $user['email']) {
            $email = $user['email'];
        } else {
            $email = '';
        }
        
        if (isset($user['phone']) && $user['phone']) {
            $telephone = $user['phone'];
        } else {
            $telephone = '';
        }
        
        if (isset($user['country']) && $user['country']) {
            $country = $user['country'];
        } else {
            $country = '';
        }
        
        if (isset($user['city']) && $user['city']) {
            $city = $user['city'];
        } else {
            $city = '';
        }
                
        $this->load->model('module/ulogin');
        $check_id = $this->model_module_ulogin->check_identity($user['identity']);
        If (!$check_id) {
            $this->load->model('account/customer');
            if ($this->model_account_customer->getTotalCustomersByEmail($email)) {
                $this->language->load('account/register');
                  $this->session->data['error_warning'] = $this->language->get('error_exists');
                  $this->redirect($this->url->link('account/login', '', 'SSL'));
            }
            $country_id = 0;
            $country_q = $this->model_module_ulogin->get_country_id(trim($country));
            if($country_q){
                $country_id = $country_q['country_id'];
            }elseif($country=='Россия'){
                $country_q = $this->model_module_ulogin->get_country_id('Российская Федерация');
                if($country_q){
                    $country_id = $country_q['country_id'];
                }
            }
            $telephone = str_replace("(", "", $telephone);
            $telephone = str_replace(")", "", $telephone);
            $telephone = str_replace("-", "", $telephone);
            $data = array(
                'identity' => $user['identity'],
                'network' => $user['network'],
                'profile' => $user['profile'],
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'telephone' => $telephone,
                'customer_group_id' => $this->config->get('config_customer_group_id'),
                'password' => $this->generate_password(10),
                'address_1' => $country.", ".$city,
                'city' => $city,
                'zone_id' => 0,
                'country_id' => $country_id
            );
            $customer_id=$this->model_module_ulogin->add_customer($data);
            $this->model_module_ulogin->login($customer_id);
                        $this->model_module_ulogin->addAddress($data, $customer_id);
        } else {
            $this->model_module_ulogin->login($check_id);
        }
        
        if(isset($_GET['p']) && $_GET['p']=='checkout'){
            $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        }
        
        if (isset($this->session->data['ulogin_redirect'])) {
            $this->redirect($this->session->data['ulogin_redirect']);
        } else {
            $this->redirect(HTTPS_SERVER);
        }
        
      }
    
    private function generate_password($number) {
        $arr = array('a','b','c','d','e','f',
                        'g','h','i','j','k','l',
                        'm','n','o','p','r','s',
                        't','u','v','x','y','z',
                        'A','B','C','D','E','F',
                        'G','H','I','J','K','L',
                        'M','N','O','P','R','S',
                        'T','U','V','X','Y','Z',
                        '1','2','3','4','5','6',
                        '7','8','9','0');
        $pass = "";
        for($i = 0; $i < $number; $i++) {
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }

        return $pass;
    }
}
?>
