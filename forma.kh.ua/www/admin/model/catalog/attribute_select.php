<?php 
class ModelCatalogAttributeSelect extends Model {	

  public function getSelects($attribute_id) {
    $selects = array();
    
    $this->load->model('catalog/attribute');
		$config = $this->config->get('attribute_select_attributes');
				
		if(!isset($config[$attribute_id]) || !$config[$attribute_id]) {
		  return $selects;
		}
    
    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    if (is_array($languages)){
      
      foreach ($languages as $language) {
        
        $query = $this->db->query("SELECT DISTINCT(text) FROM " . DB_PREFIX . "product_attribute WHERE language_id=" . $language['language_id'] . " AND attribute_id=" . (int) $attribute_id);
        
        if ($query->rows) {
          $select = "<select name='attribute_select_{$attribute_id}' class='attribute_select' attribute_id='{$attribute_id}' language_id='{$language['language_id']}' style='display:block;'>";
          $select .= "<option value=''>-</option>";
          
          foreach ($query->rows as $row) {
            $select .= "<option value='{$row['text']}'>{$row['text']}</option>";  
          }
          
          $select .= "</select>";
          $selects[$language['language_id']] = $select;
        }
        
      }
      
    }
    
    return $selects;
  }
}
