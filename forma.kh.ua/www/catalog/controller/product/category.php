<?php
class ControllerProductCategory extends Controller {
	public function index() {
        
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
        
		$this->language->load('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home','',$connection),
       		'separator' => false
   		);

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path,$connection),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}

			$category_id = (int)array_pop($parts);
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			// Nikita_Sp mod for export products of this category
			$this->data['category_id'] = $category_id;
			$this->data['category_name'] = $category_info['name'];
			$this->data['category_parent_id'] = $category_info['parent_id'];
			if (isset($this->request->get['export']) && $this->request->get['export'] == true) {
				require_once DIR_SYSTEM . 'PHPExcel/Classes/PHPExcel.php';

				// Create new PHPExcel object
				$objPHPExcel = new PHPExcel();

				// Set document properties
				$objPHPExcel->getProperties()->setCreator("Forma.kh.ua")
				                             ->setLastModifiedBy("Forma.kh.ua")
				                             ->setTitle("Forma.kh.ua PRICE")
				                             ->setSubject("Forma.kh.ua PRICE")
				                             ->setDescription("Forma.kh.ua PRICE")
				                             ->setKeywords("Forma.kh.ua PRICE")
				                             ->setCategory("");

				// Set password for readonly activesheet
				$objPHPExcel->getSecurity()->setLockWindows(true);
				$objPHPExcel->getSecurity()->setLockStructure(true);
				$objPHPExcel->getSecurity()->setWorkbookPassword("Forma_Kharkov");

				// Set password for readonly data
				$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
				$objPHPExcel->getActiveSheet()->getProtection()->setPassword("Forma_Kharkov");



				$this->load->model('catalog/export');

				$data = array();
				$config['csv_export']['core_type'] = 'opencart';
				$data = $config['csv_export'];

				$data['language_id'] = (int)$this->config->get('config_language_id');
				$data['file_format'] = "csv";
				$data['fields_set']['_NAME_'] = true;
				$data['fields_set']['_IMAGE_'] = true;
				$data['fields_set']['_IMAGES_'] = true;
				$data['fields_set']['_PRICE_'] = true;
				$data['product_qty'] = NULL;
				$data['category'] = array($category_id);
				$data['manufacturer'] = NULL;
					if($category_id == 2 || $category_info['parent_id'] == 2 ){
						$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
					}else{
						$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
					}
					$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->setCellValue("A2", strip_tags(html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8')));
					$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			    	$objPHPExcel->getActiveSheet()->getStyle("A2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(100);
				$filename = 'forma.kh.ua_price_'.'category-id_'. $category_id . '_' . (string)(date('Y-m-d-Hi')).'.xls';

				$output = $this->model_catalog_export->export($data, $objPHPExcel);

				if( is_array($output) AND isset($output['error']) ) {
					die($output['error']);
				}

				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Price');

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);

				// Redirect output to a client�s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				exit;
			}else{
			// End Nikita_Sp MOD


				if ($category_info['seo_title']) {
			  		$this->document->setTitle($category_info['seo_title']);
				} else {
			  		$this->document->setTitle($category_info['name']);
				}

				$this->document->setDescription($category_info['meta_description']);
				$this->document->setKeywords($category_info['meta_keyword']);

				$this->data['seo_h1'] = $category_info['seo_h1'];

				$this->data['heading_title'] = $category_info['name'];

                $this->data['text_products_all'] = $this->language->get('text_products_all');
                $this->data['text_download_price'] = $this->language->get('text_download_price');
                $this->data['button_show_more'] = $this->language->get('button_show_more');
				$this->data['text_refine'] = $this->language->get('text_refine');
				$this->data['text_empty'] = $this->language->get('text_empty');
				$this->data['text_quantity'] = $this->language->get('text_quantity');
				$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
				$this->data['text_model'] = $this->language->get('text_model');
				$this->data['text_price'] = $this->language->get('text_price');
				$this->data['text_tax'] = $this->language->get('text_tax');
				$this->data['text_points'] = $this->language->get('text_points');
				$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$this->data['text_display'] = $this->language->get('text_display');
				$this->data['text_list'] = $this->language->get('text_list');
				$this->data['text_grid'] = $this->language->get('text_grid');
				$this->data['text_sort'] = $this->language->get('text_sort');
				$this->data['text_limit'] = $this->language->get('text_limit');
				$this->data['text_option_upc'] = $this->language->get('text_option_upc');
				$this->data['text_min_quant'] = $this->language->get('text_min_quant');

				$this->data['button_price'] = $this->language->get('button_price');
				$this->data['button_cart'] = $this->language->get('button_cart');
				$this->data['button_wishlist'] = $this->language->get('button_wishlist');
				$this->data['button_compare'] = $this->language->get('button_compare');
				$this->data['button_continue'] = $this->language->get('button_continue');
				$this->data['currency_alt'] =  $this->language->get('currency_alt');

				if ($category_info['image']) {
					$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
				} else {
					$this->data['thumb'] = '';
				}

				$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
				$this->data['compare'] = $this->url->link('product/compare','',$connection);

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['categories'] = array();

				$results = $this->model_catalog_category->getCategories($category_id);

				foreach ($results as $result) {
					$data = array(
						'filter_category_id'  => $result['category_id'],
						'filter_sub_category' => true
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					$this->data['categories'][] = array(
						'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url,$connection)
					);
				}

				$this->data['products'] = array();

				$data = array(
					'filter_category_id' => $category_id,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);

				$product_total = $this->model_catalog_product->getTotalProducts($data);

				$results = $this->model_catalog_product->getProducts($data);

				foreach ($results as $result) {

					//$maincat = array();
					//$maincat = $this->model_catalog_product->getCategories($result['product_id']);

					if ($result['ean']>0) {
						if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $result['ean'], $this->config->get('config_image_product_height'));
						} else {
							$image = false;
						}
					} else {
						if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image = false;
						}
					}


					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price_value = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					} else {
						$price_value = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
					} else {
						$rating = false;
					}

					$images_option = false;
					if ($result['images_option'] && $result['images_option'] !== "") {
						$images_option = $result['images_option'];
						$images = $this->model_catalog_product->getProductImages($result['product_id']);
						if (count($images) !== 0) {
							$image = array();
							foreach ($images as $img) {
								if ($result['ean']>0) {
									$image[] = $this->model_tool_image->resize_watermark($img['image'], $result['ean'], $this->config->get('config_image_product_height'));
								} else {
									$image[] = $this->model_tool_image->resize_watermark($img['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
								}
							}
						}
					}

					$this->data['products'][] = array(
						'product_id'  => $result['product_id'],
	                    'minimum'  => $result['minimum'],
						'thumb'       => $image,
						'images_option' => $images_option,
						//'maincat'       => $maincat,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0,160) . '..',
						'options'	  => $this->model_catalog_product->getProductOptions($result['product_id']),
						'price'       => $price,
						'price_value'       => $price_value,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $result['rating'],
            'attribute_group' => $this->model_catalog_product->getProductAttributes($result['product_id']),
						'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'],$connection)
					);



					      //$this->data['price_value'] = $products['price'];
							  //$this->data['special_value'] = $product_info['special'];
							  //$this->data['tax_value'] = (float)$product_info['special'] ? $product_info['special'] : $product_info['price'];

							  $var_currency = array();
							  $var_currency['value'] = $this->currency->getValue();
							  $var_currency['symbol_left'] = $this->currency->getSymbolLeft();
							  $var_currency['symbol_right'] = $this->currency->getSymbolRight();
							  $var_currency['decimals'] = $this->currency->getDecimalPlace();
							  $var_currency['decimal_point'] = $this->language->get('decimal_point');
							  $var_currency['thousand_point'] = $this->language->get('thousand_point');
							  $this->data['currency'] = $var_currency;
	                //print_r($var_currency);
				}

				$url = '';

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$this->data['sorts'] = array();

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url,$connection)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url,$connection)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url,$connection)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url,$connection)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url,$connection)
				);

				if ($this->config->get('config_review_status')) {
					$this->data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_desc'),
						'value' => 'rating-DESC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url,$connection)
					);

					$this->data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_asc'),
						'value' => 'rating-ASC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url,$connection)
					);
				}/*

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url,$connection)
				);

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_model_desc'),
					'value' => 'p.model-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url,$connection)
				); */

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				$this->data['limits'] = array();

				$this->data['limits'][] = array(
					'text'  => $this->config->get('config_catalog_limit'),
					'value' => $this->config->get('config_catalog_limit'),
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $this->config->get('config_catalog_limit'),$connection)
				);

				$this->data['limits'][] = array(
					'text'  => 25,
					'value' => 25,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=25',$connection)
				);

				$this->data['limits'][] = array(
					'text'  => 50,
					'value' => 50,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=50',$connection)
				);

				$this->data['limits'][] = array(
					'text'  => 75,
					'value' => 75,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=75',$connection)
				);

				$this->data['limits'][] = array(
					'text'  => 100,
					'value' => 100,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=100',$connection)
				);

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}',$connection);
				
                $this->data['current_page'] = $pagination->page;
                $this->data['total_page'] = ceil($product_total / $limit);
				$this->data['pagination'] = $pagination->render();

                $this->data['product_total'] = $pagination->total;
                $this->data['page_url_all_products'] = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $pagination->total,$connection);
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
				$this->data['limit'] = $limit;

				$this->data['continue'] = $this->url->link('common/home','',$connection);

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
				} else {
					$this->template = 'default/template/product/category.tpl';
				}

				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);

				$this->response->setOutput($this->render());

			//Nikita_Sp MOD
			}
			// End Nikita_Sp MOD
    	} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
			);

			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->response->setOutput($this->render());
		}
  	}
    
}
