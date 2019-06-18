<?php
class ControllerMyocCmenu extends Controller
{
	protected function index()
	{
		$this->load->model('setting/extension');
		
		$extensions = $this->model_setting_extension->getExtensions('module');

		$cmenu_installed = false;
		foreach ($extensions as $extension) {
			if($extension['code'] == 'myoccmenu')
			{
				$cmenu_installed = true;
				break;
			}
		}

		$current_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('myoc/cmenu');

		$this->data['cmenus'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);

		$data = array(
			'parent_cmenu_id' => 0,
			'parent_category_id' => 0,
		);
		$cmenus = $cmenu_installed ? $this->model_myoc_cmenu->getCmenus($data) : array();
		$cmenus = array_merge($cmenus, $categories);
		
		usort($cmenus, array(get_class($this), 'sortCatCmenu'));

		foreach ($cmenus as $cmenu) {
			if(isset($cmenu['top']) && $cmenu['top'] || isset($cmenu['cmenu_id']))
			{
				$children_data = array();

				if(isset($cmenu['category_id']))
				{
					$category_children = $this->model_catalog_category->getCategories($cmenu['category_id']);
				
					$data = array(
						'parent_category_id' => $cmenu['category_id'],
					);
					$cmenu_children = $cmenu_installed ? $this->model_myoc_cmenu->getCmenus($data) : array();
				
					$cmenu_children = array_merge($cmenu_children, $category_children);
				} else {
					$data = array(
						'parent_cmenu_id' => $cmenu['cmenu_id'],
					);
					$cmenu_children = $cmenu_installed ? $this->model_myoc_cmenu->getCmenus($data) : array();
				}

				usort($cmenu_children, array(get_class($this), 'sortCatCmenu'));

				foreach ($cmenu_children as $cmenu_child) {
					//3rd level
					$gchildren_data = array();

					if(isset($cmenu_child['category_id']))
					{
						$category_gchildren = $this->model_catalog_category->getCategories($cmenu_child['category_id']);
					
						$data = array(
							'parent_category_id' => $cmenu_child['category_id'],
						);
						$cmenu_gchildren = $cmenu_installed ? $this->model_myoc_cmenu->getCmenus($data) : array();
					
						$cmenu_gchildren = array_merge($cmenu_gchildren, $category_gchildren);
					} else {
						$data = array(
							'parent_cmenu_id' => $cmenu_child['cmenu_id'],
						);
						$cmenu_gchildren = $cmenu_installed ? $this->model_myoc_cmenu->getCmenus($data) : array();
					}

					foreach ($cmenu_gchildren as $cmenu_gchild) {
						if(isset($cmenu_gchild['category_id']))
						{
							$data = array(
								'filter_category_id'  => $cmenu_gchild['category_id'],
								'filter_sub_category' => true	
							);		
								
							$product_total = $this->model_catalog_product->getTotalProducts($data);
							
							$href = $this->url->link('product/category', 'path=' . $cmenu['category_id'] . '_' . $cmenu_child['category_id'] . '_' . $cmenu_gchild['category_id']);

							$gchildren_data[] = array(
								'name'    => $cmenu_gchild['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
								'href'    => $href,	
								'popup'   => false,
								'current' => $current_url == $href,
							);
						} else {
							$href = $this->seoUrl($cmenu_gchild['link']);

							$gchildren_data[] = array(
								'name'    => $cmenu_gchild['name'],
								'href'    => $href,
								'popup'   => $cmenu_gchild['popup'],
								'current' => $current_url == $href,
							);
						}
					}
					//end 3rd level

					if(isset($cmenu_child['category_id']))
					{
						$data = array(
							'filter_category_id'  => $cmenu_child['category_id'],
							'filter_sub_category' => true	
						);		
							
						$product_total = $this->model_catalog_product->getTotalProducts($data);

						$href = $this->url->link('product/category', 'path=' . $cmenu['category_id'] . '_' . $cmenu_child['category_id']);
						
						$children_data[] = array(
							'name'     => $cmenu_child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
							'children' => $gchildren_data, //3rd level
							'column'   => $cmenu_child['column'] ? $cmenu_child['column'] : 1,
							'href'     => $href,	
							'popup'    => false,
							'current'  => $current_url == $href,
						);
					} else {
						$href = $this->seoUrl($cmenu_child['link']);

						$children_data[] = array(
							'name'     => $cmenu_child['name'],
							'children' => $gchildren_data, //3rd level
							'column'   => $cmenu_child['column'] ? $cmenu_child['column'] : 1,
							'href'     => $href,
							'popup'    => $cmenu_child['popup'],
							'current'  => $current_url == $href,
						);
					}
				}

				$href = isset($cmenu['link']) ? $this->seoUrl($cmenu['link']) : $this->url->link('product/category', 'path=' . $cmenu['category_id']);

				$this->data['cmenus'][] = array(
					'name'     => $cmenu['name'],
					'children' => $children_data,
					'column'   => $cmenu['column'] ? $cmenu['column'] : 1,
					'href'     => $href,
					'popup'    => isset($cmenu['popup']) ? $cmenu['popup'] : false,
					'current'  => $current_url == $href,
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/myoc/cmenu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/myoc/cmenu.tpl';
		} else {
			$this->template = 'default/template/myoc/cmenu.tpl';
		}
		
		$this->render();
	}

	private static function sortCatCmenu($a, $b)
	{
		$val = $a['sort_order'] - $b['sort_order'];
		if($val == 0)
		{
			return strcmp(strtolower($a['name']), strtolower($b['name']));
		}
		return $val;
	}

	private function seoUrl($link)
	{
		$parsed_url = @parse_url($link);
		$http_server = parse_url(HTTP_SERVER);
		$route = $path = '';
		if ($this->config->get('config_seo_url') && isset($parsed_url['host']) && $parsed_url['host'] == $http_server['host'] && isset($parsed_url['query']) && substr($parsed_url['query'], 0, 5) == 'route') {
			$url_query = strstr($parsed_url['query'], '=');
			$route = substr($url_query, 1, strpos($url_query, '&'));
			if($route == '')
			{
				$route = substr($url_query, 1);
			}
			$path = substr(html_entity_decode(strstr($url_query, '&')), 1);
			return $this->url->link($route, $path);
		} else {
			return $link;
		}
	}
}
?>