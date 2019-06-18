<?php
class ControllerModuleMyoccmenu extends Controller {
	private $error = array();

	public function index()
	{
		$this->language->load('module/myoccmenu');

		$this->document->setTitle($this->language->get('common_title'));
	
		$this->load->model('myoc/cmenu');

		$this->getList();
	}

	public function insert()
	{
		$this->language->load('module/myoccmenu');

		$this->document->setTitle($this->language->get('common_title'));
	
		$this->load->model('myoc/cmenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_myoc_cmenu->addCmenu($this->request->post);
		  	
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
      		$this->redirect($this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function copy()
	{
		$this->language->load('module/myoccmenu');
	
    	$this->document->setTitle($this->language->get('common_title'));
		
		$this->load->model('myoc/cmenu');
		
    	if (isset($this->request->post['selected']) && $this->validateList()) {
			
			foreach ($this->request->post['selected'] as $cmenu_id) {
				$cmenu = $this->model_myoc_cmenu->getCmenu($cmenu_id);
				$cmenu['status'] = 0;
				$cmenu['cmenu_description'] = $this->model_myoc_cmenu->getCmenuDescriptions($cmenu_id);
				$this->model_myoc_cmenu->addCmenu($cmenu);
			}
			      		
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
	}

	public function update()
	{
		$this->language->load('module/myoccmenu');

		$this->document->setTitle($this->language->get('common_title'));
	
		$this->load->model('myoc/cmenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      		$this->model_myoc_cmenu->editCmenu($this->request->get['cmenu_id'], $this->request->post);
		  	
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
      		$this->redirect($this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->language->load('module/myoccmenu');
	
    	$this->document->setTitle($this->language->get('common_title'));
		
		$this->load->model('myoc/cmenu');
		
    	if (isset($this->request->post['selected']) && $this->validateList()) {
			foreach ($this->request->post['selected'] as $cmenu_id) {
				$this->model_myoc_cmenu->deleteCmenu($cmenu_id);
			}
			      		
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
	}

	public function sort()
	{
		$this->language->load('module/myoccmenu');
	
    	$this->document->setTitle($this->language->get('common_title'));
		
		$this->load->model('myoc/cmenu');
		
    	if ($this->validateList()) {
			foreach ($this->request->post['cmenu'] as $cmenu_id => $sort_order) {
				$cmenu = $this->model_myoc_cmenu->getCmenu($cmenu_id);
				$cmenu['sort_order'] = $sort_order['sort_order'];
				$cmenu['cmenu_description'] = $this->model_myoc_cmenu->getCmenuDescriptions($cmenu_id);
				$this->model_myoc_cmenu->editCmenu($cmenu_id, $cmenu);
			}
			      		
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
   		}
	
    	$this->getList();
	}

	private function getList()
	{
		$this->fixTable();
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cd.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
       		'text'      => $this->language->get('common_title'),
			'href'      => $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('module/myoccmenu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('module/myoccmenu/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('module/myoccmenu/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['save'] = $this->url->link('module/myoccmenu/sort', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$cmenu_total = $this->model_myoc_cmenu->getTotalCmenus();

		$results = $this->model_myoc_cmenu->getCmenus($data);
 
		$this->data['cmenus'] = array();

    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/myoccmenu/update', 'token=' . $this->session->data['token'] . '&cmenu_id=' . $result['cmenu_id'] . $url, 'SSL')
			);
						
			$this->data['cmenus'][] = array(
				'cmenu_id'    	=> $result['cmenu_id'],
				'name'          => $result['name'],
				'sort_order'    => $result['sort_order'],
				'status'		=> $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'selected'      => isset($this->request->post['selected']) && in_array($result['cmenu_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('common_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		
		
 		$this->data['button_upgrade'] = $this->language->get('button_upgrade');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_copy'] = $this->language->get('button_copy');
 		$this->data['button_delete'] = $this->language->get('button_delete');
 		$this->data['button_save'] = $this->language->get('button_save');
 		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		$this->data['myoc_copyright'] = $this->language->get('myoc_copyright');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . '&sort=cd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . '&sort=c.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $cmenu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'myoc/cmenu_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function getForm()
	{
		$this->fixTable();
        $this->data['heading_title'] = $this->language->get('common_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_popup'] = $this->language->get('entry_popup');
		$this->data['entry_parent_cmenu'] = $this->language->get('entry_parent_cmenu');
		$this->data['entry_parent_category'] = $this->language->get('entry_parent_category');
		$this->data['entry_column'] = $this->language->get('entry_column');
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_sort'] = $this->language->get('entry_sort');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_route'] = $this->language->get('entry_route');
		$this->data['entry_information'] = $this->language->get('entry_information');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_category'] = $this->language->get('entry_category');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->data['myoc_copyright'] = $this->language->get('myoc_copyright');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

		if (isset($this->error['link'])) {
			$this->data['error_link'] = $this->error['link'];
		} else {
			$this->data['error_link'] = false;
		}

		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
       		'text'      => $this->language->get('common_title'),
			'href'      => $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['cmenu_id'])) {
        	$this->data['action'] = $this->url->link('module/myoccmenu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/myoccmenu/update', 'token=' . $this->session->data['token'] . '&cmenu_id=' . $this->request->get['cmenu_id'] . $url, 'SSL');
		}
		$this->data['cancel'] = $this->url->link('module/myoccmenu', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['cmenu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cmenu_info = $this->model_myoc_cmenu->getCmenu($this->request->get['cmenu_id']);
		}

		if (isset($this->request->post['cmenu_id'])) {
			$this->data['cmenu_id'] = $this->request->post['cmenu_id'];
		} elseif (!empty($cmenu_info)) {
			$this->data['cmenu_id'] = $cmenu_info['cmenu_id'];
		} else {
			$this->data['cmenu_id'] = 0;
		}

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['cmenu_description'])) {
			$this->data['cmenu_description'] = $this->request->post['cmenu_description'];
		} elseif (isset($this->request->get['cmenu_id'])) {
			$this->data['cmenu_description'] = $this->model_myoc_cmenu->getCmenuDescriptions($this->request->get['cmenu_id']);
		} else {
			$this->data['cmenu_description'] = array();
		}

		$this->load->model('catalog/information');
		$informations = $this->model_catalog_information->getInformations();
		$this->data['informations'] = array();
		foreach ($informations as $information) {
			$this->data['informations'][$information['title']] = HTTP_CATALOG . 'index.php?route=information/information&amp;information_id=' . $information['information_id'];
		}

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$categories = $this->model_myoc_cmenu->getCategories(0);

		$this->data['categories'] = array();

		foreach ($categories as $category) {					
			// Level 1
			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name' => $category['name'],
				'path' => $category['category_id']
			);

			// Level 2
			$children = $this->model_myoc_cmenu->getCategories($category['category_id']);
				
			foreach ($children as $child) {
				$this->data['categories'][] = array(
					'category_id' => $child['category_id'],
					'name' => $category['name'] . ' > ' . $child['name'],
					'path' => $category['category_id'] . '_' . $child['category_id']
				);

				// Level 3
				$gchildren = $this->model_myoc_cmenu->getCategories($child['category_id']);

				foreach ($gchildren as $gchild) {
					$this->data['categories'][] = array(
						'category_id' => $gchild['category_id'],
						'name' => $category['name'] . ' > ' . $child['name'] . ' > ' . $gchild['name'],
						'path' => $category['category_id'] . '_' . $child['category_id'] . '_' . $gchild['category_id']
					);
				}
			}
		}

		$this->data['category_products'] = array();
		
		foreach ($this->data['categories'] as $category) {
			$products = $this->model_catalog_product->getProductsByCategoryId($category['category_id']);
			if($products)
			{
				$this->data['category_products'][$category['category_id']] = array();
				foreach ($products as $product) {
					$this->data['category_products'][$category['category_id']][$product['product_id']] = $product['name'];
				}
			}
		}
		$this->data['http_catalog'] = HTTP_CATALOG;

		$this->data['routes'] = array(
			'account/account' => HTTP_CATALOG . 'index.php?route=account/account',
			'account/login' => HTTP_CATALOG . 'index.php?route=account/login',
			'account/logout' => HTTP_CATALOG . 'index.php?route=account/logout',
			'account/register' => HTTP_CATALOG . 'index.php?route=account/register',
			'account/voucher' => HTTP_CATALOG . 'index.php?route=account/voucher',
			'account/wishlist' => HTTP_CATALOG . 'index.php?route=account/wishlist',
			'account/return/insert' => HTTP_CATALOG . 'index.php?route=account/return/insert',
			'affiliate/account' => HTTP_CATALOG . 'index.php?route=affiliate/account',
			'checkout/cart' => HTTP_CATALOG . 'index.php?route=checkout/cart',
			'checkout/checkout' => HTTP_CATALOG . 'index.php?route=checkout/checkout',
			'common/home' => HTTP_CATALOG . 'index.php?route=common/home',
			'information/contact' => HTTP_CATALOG . 'index.php?route=information/contact',
			'information/sitemap' => HTTP_CATALOG . 'index.php?route=information/sitemap',
			'product/manufacturer' => HTTP_CATALOG . 'index.php?route=product/manufacturer',
			'product/special' => HTTP_CATALOG . 'index.php?route=product/special',
		);


		if (isset($this->request->post['link'])) {
			$this->data['link'] = $this->request->post['link'];
		} elseif (!empty($cmenu_info)) {
			$this->data['link'] = $cmenu_info['link'];
		} else {
			$this->data['link'] = 'http://';
		}

		if (isset($this->request->post['popup'])) {
			$this->data['popup'] = $this->request->post['popup'];
		} elseif (!empty($cmenu_info)) {
			$this->data['popup'] = $cmenu_info['popup'];
		} else {
			$this->data['popup'] = '';
		}

		$cmenus = $this->model_myoc_cmenu->getCmenus();
		$this->data['cmenus'] = $cmenus;

		if (isset($this->request->post['parent_cmenu_id'])) {
			$this->data['parent_cmenu_id'] = $this->request->post['parent_cmenu_id'];
		} elseif (!empty($cmenu_info)) {
			$this->data['parent_cmenu_id'] = $cmenu_info['parent_cmenu_id'];
		} else {
			$this->data['parent_cmenu_id'] = 0;
		}

		if (isset($this->request->post['parent_category_id'])) {
			$this->data['parent_category_id'] = $this->request->post['parent_category_id'];
		} elseif (!empty($cmenu_info)) {
			$this->data['parent_category_id'] = $cmenu_info['parent_category_id'];
		} else {
			$this->data['parent_category_id'] = 0;
		}

		if (isset($this->request->post['column'])) {
			$this->data['column'] = $this->request->post['column'];
		} elseif (!empty($cmenu_info)) {
			$this->data['column'] = $cmenu_info['column'];
		} else {
			$this->data['column'] = '1';
		}

		if (isset($this->request->post['login'])) {
			$this->data['login'] = $this->request->post['login'];
		} elseif (!empty($cmenu_info)) {
			$this->data['login'] = $cmenu_info['login'];
		} else {
			$this->data['login'] = 0;
		}

        $this->load->model('sale/customer_group');
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['customer_group'])) {
			$this->data['cmenu_customer_groups'] = $this->request->post['customer_group'];
        } elseif (!empty($cmenu_info)) {
			$this->data['cmenu_customer_groups'] = $cmenu_info['customer_group'];
		} else {
			$this->data['cmenu_customer_groups'] = array();
		}

		$this->load->model('setting/store');
        $this->data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['store'])) {
			$this->data['cmenu_stores'] = $this->request->post['store'];
        } elseif (!empty($cmenu_info)) {
			$this->data['cmenu_stores'] = $cmenu_info['store'];
		} else {
			$this->data['cmenu_stores'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($cmenu_info)) {
			$this->data['sort_order'] = $cmenu_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($cmenu_info)) {
			$this->data['status'] = $cmenu_info['status'];
		} else {
			$this->data['status'] = 1;
		}

        $this->template = 'myoc/cmenu_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm()
	{
    	if (!$this->user->hasPermission('modify', 'module/myoccmenu')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if($this->request->post['link'] != "#" && !filter_var($this->request->post['link'], FILTER_VALIDATE_URL)) {
    		$this->error['link'] = $this->language->get('error_link');
    	}

    	foreach ($this->request->post['cmenu_description'] as $language_id => $value) {
    		if(function_exists('utf8_strlen'))
    		{
	      		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
	        		$this->error['name'][$language_id] = $this->language->get('error_name');
	      		}
	      	} else {
	      		if ((strlen($value['name']) < 1) || (strlen($value['name']) > 255)) {
	        		$this->error['name'][$language_id] = $this->language->get('error_name');
	      		}
	      	}
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateList()
  	{
		if (!$this->user->hasPermission('modify', 'module/myoccmenu')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (!$this->error) { 
	  		return true;
		} else {
	  		return false;
		}
  	}

  	public function install()
  	{
  		$this->load->model('myoc/cmenu');
  		$this->model_myoc_cmenu->installTable();

  		$this->load->model('setting/setting');
  		$this->model_setting_setting->editSetting('myoccmenu', array('cmenu_installed' => 1));
  	}

  	public function uninstall()
  	{
  		$this->load->model('myoc/cmenu');
  		$this->model_myoc_cmenu->uninstallTable();

  		$this->load->model('setting/setting');
  		$this->model_setting_setting->deleteSetting('myoccmenu');
  	}

  	public function fixTable()
  	{
  		$this->load->model('myoc/cmenu');
  		$this->model_myoc_cmenu->fixTable();
  	}
}