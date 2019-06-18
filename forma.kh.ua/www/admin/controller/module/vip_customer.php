<?php
class ControllerModuleVipCustomer extends Controller {
  private $version = "v0.9.1";
  private $mod_name = "vip_customer";

  public function index() {
    $lng = $this->load->language("module/" . $this->mod_name);
    $this->data["lng"] = $lng;

    $this->data["mod_name"] = $this->mod_name;

    $this->document->setTitle($this->language->get('heading_title'));

    $this->data["heading_title"] = $lng["heading_title"] . " - " . $this->version;

    $this->data["token"] = $this->session->data["token"];

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
      'href' => $this->url->link("module/" . $this->mod_name, 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->load->model("module/" . $this->mod_name);
    $this->data["vip_levels"] = $this->model_module_vip_customer->getVipLevels();
    
    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();
    $this->data['languages'] = $languages;
    
    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    $this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

    if (isset($this->request->post['vip_customer_module'])) $this->data['modules'] = $this->request->post['vip_customer_module'];
    else {
      $this->load->model('setting/setting');
      $modules = $this->model_setting_setting->getSetting("vip_customer_module");
      $this->data['modules'] = $modules ? $modules["vip_customer_module"] : array();
    } 

    if (isset($this->request->post['vip_customer_general'])) $this->data['vip_general'] = $this->request->post['vip_customer_general'];
    else {
      $vip_general = $this->model_setting_setting->getSetting("vip_customer_general");
      if (!$vip_general) $vip_general = array(
        "customer_group_id" => array(),
        "store_id" => array(),
        "type" => "",
        "shipping" => "",
        "tax" => "",
        "credit" => "",
        "reward" => "",
        "coupon" => "",
        "email_customer" => "",
        "email_admin" => "",
        "show_price" => ""
      );
      $this->data['vip_general'] = $vip_general;
    } 
  
    $this->load->model("sale/customer_group");
    $this->data["customer_groups"] = $this->model_sale_customer_group->getCustomerGroups();
    
    $this->data["stores"] = $this->model_module_vip_customer->getStores();

    $this->template = 'module/vip_customer.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
      );

    $this->response->setOutput($this->render());
  }

  public function saveviplevels() {
    $this->load->language("module/" . $this->mod_name);
    
    if ($this->user->hasPermission('modify', "module/" . $this->mod_name)) {
      $this->load->model("module/" . $this->mod_name);
      
      if (isset($this->request->post["vip_level"])) $this->model_module_vip_customer->saveviplevels($this->request->post["vip_level"]);
      else $this->model_module_vip_customer->saveviplevels();
      
      $html = "<span class='success' style='margin-left:5px'>" . $this->language->get("success") . "</span>";
    }
    else $html = "<span class='warning' style='margin-left:5px'>" . $this->language->get("error_permission") . "</span>";
    
    $this->response->setOutput($html);
  }
    
  public function savevipmodules() {
    $this->load->language("module/" . $this->mod_name);
    
    if ($this->user->hasPermission('modify', "module/" . $this->mod_name)) {
      $this->load->model("setting/setting");
      $this->model_setting_setting->editSetting("vip_customer_module", $this->request->post);
      $html = "<span class='success' style='margin-left:5px'>" . $this->language->get("success") . "</span>";
    }
    else $html = "<span class='warning' style='margin-left:5px'>" . $this->language->get("error_permission") . "</span>";
    
    $this->response->setOutput($html);
  }
    
  public function savevipgeneral() {
    $this->load->language("module/" . $this->mod_name);
    
    if ($this->user->hasPermission('modify', "module/" . $this->mod_name)) {
      $this->load->model("setting/setting");
      $this->model_setting_setting->editSetting("vip_customer_general", $this->request->post["vip_customer_general"]);
      
      $this->load->model("module/" . $this->mod_name);
      
      if (empty($this->request->post["vip_customer_general"]["customer_group_id"]) || empty($this->request->post["vip_customer_general"]["store_id"]) || empty($this->request->post["vip_customer_general"]["order_statuses"])) $this->model_module_vip_customer->deleteVips();
      else $this->model_module_vip_customer->updateCustomers();

      if (isset($this->request->post["vip_customer_general"]["disabled"])) {
        $this->uninstall();
        $files = glob(DIR_CACHE . "cache.vip_customer*.*");
    		if ($files) {
      		foreach ($files as $file) unlink($file);
        }
      } else {
        if (!file_exists("../vqmod/xml/" . $this->mod_name . ".xml")) $this->install();
      }
      
      $html = "<span class='success' style='margin-left:5px'>" . $this->language->get("success") . "</span>";
    }
    else $html = "<span class='warning' style='margin-left:5px'>" . $this->language->get("error_permission") . "</span>";
    
    $this->response->setOutput($html);
  }

  public function getvips() {
    $this->load->model("module/" . $this->mod_name);

    $html = "";
    foreach ($this->model_module_vip_customer->getvips($this->request->post) as $r) {
      $html .= "<tr class='data'>";
      $html .= "  <td class='left'><a href='index.php?route=sale/customer/update&token=" . $this->session->data["token"] . "&customer_id=" . $r["customer_id"] . "'>" . $r["fullname"] . "</a></td>"; 
      $html .= "  <td class='left'>" . $r["email"] . "</td>"; 
      $html .= "  <td class='left'>" . $r["level"] . "</td>"; 
      $html .= "  <td class='right'>" . $this->currency->format($r["vip_init"]) . "</td>";
      $html .= "  <td class='right'>" . $this->currency->format($r["vip_total"]) . "</td>";
      $html .= "  <td class='left'><a href='index.php?route=module/vip_customer/details&customer_id=" . $r["customer_id"] . "&token=" . $this->session->data["token"] . "' target='_blank'>Detail</a></td>";
      $html .= "</tr>";
    }

    $this->response->setOutput($html);
  } //function getvips end

  public function getPagination() {
    $this->load->model("module/" . $this->mod_name);

    $page = isset($this->request->post['page']) ? $this->request->post['page'] : 1;
    $page_limit = $this->config->get("config_admin_limit");

    $pagination = new Pagination();
    $pagination->total = $this->model_module_vip_customer->getTotalVips($this->request->post);
    $pagination->page = $page;
    $pagination->limit = $page_limit;
    $pagination->text = $this->language->get('text_pagination');
    $pagination->url = "{page}";

    $this->response->setOutput(str_replace("a href", "a onclick='$(\"#page\").val(this.id); getvips();' id", $pagination->render()));
  }  //function getPagination end

  public function details() {
    $this->data["lng"] = $this->load->language("module/" . $this->mod_name);

    $this->load->model("setting/setting");
    $general_settings = $this->model_setting_setting->getSetting("vip_customer_general");

    $this->load->model("module/" . $this->mod_name);
    
    $customer_data = $this->model_module_vip_customer->getVip($this->request->get["customer_id"]);

    $this->data["current_details"] = $this->model_module_vip_customer->getDetails($this->request->get["customer_id"], "current", $general_settings);
    $this->data["new_details"] = $this->model_module_vip_customer->getDetails($this->request->get["customer_id"], "new", $general_settings);
    $this->data["customer_data"] = $customer_data;
    $this->data["general_settings"] = $general_settings;
    $this->data["vip_levels"] = $this->model_module_vip_customer->getVipLevels();
    $this->data["store_logo"] = $customer_data["store_url"] . "image/" . $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key`='config_logo' AND store_id='" . (int)$customer_data["store_id"] . "'")->row["value"];

    $this->template = "module/vip_customer_detail.tpl";

    $this->response->setOutput($this->render());
  } //function detail end

  public function install() {
    $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer_vip (
      vip_id int(11) NOT NULL AUTO_INCREMENT,
      names text NOT NULL,
      spending decimal(15,4) NOT NULL,
      discount float NOT NULL,
      image text NOT NULL,
      PRIMARY KEY (vip_id)
    ) ENGINE=MyISAM COLLATE=utf8_general_ci;");
    $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer_vip_description (
      vip_id int(11) NOT NULL,
      language_id int(11) NOT NULL,
      name text NOT NULL,
      PRIMARY KEY (vip_id, language_id)
    ) ENGINE=MyISAM COLLATE=utf8_general_ci;");

    $this->load->model("setting/extension");
    $this->model_setting_extension->uninstall("total", $this->mod_name);
    $this->model_setting_extension->install("total", $this->mod_name);

    $this->load->model('setting/setting');
    $this->model_setting_setting->editSetting($this->mod_name, array(
      $this->mod_name . "_status" => 1,
      $this->mod_name . "_sort_order" => 2
    ));

    if (!$this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "customer WHERE Field='vip_id'")->num_rows) $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD vip_id int(11) NOT NULL");
    if (!$this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "customer WHERE Field='vip_total'")->num_rows) $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD vip_total float(15,4) NOT NULL");
    if (!$this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "customer WHERE Field='vip_init'")->num_rows) $this->db->query("ALTER TABLE " . DB_PREFIX . "customer ADD vip_init float(15,4) NOT NULL");

    $filename = $this->mod_name . ".xml";
    $source = "controller/module/$filename";
    $target = "../vqmod/xml/$filename";
    $html = "<span class='success'>Upgrade success!</span>";
    if (file_exists($source)) {
      if (!copy($source, $target)) $html = "<span class='warning'>Cannot copy vqmod file.  Please manually copy admin/controller/module/" . $this->mod_name . ".xml to vqmod/xml directory.</span>";
    }
    $this->response->setOutput($html);
  } //function install end

  public function uninstall() {
    $this->load->model("setting/extension");
    $this->model_setting_extension->uninstall("total", $this->mod_name);

    $vqmod_file = $this->mod_name . ".xml";
    $source = "../vqmod/xml/$vqmod_file";
    if (file_exists($source)) unlink($source);
  } //function uninstall end
    
  } //class end
?>
