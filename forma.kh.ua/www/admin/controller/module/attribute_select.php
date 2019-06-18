<?php
class ControllerModuleAttributeSelect extends Controller {

	private $error = array(); 
		
	public function index() {   
	
		$this->load->language('module/attribute_select');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('attribute_select', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_attribute_group'] = $this->language->get('column_attribute_group');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
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
       		'text'      => $this->language->get('text_module'),
		    	'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
    			'href'      => $this->url->link('module/attribute_select', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/attribute_select', 'token=' . $this->session->data['token'], 'SSL');		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		
		$this->load->model('catalog/attribute');		
		$config = $this->config->get('attribute_select_attributes');
				
		$this->data['attributes'] = array();
		
		$results = $this->model_catalog_attribute->getAttributes();
    foreach ($results as $result) {
		  $this->data['attributes'][] = array(
			  'attribute_id'    => $result['attribute_id'],
			  'name'            => $result['name'],
			  'attribute_group' => $result['attribute_group'],
			  'sort_order'      => $result['sort_order'],
			  'checked'        => (isset($config[$result['attribute_id']]) && $config[$result['attribute_id']]) ? ' checked="checked" ' : '',
			);
		}					
		
		$this->template = 'module/attribute_select.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

  public function uninstall() {
    $this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('attribute_select');
  }
  
  public function getSelects() {
    $attribute_id = isset($this->request->get['attribute_id']) ? (int) $this->request->get['attribute_id'] : 0;
    
    $this->load->model('catalog/attribute_select');
    $selects = $this->model_catalog_attribute_select->getSelects($attribute_id);
    
    $this->response->setOutput(json_encode($selects));
  }  
  
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/attribute_select')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}		
		return !$this->error ? TRUE : FALSE;
	}
	
}
