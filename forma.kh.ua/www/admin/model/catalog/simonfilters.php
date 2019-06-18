<?php

/*
 * simonfilters - 2.11.0 Build 0013
*/

class ModelCatalogSimonFilters extends Model {

    public function getTags($language_id) {
        $data = array();
        $sql = "select pd.tag from " . DB_PREFIX . "product_description pd where pd.tag<>'' AND language_id='{$language_id}' group by tag ORDER BY pd.tag";
        $rows = $this->db->query($sql)->rows;
        $tags_temp = array();
        foreach ($rows as $row) {
            foreach (preg_split('/,/', $row['tag']) as $tag) {
                $tag=trim($tag);
                if(!in_array($tag, $tags_temp)) {
                    $tags_temp[]=$tag;
                }
            }
        }
        foreach($tags_temp as $tag) {
            $data[] = array(
                    'product_tag_id' => md5($tag),
                    'tag' => $tag
            );
        }
        return $data;
    }

    public function getPossibleFilters($language_id) {

        #$product_tag_exists = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "product_tag'")->num_rows;

        $product_tag_exists = !$this->db->query("SHOW COLUMNS FROM ". DB_PREFIX."product_description LIKE 'tag';")->num_rows;

        $this->load->model('setting/setting');
        $simonsupported = $this->model_setting_setting->getSetting('simonfilters_supported');

        $attribute_ids = isset($simonsupported['a'])?array_keys( $simonsupported['a']):array();
        array_push($attribute_ids ,-1);
        $attribute_ids= join(',',array_filter($attribute_ids));

        $option_ids = isset($simonsupported['o'])?array_keys( $simonsupported['o']):array();
        array_push($option_ids ,-1);
        $option_ids= join(',',array_filter($option_ids));
        
        $manufacturer_ids = isset($simonsupported['m'])?array_keys( $simonsupported['m']):array();
        array_push($manufacturer_ids ,-1);
        $manufacturer_ids= join(',',array_filter($manufacturer_ids));

        $data = Array(
                'options' => $this->db->query("
                    SELECT od.option_id as id, name 
                    FROM " . DB_PREFIX . "option_description od 
                    WHERE od.language_id={$language_id} AND od.option_id IN($option_ids)
                    ORDER BY name
                ")->rows,
                'manufacturer' => $this->db->query("
                    select m.manufacturer_id as id, name
                    from " . DB_PREFIX . "manufacturer m
                    where manufacturer_id IN($manufacturer_ids)
                    ORDER BY name
                ")->rows,
                'attribute' => $this->db->query("
                    SELECT a.attribute_id as id, ad.name as name, agd.name as groupname, agd.attribute_group_id
                    FROM " . DB_PREFIX . "attribute a
                    JOIN " . DB_PREFIX . "attribute_description ad ON a.attribute_id = ad.attribute_id
                    JOIN " . DB_PREFIX . "attribute_group_description agd ON a.attribute_group_id = agd.attribute_group_id AND ad.language_id = agd.language_id
                    WHERE ad.language_id='{$language_id}' ". "AND a.attribute_id IN($attribute_ids)
                    ORDER BY ad.name
                ")->rows,
                'stock' => $this->db->query("SELECT stock_status_id as id, name FROM " . DB_PREFIX . "stock_status WHERE language_id='{$language_id}' ORDER BY name")->rows,
                'tags' => (
                $product_tag_exists ?
                $this->db->query("select pt.product_tag_id, pt.tag from " . DB_PREFIX . "product_tag pt where language_id='{$language_id}' group by tag ORDER BY pt.tag")->rows :
                $this->getTags($language_id)
                ),
                'categories' => $this->db->query("SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id WHERE cd.language_id='{$language_id}' AND c.status=1")->rows
        );
        return $data;
    }

    public function isSupportedFilter($group,$attribute_id) {
        $this->load->model('setting/setting');
        $simonsupported = $this->model_setting_setting->getSetting('simonfilters_supported');
        if (!isset($simonsupported[$group]) || $attribute_id==0) {
            return false;
        }else {
            return key_exists($attribute_id, $simonsupported[$group]);
        }
    }

    public function addSupportedFilter($group,$attribute_id,$data) {
        $this->load->model('setting/setting');
        $simonsupported = $this->model_setting_setting->getSetting('simonfilters_supported');
        if (!isset($simonsupported[$group])) {
            $simonsupported[$group] = array();
        }
        if (isset($data['simonseesme'])) {
            $simonsupported[$group][$attribute_id] = 1;
        } else {
            unset($simonsupported[$group][$attribute_id]);
        }
        $this->model_setting_setting->editSetting('simonfilters_supported', $simonsupported);
    }

}
