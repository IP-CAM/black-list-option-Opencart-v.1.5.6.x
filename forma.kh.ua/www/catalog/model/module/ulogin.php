<?php
class ModelModuleUlogin extends Model {
    function check_identity($identity) {
        $query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE identity = '". $this->db->escape($identity) ."'");

        if ($query->num_rows) {
            return $query->row['customer_id'];
        } else {
            return false;
        }
    }
        
        public function addAddress($data, $customer_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '".(int)$data['zone_id']."', country_id = '".(int)$data['country_id']."'");
        
        $address_id = $this->db->getLastId();
        
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
        
        echo $this->customer->getId();
        return $address_id;
    }
        
    public function add_customer($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer (identity, network, profile, firstname, lastname, email, telephone, newsletter, customer_group_id, password, status, date_added, approved) VALUES ('" . $this->db->escape($data['identity']) . "', '" . $this->db->escape($data['network']) . "', '" . $this->db->escape($data['profile']) . "', '" . $this->db->escape($data['firstname']) . "', '" . $this->db->escape($data['lastname']) . "', '" . $this->db->escape($data['email']) . "', '" . $this->db->escape($data['telephone']) . "', '1', '" . (int)$data['customer_group_id'] . "', '" . $this->db->escape(md5($data['password'])) . "', '1', NOW(), '1')");

        return $this->db->getLastId();
    }
    
    public function login($customer_id) {
        $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "' AND status = '1'");
        
        
        if ($customer_query->num_rows) {
            $this->session->data['customer_id'] = $customer_query->row['customer_id'];    
            
            if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
                $cart = unserialize($customer_query->row['cart']);
                
                foreach ($cart as $key => $value) {
                    if (!array_key_exists($key, $this->session->data['cart'])) {
                        $this->session->data['cart'][$key] = $value;
                    } else {
                        $this->session->data['cart'][$key] += $value;
                    }
                }            
            }

            if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
                if (!isset($this->session->data['wishlist'])) {
                    $this->session->data['wishlist'] = array();
                }
                                
                $wishlist = unserialize($customer_query->row['wishlist']);
            
                foreach ($wishlist as $product_id) {
                    if (!in_array($product_id, $this->session->data['wishlist'])) {
                        $this->session->data['wishlist'][] = $product_id;
                    }
                }            
            }
            
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$customer_query->row['customer_id'] . "'");
      
              return TRUE;
        } else {
              return FALSE;
        }
      }
      
      public function get_country_id($country){
          $country_query = $this->db->query("SELECT country_id FROM " . DB_PREFIX . "country WHERE name LIKE '" . $this->db->escape($country) . "' LIMIT 1");
          return $country_query->row;
      }
}
?>
