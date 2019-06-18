<?php

/*
 * simonfilters - 2.11.0 Build 0013
*/

class ControllerModuleSimonFilters extends Controller {

    private $error = array();
    private $defaultCSS = array();
    private $SFs_version = 'simonfilters - 2.11.0 Build 0013';

    public function simonseesme() {
        $this->load->model('catalog/simonfilters');
        if(isset($this->request->get['attribute_id'])) {
            $attribute_id = $this->request->get['attribute_id'];
            $group = 'a';
        }
        if(isset($this->request->get['option_id'])) {
            $attribute_id = $this->request->get['option_id'];
            $group = 'o';
        }
        if(isset($this->request->get['manufacturer_id'])) {
            $attribute_id = $this->request->get['manufacturer_id'];
            $group = 'm';
        }

        if($this->request->get['simonseesme']=='1') {
            $data = array(
                    'simonseesme' => $this->request->get['simonseesme']
            );
        }else {
            $data = array();
        }
        $this->model_catalog_simonfilters->addSupportedFilter($group,$attribute_id,$data);
    }

    public function search_updates() {
        $url = "http://www.simonoop.com/simonservices/opencart/mymodules/simonfilters/search_updates/index.php";
        $fields = array(
                'version' => $this->SFs_version
        );
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);

        curl_close($ch);
        if ($result != 'error') {
            echo "$result";
        } else {
            echo "There was a problem! Please contact simon (oc@simonoop.com)";
        }
    }

    public function fix_invalid_tags_button() {
        $this->db->query("update ". DB_PREFIX ."product_description set tag = replace(tag,' , ',',')");
        $this->db->query("update ". DB_PREFIX ."product_description set tag = replace(tag,' ,',',')");
        $this->db->query("update ". DB_PREFIX ."product_description set tag = replace(tag,', ',',')");
        echo "Done!\n\nPlease check your database to make sure everything's working as expected!";
    }

    public function fix_db_index_button() {

        $indexes = array(
                "option_id", "product_id"
        );

        foreach ($indexes as $index) {
            $sql = "SHOW INDEX FROM " . DB_PREFIX . "product_option_value where Key_name = '{$index}'";
            $results = $this->db->query($sql);

            if ($results->num_rows == 0) {
                $sql_create_index = "CREATE INDEX {$index} ON " . DB_PREFIX . "product_option_value({$index})";
                $this->db->query($sql_create_index);
            }
        }
        echo "Done!\n\nPlease check your database to make sure everything's working as expected!";
    }

    function b64($string, $decode = false) {
        return $decode ? base64_decode(strtr($string, '-_,', '+/=')) : strtr(base64_encode($string), '+/=', '-_,');
    }

    public function change_product() {

        $catalog_model_catalog_product = preg_replace('/system[\\|\/]$/', 'catalog/model/catalog/product.php', DIR_SYSTEM);

        if (file_exists($catalog_model_catalog_product)) {

            $backup_catalog_model_catalog_product = preg_replace('/system[\\|\/]$/', 'catalog/model/catalog/product.simonbackup.' . date("YmdHms") . '.php', DIR_SYSTEM);
            copy($catalog_model_catalog_product, $backup_catalog_model_catalog_product);

            if (is_writable($catalog_model_catalog_product)) {

                $catalog_model_catalog_product_contents = file_get_contents($catalog_model_catalog_product);

                /* post stuff */
                $url = "http://www.simonoop.com/simonservices/opencart/mymodules/simonfilters/modifymoduleproduct/index.php";
                $fields = array(
                        'data' => $this->b64($catalog_model_catalog_product_contents)
                );
                $fields_string = "";
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                $result = curl_exec($ch);

                curl_close($ch);
                if ($result != 'error') {
                    #$result = date("YmdHms").$this->b64($result,true);
                    $result = $this->b64($result, true);

                    $fh = fopen($catalog_model_catalog_product, 'w') or die("can't open file");
                    fwrite($fh, $result);
                    fclose($fh);
                    echo "$result";
                } else {
                    echo "There was a problem! Please contact simon (oc@simonoop.com)";
                }
            } else {
                echo "Sorry! Permissions don't let me make the changes to the product.php file!";
            }
        }
    }

    private function upgrade() {
        $this->db->query("DELETE FROM ". DB_PREFIX ."setting WHERE `group`='filter' and (`key` like '%simon%' or `key` like '%filter_module%' or `key` like 'client_side_items_sorting' or `key` like '%sfspc%')");
    }

    public function fix_main_index_button() {
        $main_index_filename = preg_replace('/system[\\|\/]$/', 'index.php', DIR_SYSTEM);
        $backup_main_index_filename = preg_replace('/system[\\|\/]$/', 'index_backup_simonfilters.php', DIR_SYSTEM);
        if (file_exists($main_index_filename)) {

            copy($main_index_filename, $backup_main_index_filename);

            if (is_writable($main_index_filename)) {
                $main_index_contents = file_get_contents($main_index_filename);

                if (strpos($main_index_contents, "// Router")) {
                    $main_index_contents = str_replace('// Router', "// SimonFilters\n$" . "controller->addPreAction(new Action('module/simonfilters/checkvalidity'));\n\n// Router", $main_index_contents);
                    $fp = fopen($main_index_filename, 'w');
                    if (fwrite($fp, $main_index_contents)) {
                        echo "Done!";
                    }
                } else {
                    echo "Sorry! I was searching for the string '// Router' on the index file but I can't find it!";
                }
            } else {
                echo "Sorry! Permissions don't let me make the changes to the index.php file!";
            }
        }
    }

    public function fix_admin_index_button() {
        $admin_index_filename = DIR_APPLICATION . 'index.php';
        $backup_admin_index_filename = DIR_APPLICATION . 'index_backup_simonfilters.php';

        if (file_exists($admin_index_filename)) {

            copy($admin_index_filename, $backup_admin_index_filename);


            if (is_writable($admin_index_filename)) {
                $admin_index_contents = file_get_contents($admin_index_filename);

                if (strpos($admin_index_contents, "// Router")) {
                    $admin_index_contents = str_replace('// Router', "// SimonFilters\n$" . "controller->addPreAction(new Action('module/simonfilters/managecache'));\n\n// Router", $admin_index_contents);
                    $fp = fopen($admin_index_filename, 'w');
                    if (fwrite($fp, $admin_index_contents)) {
                        echo "Done!";
                    }
                } else {
                    echo "Sorry! I was searching for the string '// Router' on the admin index file but I can't find it!";
                }
            } else {
                echo "Sorry! Permissions don't let me make the changes to the index.php file!";
            }
        } else {
            echo "file doesn";
        }
    }

    public function managecache() {
        $allowedRoutes = Array(
                "catalog/product/update",
                "module/simonfilters"
        );

        if (in_array($this->request->get['route'], $allowedRoutes)) {

            if ($this->config->get('simonfilters_prevent_admin_delete_cache_on_save') != "1") {
                $this->cache->delete("simonfilters");
                $simonfilterstimestamp = time();
                $this->db->query("UPDATE " . DB_PREFIX . "setting s SET s.value='{$simonfilterstimestamp}' WHERE s.key='simonfilterstimestamp'");
            }
        }
    }

    public function rebuildclientside() {
        $this->cache->delete("simonfilters");
        $this->cache->delete("product");
        $simonfilterstimestamp = time();
        $this->db->query("UPDATE " . DB_PREFIX . "setting s SET s.value='{$simonfilterstimestamp}' WHERE s.key='simonfilterstimestamp'");

        $vqmoddir = preg_replace('/system[\\|\/]$/', 'vqmod/vqcache', DIR_SYSTEM);
        $files = glob("$vqmoddir/*product*");
        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    public function getdomids() {
        $template = $this->config->get('config_template');
        $html = @file_get_contents("http://www.simonoop.com/simonservices/opencart/mymodules/simonfilters/getdomid.php?theme={$template}", 0);
        echo $html;
    }

    public function getDefaultCSS() {

        $this->fillcss();
        $stylename = $this->request->post['stylename'];
        echo $this->defaultCSS[$stylename]['css'];
    }



    public function index() {

        //delete old simonfilters settings
        $this->upgrade();
        $this->fillcss();


        if ($this->config->get('simonfilters_allow_debug_data') == '1') {
            @file_get_contents("http://www.simonoop.com/simonservices/opencart/mymodules/simonfilters/allowed.php?email=" . urlencode($this->config->get('config_email')) . "&theme=" . urlencode($this->config->get('config_template')) . "&OC_version=" . VERSION . "&SFs_version=" . urlencode($this->SFs_version), 0);
        }

        $this->load->language('module/simonfilters');
        $this->document->addStyle("view/stylesheet/simonfilters.css");
        $this->document->addScript('view/javascript/simonoop/simonsaveandstayforsimonfilters.js');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if (!is_numeric($this->request->post["simonfilters_slider_step"])) {
                $this->request->post["simonfilters_slider_step"] = "5";
            }

            $cansave = true;
            foreach ($this->request->post as $key => $value) {
                if (is_array($value)) {
                    $structlen = strlen($this->db->escape(serialize($value)));
                    if ($structlen >= 65535) {
                        $cansave = false;
                        switch ($key) {
                            case 'activefilter_simonfilters_attribute':
                                $message = "Attributes";
                                break;
                            case 'activefilter_simonfilters_options':
                                $message = "Options";
                                break;
                            case 'activefilter_simonfilters_tags':
                                $message = "Tags";
                                break;
                            case 'activefilter_simonfilters_manufacturer':
                                $message = "Manufacturers";
                                break;
                        }
                        $perc = 100 * $structlen / 65535 - 100;
                        $arilen = count($value);
                        $nitems = $arilen * $perc / 100;
                        $this->error['warning'] = "ERROR! Data Structure in filter category <b>{$message}</b> is greater than what the database can handle.<br><br>Max length is <b>65535</b> bytes and the structure you want to save is <b>{$structlen}</b> bytes.<br><br> Please disable some filters in that category.<br><br>Press <a href='#' key='$key' nitems='{$nitems}' class='button' id='disable10'><span>HERE</span></a> to disable enough items to be able to save. An estimate of the number of items needed will be made and the items at the end of your list will be disabled. Try to save again after clicking and disable further items if needed until this message is gone. <br><br><b>NOTICE:</b>This is a limitation of OpenCart, not SimonFilters.";
                    }
                }
            }

            if ($cansave) {
                $this->request->post['simonfilterstimestamp'] = time();
                $this->model_setting_setting->editSetting('simonfilters', $this->request->post);
                $this->session->data['success'] = $this->language->get('text_success');
                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_content_top'] = $this->language->get('text_content_top');
        $this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $this->data['text_column_left'] = $this->language->get('text_column_left');
        $this->data['text_column_right'] = $this->language->get('text_column_right');

        $this->data['entry_layout'] = $this->language->get('entry_layout');
        $this->data['entry_position'] = $this->language->get('entry_position');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_module'] = $this->language->get('button_add_module');
        $this->data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_module'),
                'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/simonfilters', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/simonfilters', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['modules'] = array();

        if (isset($this->request->post['simonfilters_module'])) {
            $this->data['modules'] = $this->request->post['simonfilters_module'];
        } elseif ($this->config->get('simonfilters_module')) {
            $this->data['modules'] = $this->config->get('simonfilters_module');
        }

        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        /* SimonFilters */


        $this->load->model('catalog/simonfilters');

        $this->data['allfilters'] = $this->model_catalog_simonfilters->getPossibleFilters($this->config->get('config_language_id'));

        if (isset($this->request->post['activefilter_simonfilters_attribute'])) {
            $this->data['activefilter_simonfilters_attribute'] = $this->request->post['activefilter_simonfilters_attribute'];
        } else {
            $this->data['activefilter_simonfilters_attribute'] = $this->config->get('activefilter_simonfilters_attribute');
        }

        if (isset($this->request->post['activefilter_simonfilters_options'])) {
            $this->data['activefilter_simonfilters_options'] = $this->request->post['activefilter_simonfilters_options'];
        } else {
            $this->data['activefilter_simonfilters_options'] = $this->config->get('activefilter_simonfilters_options');
        }

        if (isset($this->request->post['activefilter_simonfilters_manufacturer'])) {
            $this->data['activefilter_simonfilters_manufacturer'] = $this->request->post['activefilter_simonfilters_manufacturer'];
        } else {
            $this->data['activefilter_simonfilters_manufacturer'] = $this->config->get('activefilter_simonfilters_manufacturer');
        }

        if (isset($this->request->post['activefilter_simonfilters_stock'])) {
            $this->data['activefilter_simonfilters_stock'] = $this->request->post['activefilter_simonfilters_stock'];
        } else {
            $this->data['activefilter_simonfilters_stock'] = $this->config->get('activefilter_simonfilters_stock');
        }

        if (isset($this->request->post['activefilter_simonfilters_price'])) {
            $this->data['activefilter_simonfilters_price'] = $this->request->post['activefilter_simonfilters_price'];
        } else {
            $this->data['activefilter_simonfilters_price'] = $this->config->get('activefilter_simonfilters_price');
        }

        if (isset($this->request->post['activefilter_simonfilters_tags'])) {
            $this->data['activefilter_simonfilters_tags'] = $this->request->post['activefilter_simonfilters_tags'];
        } else {
            $this->data['activefilter_simonfilters_tags'] = $this->config->get('activefilter_simonfilters_tags');
        }

        if (isset($this->request->post['activefilter_simonfilters_categories'])) {
            $this->data['activefilter_simonfilters_categories'] = $this->request->post['activefilter_simonfilters_categories'];
        } else {
            $this->data['activefilter_simonfilters_categories'] = $this->config->get('activefilter_simonfilters_categories');
        }

        if (isset($this->request->post['simonfilters_category_domid'])) {
            $this->data['simonfilters_category_domid'] = $this->request->post['simonfilters_category_domid'];
        } else {
            $this->data['simonfilters_category_domid'] = $this->config->get('simonfilters_category_domid');
            if (!isset($this->data['simonfilters_category_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_category_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_manufacturer_domid'])) {
            $this->data['simonfilters_manufacturer_domid'] = $this->request->post['simonfilters_manufacturer_domid'];
        } else {
            $this->data['simonfilters_manufacturer_domid'] = $this->config->get('simonfilters_manufacturer_domid');
            if (!isset($this->data['simonfilters_manufacturer_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_manufacturer_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_homepage_domid'])) {
            $this->data['simonfilters_homepage_domid'] = $this->request->post['simonfilters_homepage_domid'];
        } else {
            $this->data['simonfilters_homepage_domid'] = $this->config->get('simonfilters_homepage_domid');
            if (!isset($this->data['simonfilters_homepage_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_homepage_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_search_domid'])) {
            $this->data['simonfilters_search_domid'] = $this->request->post['simonfilters_search_domid'];
        } else {
            $this->data['simonfilters_search_domid'] = $this->config->get('simonfilters_search_domid');
            if (!isset($this->data['simonfilters_search_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_search_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_product_domid'])) {
            $this->data['simonfilters_product_domid'] = $this->request->post['simonfilters_product_domid'];
        } else {
            $this->data['simonfilters_product_domid'] = $this->config->get('simonfilters_product_domid');
            if (!isset($this->data['simonfilters_product_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_product_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_special_domid'])) {
            $this->data['simonfilters_special_domid'] = $this->request->post['simonfilters_special_domid'];
        } else {
            $this->data['simonfilters_special_domid'] = $this->config->get('simonfilters_special_domid');
            if (!isset($this->data['simonfilters_special_domid'][$this->config->get('config_template')])) {
                $this->data['simonfilters_special_domid'][$this->config->get('config_template')] = '#content';
            }
        }

        if (isset($this->request->post['simonfilters_force_siblings'])) {
            $this->data['simonfilters_force_siblings'] = $this->request->post['simonfilters_force_siblings'];
        } else {
            $this->data['simonfilters_force_siblings'] = $this->config->get('simonfilters_force_siblings');
        }

        if (isset($this->request->post['simonfilters_persist'])) {
            $this->data['simonfilters_persist'] = $this->request->post['simonfilters_persist'];
        } else {
            $this->data['simonfilters_persist'] = $this->config->get('simonfilters_persist');
        }

        if (isset($this->request->post['simonfilters_allow_debug_data'])) {
            $this->data['simonfilters_allow_debug_data'] = $this->request->post['simonfilters_allow_debug_data'];
        } else {
            $this->data['simonfilters_allow_debug_data'] = $this->config->get('simonfilters_allow_debug_data');
        }

        if (isset($this->request->post['simonfilters_front_end_diagnostics'])) {
            $this->data['simonfilters_front_end_diagnostics'] = $this->request->post['simonfilters_front_end_diagnostics'];
        } else {
            $this->data['simonfilters_front_end_diagnostics'] = $this->config->get('simonfilters_front_end_diagnostics');
        }

        $this->data['simonfilters_styles'] = array();

        if (isset($this->request->post['simonfilters_styles'])) {
            $this->data['simonfilters_styles'] = $this->request->post['simonfilters_styles'];
        } elseif ($this->config->get('simonfilters_styles')) {
            $this->data['simonfilters_styles'] = $this->config->get('simonfilters_styles');
        }

        if (!count($this->config->get('simonfilters_styles'))) {
            foreach ($this->defaultCSS as $csskey => $cssari) {
                $this->data['simonfilters_styles'][] = array(
                        'name' => $csskey
                        , 'css' => $cssari['css']
                        , 'orientation' => $cssari['orientation']
                        , 'collapsible' => $cssari['collapsible']
                );
            }
        }

        if (isset($this->request->post['simonfilters_show_more'])) {
            $this->data['simonfilters_show_more'] = $this->request->post['simonfilters_show_more'];
        } else {
            $this->data['simonfilters_show_more'] = $this->config->get('simonfilters_show_more');
        }

        if (isset($this->request->post['simonfilters_show_more_number'])) {
            $this->data['simonfilters_show_more_number'] = $this->request->post['simonfilters_show_more_number'];
        } else {
            $this->data['simonfilters_show_more_number'] = $this->config->get('simonfilters_show_more_number');
        }

        if (isset($this->request->post['simonfilters_sort'])) {
            $this->data['simonfilters_sort'] = $this->request->post['simonfilters_sort'];
        } else {
            $this->data['simonfilters_sort'] = $this->config->get('simonfilters_sort') != '' ? $this->config->get('simonfilters_sort') : 'a,o,t,m,s,p';
        }
        if (!preg_match('/c/', $this->data['simonfilters_sort']))
            $this->data['simonfilters_sort'].=',c';

        if (isset($this->request->post['simonfilters_enable_attribute_separator'])) {
            $this->data['simonfilters_enable_attribute_separator'] = $this->request->post['simonfilters_enable_attribute_separator'];
        } else {
            $this->data['simonfilters_enable_attribute_separator'] = $this->config->get('simonfilters_enable_attribute_separator');
        }

        if (isset($this->request->post['simonfilters_attribute_separator_char'])) {
            $this->data['simonfilters_attribute_separator_char'] = $this->request->post['simonfilters_attribute_separator_char'];
        } else {
            $this->data['simonfilters_attribute_separator_char'] = $this->config->get('simonfilters_attribute_separator_char');
        }

        if (isset($this->request->post['simonfilters_option_zero_quantity'])) {
            $this->data['simonfilters_option_zero_quantity'] = $this->request->post['simonfilters_option_zero_quantity'];
        } else {
            $this->data['simonfilters_option_zero_quantity'] = $this->config->get('simonfilters_option_zero_quantity');
        }

        if (isset($this->request->post['simonfilters_dynamic'])) {
            $this->data['simonfilters_dynamic'] = $this->request->post['simonfilters_dynamic'];
        } else {
            $this->data['simonfilters_dynamic'] = $this->config->get('simonfilters_dynamic');
        }

        if (isset($this->request->post['simonfilters_force_all'])) {
            $this->data['simonfilters_force_all'] = $this->request->post['simonfilters_force_all'];
        } else {
            $this->data['simonfilters_force_all'] = $this->config->get('simonfilters_force_all');
        }


        if (isset($this->request->post['simonfilters_slider_step'])) {
            $this->data['simonfilters_slider_step'] = $this->request->post['simonfilters_slider_step'];
        } else {
            $this->data['simonfilters_slider_step'] = $this->config->get('simonfilters_slider_step');
        }
        if ($this->data['simonfilters_slider_step'] == '')
            $this->data['simonfilters_slider_step'] = 5;


        if (isset($this->request->post['simonfilters_script_ignore_text'])) {
            $this->data['simonfilters_script_ignore_text'] = $this->request->post['simonfilters_script_ignore_text'];
        } else {
            $this->data['simonfilters_script_ignore_text'] = $this->config->get('simonfilters_script_ignore_text');
        }
        if ($this->data['simonfilters_script_ignore_text'] == '')
            $this->data['simonfilters_script_ignore_text'] = '';

        if (isset($this->request->post['simonfilters_script_ignore'])) {
            $this->data['simonfilters_script_ignore'] = $this->request->post['simonfilters_script_ignore'];
        } else {
            $this->data['simonfilters_script_ignore'] = $this->config->get('simonfilters_script_ignore');
        }
        if ($this->data['simonfilters_script_ignore_text'] == '')
            $this->data['simonfilters_script_ignore'] = 0;

        if (isset($this->request->post['simonfilters_execute_external_js'])) {
            $this->data['simonfilters_execute_external_js'] = $this->request->post['simonfilters_execute_external_js'];
        } else {
            $this->data['simonfilters_execute_external_js'] = $this->config->get('simonfilters_execute_external_js');
        }
        if ($this->data['simonfilters_execute_external_js'] == '')
            $this->data['simonfilters_execute_external_js'] = 0;

        if (isset($this->request->post['simonfilters_ignore_scripts_names'])) {
            $this->data['simonfilters_ignore_scripts_names'] = $this->request->post['simonfilters_ignore_scripts_names'];
        } else {
            $this->data['simonfilters_ignore_scripts_names'] = $this->config->get('simonfilters_ignore_scripts_names');
        }
        if ($this->data['simonfilters_ignore_scripts_names'] == '')
            $this->data['simonfilters_ignore_scripts_names'] = '';

        if (isset($this->request->post['client_side_items_sorting'])) {
            $this->data['client_side_items_sorting'] = $this->request->post['client_side_items_sorting'];
        } else {
            $this->data['client_side_items_sorting'] = $this->config->get('client_side_items_sorting');
        }

        if (isset($this->request->post['activefilter_simonfilters_stock_instock'])) {
            $this->data['activefilter_simonfilters_stock_instock'] = $this->request->post['activefilter_simonfilters_stock_instock'];
        } else {
            $this->data['activefilter_simonfilters_stock_instock'] = $this->config->get('activefilter_simonfilters_stock_instock');
        }

        if (isset($this->request->post['simonfilters_delete_cache_for_debug'])) {
            $this->data['simonfilters_delete_cache_for_debug'] = $this->request->post['simonfilters_delete_cache_for_debug'];
        } else {
            $this->data['simonfilters_delete_cache_for_debug'] = $this->config->get('simonfilters_delete_cache_for_debug');
        }

        if (isset($this->request->post['simonfilters_prevent_admin_delete_cache_on_save'])) {
            $this->data['simonfilters_prevent_admin_delete_cache_on_save'] = $this->request->post['simonfilters_prevent_admin_delete_cache_on_save'];
        } else {
            $this->data['simonfilters_prevent_admin_delete_cache_on_save'] = $this->config->get('simonfilters_prevent_admin_delete_cache_on_save');
        }

        if (isset($this->request->post['activefilter_simonfilters_options_behavior'])) {
            $this->data['activefilter_simonfilters_options_behavior'] = $this->request->post['activefilter_simonfilters_options_behavior'];
        } else {
            $this->data['activefilter_simonfilters_options_behavior'] = $this->config->get('activefilter_simonfilters_options_behavior');
        }

        if (isset($this->request->post['activefilter_simonfilters_attributes_behavior'])) {
            $this->data['activefilter_simonfilters_attributes_behavior'] = $this->request->post['activefilter_simonfilters_attributes_behavior'];
        } else {
            $this->data['activefilter_simonfilters_attributes_behavior'] = $this->config->get('activefilter_simonfilters_attributes_behavior');
        }

        if (isset($this->request->post['activefilter_simonfilters_expanded'])) {
            $this->data['activefilter_simonfilters_expanded'] = $this->request->post['activefilter_simonfilters_expanded'];
        } else {
            $this->data['activefilter_simonfilters_expanded'] = $this->config->get('activefilter_simonfilters_expanded');
        }

        if (isset($this->request->post['simonfilters_disableajax'])) {
            $this->data['simonfilters_disableajax'] = $this->request->post['simonfilters_disableajax'];
        } else {
            $this->data['simonfilters_disableajax'] = $this->config->get('simonfilters_disableajax');
        }

        if (isset($this->request->post['sfspc'])) {
            $this->data['sfspc'] = $this->request->post['sfspc'];
        } else {
            $this->data['sfspc'] = $this->config->get('sfspc');
        }

        if (isset($this->request->post['simonfilters_display_totals'])) {
            $this->data['simonfilters_display_totals'] = $this->request->post['simonfilters_display_totals'];
        } else {
            $this->data['simonfilters_display_totals'] = $this->config->get('simonfilters_display_totals');
        }

        if (isset($this->request->post['simonfilters_display_type'])) {
            $this->data['simonfilters_display_type'] = $this->request->post['simonfilters_display_type'];
        } else {
            $this->data['simonfilters_display_type'] = $this->config->get('simonfilters_display_type');
        }

        if (isset($this->request->post['simonfilters_cache_buster'])) {
            $this->data['simonfilters_cache_buster'] = $this->request->post['simonfilters_cache_buster'];
        } else {
            $this->data['simonfilters_cache_buster'] = $this->config->get('simonfilters_cache_buster');
        }

        if (isset($this->request->post['simonfilters_categories_behavior'])) {
            $this->data['simonfilters_categories_behavior'] = $this->request->post['simonfilters_categories_behavior'];
        } else {
            $this->data['simonfilters_categories_behavior'] = $this->config->get('simonfilters_categories_behavior');
        }

        if (isset($this->request->post['simonfilters_categories_autoselect'])) {
            $this->data['simonfilters_categories_autoselect'] = $this->request->post['simonfilters_categories_autoselect'];
        } else {
            $this->data['simonfilters_categories_autoselect'] = $this->config->get('simonfilters_categories_autoselect');
        }


        if (isset($this->request->post['simonfilters_manufacturers_autoselect'])) {
            $this->data['simonfilters_manufacturers_autoselect'] = $this->request->post['simonfilters_manufacturers_autoselect'];
        } else {
            $this->data['simonfilters_manufacturers_autoselect'] = $this->config->get('simonfilters_manufacturers_autoselect');
        }


        if (isset($this->request->post['simonfilters_hard'])) {
            $this->data['simonfilters_hard'] = $this->request->post['simonfilters_hard'];
        } else {
            $this->data['simonfilters_hard'] = $this->config->get('simonfilters_hard');
        }

        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('localisation/stock_status');
        $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        $this->data['entry_SFs_version'] = $this->SFs_version;

        $this->data['entry_display_type'] = $this->language->get('entry_display_type');
        $this->data['entry_display_type_vertical'] = $this->language->get('entry_display_type_vertical');
        $this->data['entry_display_totals'] = $this->language->get('entry_display_totals');
        $this->data['entry_active_filters'] = $this->language->get('entry_active_filters');

        $this->data['entry_active_filters_attribute'] = $this->language->get('entry_active_filters_attribute');
        $this->data['entry_theme_config'] = $this->language->get('entry_theme_config');
        $this->data['entry_styles'] = $this->language->get('entry_styles');
        $this->data['entry_active_filters_options'] = $this->language->get('entry_active_filters_options');
        $this->data['entry_active_filters_tags'] = $this->language->get('entry_active_filters_tags');
        $this->data['entry_active_filters_manufacturer'] = $this->language->get('entry_active_filters_manufacturer');
        $this->data['entry_active_filters_stock'] = $this->language->get('entry_active_filters_stock');
        $this->data['entry_active_filters_price'] = $this->language->get('entry_active_filters_price');
        $this->data['entry_active_filters_price_tax'] = $this->language->get('entry_active_filters_price_tax');
        $this->data['entry_active_filters_price_special'] = $this->language->get('entry_active_filters_price_special');
        $this->data['entry_active_filters_misc'] = $this->language->get('entry_active_filters_misc');
        $this->data['entry_client_side_attributes'] = $this->language->get('entry_client_side_attributes');
        $this->data['entry_client_side_attributes_button'] = $this->language->get('entry_client_side_attributes_button');
        $this->data['entry_client_side_sorting'] = $this->language->get('entry_client_side_sorting');
        $this->data['entry_client_side_items_sorting'] = $this->language->get('entry_client_side_items_sorting');
        $this->data['entry_attribute_separator'] = $this->language->get('entry_attribute_separator');

        $this->data['entry_category_domid'] = $this->language->get('entry_category_domid');
        $this->data['entry_manufacturer_domid'] = $this->language->get('entry_manufacturer_domid');
        $this->data['entry_homepage_domid'] = $this->language->get('entry_homepage_domid');
        $this->data['entry_search_domid'] = $this->language->get('entry_search_domid');
        $this->data['entry_product_domid'] = $this->language->get('entry_product_domid');
        $this->data['entry_special_domid'] = $this->language->get('entry_special_domid');
        $this->data['entry_get_domids'] = $this->language->get('entry_get_domids');
        $this->data['entry_get_domids_button'] = $this->language->get('entry_get_domids_button');
        $this->data['entry_force_siblings'] = $this->language->get('entry_force_siblings');
        $this->data['entry_filter_persist'] = $this->language->get('entry_filter_persist');
        $this->data['entry_allow_debug_data'] = $this->language->get('entry_allow_debug_data');
        $this->data['entry_enable_front_end_diagnostics'] = $this->language->get('entry_enable_front_end_diagnostics');
        $this->data['entry_option_quantity_zero'] = $this->language->get('entry_option_quantity_zero');
        $this->data['entry_dynamic'] = $this->language->get('entry_dynamic');
        $this->data['entry_force_all'] = $this->language->get('entry_force_all');
        $this->data['entry_filters_slider_step'] = $this->language->get('entry_filters_slider_step');
        $this->data['entry_script_ignore'] = $this->language->get('entry_script_ignore');
        $this->data['entry_active_filters_categories'] = $this->language->get('entry_active_filters_categories');
        $this->data['entry_activefilter_simonfilters_stock_instock'] = $this->language->get('entry_activefilter_simonfilters_stock_instock');
        $this->data['entry_delete_cache_for_debug'] = $this->language->get('entry_delete_cache_for_debug');
        $this->data['entry_prevent_admin_delete_cache_on_save'] = $this->language->get('entry_prevent_admin_delete_cache_on_save');
        $this->data['entry_options_behavior'] = $this->language->get('entry_options_behavior');
        $this->data['entry_attributes_behavior'] = $this->language->get('entry_attributes_behavior');
        $this->data['entry_expanded_collapsed'] = $this->language->get('entry_expanded_collapsed');
        $this->data['entry_disableajax'] = $this->language->get('entry_disableajax');
        $this->data['entry_execute_external_js'] = $this->language->get('entry_execute_external_js');
        $this->data['entry_show_more'] = $this->language->get('entry_show_more');
        $this->data['entry_active_filters_price_type'] = $this->language->get('entry_active_filters_price_type');
        $this->data['entry_filters_hide_products_less'] = $this->language->get('entry_filters_hide_products_less');
        $this->data['entry_filters_hide_products_less_price'] = $this->language->get('entry_filters_hide_products_less_price');
        $this->data['entry_search_updates'] = $this->language->get('entry_search_updates');
        $this->data['entry_search_updates_button'] = $this->language->get('entry_search_updates_button');
        $this->data['entry_cache_buster'] = $this->language->get('entry_cache_buster');
        $this->data['entry_force_currency'] = $this->language->get('entry_force_currency');

        $this->data['ajax_action_client_side_rebuild'] = $this->url->link('module/simonfilters/rebuildclientside', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_get_domids'] = $this->url->link('module/simonfilters/getdomids', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_get_default_style'] = $this->url->link('module/simonfilters/getDefaultCSS', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_main_index_button'] = $this->url->link('module/simonfilters/fix_main_index_button', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_admin_index_button'] = $this->url->link('module/simonfilters/fix_admin_index_button', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_db_index_button'] = $this->url->link('module/simonfilters/fix_db_index_button', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_change_product'] = $this->url->link('module/simonfilters/change_product', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_search_updates'] = $this->url->link('module/simonfilters/search_updates', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['generic_link'] = $this->url->link('LINK', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['ajax_action_invalid_tags_button'] = $this->url->link('module/simonfilters/fix_invalid_tags_button', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['text_type_default'] = $this->language->get('text_type_default');
        $this->data['text_type_checkbox'] = $this->language->get('text_type_checkbox');
        $this->data['text_type_dropdown'] = $this->language->get('text_type_dropdown');
        $this->data['text_type_clean'] = $this->language->get('text_type_clean');
        $this->data['text_type_horizontal'] = $this->language->get('text_type_horizontal');
        $this->data['text_type_yes'] = $this->language->get('text_type_yes');
        $this->data['text_type_no'] = $this->language->get('text_type_no');
        $this->data['text_type_collapsed'] = $this->language->get('text_type_collapsed');
        $this->data['text_type_expanded'] = $this->language->get('text_type_expanded');

        $this->data['config_template'] = $this->config->get('config_template');
        $this->data['warning_unsupported_theme'] = preg_replace('/\{0\}/', $this->config->get('config_template'), $this->language->get('warning_unsupported_theme'));
        $this->data['warning_main_index'] = $this->language->get('warning_main_index');
        $this->data['warning_admin_index'] = $this->language->get('warning_admin_index');
        $this->data['warning_invalid_tags'] = $this->language->get('warning_invalid_tags');

        $this->data['simon_save_and_stay'] = $this->language->get('simon_save_and_stay');

        $this->data['valid_main_index'] = true;
        $this->data['valid_admin_index'] = true;

        $this->load->model('catalog/category');
        $this->data['categories'] = $this->model_catalog_category->getCategories(0);

        $this->load->model('localisation/currency');
        $this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

        if ($this->request->server['REQUEST_METHOD'] == 'GET') {

            $isVQMod = false;
            $modelProductHasSimon = false;

            //check for invalid tags
            $product_tag_exists = !$this->db->query("SHOW COLUMNS FROM ". DB_PREFIX."product_description LIKE 'tag';")->num_rows;
            if(!$product_tag_exists) {
                $this->data['valid_invalid_tags'] = !$this->db->query("select * from ". DB_PREFIX ."product_description where tag like '%, %' or tag like '% ,%'")->num_rows;
            }else {
                $this->data['valid_invalid_tags'] = 1;
            }

            //check for simonfilter data on main index.php
            $main_index_filename = preg_replace('/system[\\|\/]$/', 'index.php', DIR_SYSTEM);
            if (file_exists($main_index_filename)) {
                $main_index_contents = file_get_contents($main_index_filename);
                $this->data['valid_main_index'] = preg_match('/[\s]\$controller->addPreAction\(new Action\(\'module\/simonfilters\/checkvalidity\'\)\);/', $main_index_contents);

                $VQmodpresent = strpos($main_index_contents, "require_once($" . "vqmod->modCheck(DIR_SYSTEM . 'startup.php'));");
            }

            //check for simonfilter data on admin index.php
            $admin_index_filename = DIR_APPLICATION . 'index.php';
            if (file_exists($admin_index_filename)) {
                $admin_index_contents = file_get_contents($admin_index_filename);
                $this->data['valid_admin_index'] = preg_match('/[\s]\$controller->addPreAction\(new Action\(\'module\/simonfilters\/managecache\'\)\);/', $admin_index_contents);
            }

            // check if simonfilters.xml exists in the vqmod/xml folder
            $vqmodfile = preg_replace('/system[\\|\/]$/', 'vqmod/xml/simonfilters.xml', DIR_SYSTEM);
            $vqmodfileexists = file_exists($vqmodfile);

            //check to see if catalog/model/catalog/product.php has necessary changes
            if ($this->data['error_warning'] == '') {
                $catalog_model_catalog_product = preg_replace('/system[\\|\/]$/', 'catalog/model/catalog/product.php', DIR_SYSTEM);
                if (file_exists($catalog_model_catalog_product)) {
                    $catalog_model_catalog_product_contents = file_get_contents($catalog_model_catalog_product);
                    $modelProductHasSimon = strpos($catalog_model_catalog_product_contents, "simonfilters");
                }
            }

            #$VQmodpresent = false;
            switch ($VQmodpresent) {
                case false:
                    if (!$modelProductHasSimon) {
                        $this->data['error_warning'] = "Warning!<br><br>Your store doesn't appear to have VQmod installed.<br><br>
                                                            It is highly advisable to install VQmod and to use the vqmod file provided.<br><br>
                                                                                                                        
                                                            If, for some reason, you don't want to use VQmod, I can try to make the necessary changes to the <code>catalog/model/catalog/product.php</code> 
                                                            but you must understand that if you have custom changes to that file I may be unable to make all the changes and the module may not work.<br><br>
                                                            <div class='buttons'>
                                                                <a id='change_product' class='button'><span>Let me try to do that for you!</span></a>&nbsp;<a id='ignore_change_product' class='button'><span>Ignore this warning!</span></a>
                                                            </div>";
                    }
                    break;
                default:
                    switch ($vqmodfileexists) {
                        case true: //simonfilters.xml exists
                            if ($modelProductHasSimon) {
                                $this->data['error_warning'] = 'Warning!<br><br>Your <code>catalog/model/catalog/product.php</code> may still have some code from when simonfilters didn\'t support VQmod<br><br>Please replace that file with the stock one for your OC version.<br><br>Please contact Simon ( <a href="mailto:oc@simonoop.com">oc@simonoop.com</a> ) if you need help!';
                            }
                            break;
                        case false: //simonfilters.xml does not exist
                            if (!$modelProductHasSimon) {
                                $this->data['error_warning'] = "Warning!<br><br>You have VQmod installed in this store but I can't find the <b>simonfilters.xml</b> file under your vqmod/xml folder.<br>
                                                            If you don't want to use vqmod then your catalog/model/catalog/product.php must have the necessary changes but it doesn't seem to have them!<br><br>
                                                            Please install the file and return here or contact simon to get a correct product.php file<br><br>
                                                            The module won't function until you do that!";
                            }
                            break;
                    }
                    break;
            }

            if ($this->data['error_warning'] == '') {
                $sql = "SHOW INDEX FROM " . DB_PREFIX . "product_option_value where Key_name IN('option_id', 'product_id')";
                $results = $this->db->query($sql);

                if ($results->num_rows != 2) {
                    $this->data['error_warning'] = "Warning!<br><br>Your database could benefit from some index tuning.<br><br>Execute the following code on your database:<br><br>CREATE INDEX option_id ON " . DB_PREFIX . "product_option_value(option_id);<br>CREATE INDEX product_id ON " . DB_PREFIX . "product_option_value(product_id);<br><br><br><div class='buttons'><a id='db_index_button' class='button'><span>Let me try to do that for you!</span></a></div>  ";
                }
            }
        }

        /* SimonFilters */

        $this->template = 'module/simonfilters.tpl';
        $this->children = array(
                'common/header',
                'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/simonfilters')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function fillcss() {

        $this->defaultCSS['default'] =
                array(
                "orientation" => "vertical",
                "collapsible" => "0",
                "css" =>
                "/*************************************************************
common
*************************************************************/
div.box.simonfilters ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

div.box.simonfilters .filter_title{
    font-weight: bold;
    color:red;
    border-bottom: 1px solid #eaeaea;
    padding-top:4px
}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
    float:right;
    color:#38B0E3;
    cursor:pointer;
    clear:right;
}

div.box.simonfilters .add_filter{
    padding:3px;
    font-size: 12px;
    font-weight: bold;
    cursor:pointer;
    color:#38B0E3;
    clear:right;
}

/*************************************************************
default specific
*************************************************************/
div.box.simonfilters li.checked{
    display:none;
}


/************************************************************
extras
************************************************************/
div.box.simonfilters .clear_filters_local_container{
  display:inline;
}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
  display:none;
}

div.box.simonfilters .clear_filters_global_container{

}
");

        $this->defaultCSS['checkbox'] =
                array(
                "orientation" => "vertical",
                "collapsible" => "0",
                "css" =>
                "/*************************************************************
common
*************************************************************/
div.box.simonfilters ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

div.box.simonfilters div.box-content{
    overflow:auto
}

div.box.simonfilters .filter_title{
    font-weight: bold;
    color:red;
    border-bottom: 1px solid #eaeaea;
    padding-top:4px
}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
  float:right;
  color:#38B0E3;
  cursor:pointer;
  clear:right;
}

div.box.simonfilters .add_filter{
    padding:3px;
    font-size: 12px;
    font-weight: bold;
    cursor:pointer;
    color:#38B0E3;
    clear:right;
}

/************************************************************
checkbox specific
************************************************************/

div.box.simonfilters .unchecked{
    padding: 2px 3px 3px 20px;
    background-repeat: no-repeat !important;
    background: url(\"image/simonfilters/check_small_off.png\");
}
div.box.simonfilters .checked{
    padding: 2px 3px 3px 20px;
    background-repeat: no-repeat !important;
    background: url(\"image/simonfilters/check_small_on.png\");
}

/************************************************************
extras
************************************************************/
div.box.simonfilters .clear_filters_local_container{

}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
  display:none;
}

div.box.simonfilters .clear_filters_global_container{

}
                ");

        $this->defaultCSS['dropdown'] =
                array(
                "orientation" => "vertical",
                "collapsible" => "1",
                "css" =>
                "/*************************************************************
common
*************************************************************/
div.box.simonfilters ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

div.box.simonfilters .filter_title{
    font-weight: bold;
    color:red;
    border-bottom: 1px solid #eaeaea;
    padding-top:4px
}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
    float:right;
    color:#38B0E3;
    cursor:pointer;
    clear:right;
}

div.box.simonfilters div.box-content{
    overflow:auto
}

div.box.simonfilters .add_filter{
    padding:3px;
    font-size: 12px;
    font-weight: bold;
    cursor:pointer;
    color:#38B0E3;
    clear:right;
}

/************************************************************
checkbox specific
************************************************************/

div.box.simonfilters .unchecked{
    padding: 2px 3px 3px 20px;
    background-repeat: no-repeat !important;
    background: url(\"image/simonfilters/check_small_off.png\");
}
div.box.simonfilters .checked{
    padding: 2px 3px 3px 20px;
    background-repeat: no-repeat !important;
    background: url(\"image/simonfilters/check_small_on.png\");
}

/************************************************************
dropdown specific
************************************************************/
div.box.simonfilters div.filter_title{
    cursor:pointer;
}

div.box.simonfilters .expanded{
    padding: 2px 3px 3px 20px;
    background: url(\"image/simonfilters/collapse.png\");
    background-repeat: no-repeat;
    background-position:0px 4px;
}

div.box.simonfilters .collapsed{
    padding: 2px 3px 3px 20px;
    background: url(\"image/simonfilters/expand.png\");
    background-repeat: no-repeat;
    background-position:0px 4px;
}

/************************************************************
extras
************************************************************/
div.box.simonfilters .clear_filters_local_container{
    display:none;
}
div.box.simonfilters .clear_filters_global_container{
    display:inline;
}
div.box.simonfilters .filter_title{
  position:relative;
  left:-15px;
  padding-left:15px;
}

");

        $this->defaultCSS['horizontal'] =
                array(
                "orientation" => "horizontal",
                "collapsible" => "0",
                "css" =>
                "/*************************************************************
common
*************************************************************/
div.box.simonfilters ul{
    list-style: none;
    margin: 0;
    padding: 0;
}

div.box.simonfilters .filter_title{
    font-weight: bold;
    color:red;
    border-bottom: 1px solid #eaeaea;
    padding-top:4px
}

div.box.simonfilters .clear_filters_global,
div.box.simonfilters .clear_filters_local{
    float:right;
    color:#38B0E3;
    cursor:pointer;
    display:none;
    clear:right;
}

div.box.simonfilters .add_filter{
    padding:3px;
    padding-left:15px;
    font-size: 12px;
    font-weight: bold;
    cursor:pointer;
    color:#38B0E3;
    clear:right;
}

div.box.simonfilters .filter_title{
    background-color: #eee;
    padding:2px;
    font-family: Verdana, Geneva, sans-serif;
    font-size: 11px;
    font-weight: bold;
    width:100%;
}

div.box.simonfilters td:first-child{
    padding:0px;
    white-space:nowrap;
}

div.box.simonfilters ul. table{
    width:100%;
    border-bottom:1px solid #dadada;
}

div.box.simonfilters ul  li:last-child table{
    border:0px;
}

div.box.simonfilters ul tr td:first-child{
    padding-left: 2px;
    padding-right: 10px;
}

div.box.simonfilters li.add_filter
, li.separator{
    float:left;
    padding:0px;
    padding-right:5px;
}

div.box.simonfilters .filter_ul li:last-child{
    display: none;
}

div.box.simonfilters .checked{
    font-style:italic;
    color:#a1a1a1;
}
");
    }

}

?>