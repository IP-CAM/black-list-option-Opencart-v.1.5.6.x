<?php
class ControllerCommonJsonLdWebsite extends Controller {
    
	public function index() {
        $data = [];
        if ($this->request->server['HTTPS']) {
			$this->data['url'] = HTTPS_SERVER;
		} else {
			$this->data['url'] = HTTP_SERVER;
		}
        $this->data["name"] =  $this->config->get('config_name');
        $this->data["description"] =  $this->config->get('config_meta_description');
        
        $this->data["SearchAction"] =  $this->data['url']."/index.php?route=product/search&filter_name=";
        
        $this->data["inLanguages"][] = [
            'name' => 'Russian',
            'code' => 'ru',
            'image'=> '/catalog/language/ru-ru/ru-ru.png'
        ];
        
        $this->data["inLanguages"][] = [
            'name' => 'Українська',
            'code' => 'ua',
            'image'=> '/catalog/language/uk-ua/uk-ua.png'
        ];
        
        $this->template = 'default/template/common/json_ld/website.tpl';
		
		$this->render();
    }
}
