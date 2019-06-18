<?php
class ControllerModuleVipCustomer extends Controller {
  protected function index($setting) {
    $this->load->model('module/vip_customer');
    $customer_data = $this->model_module_vip_customer->getVip($this->customer->getId());

    if ($customer_data["vip_status"]) {
      $this->data["customer_data"] = $customer_data;

      $this->data["lng"] = $this->load->language("module/vip_customer");

      $this->data["position"] = $setting["position"];
      
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/vip_customer.tpl')) $this->template = $this->config->get('config_template') . '/template/module/vip_customer.tpl';
      else $this->template = 'default/template/module/vip_customer.tpl';
  
      $this->render();
    }
  } //function index end
} //class end
?>
