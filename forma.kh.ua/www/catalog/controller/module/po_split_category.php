<?php  
class ControllerModulePOSplitCategory extends Controller {
	protected function index($setting) {		
    	$this->data['heading_title'] = $setting['title'];
		
		$split_categories = $setting['categories'];
		$count_split = count($split_categories);
		$split_string = '';
		for ($i = 0; $i < $count_split ; $i ++) {
			$split_string .= $split_categories[$i];
			if(!($i + 1 == $count_split)) {
				$split_string .= ' , ';
			}
		}
				
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['category_id'] = $parts[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
		
		if($setting['expand'] == 1) {
			$this->data['class'] = 'class="active"';
		} else {
			$this->data['class'] = '';
		}
									
		$this->load->model('catalog/po_split_category');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_po_split_category->getSplitCategories($split_string);
		
		foreach ($categories as $category) {
			$children_data = array();
			
			$children = $this->model_catalog_category->getCategories($category['category_id']);
			
			foreach ($children as $child) {
				$data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);		
					
				$product_total = $this->model_catalog_product->getTotalProducts($data);
							
				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name']/* . ' (' . $product_total . ')'*/,
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
				);					
			}
			
			$data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true	
			);		
				
			/*$product_total = $this->model_catalog_product->getTotalProducts($data);*/
						
			$this->data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] /*. ' (' . $product_total . ')'*/,
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/po_split_category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/po_split_category.tpl';
		} else {
			$this->template = 'default/template/module/po_split_category.tpl';
		}
		
		$this->render();
  	}
}
?>