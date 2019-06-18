<?php  
class ControllerModuleCurrency extends Controller {
	public function index() {
		if (isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);
            $this->updateCurrencies();
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
   		}
		
		$this->language->load('module/currency');
		
    	$this->data['text_currency'] = $this->language->get('text_currency');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
		
		$this->data['action'] = $this->url->link('module/currency', '', $connection);
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
		
		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home','',$connection);
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	
						
			$this->data['redirect'] = $this->url->link($route, $url, $connection);
		}	

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/currency.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/currency.tpl';
		} else {
			$this->template = 'default/template/module/currency.tpl';
		}
		
		$this->render();
	}

    public function updateCurrencies($force = false) {
        $valute = $this->getValute();

        if($valute['USD']>0 && $valute['USD']<100){
            $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '".$this->db->escape($valute['USD'])."', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = 'USD'");
        }

        if($valute['RUR']>0 && $valute['RUR']<100){
            $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '".$this->db->escape($valute['RUR'])."', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = 'RUR'");
        }

        $this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");

    }

    public function getValute(){
        $url='https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        $results = json_decode($response);
        $currency=array(
            'USD' => false,
            'RUR' => false,
        );
        foreach ($results as $result) {
            if($result->ccy=='RUR'){
                $currency['RUR']=1/$result->sale;
            }elseif($result->ccy=='USD'){
                $currency['USD']=1/$result->sale;
            }
        }
        return $currency;
    }

}
?>