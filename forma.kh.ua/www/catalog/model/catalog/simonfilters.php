<?php

/*
 * simonfilters - 2.11.0 Build 0013
*/

class ModelCatalogSimonFilters extends Model {

    public static $sent = 0;
    public static $data = null;
    public static $simonsql = '';
    public static $simonsqloptions = '';
    public static $currentfilters = array();
    public static $debugmode = false;
    public static $language_id = 0;
    public static $store_id = 0;
    public static $product_tag_exists = 0;
    public static $static_isSupportedRoute = null;
    public static $static_SimonAttributes  = null;
    public static $previousids = "";

    public function myutf8($value2convert) {
        if (function_exists('utf8_strtolower')) {
            return utf8_strtolower($value2convert);
        } else {
            return mb_strtolower($value2convert, 'UTF-8');
        }
    }

    public function isActive() {
        $layout_id = $this->getLayoutID();
        foreach ($this->config->get("simonfilters_module") as $simonfilters_module) {
            if ($simonfilters_module['layout_id'] == $layout_id && $simonfilters_module['status']==1) {
                return true;
            }
        }
        return false;
    }

    public function getLayoutID() {
        if (isset($this->request->get['route'])) {
            $route = $this->request->get['route'];
        } else {
            $route = 'common/home';
        }

        $layout_id = 0;

        if (substr($route, 0, 16) == 'product/category' && isset($this->request->get['path'])) {
            $path = explode('_', (string)$this->request->get['path']);

            $layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
        }

        if (substr($route, 0, 15) == 'product/product' && isset($this->request->get['product_id'])) {
            $layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
        }

        if (substr($route, 0, 23) == 'information/information' && isset($this->request->get['information_id'])) {
            $layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
        }

        if (!$layout_id) {
            $this->load->model('design/layout');
            $layout_id = $this->model_design_layout->getLayout($route);
        }

        if (!$layout_id) {
            $layout_id = $this->config->get('config_layout_id');
        }
        return $layout_id;
    }

    public function getAllProductCategories() {
        $cats = array();
        $product_id = @$this->request->get["product_id"] or $product_id = 0;
        if ($product_id != 0) {
            $sql = "SELECT pc.category_id FROM " . DB_PREFIX . "product_to_category pc WHERE pc.product_id = '" . (int) $product_id . "'";
            $query = $this->db->query($sql);
            foreach ($query->rows as $row) {
                $cats[] = $row['category_id'];
            }
        }
        return $cats;
    }

    public function getCurrentIDSbyCategory() {

        $product_id = @$this->request->get["product_id"] or $product_id = 0;
        $sql = "
            SELECT DISTINCT pc.product_id
            FROM " . DB_PREFIX . "product_to_category pc
            WHERE pc.category_id IN (
            SELECT category_id
            FROM " . DB_PREFIX . "product_to_category
            WHERE product_id='" . (int) $product_id . "')
        ";
        $product_data = array();

        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $product_data[] = $result['product_id'];
        }
        return $product_data;
    }

    public function getCurrentIDSbySpecial() {

        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $product_id = (isset($this->request->get["product_id"])) ? $this->request->get["product_id"] : 0;

        $sql = "
            SELECT DISTINCT 
                ps.product_id
            FROM 
                " . DB_PREFIX . "product_special ps
                LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
            WHERE 
                p.status = '1' 
                AND p.date_available <= NOW() 
                AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' 
                AND ps.customer_group_id = '" . $customer_group_id . "' 
                AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) 
                AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
            GROUP BY ps.product_id    
        ";
        $product_data = array();

        $query = $this->db->query($sql);
        foreach ($query->rows as $result) {
            $product_data[] = $result['product_id'];
        }
        return $product_data;
    }

    public function getCurrentIDSfromWishList() {
        $product_data = array();
        foreach ($this->session->data['wishlist'] as $product_id) {
            $product_data[] = $product_id;
        }
        return $product_data;
    }

    public function getCurrentIDS() {

        $SimonAttributes = $this->getSimonAttributes();
        $this->load->model("catalog/product");
        $product_data = array();

        $data = array(
                'filter_category_id' => $this->getCategoryID()
        );

        switch ($this->getRoute()) {
            case "product/search":
                $data['limit']=500;
                $data['start']=0;
                break;
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $data['filter_manufacturer_id'] = $this->request->get['manufacturer_id'];
        }
        if (isset($this->request->get['filter_name'])) {
            $data['filter_name'] = $this->request->get['filter_name'];
        }
        if (isset($this->request->get['filter_tag'])) {
            $data['filter_tag']  = $this->request->get['filter_tag'];
        }

        $rows = $this->model_catalog_product->getProducts($data);
        foreach ($rows as $result) {
            $product_data[] = $result['product_id'];
        }

        return $product_data;
    }

    /*
     * constructor
    */

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('setting/setting');
        $this->setting = $this->model_setting_setting->getSetting('filter');
        $this->load->model('localisation/currency');
        if ($this->sent == 0 && $this->isSupportedRoute()) {

            $this->product_tag_exists = !$this->db->query("SHOW COLUMNS FROM ". DB_PREFIX."product_description LIKE 'tag';")->num_rows;
            $this->language_id = (int) $this->config->get('config_language_id');
            $this->store_id = (int) $this->config->get('config_store_id');

            $index = $this->getSimonIndex();
            if (isset($_GET['forcefiltersupdate'])) {
                if (isset($_GET['checkedfilters']) && $_GET['checkedfilters'] != '') {
                    $this->currentfilters = $_GET['checkedfilters'];
                } else {
                    $this->currentfilters = array();
                }
                $_SESSION['simonfilters']['filters'][$index] = $this->currentfilters;
            } elseif (isset($_SESSION['simonfilters']['filters'][$index])) {
                if ($this->config->get('simonfilters_persist') == '1' || isset($_GET['limit']) || isset($_GET['page'])) {
                    $this->currentfilters = $_SESSION['simonfilters']['filters'][$index];
                } else {
                    $this->currentfilters = array();
                }
            } else {
                $this->currentfilters = array();
            }

            /*
             * Debug stuff
            */
            if (defined("SIMON_DEBUG") && SIMON_DEBUG == '1')
                $this->debugmode = true;
            if (isset($_GET['debug'])) {
// Comment next line if you don't want me to debug your site!
                $_SESSION['simonisdebugging'] = ($_GET['debug'] == 'itsmesimon' ? 1 : 0);
            }
            if (isset($_SESSION['simonisdebugging']) && $_SESSION['simonisdebugging'] == 1) {
                $this->debugmode = true;
            }
            /*
             * Debug stuff
            */



            $this->data = $this->getFiltersByIds();
        }
    }

    public function manageCache($sql, $name) {
        $cache = md5($sql);
        $query = $this->cache->get($name . '.' . $cache);
        if (!$query) {
            $query = $this->db->query($sql);
            $this->cache->set($name . '.' . $cache, $query);
        }
        return $query;
    }

    public function getAllTags1541($language_id) {
        $data = array();
        $activefilter_simonfilters_tags = $this->config->get("activefilter_simonfilters_tags");
        $sql = "select pd.tag from " . DB_PREFIX . "product_description pd where pd.tag<>'' AND language_id='{$language_id}' group by tag ORDER BY pd.tag";
        $rows = $this->db->query($sql)->rows;
        foreach ($rows as $row) {
            foreach (preg_split('/,/', $row['tag']) as $tag) {
                $product_tag_id = md5($tag);
                if(isset($activefilter_simonfilters_tags[$product_tag_id]) && !in_array($tag,$data)) {
                    $data[$tag] = $tag;
                }
            }
        }
        return $data;
    }

    public function getsfsid() {
        switch ($this->getRoute()) {
            case "product/product":
                $currentIDS = $this->getCurrentIDSbyCategory();
                if (count($currentIDS) == 0)
                    $currentIDS[] = 0;
                $sfsids = join(',', $currentIDS);
                break;
            case "product/special":
                $currentIDS = $this->getCurrentIDSbySpecial();
                if (count($currentIDS) == 0)
                    $currentIDS[] = 0;
                $sfsids = join(',', $currentIDS);
                break;
            case "account/wishlist":
                $currentIDS = $this->getCurrentIDSfromWishList();
                if (count($currentIDS) == 0)
                    $currentIDS[] = 0;
                $sfsids = join(',', $currentIDS);
                break;
            case "homepage":
                $currentIDS = array();
                $sfsids = "-1";
                break;
            default:
                $currentIDS = $this->getCurrentIDS();
                if (count($currentIDS) == 0) {
#$currentIDS=array();
                    $currentIDS[] = 0;
                }
                $sfsids = join(',', $currentIDS);
                break;
        }
        return array(
                'currentIDS' => $currentIDS,
                'sfsids' => $sfsids
        );
    }

    public function getFiltersByIds() {
        if ($this->isSupportedRoute()) {
            $this->load->language('module/simonfilters');
            $store_id = (int) $this->config->get('config_store_id');
            $language_id = $this->config->get('config_language_id');

//todo as condições
#if($this->getRoute()=='homepage'){
#$this->config->set("simonfilters_force_all",1);
#}

            if ($this->config->get("simonfilters_force_all") == '1') {
                $sfsids = "";
            } else {
                $sfsidsdata = $this->getsfsid();
                $sfsids = $sfsidsdata['sfsids'];
                $currentIDS = $sfsidsdata['currentIDS'];
            }
            if($sfsids!='0' && $sfsids!='' && $sfsids!='-1' && $this->getRoute()!='home') {
                $_SESSION['simonfilters']['previousfilters'][$this->getSimonIndex()]['sfsids'] = $sfsids;
                $_SESSION['simonfilters']['previousfilters'][$this->getSimonIndex()]['currentIDS'] = $currentIDS;
            }else {
                if(isset( $_SESSION['simonfilters']['previousfilters'][$this->getSimonIndex()] )) {
                    $sfsids = $_SESSION['simonfilters']['previousfilters'][$this->getSimonIndex()]['sfsids'];
                    $currentIDS = $_SESSION['simonfilters']['previousfilters'][$this->getSimonIndex()]['currentIDS'];
                }
            }
            $data = array();

            $activefilter_simonfilters_expanded = $this->config->get("activefilter_simonfilters_expanded");

            $activefilter_simonfilters_attribute = $this->config->get("activefilter_simonfilters_attribute");
            $activefilter_simonfilters_options = $this->config->get("activefilter_simonfilters_options");
            $activefilter_simonfilters_tags = $this->config->get("activefilter_simonfilters_tags");
            $activefilter_simonfilters_manufacturer = $this->config->get("activefilter_simonfilters_manufacturer");
            $activefilter_simonfilters_stock = $this->config->get("activefilter_simonfilters_stock");
            $activefilter_simonfilters_categories = $this->config->get("activefilter_simonfilters_categories");
            $price_config_data = $this->config->get("activefilter_simonfilters_price");
            $activefilter_simonfilters_price = isset($price_config_data[0]['a']);

            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_attribute', $activefilter_simonfilters_attribute);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_options', $activefilter_simonfilters_options);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_tags', $activefilter_simonfilters_tags);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_manufacturer', $activefilter_simonfilters_manufacturer);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_stock', $activefilter_simonfilters_stock);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_price', $activefilter_simonfilters_price);
            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:\$activefilter_simonfilters_categories', $activefilter_simonfilters_categories);

            $simonfilters_hard = $this->config->get("simonfilters_hard");
            /* Categories */

            if (count($activefilter_simonfilters_categories) > 0) {

                $simonfilters_categories_behavior = $this->config->get("simonfilters_categories_behavior");
                if(isset($simonfilters_categories_behavior) && ($this->getRoute()=='product/category')) {
                    switch($simonfilters_categories_behavior) {
                        case '0'://all;
                            $attribute_ids = implode(',', array_keys($activefilter_simonfilters_categories));
                            break;
                        case '1'://only current & childs

                            $this->load->model("catalog/category");
                            $childcats = $this->model_catalog_category->getCategories($this->getCategoryID());
                            $childcatsAri = array();
                            if( in_array( $this->getCategoryID() ,array_keys($activefilter_simonfilters_categories) ) ) {
                                array_push($childcatsAri, $this->getCategoryID());
                            }
                            foreach($childcats as $childcat) {
                                if( in_array( $childcat['category_id'] ,array_keys($activefilter_simonfilters_categories) ) ) {
                                    array_push($childcatsAri, $childcat['category_id']);
                                }
                            }

                            $childcatsAri = array_diff(array_keys($activefilter_simonfilters_categories), $childcatsAri);
                            $attribute_ids = implode(',', $childcatsAri);
                            break;
                    }
                }else {
                    $attribute_ids = implode(',', array_keys($activefilter_simonfilters_categories));
                }

                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND pdc.product_id IN ($sfsids)";
                }
                if($attribute_ids=='')$attribute_ids='0';

                $sql = "
                    SELECT c.category_id, cd.name AS filter_name, '" . $this->language->get('categories') . "' AS filter_group_name, 1 AS group_id, count(*) as total
                    FROM
                    " . DB_PREFIX . "category c
                    JOIN " . DB_PREFIX . "product_to_category pdc ON c.category_id = pdc.category_id
                    JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id AND cd.language_id=$language_id
                    WHERE
                            c.status=1 AND c.category_id in ($attribute_ids) {$sql_id_products} AND cd.language_id = $language_id 
                    GROUP BY
                            category_id
                    ORDER by c.sort_order
                        ";

                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByIds:categories sql', $sql);
                $query = $this->manageCache($sql, 'simonfilters.getCategories' . $language_id . '.' . $store_id);
                foreach ($query->rows as $row) {
                    $data['c'][$row['group_id']]['filter_group_name'] = $row['filter_group_name'];
                    $data['c'][$row['group_id']][$row['category_id']]['filter_name'][md5($row['category_id'])] = $row['filter_name'];
                    $data['c'][$row['group_id']][$row['category_id']]['filter_totals'][md5($row['category_id'])] = $row['total'];
                }
                if ($query->num_rows) {
                    if (isset($activefilter_simonfilters_expanded['categories'])) {
                        $data['c'][$row['group_id']]['b'] = $activefilter_simonfilters_expanded['categories'];

                    }
                }
            }


            /* Attributes */
            if (count($activefilter_simonfilters_attribute) > 0) {
                $attribute_ids = implode(',', array_keys($activefilter_simonfilters_attribute));

                if ($this->config->get("simonfilters_option_zero_quantity") == '1') {
                    $sql_zero_quantity1 = "JOIN " . DB_PREFIX . "product p ON p.product_id = pa.product_id ";
                    $sql_zero_quantity2 = "AND p.quantity>0";
                } else {
                    $sql_zero_quantity1 = "";
                    $sql_zero_quantity2 = "";
                }

                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND pa.product_id IN ($sfsids)";
                }

                $sql = "
                SELECT
                    ad.attribute_id, ad.name AS filter_name, agd.name AS filter_group_name, ad.attribute_id as group_id, pa.text
                FROM " . DB_PREFIX . "attribute a
                    JOIN " . DB_PREFIX . "attribute_description ad ON a.attribute_id = ad.attribute_id
                    JOIN " . DB_PREFIX . "attribute_group_description agd ON agd.attribute_group_id = a.attribute_group_id
                    JOIN " . DB_PREFIX . "product_attribute pa ON a.attribute_id = pa.attribute_id
                        {$sql_zero_quantity1}
                WHERE
                    pa.text<>'' AND a.attribute_id IN($attribute_ids) AND ad.language_id='$language_id' AND agd.language_id = ad.language_id AND pa.language_id = ad.language_id
                        {$sql_zero_quantity2}
                        {$sql_id_products}
                GROUP BY a.attribute_id, ad.name, agd.name, agd.attribute_group_id, pa.text
                ORDER BY a.sort_order
                        ";
                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByIds:attribute sql', $sql);
                $query = $this->manageCache($sql, 'simonfilters.getAttributesByIds' . $language_id . '.' . $store_id);
                $simonfilters_enable_attribute_separator = $this->config->get("simonfilters_enable_attribute_separator");



                foreach ($query->rows as $row) {

                    if( isset($simonfilters_hard['attribute'][$row['attribute_id']])) {
                        if($_SESSION['language'] == $this->config->get("config_admin_language")) {
                            $row['filter_name'] = $simonfilters_hard['attribute'][$row['attribute_id']];
                        }
                    }

                    $data['a'][$row['group_id']]['filter_group_name'] = $row['filter_name'];
                    $total = 0;
                    switch ($simonfilters_enable_attribute_separator) {
                        case '1':
                            $simonfilters_attribute_separator_char = $this->config->get("simonfilters_attribute_separator_char");

                            if ($simonfilters_attribute_separator_char != '') {

                                foreach (explode($simonfilters_attribute_separator_char, $row['text']) as $a) {
                                    $total = 0;
                                    $simonfilters_attribute_separator_char_safe = $this->makeTextSafe($simonfilters_attribute_separator_char);
                                    $a = trim($a);
                                    $data['a'][$row['group_id']][$row['attribute_id']]['filter_name'][md5($a)] = $a;

                                    if ($this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals")=='2') {
                                        $textSafe = $this->makeTextSafe($a);

                                        $attribute_id = $row['attribute_id'];
                                        $sql = "SELECT count(distinct pa.product_id) as total FROM " . DB_PREFIX . "product_attribute pa JOIN " . DB_PREFIX . "product p ON pa.product_id = p.product_id WHERE ";
                                        $sql .= "
                                            (pa.text LIKE '{$textSafe}{$simonfilters_attribute_separator_char_safe}%'
                                            or pa.text LIKE '%{$simonfilters_attribute_separator_char_safe}{$textSafe}{$simonfilters_attribute_separator_char_safe}%'
                                            or pa.text LIKE '%{$simonfilters_attribute_separator_char_safe}{$textSafe}'
                                            or pa.text = '{$textSafe}')
                                                $sql_id_products
                                                $sql_zero_quantity2
                                            AND pa.language_id={$language_id}
                                            AND pa.attribute_id={$attribute_id}
                                            AND p.status = 1
                                                ";
                                        $this->debug(__FILE__ . ';' . __LINE__, 'Count product totals(attribute)', $sql);
                                        if ($this->config->get("simonfilters_display_totals") == '1') {
                                            $query = $this->db->query($sql);
                                        }else {
                                            $query = $this->manageCache($sql, 'simonfilters.getAttributesTotals.' . $attribute_id.'.'. md5($a) .'.'. $language_id . '.' . $store_id);
                                        }
                                        $total = $query->row['total'];
                                    }

                                    $data['a'][$row['group_id']][$row['attribute_id']]['filter_totals'][md5($a)] = $total;
                                }
                            } else {
                                $data['a'][$row['group_id']][$row['attribute_id']]['filter_name'][md5($row['text'])] = trim($row['text']);
                                $total = 0;
                                if ($this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals")=='2') {
                                    $text = trim($row['text']);
                                    $textSafe = $this->makeTextSafe($text);
                                    $attribute_id = $row['attribute_id'];
                                    $sql = "SELECT count(distinct pa.product_id) as total FROM " . DB_PREFIX . "product_attribute pa JOIN " . DB_PREFIX . "product p ON pa.product_id = p.product_id WHERE ";
                                    $sql .= "
                                        (pa.text = '{$textsafe}')
                                            $sql_id_products
                                            $sql_zero_quantity2
                                        AND pa.language_id={$language_id}
                                        AND pa.attribute_id={$attribute_id}
                                        AND p.status = 1
                                            ";

                                    if ($this->config->get("simonfilters_display_totals") == '1') {
                                        $query = $this->db->query($sql);
                                    }else {
                                        $query = $this->manageCache($sql, 'simonfilters.getAttributesTotals.' . $attribute_id. '.'. md5($row['text']) .'.'. $language_id . '.' . $store_id);
                                    }
                                    $total = $query->row['total'];
                                }
                                $data['a'][$row['group_id']][$row['attribute_id']]['filter_totals'][md5($row['text'])] = $total;
                            }

                            break;
                        default:
                            $data['a'][$row['group_id']][$row['attribute_id']]['filter_name'][md5($row['text'])] = trim($row['text']);
                            if ($this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals")=='2') {
                                $text = trim($row['text']);
                                $textSafe = $this->makeTextSafe($text);
                                $attribute_id = $row['attribute_id'];
                                $sql = "SELECT count(distinct pa.product_id) as total FROM " . DB_PREFIX . "product_attribute pa JOIN " . DB_PREFIX . "product p ON pa.product_id = p.product_id WHERE ";
                                $sql .= "
                                    (pa.text = '{$textSafe}')
                                        $sql_id_products
                                        $sql_zero_quantity2
				    AND pa.language_id={$language_id}
				    AND pa.attribute_id={$attribute_id}
				    AND p.status = 1
                                        ";

                                if ($this->config->get("simonfilters_display_totals") == '1') {
                                    $query = $this->db->query($sql);
                                }else {
                                    $query = $this->manageCache($sql, 'simonfilters.getAttributesTotals.' . $attribute_id .'.'. $language_id . '.' . $store_id);
                                }
                                $total = $query->row['total'];
                            }
                            $data['a'][$row['group_id']][$row['attribute_id']]['filter_totals'][md5($row['text'])] = $total;
                            break;
                    }



                    $data['a'][$row['group_id']]['b'] = $activefilter_simonfilters_attribute[$row['attribute_id']]['b'];
                    $data['a'][$row['group_id']]['col'] = $activefilter_simonfilters_attribute[$row['attribute_id']]['col'];
                    $data['a'][$row['group_id']]['more'] = $activefilter_simonfilters_attribute[$row['attribute_id']]['more'];
                    $data['a'][$row['group_id']]['more_number'] = $activefilter_simonfilters_attribute[$row['attribute_id']]['more_number'];
                }
            }

            /* Options */
            if (count($activefilter_simonfilters_options) > 0) {
                $attribute_ids = implode(',', array_keys($activefilter_simonfilters_options));
                $filter_group_name = $this->get_filter_group_name($language_id);

                foreach($filter_group_name as $filter_group_name_key => $filter_group_name_value) {

                    if( isset($simonfilters_hard['options'][$filter_group_name_key])) {
                        if($_SESSION['language'] == $this->config->get("config_admin_language")) {
                            foreach($filter_group_name_value as $_k => $_v) {
                                $filter_group_name[$filter_group_name_key][$_k]['filter_group_name'] = $simonfilters_hard['options'][$filter_group_name_key];
                            }
                        }
                    }
                }

                if ($this->config->get("simonfilters_option_zero_quantity") == '1') {
                    $sql_option_zero_quantity = "AND pov.quantity>0";
                } else {
                    $sql_option_zero_quantity = "";
                }

                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND pov.product_id IN ($sfsids)";
                }

                $sql = "
                SELECT DISTINCT
                    pov.option_id as group_id ,pov.option_value_id, ov.sort_order, count(*) as total
                FROM
                    " . DB_PREFIX . "product_option_value pov
                    JOIN `" . DB_PREFIX . "option` o ON pov.option_id = o.option_id
                    JOIN " . DB_PREFIX . "option_value ov on ov.option_value_id = pov.option_value_id
                WHERE
                    pov.option_id IN({$attribute_ids}) {$sql_id_products} {$sql_option_zero_quantity}
                    GROUP BY pov.option_id , pov.option_value_id
                        ";

                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:option sql', $sql);
                $query = $this->manageCache($sql, 'simonfilters.getOptionsByIds.' . $language_id . '.' . $store_id);

                foreach ($query->rows as $row) {
                    if (isset($row['group_id'])) {
                        if (isset($filter_group_name[$row['group_id']][$row['option_value_id']]['filter_name'])) {
                            $data['o'][$row['group_id']]['filter_group_name'] = $filter_group_name[$row['group_id']][$row['option_value_id']]['filter_group_name'];
                            $data['o'][$row['group_id']][$row['option_value_id']]['filter_name'][md5($row['option_value_id'])] = $filter_group_name[$row['group_id']][$row['option_value_id']]['filter_name'];
                            $data['o'][$row['group_id']][$row['option_value_id']]['filter_totals'][md5($row['option_value_id'])] = $row['total'];
#toslider#$data['o'][$row['group_id']][$row['group_id']]['filter_name'][md5($row['option_value_id'])] = $filter_group_name[$row['group_id']][$row['option_value_id']]['filter_name'];
#toslider#$data['o'][$row['group_id']][$row['group_id']]['filter_totals'][md5($row['option_value_id'])] = $row['total'];

                            $data['o'][$row['group_id']]['b'] = $activefilter_simonfilters_options[$row['group_id']]['b'];
                            $data['o'][$row['group_id']]['col'] = $activefilter_simonfilters_options[$row['group_id']]['col'];
                            $data['o'][$row['group_id']]['more'] = $activefilter_simonfilters_options[$row['group_id']]['more'];
                            $data['o'][$row['group_id']]['more_number'] = $activefilter_simonfilters_options[$row['group_id']]['more_number'];

                            $data['o'][$row['group_id']][$row['option_value_id']]['sort_order'] = $row['sort_order'];
#toslider#$data['o'][$row['group_id']][$row['group_id']]['sort_order'] = $row['sort_order'];
                        }
                    }
                }
            }

            /* Tags */
            if (count($activefilter_simonfilters_tags) > 0) {
                $attribute_ids = array_keys($activefilter_simonfilters_tags);
                foreach ($attribute_ids as $ak => $av)
                    $attribute_ids[$ak] = "'" . $av . "'";
                $attribute_ids = implode(',', $attribute_ids);
                $tags = array();
                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND p.product_id IN ($sfsids)";
                }
                if ($this->product_tag_exists) {
                    $sql = "
                        SELECT pt.product_tag_id
                        FROM " . DB_PREFIX . "product_tag pt
                        WHERE pt.tag IN (
                                    SELECT pt2.tag FROM " . DB_PREFIX . "product_tag pt2 WHERE pt2.product_tag_id IN($attribute_ids) AND pt2.language_id=$language_id
                        ) AND pt.language_id=$language_id";
                    $query = $this->manageCache($sql, 'simonfilters.getTagsIdentical.' . $language_id . '.' . $store_id);
                    foreach ($query->rows as $row) {
                        $tags[] = $row['product_tag_id'];
                    }
                    $tagsflat = implode(',', $tags);
                    $sql = "
                        SELECT pt.product_tag_id, pt.tag AS filter_name, '" . $this->language->get('tags') . "' AS filter_group_name, 1 AS group_id, COUNT(distinct pt.product_id) AS total
                        FROM " . DB_PREFIX . "product_tag pt
                        JOIN " . DB_PREFIX . "product p ON pt.product_id = p.product_id
                        JOIN " . DB_PREFIX . "product_to_category pc ON p.product_id = pc.product_id
                        WHERE p.status=1 AND pt.product_tag_id IN({$tagsflat}) $sql_id_products AND pt.language_id={$language_id}
                        GROUP BY pt.tag";
                    $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByIds:tags sql', $sql);
                    $query = $this->manageCache($sql, 'simonfilters.getTagsByIds.' . $language_id . '.' . $store_id);
                    foreach ($query->rows as $row) {
                        $data['t'][$row['group_id']]['filter_group_name'] = $row['filter_group_name'];
                        $data['t'][$row['group_id']][$row['product_tag_id']]['filter_name'][md5($row['product_tag_id'])] = $row['filter_name'];
                        $data['t'][$row['group_id']][$row['product_tag_id']]['filter_totals'][md5($row['product_tag_id'])] = $row['total'];
                    }

                    if ($query->num_rows) {
                        if (isset($activefilter_simonfilters_expanded['tags'])) {
                            $data['t'][1]['b'] = $activefilter_simonfilters_expanded['tags'];
                        }
                    }
                } else {
                    $tags = $this->getAllTags1541($language_id);
                    $data['t'][1]['filter_group_name'] = $this->language->get('tags');
                    foreach ($tags as $tag) {
                        $data['t'][1][1]['filter_name'][md5($tag)] = $tag;
                        $data['t'][1][1]['filter_totals'][md5($tag)] = 0;
                        if ($this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals")=='2') {
                            $textSafe = $tag;

                            $sql = "SELECT count(distinct pd.product_id) as total
                                FROM " . DB_PREFIX . "product_description pd
                                JOIN " . DB_PREFIX . "product p ON pd.product_id = p.product_id
                                WHERE
                                (pd.tag LIKE '{$textSafe},%'
                                or pd.tag LIKE '%,{$textSafe},%'
                                or pd.tag LIKE '%,{$textSafe}'
                                or pd.tag = '{$textSafe}')
                                    $sql_id_products
                                AND pd.language_id={$language_id}
                                AND p.status = 1
                                    ";
                            $this->debug(__FILE__ . ';' . __LINE__, 'Count product totals(tagsoc1541)', $sql);
                            if ($this->config->get("simonfilters_display_totals") == '1') {
                                $query = $this->db->query($sql);
                            }else {
                                $query = $this->manageCache($sql, 'simonfilters.gettagsTotalsOC1541.' . $attribute_id.'.'. md5($a) .'.'. $language_id . '.' . $store_id);
                            }
                            if($query->row['total']>0) {
                                $data['t'][1][1]['filter_totals'][md5($tag)] = $query->row['total'];
                            }else {
                                unset($data['t'][1][1]['filter_totals'][md5($tag)]);
                                unset($data['t'][1][1]['filter_name'][md5($tag)]);
                            }
                        }
                    }
                    if(count($data['t'][1][1]['filter_name'])==0) {
                        unset($data['t']);
                    }
                }


            }

            /* manufacturers */

            $manufacturerText = $this->language->get('manufacturer');

            if (count($activefilter_simonfilters_manufacturer) > 0) {
                $attribute_ids = implode(',', array_keys($activefilter_simonfilters_manufacturer));

                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND p.product_id IN($sfsids)";
                }

                $sql = "
                    select
                        m.name as filter_name, p.manufacturer_id, '{$manufacturerText}' as filter_group_name, 1 as group_id, count(*) as total
                    from
                        " . DB_PREFIX . "product p
                        JOIN " . DB_PREFIX . "manufacturer m ON p.manufacturer_id = m.manufacturer_id
                    WHERE
                        p.status=1 AND p.manufacturer_id IN($attribute_ids) {$sql_id_products}
                    GROUP BY manufacturer_id 
                    ORDER BY m.sort_order
                        ";

                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:manufacturers sql', $sql);

                $query = $this->manageCache($sql, 'simonfilters.getManufacturersByIds.' . $language_id . '.' . $store_id);

                foreach ($query->rows as $row) {
                    $data['m'][$row['group_id']]['filter_group_name'] = $row['filter_group_name'];
                    $data['m'][$row['group_id']][$row['manufacturer_id']]['filter_name'][] = $row['filter_name'];
                    $data['m'][$row['group_id']][$row['manufacturer_id']]['filter_totals'][] = $row['total'];
                }
                if ($query->num_rows) {
                    if (isset($activefilter_simonfilters_expanded['manufacturers'])) {
                        $data['m'][1]['b'] = $activefilter_simonfilters_expanded['manufacturers'];
                    }
                }
            }

            /* stock */
            $stockText = $this->language->get('stock');

            if (count($activefilter_simonfilters_stock) > 0) {
                $attribute_ids = implode(',', array_keys($activefilter_simonfilters_stock));

                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = "AND p.product_id IN({$sfsids})";
                }
                $activefilter_simonfilters_stock_instock = $this->config->get("activefilter_simonfilters_stock_instock");


                $sql = "
                SELECT
                        ss.name AS filter_name, '{$stockText}' AS filter_group_name, IF(p.quantity>0, $activefilter_simonfilters_stock_instock, p.stock_status_id) AS stock_status_id, COUNT(p.product_id) AS total, 1 AS group_id
                FROM " . DB_PREFIX . "product p
                JOIN " . DB_PREFIX . "stock_status ss ON ss.stock_status_id = IF(p.quantity>0, $activefilter_simonfilters_stock_instock, p.stock_status_id) AND ss.language_id={$language_id}
                WHERE
                        p.stock_status_id IN($attribute_ids) {$sql_id_products}
                GROUP BY ss.name

                        ";

                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:stocks sql', $sql);
                $query = $this->manageCache($sql, 'simonfilters.getStockByIds.' . $language_id . '.' . $store_id);
                foreach ($query->rows as $row) {
                    $data['s'][$row['group_id']]['filter_group_name'] = $row['filter_group_name'];
                    $data['s'][$row['group_id']][$row['stock_status_id']]['filter_name'][] = $row['filter_name'];
                    $data['s'][$row['group_id']][$row['stock_status_id']]['filter_totals'][] = $row['total'];
                }
                if ($query->num_rows) {
                    if (isset($activefilter_simonfilters_expanded['stocks'])) {
                        $data['s'][1]['b'] = $activefilter_simonfilters_expanded['stocks'];
                    }
                }
            }

            /* price */
            $priceText = $this->language->get('price');


            $activefilter_simonfilters_price_data = $this->config->get("activefilter_simonfilters_price");
            if (isset($activefilter_simonfilters_price_data['min_products'])) {
                $simon_min_products = (int) $activefilter_simonfilters_price_data['min_products'];
            } else {
                $simon_min_products = 10;
            }

            if (isset($activefilter_simonfilters_price_data['min_price']) && $activefilter_simonfilters_price_data['min_price'] > 0) {
                $simon_min_price = (int) $activefilter_simonfilters_price_data['min_price'];
            } else {
                $simon_min_price = 10;
            }
            if (($this->config->get("simonfilters_force_all") == "1") || ($activefilter_simonfilters_price && (count($currentIDS) > $simon_min_products || $sfsids=='-1') ) ) {

//Needs more work. p.tax_class_id usage is a bit silly


                if ($this->config->get("simonfilters_force_all") == "1" || $sfsids=='-1') {
                    $sql_id_products = "";
                } else {
                    $sql_id_products = " AND p.product_id IN ($sfsids) ";
                }

                $route = $this->getRoute();

                $psclause = " ((p.date_start = '0000-00-00' OR p.date_start < NOW()) AND (p.date_end = '0000-00-00' OR p.date_end > NOW())) ";

                switch ($route) {
                    case "product/product":
                        $catsForPriceAri = $this->getAllProductCategories();
                        $catsForPrice = join(',', $catsForPriceAri);
                        $price_filter = " WHERE p.price>0 AND p.product_id in (select pc.product_id FROM " . DB_PREFIX . "product_to_category pc WHERE pc.category_id IN(" . $catsForPrice . "))";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "product/category":
                        $price_filter = " WHERE p.price>0 AND p.product_id in (select pc.product_id FROM " . DB_PREFIX . "product_to_category pc WHERE pc.category_id='" . $this->getCategoryID() . "')";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "product/manufacturer/info":
                        $price_filter = " WHERE p.price>0 AND p.product_id in (select p.product_id FROM " . DB_PREFIX . "product p WHERE p.manufacturer_id='" . $this->getManufacturerID() . "')";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "product/manufacturer/product":
                        $price_filter = " WHERE p.price>0 AND p.product_id in (select p.product_id FROM " . DB_PREFIX . "product p WHERE p.manufacturer_id='" . $this->getManufacturerID() . "')";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "product/manufacturer":
                        $price_filter = " WHERE p.price>0 AND p.product_id in (select p.product_id FROM " . DB_PREFIX . "product p WHERE p.manufacturer_id='" . $this->getManufacturerID() . "')";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "product/search":
                        $price_filter = " WHERE p.price>0 ";
                        $pswhereclause = " WHERE $psclause";
                        break;
                    case "product/special":
                        $price_filter = " WHERE p.price>0 $sql_id_products";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    case "account/wishlist":
                        $price_filter = " WHERE p.price>0 $sql_id_products";
                        $pswhereclause = " $price_filter AND $psclause";
                        break;
                    default:
                        $price_filter = " WHERE p.price>0 ";
                        $pswhereclause = " WHERE $psclause";
                        break;
                }



                $sql = "
                 (
                 
                SELECT 'min' AS title, p.price, p.tax_class_id FROM " . DB_PREFIX . "product p $price_filter                        
                ORDER BY p.price ASC LIMIT 1                    
                    
                )UNION(
                    
                SELECT 'min_special' AS title, p.price, 0 as tax_class_id FROM " . DB_PREFIX . "product_special p $pswhereclause
                ORDER BY p.priority ASC, p.price ASC LIMIT 1
                    
                )UNION(
                    
                SELECT 'max' AS title, p.price, p.tax_class_id FROM " . DB_PREFIX . "product p $price_filter 
                ORDER BY p.price DESC LIMIT 1
                
                )UNION(
                
                SELECT 'max_special' AS title, p.price, 0 as tax_class_id FROM " . DB_PREFIX . "product_special p $pswhereclause
                ORDER BY p.priority ASC, p.price DESC LIMIT 1
                )
                        ";
                $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:price sql', $sql);

                $query = $this->manageCache($sql, 'simonfilters.getPriceByIds.' . $language_id . '.' . $store_id);
                $rows = $query->rows;

                $activeCurrency = $this->getActiveCurrency();

                if (count($rows) > 1) {
                    $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:prices found', $rows);
                    $prices = array();
                    if (count($rows) == 4) {
#BEFORE#SUPPORT#FORCE#$prices['minprice'] = $this->currency->convert($rows[0]['price'], $this->config->get('config_currency'), $this->currency->getCode());
#BEFORE#SUPPORT#FORCE#$prices['minprice_special'] = $this->currency->convert($rows[1]['price'], $this->config->get('config_currency'), $this->currency->getCode());
#BEFORE#SUPPORT#FORCE#$prices['maxprice'] = $this->currency->convert($rows[2]['price'], $this->config->get('config_currency'), $this->currency->getCode());
#BEFORE#SUPPORT#FORCE#$prices['maxprice_special'] = $this->currency->convert($rows[3]['price'], $this->config->get('config_currency'), $this->currency->getCode());

                        $prices['minprice'] = $this->currency->convert($rows[0]['price'], $this->config->get('config_currency'), $activeCurrency['code']);
                        $prices['minprice_special'] = $this->currency->convert($rows[1]['price'], $this->config->get('config_currency'), $activeCurrency['code']);
                        $prices['maxprice'] = $this->currency->convert($rows[2]['price'], $this->config->get('config_currency'), $activeCurrency['code']);
                        $prices['maxprice_special'] = $this->currency->convert($rows[3]['price'], $this->config->get('config_currency'), $activeCurrency['code']);

                        if (isset($activefilter_simonfilters_price_data['special'])) {
                            if ($prices['minprice_special'] < $prices['minprice'])
                                $prices['minprice'] = $prices['minprice_special'];
                            if ($prices['maxprice_special'] > $prices['maxprice'])
                                $prices['maxprice'] = $prices['maxprice_special'];
                        }
                        $tax_min = $rows[0]['tax_class_id'];
                        $tax_max = $rows[2]['tax_class_id'];
                    }else {
#BEFORE#SUPPORT#FORCE#$prices['minprice'] = $this->currency->convert($rows[0]['price'], $this->config->get('config_currency'), $this->currency->getCode());
#BEFORE#SUPPORT#FORCE#$prices['maxprice'] = $this->currency->convert($rows[1]['price'], $this->config->get('config_currency'), $this->currency->getCode());

                        $prices['minprice'] = $this->currency->convert($rows[0]['price'], $this->config->get('config_currency'), $activeCurrency['code']);
                        $prices['maxprice'] = $this->currency->convert($rows[1]['price'], $this->config->get('config_currency'), $activeCurrency['code']);

                        $tax_min = $rows[0]['tax_class_id'];
                        $tax_max = $rows[1]['tax_class_id'];
                    }
                    $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:prices array before tax', $rows);
                    if (isset($activefilter_simonfilters_price_data['tax'])) {
                        $prices['minprice'] = floor($this->tax->calculate($prices['minprice'], $tax_min, true));
                        $prices['maxprice'] = ceil($this->tax->calculate($prices['maxprice'], $tax_max, true));
                    } else {
                        $prices['minprice'] = floor($prices['minprice']);
                        $prices['maxprice'] = ceil($prices['maxprice']);
                    }

                    $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:prices array after tax', $rows);
                    $pricediff = $prices['maxprice'] - $prices['minprice'];

                    if ($pricediff > $simon_min_price) {

                        $data['p'][1]['filter_group_name'] = $priceText;
                        $data['p'][1][1]['filter_name'][] = 'price';
                        $data['p'][1]['prices'] = $prices;
                    }
#}
                }
            }

            $this->debug(__FILE__ . ';' . __LINE__, 'getFiltersByCategory:data', $data);

            return $data;
        }
    }

    public function isThereAnyFilterEnabled() {
        return
                (count($this->config->get("activefilter_simonfilters_attribute")) > 0)
                || (count($this->config->get("activefilter_simonfilters_options")) > 0)
                || (count($this->config->get("activefilter_simonfilters_tags")) > 0)
                || (count($this->config->get("activefilter_simonfilters_manufacturer")) > 0)
                || (count($this->config->get("activefilter_simonfilters_stock")) > 0)
                || (count($this->config->get("activefilter_simonfilters_price")) > 0);
    }

    public function debug($data) {
        if ($this->debugmode) {
            echo "<pre class='simondebug'>";
            foreach (func_get_args() as $arg) {
                echo ( is_array($arg) ? print_r($arg, 1) : "<p>{$arg}</p>" );
            }
            echo "</pre>";
        }
    }

    public function getHeader($header) {
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (isset($_SERVER[$temp]) && !empty($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }
        return false;
    }

    private function get_options_totals($option_value_id) {
        $totals = array();
        if (count($option_value_id) > 0) {
            $store_id = (int) $this->config->get('config_store_id');
            $option_value_id_flat = implode(',', $option_value_id);
            $sql = "
            SELECT
            pov.option_value_id,
            count(pov.product_id) as total

            FROM
            " . DB_PREFIX . "product_option_value pov
            join " . DB_PREFIX . "product p on pov.product_id = p.product_id
            where
            pov.option_value_id in ({$option_value_id_flat})
            AND p.status=1
            group by pov.option_value_id

                    ";
            $this->debug(__FILE__ . ';' . __LINE__, 'get_options_totals: sql', $sql);

            $cache = md5($sql);

            $option_value_id_md5 = str_replace(',', '_', $option_value_id);
            $query = $this->cache->get('simonfilters.get_options_totals.' . $store_id . '.' . $cache);
            if (!$query) {
                $query = $this->db->query($sql);
                $this->cache->set('simonfilters.get_options_totals.' . $store_id . '.' . $cache, $query);
            }

            foreach ($query->rows as $row) {
                $totals[$row['option_value_id']] = $row['total'];
            }
        }
        return $totals;
    }

    private function get_filter_group_name($language_id) {
        $store_id = (int) $this->config->get('config_store_id');
        $sql = "
            select
                    od.option_id
                    ,ovd.option_value_id
                    ,od.name as filter_group_name
                    ,ovd.name as filter_name
            from
                    " . DB_PREFIX . "option_description od
                join " . DB_PREFIX . "option_value_description ovd ON od.option_id = ovd.option_id AND od.language_id = ovd.language_id 
            where
                    od.language_id={$language_id}
                ";
        $this->debug(__FILE__ . ';' . __LINE__, 'get_filter_group_name: sql', $sql);

        $cache = md5($sql);
        $query = $this->cache->get('simonfilters.get_filter_group_name.' . $language_id . '.' . $store_id . '.' . $cache);
        if (!$query) {
            $query = $this->db->query($sql);
            $this->cache->set('simonfilters.get_filter_group_name.' . $language_id . '.' . $store_id . '.' . $cache, $query);
        }
        $filter_group_name = array();
        foreach ($query->rows as $row) {
            $filter_group_name[$row['option_id']][$row['option_value_id']]['filter_group_name'] = $row['filter_group_name'];
            $filter_group_name[$row['option_id']][$row['option_value_id']]['filter_name'] = $row['filter_name'];
        }

        return $filter_group_name;
    }

    private function getCurrencies($currency_code = null) {
        $currencies = array();
        $results = $this->model_localisation_currency->getCurrencies();
        foreach ($results as $result) {
            if ($result['status']) {
                if($currency_code==null || $currency_code == $result['code']) {
                    $currencies[] = array(
                            'code' => $result['code'],
                            'symbol_left'   => $result['symbol_left'],
                            'symbol_right'  => $result['symbol_right'],
                            'decimal_place' => $result['decimal_place'],
                            'value'         => $result['value']
                    );
                }
            }
        }
        return $currencies;
    }

    public function getActiveCurrency() {
        $price_config_data = $this->config->get("activefilter_simonfilters_price");
        $force_currency = $price_config_data['force_currency'];
        switch($force_currency) {
            case "user-defined":
                $outData = $this->getCurrencies($this->currency->getCode());
                $outData = $outData[0];
                break;
            default:
                $outData = $this->getCurrencies($force_currency);
                $outData = $outData[0];
                break;
        }
#var_dump($outData);
        return $outData;
    }

    public function getCategoryID() {
        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);
        } else {
            $parts = array();
        }

        if (count($parts) > 0) {
            return $parts[count($parts) - 1];
        } else {
            $product_id = isset($this->request->get["product_id"]) ? $this->request->get["product_id"] : 0;
            if ($product_id != 0) {
                $sql = "SELECT pc.category_id FROM " . DB_PREFIX . "product_to_category pc WHERE pc.product_id = '" . (int) $product_id . "'";
                $query = $this->db->query($sql);
                if ($query->num_rows) {
                    return $query->row['category_id'];
                }
            }

            return 0;
        }
    }

    public function getManufacturerID() {
        return (isset($this->request->get['manufacturer_id']) ? $this->request->get['manufacturer_id'] : 0);
    }

    public function getProductID() {
        return (isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0);
    }

    public function getRoute() {
        $route = isset($this->request->get['route']) ? $this->request->get['route'] : 'homepage';
        switch ($route) {
            case 'common/home':
                $route = "homepage";
                break;
        }
        return $route;
    }

    public function isSupportedRoute() {
        if ($this->static_isSupportedRoute != null)
            return $this->static_isSupportedRoute;

        switch ($this->getRoute()) {
            case "account/wishlist":
                $this->static_isSupportedRoute = true;
                return true;
                break;
            case "homepage":
            case "product/manufacturer/product":
            case "product/manufacturer/info":
            case "product/search":
            case "product/product":
            case "product/special":
                $this->static_isSupportedRoute = true;
                return true;
                break;
            case "product/category":
                $category_id = $this->getCategoryID();
                $enabled_cats = $this->config->get("sfspc");
                $this->static_isSupportedRoute = isset($enabled_cats[$category_id]);
                return isset($enabled_cats[$category_id]);
                break;
            default:
                $this->static_isSupportedRoute = false;
                return false;
                break;
        }
    }

    public function getTaxClass() {
        $route = $this->getRoute();
        switch ($route) {
            case "product/product":
                $i = $this->getProductID();
                $sql = "SELECT tax_class_id FROM " . DB_PREFIX . "product WHERE product_id={$i}";
                break;
            case "product/category":
                $i = $this->getCategoryID();
                $sql = "SELECT tax_class_id FROM " . DB_PREFIX . "product_to_category pdc JOIN " . DB_PREFIX . "product p ON pdc.product_id = p.product_id WHERE pdc.category_id=$i LIMIT 1";
                break;
            case "product/manufacturer/info":
            case "product/manufacturer/product":
                $i = $this->getManufacturerID();
                $sql = "
                    SELECT DISTINCT
                    p.tax_class_id
                    FROM 
                    " . DB_PREFIX . "product p
                    JOIN " . DB_PREFIX . "manufacturer m ON m.manufacturer_id = p.manufacturer_id
                    WHERE
                    m.manufacturer_id=$i
                    ORDER BY p.tax_class_id DESC
                    LIMIT 1                    
                        ";
                break;
            case "product/special":
                $sql = "
                    SELECT DISTINCT
                    p.tax_class_id
                    FROM 
                    " . DB_PREFIX . "product p
                    JOIN " . DB_PREFIX . "product_special ps ON ps.product_id = p.product_id

                    ORDER BY p.tax_class_id DESC
                    LIMIT 1                    
                ";
#die("a");
                break;
            default:
                $sql = "
                    SELECT DISTINCT
                    p.tax_class_id
                    FROM 
                    ". DB_PREFIX ."product p
                    ORDER BY p.tax_class_id DESC
                    LIMIT 1                    
                ";
                $index = 'hp';
                break;
        }
        $res = $this->db->query($sql)->row;
        return $res['tax_class_id'];
    }

    public function getSimonIndex() {
        $route = $this->getRoute();
        switch ($route) {
            case "account/wishlist":
                $index = 'account_wish';
                break;
            case "product/product":
                $index = 'prod_' . $this->getCategoryID();
                break;
            case "product/category":
                $index = 'cat_' . $this->getCategoryID();
                break;
            case "product/manufacturer/info":
            case "product/manufacturer/product":
                $index = 'man_' . $this->getManufacturerID();
                break;
            case "product/manufacturer":
                $index = 'man_index';
                break;
            case "product/special":
                $index = 'prod_special';
                break;
            default:
                $index = 'hp';
                break;
        }
        return $index;
    }

    public function getAllProductAttributes() {

        $a = array();

        $activefilter_simonfilters_attribute = $this->config->get("activefilter_simonfilters_attribute");

        if(isset($activefilter_simonfilters_attribute)) {

            $attribute_ids = implode(',', array_keys($activefilter_simonfilters_attribute));

            $sql = "
                        SELECT a.attribute_id, pa.text
                        FROM " . DB_PREFIX . "attribute a
                        JOIN " . DB_PREFIX . "product_attribute pa ON a.attribute_id = pa.attribute_id
                        WHERE pa.text<>'' AND a.attribute_id IN($attribute_ids) AND pa.language_id={$this->language_id}
                        GROUP BY a.attribute_id, pa.text
                    ";
            $query = $this->manageCache($sql, 'simonfilters.getAllProductAttributes.' . $this->language_id . '.' . $this->store_id);

            foreach ($query->rows as $row) {
                $simonfilters_enable_attribute_separator = $this->config->get("simonfilters_enable_attribute_separator");
                switch ($simonfilters_enable_attribute_separator) {
                    case '1':
                        $simonfilters_attribute_separator_char = $this->config->get("simonfilters_attribute_separator_char");
                        if ($simonfilters_attribute_separator_char != '') {
                            foreach (explode($simonfilters_attribute_separator_char, $row['text']) as $attr_item) {
                                $attr_item = trim($attr_item);
#if($attr_item!='')$a[$row['attribute_id']][md5($attr_item)]=$attr_item;
                                $attribute_id = $row['attribute_id'];
                                $md5_attr_item = md5($attr_item);
                                if ($attr_item != '')
                                    $a["a.{$attribute_id}.{$md5_attr_item}"] = $attr_item;
                            }
                        }else {
                            $attribute_id = $row['attribute_id'];
                            $md5_text = md5(trim($row['text']));
                            if ($row['text'] != '')
                                $a["a.{$attribute_id}.{$md5_text}"] = trim($row['text']);
                        }
                        break;
                    default:
                        $attribute_id = $row['attribute_id'];
                        $md5_text = md5(trim($row['text']));
                        if ($row['text'] != '')
                            $a["a.{$attribute_id}.{$md5_text}"] = trim($row['text']);
                        break;
                }
            }
        }
        return $a;
    }

    /*
     *
     *
     * stuff for catalog\model\catalog\product.php
     *
     *
     *
    */

    public function getSimonAttributesCacheData() {
        return (sizeof($this->currentfilters) > 0 ? implode(',', $this->currentfilters) : '');
    }

    public function makeTextSafe($text) {
        $text = $this->db->escape($text);
#$text = str_replace('*', '\\\\*', $text);
#$text = str_replace('+', '\\\\+', $text);
#$text = str_replace('[', '\\\\[', $text);
#$text = str_replace(']', '\\\\]', $text);
#$text = str_replace(')', '\\\\)', $text);
#$text = str_replace('(', '\\\\(', $text);
#$text = str_replace('|', '\\\\|', $text);
        return $text;
    }

    public function getSimonAttributes() {
        if($this->sent==0) {
            $sqlfilters = "";
            $attributeCount = 0;
            $manufacturerCount = 0;
            $store_id = (int) $this->config->get('config_store_id');
            $route = $this->getRoute();
            $index = $this->getSimonIndex();
            $language_id = (int) $this->config->get('config_language_id');

            if ($this->isSupportedRoute()) {
                if (!$this->product_tag_exists) {
                    $sql = "select pd.tag from " . DB_PREFIX . "product_description pd where pd.tag<>'' AND language_id='{$language_id}' group by tag ORDER BY pd.tag";
                    $query = $this->manageCache($sql, 'simonfilters.getTagsIdentical.' . $language_id . '.' . $store_id);
                    $existingtags = array();
                    foreach ($query->rows as $row) {
                        foreach (preg_split('/,/', $row['tag']) as $tag) {
                            $existingtags[] = $tag;
                        }
                    }
                }
            }

            if ($this->isSupportedRoute()) {

                $activefilter_simonfilters_options = $this->config->get("activefilter_simonfilters_options");
                $activefilter_simonfilters_attribute = $this->config->get("activefilter_simonfilters_attribute");
                $a = $this->getAllProductAttributes();

                if ($this->sent == 0) {
                    $this->simonsql = '';
                    $ari = Array(
                            'a' => Array(),
                            'o' => Array(),
                            'm' => Array(),
                            's' => Array(),
                            'p' => Array()
                    );

                    $array_for_dslider = array();

                    foreach ($this->currentfilters as $checkedfilter) {

                        $checkedfilterari = preg_split('/\./', $checkedfilter);

                        list($sfsfilter_type_name, $sfsgroup_id, $sfsfilter_id, $sfskey) = $checkedfilterari;



                        switch ($sfsfilter_type_name) {
                            case 'a':
                                if ($activefilter_simonfilters_attribute[$sfsgroup_id]['l'] == 3) {

                                    if(in_array($sfskey, array('begin','end'))) {
                                        $textSafe = "$sfskey";
                                    }else {
                                        $textSafe = $this->makeTextSafe($a["a.{$sfsfilter_id}.{$sfskey}"]);
                                    }
                                    $ari['aslider'][$sfsfilter_id][] = $textSafe;
                                }else {
                                    if (array_key_exists("a.{$sfsfilter_id}.{$sfskey}", $a)) {
                                        $textSafe = $this->makeTextSafe($a["a.{$sfsfilter_id}.{$sfskey}"]);

                                        $simonfilters_enable_attribute_separator = $this->config->get("simonfilters_enable_attribute_separator");
                                        $simonfilters_attribute_separator_char = $this->config->get("simonfilters_attribute_separator_char");
                                        $simonfilters_attribute_separator_char = $this->makeTextSafe($simonfilters_attribute_separator_char);
                                        if ($simonfilters_enable_attribute_separator && $simonfilters_attribute_separator_char != '') {
                                            $ari[$sfsfilter_type_name][$sfsfilter_id][] = " (
                                                text LIKE '{$textSafe}{$simonfilters_attribute_separator_char}%'
                                                or text LIKE '%{$simonfilters_attribute_separator_char}{$textSafe}{$simonfilters_attribute_separator_char}%'
                                                or text LIKE '%{$simonfilters_attribute_separator_char}{$textSafe}'
                                                or text = '{$textSafe}'
                                            )";
                                        } else {
                                            $ari[$sfsfilter_type_name][$sfsfilter_id][] = " (text = '{$textSafe}')";
                                        }
                                    } else {
                                        $this->debug(__FILE__ . ';' . __LINE__, "Error", $sfsfilter_type_name . '.' . $sfsfilter_id . '.' . $sfskey);
                                    }
                                }
                                break;
                            case 'o':

                                $ari[$sfsfilter_type_name][$sfsgroup_id][] = $sfsfilter_id;
                                break;
                            case 't':
                                if ($this->product_tag_exists) {
                                    $ari[$sfsfilter_type_name][] = "'$sfskey'";
                                } else {
                                    foreach ($existingtags as $tag) {
                                        if (md5($tag) == $sfskey)
                                            $ari[$sfsfilter_type_name][$tag] = "$tag";
                                    }
                                }
                                break;
                            case 'm':
                                $ari[$sfsfilter_type_name][] = $sfsfilter_id;
                                break;
                            case 's':
                                $ari['s'][] = $sfsfilter_id;
                                break;
                            case 'p':
                                $sfskeyAri = preg_split('/,/', $sfskey);
                                $ari[$sfsfilter_type_name] = $sfskeyAri;
                                break;
                            case 'c':
                                $ari[$sfsfilter_type_name][] = $sfsfilter_id;
                                break;
                        }
                    }

                    foreach ($ari as $k => $attrAri) {
                        switch ($k) {
                            case 'p':

                                if (count($attrAri) > 0 && count($attrAri) == 2) {
                                    $defaultcurrency = $this->config->get('config_currency');
                                    $currentcurrency = $this->currency->getCode();

                                    $localeconv = localeconv();
                                    $local_decimal_point = $localeconv['decimal_point'];
                                    $attrAri[0] = preg_replace('/SFS/', $local_decimal_point, $attrAri[0]);
                                    $attrAri[1] = preg_replace('/SFS/', $local_decimal_point, $attrAri[1]);

                                    $minprice_slider = $this->currency->convert($attrAri[0], $currentcurrency, $defaultcurrency);
                                    $maxprice_slider = $this->currency->convert($attrAri[1], $currentcurrency, $defaultcurrency);

                                    $activefilter_simonfilters_price = $this->config->get("activefilter_simonfilters_price");

                                    if (isset($activefilter_simonfilters_price['tax'])) {

                                        if (isset($this->data['p'])) {
                                            $minpriceclean = $this->data['p']['1']['prices']['minprice'];
                                            $maxpriceclean = $this->data['p']['1']['prices']['maxprice'];
                                        } else {
                                            $minpriceclean = $minprice_slider;
                                            $maxpriceclean = $maxprice_slider;
                                        }

                                        $price_tax = $this->getTaxClass();

                                        $minpricecleantax = $this->tax->calculate($minpriceclean, $price_tax, true);
                                        $maxpricecleantax = $this->tax->calculate($maxpriceclean, $price_tax, true);

                                        $this->debug(__FILE__ . ';' . __LINE__, "minprice_slider", $minprice_slider, "maxprice_slider", $maxprice_slider);

                                        if ($minpriceclean == 0)
                                            $minpriceclean = 1;
                                        if ($maxpriceclean == 0)
                                            $maxpriceclean = 1;

                                        if ($minpricecleantax == 0)
                                            $minpricecleantax = 1;
                                        if ($maxpricecleantax == 0)
                                            $maxpricecleantax = 1;

                                        $minprice_slider = floor($minprice_slider * $minpriceclean / $minpricecleantax);
                                        $maxprice_slider = ceil($maxprice_slider * $maxpriceclean / $maxpricecleantax);

                                        $this->debug(__FILE__ . ';' . __LINE__, "minprice_slider", $minprice_slider, "maxprice_slider", $maxprice_slider);
                                        $this->debug(__FILE__ . ';' . __LINE__, "minpriceclean", $minpriceclean, "maxpriceclean", $maxpriceclean);
                                    }

                                    $activefilter_simonfilters_price_data = $this->config->get("activefilter_simonfilters_price");
                                    if (isset($activefilter_simonfilters_price_data['special']) && (VERSION>'1.5.1.2')) {
                                        $this->simonsql .= "(p.price BETWEEN $minprice_slider AND $maxprice_slider OR ps.price BETWEEN $minprice_slider AND $maxprice_slider ) AND ";
                                    } else {
                                        $this->simonsql .= "p.price BETWEEN $minprice_slider AND $maxprice_slider AND ";
                                    }
                                }
                                break;
                            case 's':
                                if (count($attrAri) > 0) {
                                    $this->simonsql .= "(";
                                    foreach ($attrAri as $attr) {
                                        if ($attr == $this->config->get("activefilter_simonfilters_stock_instock")) {
                                            $this->simonsql .= "p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product WHERE (quantity>0)) AND ";
                                        } else {
                                            $this->simonsql .= "p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product WHERE (quantity<=0 AND stock_status_id = '{$attr}')) AND ";
                                        }
                                    }
                                    $this->simonsql = $this->simonsql != '' ? preg_replace('/(AND$|AND $)/', ' ', $this->simonsql) : '';
                                    $this->simonsql .= ") AND ";
                                }
                                break;
                            case 'aslider':
#ump($attrAri);
                                $x = '';
                                foreach ($attrAri as $attribute_id => $attr) {
                                    $xTemplate = "p.product_id IN (SELECT DISTINCT product_id FROM " . DB_PREFIX . "product_attribute pa WHERE (pa.attribute_id='{$attribute_id}' AND (";
                                    $xTemplateEnd = ") )) AND ";
                                    if(count($attr)==1 || count($attr)==2) {

                                        if(count($attr)==1) {
                                            $x .= $xTemplate. "text = '". $attr[0] ."'". $xTemplateEnd;
                                        }else {

                                            $singlequote0 = ( is_numeric($attr[0]) ?'':"'" );
                                            $singlequote1 = ( is_numeric($attr[1]) ?'':"'" );

                                            if($attr[0]=='begin' && $attr[1]=='end') {

                                            }elseif($attr[0]=='begin') {
                                                $x .= $xTemplate. "text <= {$singlequote1}". $attr[1] ."{$singlequote1}". $xTemplateEnd;
                                            }elseif($attr[1]=='end') {
                                                $x .= $xTemplate. "text >= {$singlequote0}". $attr[0] ."{$singlequote0}". $xTemplateEnd;
                                            }else {
                                                $x .= $xTemplate. "text BETWEEN {$singlequote0}". $attr[0] ."{$singlequote0} AND {$singlequote1}".$attr[1]."{$singlequote1}" .$xTemplateEnd;
                                            }
                                        }
                                    }
                                }
                                $this->simonsql .= $x;
                                break;

                            case 'a':

                                $x = "";
                                $attrIterCount=0;
                                foreach ($attrAri as $attribute_id => $attr) {
                                    $attrIterCount ++;
                                    $attribute_logic = $activefilter_simonfilters_attribute[$attribute_id]['l'];
                                    $x .= "p.product_id IN (\n\nSELECT DISTINCT product_id FROM " . DB_PREFIX .
                                            "product_attribute WHERE (attribute_id='{$attribute_id}' AND (";

                                    if ($this->config->get('activefilter_simonfilters_attributes_behavior') == '1') {
                                        $flatari = join($attr, ' AND ');
                                        $x .= $flatari;
                                    } else {
                                        $flatari = join($attr, ' OR ');
                                        $x .= $flatari;
                                    }
                                    $x.=") )) ";

                                    if($attrIterCount == count($attrAri)) {
                                        $x.= " AND ";
                                    }else {
                                        if ($this->config->get('activefilter_simonfilters_attributes_behavior') == '1') {
                                            $x.= ' AND ';
                                        }else {
                                            $x.= ' OR ';
                                        }
                                    }
                                }
                                $this->simonsql .= $x ."\n\n";
                                break;


                            case 'a.old':

                                $x = "";
                                foreach ($attrAri as $attribute_id => $attr) {
                                    $attribute_logic = $activefilter_simonfilters_attribute[$attribute_id]['l'];
                                    $x .= "p.product_id IN (SELECT DISTINCT product_id FROM " . DB_PREFIX . "product_attribute WHERE (attribute_id='{$attribute_id}' AND (";
                                    if ($this->config->get('activefilter_simonfilters_attributes_behavior') == '1') {
                                        $flatari = join($attr, ' AND ');
                                        $x .= $flatari;
                                    } else {
                                        $flatari = join($attr, ' OR ');
                                        $x .= $flatari;
                                    }
                                    $x.=") )) AND ";
                                }
                                $this->simonsql .= $x;
                                break;
                            case 'o':
                                if ($this->config->get('activefilter_simonfilters_options_behavior') == '1') {
                                    $options_logic = " AND ";
                                } else {
                                    $options_logic = " OR ";
                                }

                                $sqlJoins = array();
                                $sqlJoinsConditions = array();
                                $joinCount = 0;
                                foreach ($attrAri as $attribute_id => $attr) {
                                    $joinCount++;
                                    $flatari = join($attr, ',');
                                    $option_logic = $activefilter_simonfilters_options[$attribute_id]['l'];

                                    if ($this->config->get("simonfilters_option_zero_quantity") == '1') {
                                        $zero_options_sql= " AND quantity>0 ";
                                    }else {
                                        $zero_options_sql= "";
                                    }

                                    switch ($option_logic) {
                                        case "0": //checkboxes
                                            $sqlJoinsConditions[] = "x{$joinCount}.product_id IS NOT NULL";
                                            $sqlJoins[] = "
                                            LEFT JOIN
                                            (
                                                SELECT product_id
                                                FROM " . DB_PREFIX . "product_option_value
                                                WHERE (option_value_id IN({$flatari}){$zero_options_sql})
                                            ) AS x{$joinCount} ON x{$joinCount}.product_id = p.product_id
                                                    ";
                                            break;
                                        case "1": //radio buttons
                                        case "2":
                                            $sqlJoinsConditions[] = "x{$joinCount}.product_id IS NOT NULL";
                                            $sqlJoins[] = "
                                            LEFT JOIN
                                            (
                                            SELECT product_id
                                            FROM " . DB_PREFIX . "product_option_value
                                            WHERE (option_value_id IN({$flatari}){$zero_options_sql})
                                            GROUP BY product_id
                                            HAVING COUNT(*)>=" . count($attr) . "
                                            ) AS x{$joinCount} ON x{$joinCount}.product_id = p.product_id
                                                    ";
                                            break;
                                    }
                                }
                                if (count($sqlJoins) > 0) {
                                    $this->simonsql_options = join($sqlJoins, '');
                                    $this->simonsql.= "(" . join($sqlJoinsConditions, $options_logic) . " ) AND ";
                                }
                                break;

                            case 't':
                                if ($this->product_tag_exists) {
                                    $flatari = join($attrAri, ',');
                                    if ($flatari)
                                        $this->simonsql .= "p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_tag pt WHERE md5(tag) IN({$flatari})) AND ";
                                }else {
                                    $sqlTags = array();
                                    foreach($attrAri as $tag) {
                                        $sqlTags[]="
							(pd.tag LIKE '{$tag},%'
        			                        or pd.tag LIKE '%,{$tag},%'
                                			or pd.tag LIKE '%,{$tag}'
			                                or pd.tag = '{$tag}')";

                                    }
                                    $this->simonsql .= "(". join($sqlTags,' OR ') .") AND ";
                                }
                                break;
                            case 'm':
                                $flatari = join($attrAri, ',');
                                if ($flatari)
                                    $this->simonsql .= "p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product WHERE (p.manufacturer_id IN({$flatari}))) AND ";
                                break;
                            case 'c':
                                $flatari = join($attrAri, ',');
                                if ($flatari)
                                    $this->simonsql .= "p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category ptc WHERE (ptc.category_id IN({$flatari}))) AND ";
                                break;
                        }
                    }
#$this->simonsql = $this->simonsql != '' ? " AND " . preg_replace('/(AND$|AND $)/', ' ', $this->simonsql) : '';
#$this->simonsql = $this->simonsql != '' ? preg_replace('/(AND$|AND $)/', ' ', $this->simonsql) : '';
                    $this->debug(__FILE__ . ';' . __LINE__, "simonsql", $this->simonsql);

                }

            }

            $this->sent = 1;
            $this->static_SimonAttributes = array(
                    "default" => $this->simonsql
                    , "options" => $this->simonsql_options
            );
        }

        return $this->static_SimonAttributes;

    }

    function replaceNth($n,$search,$replace,$string) {
        $ari = preg_split("/$search/",$string);
        $out = "";
        for($i=0;
        $i<count($ari);
        $i++) {
            $out .= $ari[$i];
            if($i+1==$n)$out.=$replace;
            if($i<count($ari)-1 && $i+1!=$n)$out .= " $search ";
        }
        return $out;
    }

}

?>