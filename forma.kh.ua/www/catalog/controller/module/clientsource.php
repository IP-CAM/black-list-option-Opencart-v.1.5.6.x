<?php
class ControllerModuleClientsource extends Controller {
    public function index() {

        if (isset($this->request->post['clientsource_module'])) {
            $this->data['modules'] = $this->request->post['clientsource_module'];
        } elseif ($this->config->get('clientsource_module')) {
            $this->data['modules'] = $this->config->get('clientsource_module');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/clientsource.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/clientsource.tpl';
        } else {
            $this->template = 'default/template/module/clientsource.tpl';
        }
        $this->response->setOutput($this->render());
    }
}
?>