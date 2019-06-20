<?php
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}

		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}

        $url = $this->request->server['REQUEST_URI'];

        $this->data['gtag_event'] = ($url == "/formi/oblicovochnaya-plitka/" ||
                $url == "/formi/oblicovochnaya-plitka/formy-dlya-3d-paneley/" ||
                $url == "/formi/oblicovochnaya-plitka/formy-dlya-polifasada/" ||
                $url == "/formi/zabori/" ||
                $url == "/formi/trotuarnaya-plitka/" ||
                $url == "/dobavki-v-beton/");

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}

		$this->language->load('common/header');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		$this->data['name'] = $this->config->get('config_name');

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}

		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
    	$this->data['text_call'] = $this->language->get('text_call');

		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        if($url == "/secret-razdel/" && !$this->data['logged']){
            $this->response->redirect('/',307);
        }

		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}

		// Menu
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {

            if(!$this->data['logged'] && $category['category_id']==90) continue;

			if ($category['top']) {
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {

                    // Level 3
                    $children_data_3 = array();

                    $children_3 = $this->model_catalog_category->getCategories($child['category_id']);

                    foreach ($children_3 as $child_3) {

                        $filter_data_3 = array(
                            'filter_category_id'  => $child_3['category_id'],
                            'filter_sub_category' => true
                        );

                        $children_data_3[] = array(
                            'name'  => $child_3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data_3) . ')' : ''),
                            'href'  => $this->url->link('product/category', 'path=' . $child['category_id'] . '_' . $child_3['category_id'],$connection)
                        );
                    }
                    //end of level 3

					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					if ($child['image'] !== "") {
						$image = $this->model_tool_image->resize($child['image'], 50, 50);
					} else {
						$image = false;
					}

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'active'   => in_array($child['category_id'], $parts),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'],$connection),
						'image' => $image,
                        'grand_childs' => $children_data_3
					);
				}

				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'active'   => in_array($category['category_id'], $parts),
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'],$connection)
				);
			}
		}

		$this->data['catinfopages'] = array();

		$wholesale = $this->model_catalog_information->getInformation(13);
		$this->data['catinfopages'][] = array(
			'title' => $wholesale['title'],
			'href'  => $this->url->link('information/information', 'information_id=' . $wholesale['information_id'],$connection)
		);

		$this->data['catinfopages'][] = array(
			'title' => $this->language->get('articles'),
			'href'  => $this->url->link('information/news','',$connection)
		);

		$wholesale = $this->model_catalog_information->getInformation(7);
		$this->data['catinfopages'][] = array(
			'title' => $wholesale['title'],
			'href'  => $this->url->link('information/information', 'information_id=' . $wholesale['information_id'],$connection)
		);

		$this->data['catinfopages'][] = array(
			'title' => $this->language->get('contacts'),
			'href'  => $this->url->link('information/contact','',$connection)
		);

		$wholesale = $this->model_catalog_information->getInformation(6);
		$this->data['catinfopages'][] = array(
			'title' => $wholesale['title'],
			'href'  => $this->url->link('information/information', 'information_id=' . $wholesale['information_id'],$connection)
		);

        $this->language->load('module/clientsource');

		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart',
            'module/clientsource'
		);
        
        $this->data['json_ld_website'] = $this->getChild('common/json_ld/website');
        $this->data['json_ld_organization'] = $this->getChild('common/json_ld/organization');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}

    	$this->render();
	}
}
