<?php  
class ControllerModuleLoginza extends Controller {
	private $error = array();
	
	protected function index() {
		$this->language->load('module/loginza');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		
		
		
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_account_create'] = $this->language->get('text_account_create');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
		$this->data['text_my_account'] = $this->language->get('text_my_account');
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_welcome'] = $this->language->get('text_welcome');
    	$this->data['text_password'] = $this->language->get('text_password');
		
    	$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_password'] = $this->language->get('entry_password');
		
		$this->data['button_login'] = $this->language->get('button_login');
		$this->data['button_logout'] = $this->language->get('button_logout');
		
		$this->data['information'] = HTTPS_SERVER . 'index.php?route=account/edit';
    	$this->data['password'] = HTTPS_SERVER . 'index.php?route=account/password';
		$this->data['address'] = HTTPS_SERVER . 'index.php?route=account/address';
    	$this->data['history'] = HTTPS_SERVER . 'index.php?route=account/history';
    	$this->data['download'] = HTTPS_SERVER . 'index.php?route=account/download';
		$this->data['newsletter'] = HTTPS_SERVER . 'index.php?route=account/newsletter';
		
		$this->data['account_create'] = (HTTP_SERVER . 'index.php?route=account/create');
		$this->data['my_account'] = (HTTP_SERVER . 'index.php?route=account/account');
		$this->data['forgotten'] = (HTTPS_SERVER . 'index.php?route=account/forgotten');
		$this->data['logout'] = (HTTPS_SERVER . 'index.php?route=account/logout');
		
		$this->id = 'loginza';

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->validate()) {
				if (isset($this->request->post['redirect'])) {
					$this->redirect($this->request->post['redirect']);
				} else {
					$this->redirect(HTTPS_SERVER);
				} 
			}
		}
		
		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer((int)$this->customer->getId());
			$this->data['firstname'] = $customer_info['firstname'];
		}
		
		
		if (isset($this->error['message'])) {
			$this->data['error'] = $this->error['message'];
		} else {
			$this->data['error'] = '';
		}
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=account/login';
		$this->data['action_loginza'] = urlencode(HTTPS_SERVER . 'index.php?route=account/loginza');
		
		if (isset($this->request->post['redirect'])) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];
			unset($this->session->data['redirect']);		  	
    	} else {
			$this->data['redirect'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->session->data['account'])) {
			$this->data['account'] = $this->session->data['account'];
		} else {
			$this->data['account'] = 'register';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/loginza.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/loginza.tpl';
		} else {
			$this->template = 'default/template/module/loginza.tpl';
		}
		
		$this->render();
	}
	
	private function validate() {
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->error['message'] = $this->language->get('error_login');
    	}
	
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}  	
  	}
}
?>