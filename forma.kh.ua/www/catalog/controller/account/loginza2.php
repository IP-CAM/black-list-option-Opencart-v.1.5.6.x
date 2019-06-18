<?php
class ControllerAccountLoginza2 extends Controller {
	private $error = array();

	/*
		google, yandex, mailruapi, mailru, vkontakte, facebook, odnoklassniki, livejournal, twitter, linkedin, loginza, myopenid, webmoney, rambler, flickr, lastfm, verisign, aol, steam, openid 
	*/
	private $socnets = array(
				'google.com'=>'google', 
				'yandex.ru'=>'yandex', 
				'openid.mail.ru'=>'mailruapi', 
				'http://mail.ru/'=>'mailru',
				'vk.com'=>'vkontakte', 
				'facebook.com'=>'facebook', 
				'odnoklassniki'=>'odnoklassniki', 
				'livejournal.com'=>'livejournal', 
				'twitter.com'=>'twitter', 
				'linkedin'=>'linkedin',
				'loginza.ru'=>'loginza', 
				'myopenid.com'=>'myopenid', 
				'webmoney'=>'wmkeeper.com', 
				'rambler'=>'rambler',   //?
				'flickr'=>'flickr',     //?
				'last.fm'=>'last.fm',    
				'verisign'=>'verisign', //?
				'aol'=>'aol.com',       
				'steam'=>'steamcommunity.com',    
				'openid'=>'openid'  
	);
	
	public function index() {
/*
        function file_get_contents_curl($url) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);

            $data = curl_exec($ch);
            curl_close($ch);

            return $data;
        }*/
	
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link($this->session->data['loginza2_lastlink'], '', 'SSL'));
		}
		
		if( empty( $this->request->post['token'] ) )
		{
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$TOKEN = $this->request->post['token'];
		
		if( $this->config->get('loginza2_safemode') && $this->config->get('loginza2_widget_id') && $this->config->get('loginza2_widget_sig') )
		{
			$ID = $this->config->get('loginza2_widget_id');
			$SIG = $this->config->get('loginza2_widget_sig');
			$SIG = md5($TOKEN.$SIG);
			
			$url = "http://213.180.204.205/api/authinfo?token=".$TOKEN."&id=".$ID."&sig=".$SIG;
		}
		else
		{
			$url = "http://213.180.204.205/api/authinfo?token=".$TOKEN;
		}
		
		
		if( extension_loaded('curl') )
		{
			$c = curl_init($url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$page = curl_exec($c);
			curl_close($c);
		}
		else
		{
			$page = file_get_contents($url);
		}
		
		if( empty($page) )
		{
			exit('error connection');
		}
		
		
		
		$arr = json_decode($page, true);
		
		
	/* * /
		foreach($arr as $key=>$val)
		{
			echo $key." - ".$val."<br>";
				
						echo "1===";
			if( is_array($val) )
			{
						echo "2===";
				foreach($val as $k=>$v)
				{
						echo "3===";
					echo '-'.$k." - ".$v."<br>";
					if(  is_array( $v ) )
					{
						echo "4===";
						foreach($v as $k1=>$v1)
						{
						echo "5===";
							echo "--".$k1." - ".$v1."<br>";
						}
					}
					
				}
			}
		}
		//echo "<hr>".$arr['provider'];
		
		exit();
	/* */	
		
		if( !empty($arr['error_type']) )
		{
			exit($arr['error_type']." - ".$arr['error_message']);
		}
		
		if( empty($arr['identity']) ) exit('not identity');
		
		$provider = '';
		
		foreach($this->socnets as $search=>$net)
		{
			if( strstr($arr['provider'], $search) )
			{
				$provider = $net;
				break;
			}
		}
		
		$data = array(
			'identity'  => $arr['identity'],
			'firstname' => '',
			'lastname'  => '',
			'email'     => '',
			'telephone'		=> '',
			'data'		=> serialize($arr),
			'provider'  => $provider
		);
		
		if( !empty( $arr['name']['first_name'] ) )
		{
			$data['firstname'] = $arr['name']['first_name'];
		}
		
		if( !empty( $arr['name']['last_name'] ) )
		{
			$data['lastname'] = $arr['name']['last_name'];
		}
		
		if( !empty( $arr['email'] ) )
		{
			$data['email'] = $arr['email'];
		}
		
		if( !empty( $arr['phone']['preferred'] ) )
		{
			$data['telephone'] = $ar['phone']['preferred'];
		}
		elseif( !empty( $arr['phone']['mobile'] ) )
		{
			$data['telephone'] = $arr['phone']['mobile'];
		}
		elseif( !empty( $arr['phone']['home'] ) )
		{
			$data['telephone'] = $arr['phone']['home'];
		}
		elseif( !empty( $arr['phone']['work'] ) )
		{
			$data['telephone'] = $arr['phone']['work'];
		}
		elseif( !empty( $arr['phone']['fax'] ) )
		{
			$data['telephone'] = $arr['phone']['fax'];
		}
		
		
		if( empty( $arr['name']['first_name'] ) && 
			empty( $arr['name']['last_name'] ) &&  
			!empty( $arr['name']['full_name'] ) )
		{
			$ar = explode(" ", $arr['name']['full_name']);
			
			$data['firstname'] = $ar[0];
			if( !empty($ar[1]) )
			$data['lastname']  = $ar[1]; 
		}
		
		$this->load->model('account/loginza2');
		
		if( $customer_id = $this->model_account_loginza2->checkNew( $data ) )
		{
			$this->session->data['customer_id'] = $customer_id;
			$this->session->data['loginza2_confirmdata_show'] = 0;
			$this->redirect( $this->url->link($this->session->data['loginza2_lastlink'], '', 'SSL') );
		}
		else
		{
			if( $confirm_data = $this->isNeedConfirm( $data ) )
			{
				$confirm_data['data'] = $data;
				$this->session->data['loginza2_confirmdata'] = serialize($confirm_data);
				$this->session->data['loginza2_confirmdata_show'] = 1;
				$this->redirect( $this->url->link($this->session->data['loginza2_lastlink'], 'loginza2_confirm=1', 'SSL') );
			}
			else
			{
				$this->session->data['loginza2_confirmdata'] = '';
				$this->session->data['loginza2_confirmdata_show'] = '';
				
				$customer_id = $this->model_account_loginza2->addCustomer( $data );
				$this->session->data['customer_id'] = $customer_id;	
				$this->redirect( $this->url->link($this->session->data['loginza2_lastlink'], '', 'SSL') );
			}
		}
	}
	
	public function frame()
	{
		$this->language->load('account/loginza2');
		$this->load->model('account/loginza2');
		
		$loginza_data = unserialize( $this->session->data['loginza2_confirmdata'] );
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && 
			$data = $this->validate($loginza_data['data'])) 
		{
			$this->session->data['loginza2_confirmdata'] = '';
			$customer_id = $this->model_account_loginza2->addCustomer( $data );
			$this->session->data['customer_id'] = $customer_id;	
			$this->redirect( $this->url->link('account/loginza2/success', '', 'SSL') );
		}
		
		$this->data['action'] = $this->url->link('account/loginza2/frame', '', 'SSL');
		
		$this->data['header'] = $this->language->get('header');
		$this->data['header_notice'] = $this->language->get('header_notice');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['text_submit'] = $this->language->get('text_submit');
		
		
		if( !empty($this->request->post['firstname']) )
		{
			$this->data['firstname'] = $this->request->post['firstname'];
		}
		elseif( isset($loginza_data['firstname']) )
		{
			$this->data['firstname'] = $loginza_data['firstname'];
		}
		else
		{
			$this->data['firstname'] = '';
		}
		
		if( isset($loginza_data['firstname']) )
		{
			$this->data['is_firstname'] = 1;
		}
		else
		{
			$this->data['is_firstname'] = 0;
		}
		
		if( !empty($this->request->post['lastname']) )
		{
			$this->data['lastname'] = $this->request->post['lastname'];
		}
		elseif( isset($loginza_data['lastname']) )
		{
			$this->data['lastname'] = $loginza_data['lastname'];
		}
		else
		{
			$this->data['lastname'] = '';
		}
		
		if( isset($loginza_data['lastname']) )
		{
			$this->data['is_lastname'] = 1;
		}
		else
		{
			$this->data['is_lastname'] = 0;
		}
		
		if( !empty($this->request->post['email']) )
		{
			$this->data['email'] = $this->request->post['email'];
		}
		elseif( isset($loginza_data['email']) )
		{
			$this->data['email'] = $loginza_data['email'];
		}
		else
		{
			$this->data['email'] = '';
		}
		
		if( isset($loginza_data['email']) )
		{
			$this->data['is_email'] = 1;
		}
		else
		{
			$this->data['is_email'] = 0;
		}
		
		if( !empty($this->request->post['telephone']) )
		{
			$this->data['telephone'] = $this->request->post['telephone'];
		}
		elseif( isset($loginza_data['telephone']) )
		{
			$this->data['telephone'] = $loginza_data['telephone'];
		}
		else
		{
			$this->data['telephone'] = '';
		}
		
		if( isset($loginza_data['telephone']) )
		{
			$this->data['is_telephone'] = 1;
		}
		else
		{
			$this->data['is_telephone'] = 0;
		}
		
		if( !empty( $this->error['firstname'] ) )
		{
			$this->data['error_firstname'] = $this->error['firstname'];
		}
		else
		{
			$this->data['error_firstname'] = '';
		}
		
		if( !empty( $this->error['lastname'] ) )
		{
			$this->data['error_lastname'] = $this->error['lastname'];
		}
		else
		{
			$this->data['error_lastname'] = '';
		}
		
		if( !empty( $this->error['email'] ) )
		{
			$this->data['error_email'] = $this->error['email'];
		}
		else
		{
			$this->data['error_email'] = '';
		}
		
		if( !empty( $this->error['telephone'] ) )
		{
			$this->data['error_telephone'] = $this->error['telephone'];
		}
		else
		{
			$this->data['error_telephone'] = '';
		}
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/loginza2_frame.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/loginza2_frame.tpl';
		} else {
			$this->template = 'default/template/account/loginza2_frame.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function success()
	{
		$this->language->load('account/loginza2');
		$this->data['header'] = $this->language->get('header');
		$this->data['success'] = $this->language->get('success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/loginza2_frame_success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/loginza2_frame_success.tpl';
		} else {
			$this->template = 'default/template/account/loginza2_frame_success.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	
  	private function validate($data) {
    	
		if( isset( $this->request->post['firstname'] ) && 
			empty( $this->request->post['firstname'] )
		)
		{
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if( isset( $this->request->post['lastname'] ) && 
			empty( $this->request->post['lastname'] )
		)
		{
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		
		if( isset( $this->request->post['email'] ) && 
			empty( $this->request->post['email'] )
		)
		{
			$this->error['email'] = $this->language->get('error_email');
		}
		elseif( 
			isset( $this->request->post['email'] ) && 
			!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'] )
		)
		{
			$this->error['email'] = $this->language->get('error_email2');
		}
		
		if( isset( $this->request->post['telephone'] ) && 
			empty( $this->request->post['telephone'] ) )
		{
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		
    	if (!$this->error) {
			if( !empty($this->request->post['firstname']) )
			{
				$data['firstname'] = $this->request->post['firstname'];
			}
			
			if( !empty($this->request->post['lastname']) )
			{
				$data['lastname']  = $this->request->post['lastname'];
			}
						
			if( !empty($this->request->post['email']) )
			{
				$data['email']  = $this->request->post['email'];
			}
						
			if( !empty($this->request->post['telephone']) )
			{
				$data['telephone']  = $this->request->post['telephone'];
			}
			
      		return $data;
    	} else {
      		return false;
    	}  	
  	}
	
	
	protected function isNeedConfirm($data)
	{
		$confirm_data = array();
		
		if( $this->config->get('loginza2_confirm_firstname_status') == 2 || (
			$this->config->get('loginza2_confirm_firstname_status') == 1 && empty($data['firstname'])
			) )
		{
			$confirm_data['firstname'] = $data['firstname'];
		}
		
		if( $this->config->get('loginza2_confirm_lastname_status') == 2 || (
			$this->config->get('loginza2_confirm_lastname_status') == 1 && empty($data['lastname'])
		) )
		{
			$confirm_data['lastname'] = $data['lastname'];
		}
		
		if( $this->config->get('loginza2_confirm_email_status') == 2 || (
			$this->config->get('loginza2_confirm_email_status') == 1 && empty($data['email'])
			) )
		{
			$confirm_data['email'] = $data['email'];
		}
		
		if( $this->config->get('loginza2_confirm_phone_status') == 2 || (
			$this->config->get('loginza2_confirm_phone_status') == 1 && empty($data['telephone'])
		) )
		{
			$confirm_data['telephone'] = $data['telephone'];
		}
		
		
		
		
		if( !$confirm_data ) return false;
		else
		{		
			return $confirm_data;
		}
	}
}
?>