<?php
class ModelCatalogExport extends Model {
	private $CSV_SEPARATOR = ';';
	private $CSV_ENCLOSURE = '"';

	private $field_caption = NULL;
	private $setting = array();
	private $attributes = array();
	private $CoreType = NULL;
	private $PathNameByCategory = array();
	private $CustomFields = array();

	// File
	private $f_tell = 0;

	public function export(&$data, &$objPHPExcel) {
		$this->language->load('module/export');
		$text_title = $this->language->get('text_title');
		$text_image = $this->language->get('text_image');
		$text_weight = $this->language->get('text_weight');
		$text_consumption = $this->language->get('text_consumption');
		$text_sizes = $this->language->get('text_sizes');
		$text_materials = $this->language->get('text_materials');
		$text_quantity = $this->language->get('text_quantity');
		$text_min = $this->language->get('text_min');
		$text_price = $this->language->get('text_price');
		$text_images = $this->language->get('text_images');

		$this->load->model('catalog/product');
		$this->setting = $data;

		$output = '';
		$ods_title = array();

		//$ods_title[] = '_CATEGORY_';
		$_fields = array();
		$_left = array();
		$_where = array();

		$_fields[] = 'p.product_id';

		if(isset($data['fields_set']['_NAME_'])) {
			$_fields[] = 'REPLACE (pd.name, \'&quot;\', \'"\') AS name';
			if( ! isset($_left['pd']) ) $_left['pd'] = " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id) ";
			if( ! isset($_where['pd']) ) $_where['pd'] = " pd.language_id = '" . (int)$data['language_id'] . "' ";
			$ods_title[] = $text_title;
		}
        $_fields[] = 'p.ean';
		$_fields[] = 'p.image'; $ods_title[] = $text_image;
		$isCategoryDobavki = false;
		if(in_array(2, $data['category']) || in_array(17, $data['category']) || in_array(18, $data['category']) || in_array(65, $data['category']) || in_array(68, $data['category'])
		|| in_array(67, $data['category']) || in_array(19, $data['category']) || in_array(66, $data['category'])){
			$isCategoryDobavki = true;
            $_fields[] = 'p.jan';  $ods_title[] = $text_weight;
            $_fields[] = 'p.status'; $ods_title[] = $text_consumption;
		}else{
			$_fields[] = 'p.upc'; $ods_title[] = $text_sizes;
			$ods_title[] = $text_materials;
			$_fields[] = 'p.location'; $ods_title[] = $text_quantity;
			$_fields[] = 'p.minimum'; $ods_title[] = $text_min;
		}
		$_fields[] = 'TRUNCATE(p.price, 2) AS price'; $ods_title[] = $text_price;

		// WHERE category
		if($data['category']) {
			$category  =  implode(',', $data['category']);
			$_where[] = ' (p2c.category_id IN ('.$category .')) ';
			$_left[] = ' LEFT JOIN `' . DB_PREFIX . 'product_to_category` p2c ON (p.product_id = p2c.product_id) ';
		}
		// visible products
		$_where[] = ' (p.status = 1) ';

		if( count($_where) > 0 ) {
			$WHERE = ' WHERE ' . implode('AND', $_where) . ' ';
		} else {
			$WHERE = '';
		}

		$sql = 'SELECT DISTINCT '  . (implode(',', $_fields)) . ' FROM ' . DB_PREFIX . 'product p '. (implode(' ', $_left)) . $WHERE . ' ORDER BY p.product_id';
		$query = $this->db->query($sql);

		if(count($query->rows) < 1) {
			$output = array(
				'error' => 'error_export_empty_rows'
			);
			return $output;
		}

		if ($data['file_format'] == 'csv') {
			//EXPORT CSV FORMAT

			$charset = ini_get('default_charset');
			ini_set('default_charset', 'UTF-8');

				if(isset($data['fields_set']['_IMAGES_'])) {
					$ods_title[] = $text_images;
				}
				// Add some data
				$alphabet = array("A","B","C","D","E","F","G","H");
				$sheet = $objPHPExcel->setActiveSheetIndex(0);

				// Add logo and phones
				if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setWorksheet($sheet);
					$objDrawing->setResizeProportional(false);
					$objDrawing->setName("Logo");
					$objDrawing->setDescription("Logo of forma.kh.ua");
					$objDrawing->setPath(DIR_IMAGE.$this->config->get('config_logo'));
					$objDrawing->setWidth(210);
					$objDrawing->setCoordinates("A1");
					$sheet->getRowDimension(1)->setRowHeight(60);
				}
				$sheet->getStyle('D1')->getAlignment()->setWrapText(true);
				$sheet->getStyle('E1')->getAlignment()->setWrapText(true);
				$sheet->setCellValue("D1", "http://forma.kh.ua\nforma@ua.fm");
				$sheet->getColumnDimension('E')->setWidth(20);
				$sheet->setCellValue("E1", "Artem\ntel.:095-058-59-53\n096-427-42-59");
				$sheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				if($isCategoryDobavki){
			    	$sheet->getStyle("A1:F1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					//$sheet->mergeCells('C1:D1');
					$sheet->mergeCells('E1:F1');
				}else{
			    	$sheet->getStyle("A1:G1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					//$sheet->mergeCells('C1:D1');
					$sheet->mergeCells('E1:G1');
				}

				$this->load->model('tool/image');
				foreach($ods_title as $key => $title){
					$sheet->setCellValue($alphabet[$key]."4", $title);
					$sheet->getColumnDimension($alphabet[$key])->setAutoSize(true);
					$sheet->getStyle($alphabet[$key]."4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$sheet->getStyle($alphabet[$key]."4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$sheet->getColumnDimension('A')->setAutoSize(false);
				$sheet->getColumnDimension('A')->setWidth(30);
				$sheet->getColumnDimension('B')->setAutoSize(false);
				$sheet->getColumnDimension('B')->setWidth(15);
				foreach ($query->rows as $cell => $fields) {
					if(isset($data['fields_set']['_IMAGES_'])) {
						$fields['images'] = $this->getProductImages($fields['product_id']);
					}

					$key = 0;
					$i = 0;
					$options = $this->getProductOptions($fields['product_id'], floatval($fields['price']));
					$isUPC = true;
    				foreach($fields as $field_name => $field){
    					if($field_name == "product_id" || $field_name == "ean"){
    						continue;
    					}elseif ($field_name == "status"){
    						// Nothing to do here it's hack for non existing field
    					}elseif ($field_name == "price") {
    						$val = floatval($field);
    						if ($this->currency->getCode() === "USD") {
								$val = $this->currency->convert($val, "UAH", "USD");
							} else if ($this->currency->getCode() === "RUR") {
								$val = $this->currency->convert($val, "UAH", "RUR");
							}
    						$sheet->setCellValue($alphabet[$key].($cell+5), round($val, 2));
	    					$i++;
	    					$sheet->getStyle($alphabet[$key].($cell+5))->getAlignment()->setWrapText(true);
	    					$sheet->getCell($alphabet[$key].($cell+5))->getHyperlink()->setUrl($this->url->link('product/product', 'product_id=' . $fields['product_id']));
    					}elseif ($field_name == "image"){
    						if(isset($field) && $field != ''){
	    						if(is_readable(DIR_IMAGE.preg_replace('#^https?://forma.kh.ua/image/#', '', $this->model_tool_image->resize_watermark($field, 100, 150)))){
	    							if(isset($fields['ean']) && $fields['ean'] != '' ){
	    								$sheet->getColumnDimension('B')->setWidth($fields['ean']/10);
	    								$imageUrl = pathinfo($this->model_tool_image->resize_watermark($field, $fields['ean'], 150));
	    							}else{
	    								$imageUrl = pathinfo($this->model_tool_image->resize_watermark($field, 150, 150));
	    							}
	    							if(!isset($imageUrl['dirname'])){ die("Error on product:". $fields['name'] ."! Cannot access to file: ".$field); }
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setWorksheet($sheet);
									$objDrawing->setName($fields['name']);
									$objDrawing->setDescription("");
									$objDrawing->setPath(DIR_IMAGE.preg_replace('#^https?://forma.kh.ua/image/#', '', $imageUrl['dirname']) .'/'.urlencode($imageUrl['basename']));
									$objDrawing->setCoordinates($alphabet[$key].($cell+5));
									if(isset($fields['ean']) && $fields['ean'] != '' ){
										$objDrawing->setWidthAndHeight($fields['ean'], 100);
									}else{
										$objDrawing->setWidthAndHeight(100, 100);
									}
									$sheet->getRowDimension($cell+5)->setRowHeight(100);
								}else{
									$sheet->setCellValue($alphabet[$key].($cell+5), $field);
									$sheet->getRowDimension($cell+5)->setRowHeight(100);
								}
							}
	    				}elseif ($field_name == "images"){
	    					$images = explode(",", $field);
	    					if(isset($images[0]) && $images[0] != ''){
	    						if(is_readable(DIR_IMAGE.preg_replace('#^https?://forma.kh.ua/image/#', '', $this->model_tool_image->resize_watermark($images[0], 100, 150)))){
	    							$imageUrl = pathinfo($this->model_tool_image->resize_watermark($images[0], 100, 150));
	    							if(!isset($imageUrl['dirname'])){ die("Error on product:". $fields['name'] ."! Cannot access to file: ".$field); }
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setWorksheet($sheet);
									$objDrawing->setName($fields['name']);
									$objDrawing->setDescription("");
									$objDrawing->setPath(DIR_IMAGE.preg_replace('#^https?://forma.kh.ua/image/#', '', $imageUrl['dirname']) .'/'.urlencode($imageUrl['basename']));
									$objDrawing->setCoordinates($alphabet[$key].($cell+5));
									$objDrawing->setWidthAndHeight(100, 100);
									$sheet->getRowDimension($cell+5)->setRowHeight(100);
								}else{
									$sheet->setCellValue($alphabet[$key].($cell+5), $images[0]);
									$sheet->getRowDimension($cell+5)->setRowHeight(100);
								}
							}
	    				}else{
	    					if ($i == 2) {
	    						$key++;
	    						$i++;
	    					}
    						if ($i == 3 && count($options) > 0) {
    							$value = "";
    							$value2 = "";
    							foreach ($options as $option) {
    								if ($option['name'] == $text_sizes) {
    									$isUPC = false;
    									$value = $option['value'];
    								} elseif ($option['name'] == $text_materials) {
    									$value2 = $option['value'];
    								}
    							}
    							if (!$isUPC) {
    								$sheet->setCellValue("C".($cell+5), $value);
    							}
    							$sheet->setCellValue("D".($cell+5), $value2);
    							$sheet->getStyle("D".($cell+5))->getAlignment()->setWrapText(true);
	    						$sheet->getCell("D".($cell+5))->getHyperlink()->setUrl($this->url->link('product/product', 'product_id=' . $fields['product_id']));
    							$sheet->getStyle("D".($cell+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	    						$sheet->getStyle("D".($cell+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    						}
	    					$sheet->setCellValue($alphabet[$key].($cell+5), $field);
	    					$i++;
	    					$sheet->getStyle($alphabet[$key].($cell+5))->getAlignment()->setWrapText(true);
	    					$sheet->getCell($alphabet[$key].($cell+5))->getHyperlink()->setUrl($this->url->link('product/product', 'product_id=' . $fields['product_id']));
	    				}
	    				$sheet->getStyle($alphabet[$key].($cell+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	    				$sheet->getStyle($alphabet[$key].($cell+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    					$key++;
    				}
				}
		}
	}

	// get Product Images
	//-------------------------------------------------------------------------
	private function getProductImages($product_id) {
		$images = array();
		$query = $this->db->query('SELECT pi.image FROM `'. DB_PREFIX . 'product_image` pi WHERE pi.product_id = ' . (int)$product_id);

		foreach ($query->rows as $result) {
			$images[] = $result['image'];
		}

		return implode(",", $images);
	}

	private function getProductOptions($product_id, $price) {
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "'");
		$result = array();
		$curr = " грн/шт.)\n";
		if ($this->currency->getCode() === "USD") {
			$curr = " $/шт.)\n";
			$price = $this->currency->convert($price, "UAH", "USD");
		} else if ($this->currency->getCode() === "RUR") {
			$curr = " руб/шт.)\n";	
			$price = $this->currency->convert($price, "UAH", "RUR");
		}
		foreach ($this->model_catalog_product->getProductOptions($product_id) as $product_option) {
			if ($product_option['type'] == 'select') {
				$value = "";
				foreach ($product_option['option_value'] as $product_option_value) {
					$val = floatval(substr($product_option_value['price'], 0, 4));
					if ($this->currency->getCode() === "USD") {
						$val = $this->currency->convert($val, "UAH", "USD");
					} else if ($this->currency->getCode() === "RUR") {
						$val = $this->currency->convert($val, "UAH", "RUR");
					}
					if ($product_option_value['price_prefix'] == "+") {
						$value = $value.$product_option_value['name']."(".round($val+$price,2).$curr;
					} elseif ($product_option_value['price_prefix'] == "-") {
						$value = $value.$product_option_value['name']."(".round($price-$val,2).$curr;
					}
				}
				$result[] = array(
					"name"  => $product_option['name'],
					"value"  => $value
				);
			}
      	}
      	return $result;
    }

	public function get_tmp_dir() {
		return DIR_SYSTEM . 'prices/';
	}
}
?>