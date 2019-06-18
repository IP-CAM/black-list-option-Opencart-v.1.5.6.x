<?php
class ModelAccountLoginza2 extends Model
{
    public function checkNew($data)
    {
        $check = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer
								   WHERE loginza2_identity='".$this->db->escape($data['identity'])."'");

        if( empty($check->rows) )
        {
            return false;
        }
        else
        {
            $upd = '';

            if( !empty($data['firstname']) )
            {
                $upd .= " firstname = '".$this->db->escape($data['firstname']).' '.$data['lastname']."', ";
            }
            /*
            if( !empty($data['lastname']) )
            {
                $upd .= " lastname = '".$this->db->escape($data['lastname'])."', ";
            }
            */

            if( !empty($data['telephone']) )
            {
                $upd .= " telephone = '".$this->db->escape($data['telephone'])."', ";
            }

            if( !empty($data['email']) )
            {
                $upd .= " email = '".$this->db->escape($data['email'])."', ";
            }

            $this->db->query("UPDATE " . DB_PREFIX . "customer
							  SET
							  ". $upd ."

								loginza2_data = '".$this->db->escape($data['data'])."',
								ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'
							  WHERE
							    loginza2_identity = '" .$this->db->escape($data['identity']) . "'");

            return $check->row['customer_id'];
        }
    }

    public function addCustomer($data)
    {
        /* kin insert metka: c1 */
        $fields = array("firstname", "lastname", "email", "telephone", "company", "postcode",
            "country", "zone", "city", "address_1" );

        foreach($fields as $field)
        {
            if( !isset($data[$field]) )
            {
                $data[$field] = '';
            }
        }
        /* end kin metka: c1 */

        $customer_group_id = $this->config->get('config_customer_group_id');

        $this->load->model('account/customer_group');

        $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer
							  SET
								firstname = '".$this->db->escape($data['firstname'].' '.$data['lastname'])."',
								email = '".$this->db->escape($data['email'])."',
								telephone = '".$this->db->escape($data['telephone'])."',
							    loginza2_identity = '" .$this->db->escape($data['identity']) . "',
								loginza2_provider = '".$this->db->escape($data['provider']) ."',
								loginza2_data = '".$this->db->escape($data['data'])."',
								ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
								approved = '" . (int)!$customer_group_info['approval'] . "',
								salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "',
								customer_group_id = '" . (int)$customer_group_id . "',
								status = '1',
								date_added = NOW()");

        $customer_id = $this->db->getLastId();

        /* kin insert metka: c1 */
        //	lastname = '" . $this->db->escape($data['lastname']) . "',

        $this->db->query("INSERT INTO " . DB_PREFIX . "address
		SET
			customer_id = '" . (int)$customer_id . "',
			firstname = '" . $this->db->escape($data['firstname'].' '.$data['lastname']) . "',
			company = '" . $this->db->escape($data['company']) . "',
			address_1 = '" . $this->db->escape($data['address_1']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "',
			city = '" . $this->db->escape($data['city']) . "',
			zone_id = '" . (int)$data['zone'] . "',
			country_id = '" . (int)$data['country'] . "'");

        $address_id = $this->db->getLastId();

        $this->db->query("UPDATE " . DB_PREFIX . "customer
						  SET address_id = '" . (int)$address_id . "'
						  WHERE customer_id = '" . (int)$customer_id . "'");
        /* end kin metka: c1 */

        return $customer_id;
    }
}

?>