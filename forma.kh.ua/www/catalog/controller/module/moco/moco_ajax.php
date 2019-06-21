<?php
class ControllerModuleMocoMocoAjax extends Controller {  
    
    
    private $dataIP = [];
    
    public function __construct($registry) {
        
        parent::__construct($registry);
        
        $Info_IP = $this->getInfo_IP();
        
        if(isset($Info_IP->geoplugin_request)){            
            $this->dataIP["ip"] = $Info_IP->geoplugin_request;
        } else {
            $this->dataIP["ip"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_countryName)){            
            $this->dataIP["ip_countryName"] = $Info_IP->geoplugin_countryName;
        } else {
            $this->dataIP["ip_countryName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_regionName)){
            $this->dataIP["ip_regionName"] = $Info_IP->geoplugin_regionName;
        } else {
            $this->dataIP["ip_regionName"] = "---";
        }
        
        if(isset($Info_IP->geoplugin_city)){
            $this->dataIP["ip_city"] = $Info_IP->geoplugin_city;
        } else {
            $this->dataIP["ip_city"] = "---";
        }
        
        
 
        $this->dataIP['text'][] = 'Додаткова інформація визначається по IP(можливі неточності)';
        $this->dataIP['text'][] = 'Країна: '.$this->dataIP["ip_countryName"];
        $this->dataIP['text'][] = 'Регіон: '.$this->dataIP["ip_regionName"];
        $this->dataIP['text'][] = 'Місто: '.$this->dataIP["ip_city"];
        
    }
    
    public function IP_text() {
        $this->dataIP['text'][] = 'Додаткова інформація визначається по IP(можливі неточності)';
        $this->dataIP['text'][] = 'Країна: '.$this->dataIP["ip_countryName"];
        $this->dataIP['text'][] = 'Регіон: '.$this->dataIP["ip_regionName"];
        $this->dataIP['text'][] = 'Місто: '.$this->dataIP["ip_city"];
    }
    
    public function index() {}
    
    
    public function push_phone() {
        
        $data = $this->data;
        
        if (isset($this->request->post['url'])) {
            $data["url"] = $this->request->post['url'];
        }else{
            echo 'not url';
            die;
        }
        
        if (isset($this->request->post['title'])) {
            $data["title"] = $this->request->post['title'];
        }else{
            echo 'not title';
            die;
        }
        
        if (isset($this->request->post['telephone'])) {
            $data["telephone"] = $this->request->post['telephone'];
        }else{
            echo 'not telephone';
            die;
        }
        
        if (isset($this->request->post['name'])) {
            $data["name"] = $this->request->post['name'];
        }else{
            $data["name"] = "👽";
        }
        
        $text = "📞Клієнт запитує консультацію!!! 😱👇"
                . "\n\n"
                . "ім'я: {$data["name"]}\n"
                . "телефон: {$data["telephone"]}"
                . "\n\n";
                
        $text .= "[".$data["title"]."](".$data["url"].")";
        
        if($this->dataIP["ip_countryName"] == "Ukraine") $this->dataIP["ip_countryName"] .= "🇺🇦";
                
        $text .= "\n\n"
                . "Додаткова інформація визначається по IP(можливі неточності)"
                . "\n"
                . "Країна: {$this->dataIP["ip_countryName"]}"
                . "\n"
                . "Регіон: {$this->dataIP["ip_regionName"]}"
                . "\n"
                . "Місто: {$this->dataIP["ip_city"]}"
                . "\n";

        $status = $this->getChild('module/moco/ms/telegram/push', $text);
        
        return $status;
        
    }
    
    public function push_review($data) {
        
        $rating ='';
        
        for ($i = 1; $i <= $data['rating']; $i++) {
            $rating .= '🌟';
        }
        
        $text = "📞Test Клієнт написав відгуки!!! 😱👇\n\n"
            . "[Редагувати/Опублікувати - відгук](https://scandi.top/admin/index.php?route=catalog/review/edit&review_id={$data['review_id']})"
            . "\n"
            . "[в товарі](https://scandi.top/index.php?route=product/product&product_id={$data['product_id']})\n"        
            . "ім'я: {$data["name"]}\n"
            . "рейтинг: {$rating}\n"
            . "текст:\n"
            . "{$data['text']}\n\n";
        
        $text .= join($this->dataIP['text'], '\n');
        
        $this->load->controller('extension/module/moco/ms/telegram/push', $text);
    }
    
    public function push_order($order_id) {
        
        if(!$order_id) return;
        
        $this->load->model('checkout/order');
        $data = $this->getOrder($order_id);
        
        
        
        $data["products"] = $this->getOrderProducts($order_id);
        
        $data["totals"] = $this->getOrderTotals($order_id);
        
        $text = "📞Клієнт оформив замовлення!!! 😱👇"
            . "\n\n"
            . "[Номер замовлення: {$data["order_id"]}](https://forma.kh.ua/admin/index.php?route=sale/order/info&order_id={$data["order_id"]})\n"
            . "\n"
            . "Клієнт"
            . "\n"
            . "**ім'я:** {$data["firstname"]}\n"
            . "**прізвище:** {$data["lastname"]}\n"
            . "**email:** {$data["email"]}\n"
            . "**телефон:** {$data["telephone"]}\n"
            . "\n"
            //. "Платіж"
            //. "\n"
            . "Товари"
            . "\n";
        $i = 1;
        //$totals = 0;
        foreach ($data["products"] as $product) {
            $text .= "{$i}. [{$product['name']}]({$this->url->link('product/product', 'product_id=' . $product['product_id'])}) - {$product['quantity']}x{$this->currency->format($product['price'], $this->config->get('config_currency'))}={$this->currency->format($product['total'], $this->config->get('config_currency'))}\n";
            //$totals +=(float)$product['total'];
            $i++;
        }        
        
        $totals_ = "Разом по товару:{$data["totals"][0]['text']}";
        
        $l = 93-mb_strlen($totals_);
        
        $totals = substr("                                                                                            ",0, $l);
        
        $text .= $totals.$totals_;
 
        $this->getChild('module/moco/ms/telegram/push', $text);
        
    }
    
    public function getInfo_IP() {
        $ip = "";
        if($_SERVER['REMOTE_ADDR']){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];

            if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
            elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
            else $ip = @$_SERVER['REMOTE_ADDR'];
        }
        
        return $this->getInfo_IP_A1($ip);
        
    }
    
    public function getInfo_IP_A1($IP) {        
        return @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$IP));        
    }
    
    
    public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$reward = 0;
			
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}			
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
                'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
                'email'                   => $order_query->row['email'],
                'telephone'               => $order_query->row['telephone'],
                
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
                
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_company_id'      => $order_query->row['payment_company_id'],
				'payment_tax_id'          => $order_query->row['payment_tax_id'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],				
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],			
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
			);
		} else {
			return false;
		}
	}
    
    public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
    
    public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}
}