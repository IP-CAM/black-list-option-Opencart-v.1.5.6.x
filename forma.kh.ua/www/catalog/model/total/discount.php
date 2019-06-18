<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {

		$discount_customer_group_id = (int)$this->config->get('discount_customer_group_id');

		$auth = $this->customer->isLogged() && ($discount_customer_group_id != (int)$this->customer->getCustomerGroupId());

		$notAuth = !$this->customer->isLogged() && ($discount_customer_group_id != (int)$this->config->get('config_customer_group_id'));

		
		if($discount_customer_group_id !== 0 && ($auth || $notAuth)) {
			return;
		}

		$this->load->language('total/discount');

	 
		$sub_total = $this->cart->getSubTotal();

		$perc = 0;
		foreach(explode(',', $this->config->get('discount_totals')) as $data) {
			$data = explode(':', $data);
			if ($data[0] >= $sub_total) {
				if (isset($data[1])) {
					$perc = $data[1];
				}
				break;
			}
		}
		if ($perc == 0) {
			return;
		}
		$discount =  - $sub_total/100 * $perc;
		$total += $discount;
		$total_data[] = array(
			'code'       => 'discount',
			'title'      => sprintf($this->language->get('text_discount'), $perc),
			'text'       => $this->currency->format($discount),
			'value'      => $discount,
			'sort_order' => $this->config->get('discount_sort_order')
		);
	}
}
?>