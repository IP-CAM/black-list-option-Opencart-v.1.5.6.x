<?php 
class ControllerTotalDiscount extends Controller { 
	private $error = array();
	 
	public function index() { 
		$this->load->language('total/discount');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('discount', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		
		$this->load->model('sale/customer_group');		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/discount', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['discount_status'])) {
			$this->data['discount_status'] = $this->request->post['discount_status'];
		} else {
			$this->data['discount_status'] = $this->config->get('discount_status');
		}

		if (isset($this->request->post['discount_sort_order'])) {
			$this->data['discount_sort_order'] = $this->request->post['discount_sort_order'];
		} else {
			$this->data['discount_sort_order'] = $this->config->get('discount_sort_order');
		}

		if (isset($this->request->post['discount_totals'])) {
			$this->data['discount_totals'] = $this->request->post['discount_totals'];
		} else {
			$this->data['discount_totals'] = $this->config->get('discount_totals');
		}

		if (isset($this->request->post['discount_customer_group_id'])) {
			$this->data['discount_customer_group_id'] = $this->request->post['discount_customer_group_id'];
		} else {
			$this->data['discount_customer_group_id'] = $this->config->get('discount_customer_group_id');
		}


		$this->template = 'total/discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>