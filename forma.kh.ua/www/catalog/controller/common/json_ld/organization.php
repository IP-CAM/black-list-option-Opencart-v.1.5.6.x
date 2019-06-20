<?php
class ControllerCommonJsonLdOrganization extends Controller {
    //https://schema.org/Organization
	public function index($data_) {
        
        if ($this->request->server['HTTPS']) {
			$this->data['url'] = HTTPS_SERVER;
		} else {
			$this->data['url'] = HTTP_SERVER;
		}
        
        $this->data["name"] =  $this->config->get('config_name');
        $this->data["email"] =  $this->config->get('config_mail_smtp_username');
        
        //Официальное название организации
        $data["legalName"] =  "";
        
        
        
        $this->data["logo"] =  HTTPS_IMAGE.$this->config->get('config_logo');
        //Физический адрес элемента.
        
        $data["address"] =  "";
        //
        
        $data["areaServed"] =  "";
                
        $data["contactPoint"] = [];
        
        /*
        Friday - П'ятниця
        Monday - Понеділок
        PublicHolidays - Святкові дні
        Saturday - Субота
        Sunday - Неділя
        Thursday - Четвер
        Tuesday - Вівторок
        Wednesday - Середа
        */
        
        
        //"[http://schema.org/Monday,'http://schema.org/Tuesday','http://schema.org/Wednesday','http://schema.org/Thursday','http://schema.org/Friday']"
        $data["contactPoints"][] = [
            "telephone" => "",
            "contactType" => "customer service",
            "areaServed" => "UA",
            "openingHoursSpecification" =>[
                [
                    'opens' => '09:30:00',
                    'closes' => '19:00:00',
                    "dayOfWeek" => "http://schema.org/Monday,http://schema.org/Tuesday,http://schema.org/Wednesday,http://schema.org/Thursday,http://schema.org/Friday"
                ]
            ]
            
        ];
        
        $this->template = 'default/template/common/json_ld/organization.tpl';
		
		$this->render();
    }
}