<?php  
class ControllerModuleTesting extends Controller {
	protected function index() {
		
        $query = $this->db->query("SELECT DISTINCT ovd.option_value_id, ovd.*, od.name as 'option_name', ov.image 
FROM `khf2_option_value_description` ovd
	LEFT JOIN khf2_option_value ov ON(ovd.option_value_id=ov.option_value_id)
	LEFT JOIN khf2_option_description od ON(ov.option_id=od.option_id)
	LEFT JOIN `khf2_option` o ON(ov.option_id=o.option_id)
	LEFT JOIN khf2_product_option_value pov ON(ovd.`option_value_id`=pov.`option_value_id`)
	LEFT JOIN khf2_product p ON(pov.product_id = p.product_id) 
	LEFT JOIN khf2_product_to_category p2c ON(p.product_id = p2c.product_id) 
	LEFT JOIN khf2_product_to_store p2s ON(p.product_id=p2s.product_id)
WHERE ovd.language_id = '7' AND od.language_id = '7'  AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id =0 AND p2c.category_id = '60' 
ORDER BY o.sort_order, ov.sort_order, ovd.option_id ");
		$options = array();
		foreach($query->rows as $row) {
			if(!isset($options[$row['option_id']])) {
				$options[$row['option_id']] = array('option_id' => $row['option_id'],
													'name' => $row['option_name'],
													'option_values' => array());
			}

		if($this->normalizeVersion() < 1513) {
			$row['image'] = "";
		}
			$options[$row['option_id']]['option_values'][] = array('option_value_id' => $row['option_value_id'], 'name' => $row['name'], 'image' => $row['image']);
		}
        $this->data['options'] = $options;
        
        $this->load->model('tool/image');
        foreach($this->data['options'] as $i => $option) {
            if(!isset($filterpro_setting['display_option_' . $option['option_id']])) {
                $filterpro_setting['display_option_' . $option['option_id']] = 'none';
            }
            $display_option = $filterpro_setting['display_option_' . $option['option_id']];
            if($display_option != 'none') {
                $this->data['options'][$i]['display'] = $display_option;
                $this->data['options'][$i]['expanded'] = isset($filterpro_setting['expanded_option_' . $option['option_id']]) ? 1 : 0;
                foreach($this->data['options'][$i]['option_values'] as $j => $option_value) {
                    $this->data['options'][$i]['option_values'][$j]['thumb'] = $this->model_tool_image->resize($this->data['options'][$i]['option_values'][$j]['image'], 20, 20);
                }
            } else {
                unset($this->data['options'][$i]);
            }
        }
        
	}
}