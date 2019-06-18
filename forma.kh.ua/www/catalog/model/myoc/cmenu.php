<?php
class ModelMyocCmenu extends Model
{
	public function getCmenus($data = array())
	{
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = 0;
		}	

		$sql = "SELECT * FROM " . DB_PREFIX . "myoc_cmenu c LEFT JOIN " . DB_PREFIX . "myoc_cmenu_description cd ON (c.cmenu_id = cd.cmenu_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1'";
		
		if(isset($data['parent_cmenu_id']))
		{
			$sql .= " AND c.parent_cmenu_id = '" . (int)$data['parent_cmenu_id'] . "'";
		}

		if(isset($data['parent_category_id']))
		{
			$sql .= " AND c.parent_category_id = '" . (int)$data['parent_category_id'] . "'";
		}

		$sql .= ' ORDER BY c.sort_order, LCASE(cd.name)';

		$query = $this->db->query($sql);
		$cmenus = array();
		if($query->num_rows)
		{
			foreach($query->rows as $result)
			{
				$customer_groups = unserialize($result['customer_group']);
				$stores = unserialize($result['store']);
				if($stores && in_array($this->config->get('config_store_id'), $stores) && (!$result['login'] || $customer_groups && in_array($customer_group_id, $customer_groups)))
				{
					$cmenus[$result['cmenu_id']] = array(
						'cmenu_id' => $result['cmenu_id'],
						'name' => $result['name'],
						'link' => $result['link'],
						'popup' => $result['popup'],
						'parent_cmenu_id' => $result['parent_cmenu_id'],
						'parent_category_id' => $result['parent_category_id'],
						'column' => $result['column'],
						'sort_order' => $result['sort_order'],
					);
				}
			}
		}
		return $cmenus;
	}
}
?>