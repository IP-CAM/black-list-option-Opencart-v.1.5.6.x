<?php
class ModelTotalVipCustomer extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$lng = $this->load->language("module/vip_customer");

    $this->load->model("module/vip_customer");
    $vip_customer = $this->model_module_vip_customer->getVip($this->customer->getId());
		
		if ($vip_customer) {
			$discount_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				$discount = 0;
				$product_id = $product["product_id"];
				$customer_group_id = $this->customer->getCustomerGroupId();

				if (empty($vip_customer["setting"]["product_discount"])) {
				  $discount_quantity = 0;
  					
  				foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
  					$product_2 = explode(':', $key_2);
  					if ($product_2[0] == $product_id) $discount_quantity += $quantity_2;
  				}
  				
  				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
  				
  				if ($product_discount_query->num_rows) continue;
				}
				
				if (empty($vip_customer["setting"]["product_special"])) {
				  $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
			
				  if ($product_special_query->num_rows) continue;
				}
				
				$discount = $product['total'] / 100 * $vip_customer['discount'];
		
				if ($product['tax_class_id']) {
					$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount), $product['tax_class_id']);
					
					foreach ($tax_rates as $tax_rate) {
						if ($tax_rate['type'] == 'P') {
							$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
						}
					}
				}
				
				$discount_total += $discount;
			}
			
			$total_data[] = array(
				"code"       => "vip_customer",
      	"title"      => str_replace("{vip_discount}", $vip_customer["discount"] . "%", $lng["vip_discount_total"]),
    		"text"       => $this->currency->format(-$discount_total),
      	"value"      => -$discount_total,
				"sort_order" => $this->config->get("vip_customer_sort_order")
    	);

			$total -= $discount_total;
		} 
	} //gettotal end

	public function confirm($order_info, $order_total) {
		$this->load->model("module/vip_customer");
    $this->model_module_vip_customer->setVip($order_info["customer_id"]);
	} //confirm end
	
} //class end
?>