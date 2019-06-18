<?php
class ModelShippingAny extends Model {
	function getQuote($address) {
		$this->load->language('shipping/any');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('any_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('any_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();

            $simple_create_order = !empty($this->request->post['simple_create_order']);  

            $any_value = isset($this->request->post['any_value']) ? trim($this->request->post['any_value']) : '';

            $error = '';

            if ($this->request->server['REQUEST_METHOD'] == 'POST' && $any_value == '' && !empty($this->request->post['shipping_method']) && $this->request->post['shipping_method'] == 'any.any') {
                $error = $this->language->get('error_any');
                $this->simple->add_error('shipping');
            }

            if ($this->request->server['REQUEST_METHOD'] == 'GET') {
                $this->simple->add_error('shipping');
            }

            $tpl = '</label>'.(($simple_create_order && $error) ? '<div id="any_error" class="simplecheckout-warning-block">'.$error.'</div>' : '');
            $tpl .= '<input type="text" size="30" name="any_value" onchange="$(this).parent().prev().find(\'input\').attr(\'checked\',\'checked\');simplecheckout_reload();" id="any_value" value="'.$any_value.'"><label>';
			
            $tpl .= '<script type="text/javascript">$(function(){';
            $tpl .= 'if ($("#any_error").length == 0) {$("#any_value").closest("tr").hide()} else {$("#any_error").css("margin","0px")}';
            $tpl .= '$("#any_value").closest("tr").prev().find("td").eq(1).find("label").remove();';
            $tpl .= '$("#any_value").closest("tr").prev().find("td").eq(1).append($("#any_value"));';
            $tpl .= '});</script>';

      		$quote_data['any'] = array(
        		'code'         => 'any.any',
        		'title'        => $this->request->server['REQUEST_METHOD'] == 'POST' && $any_value != '' ? $any_value : $this->language->get('text_description'),
                'description'  => $tpl,
        		'cost'         => 0.00,
        		'tax_class_id' => 0,
				'text'         => ''
      		);

      		$method_data = array(
        		'code'       => 'any',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('any_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
}
?>