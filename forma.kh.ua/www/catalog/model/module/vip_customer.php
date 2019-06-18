<?php
class ModelModuleVipCustomer extends Model {
  public function getVip($customer_id=0) {
    $customer_data = $this->cache->get("vip_customer" . $customer_id . $this->config->get("config_language_id"));

    if (!$customer_data && $customer_id) {
      $this->load->model("setting/setting");

      $setting = $this->model_setting_setting->getSetting("vip_customer_general");

      if (isset($setting["disabled"])) return false;
      
      $query = $this->db->query("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS fullname, c.store_id, c.customer_group_id, c.email, cvd.name AS vip_name, s.name AS store_name, s.url FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_vip cv ON c.vip_id=cv.vip_id LEFT JOIN " . DB_PREFIX . "customer_vip_description cvd ON c.vip_id=cvd.vip_id LEFT JOIN " . DB_PREFIX . "store s ON c.store_id=s.store_id WHERE (ISNULL(cvd.language_id) OR cvd.language_id='" . $this->config->get("config_language_id") . "') AND c.customer_id='" . (int)$customer_id . "'");

      if ($query->num_rows) $current = $query->row;
      else return;

      if (!$current["store_name"]) $current["store_name"] = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `key`='config_name' AND store_id='" . (int)$current["store_id"] . "'")->row["value"];
      if (!$current["url"]) $current["url"] = str_replace("admin/", "", HTTP_SERVER);

      $vip_status = in_array($current["store_id"], $setting["store_id"]) && in_array($current["customer_group_id"], $setting["customer_group_id"]) && isset($setting["order_statuses"]) ? 1 : 0;

      $current_discount_end_date = "";
      switch ($setting["type"]) {
        case "annually":
          $current_discount_end_date = date("Y") . "-12-31 23:59:59";
          break;
        case "semiannually":
          $current_discount_end_date = date("Y") . (date("m") > 6 ? "-12-31 23:59:59" : "-6-30 23:59:59");
          break;
        case "quarterly":
          if (date("m") <= 3) $current_discount_end_date = date("Y") . "-03-31 23:59:59";
          elseif (date("m") <= 6) $current_discount_end_date = date("Y") . "-06-30 23:59:59";
          elseif (date("m") <= 9) $current_discount_end_date = date("Y") . "-09-30 23:59:59";
          elseif (date("m") <= 12) $current_discount_end_date = date("Y") . "-12-31 23:59:59";
          break;
        case "monthly":
          $next_month = date("Y-m", strtotime("next month")) . "-01 00:00:00";
          $current_discount_end_date = date("Y-m-d", strtotime("$next_month") - 1) . " 23:59:59";
      }

      $next_discount_start_date = $current_discount_end_date ? date("Y-m-d", strtotime($current_discount_end_date) + 1) . " 00:00:00" : "";

      $next = $this->db->query("SELECT cv.*, cvd.name FROM " . DB_PREFIX . "customer_vip cv JOIN " . DB_PREFIX . "customer_vip_description cvd ON cv.vip_id= cvd.vip_id WHERE cv.spending > '" . (float)$current["vip_total"] . "' LIMIT 1")->row;

      if (!$next) $next = array(
        "vip_id" => "",
        "name" => "",
        "discount" => "",
        "image" => "",
        "spending" => ""
      );

      $this->load->model("tool/image");

      $customer_data = array(
        "vip_status" => $vip_status,
        "setting" => $setting,
        "customer_id" => $current["customer_id"],
        "fullname" => $current["fullname"],
        "vip_id" => $current["vip_id"],
        "vip_init" => $current["vip_init"],
        "store_id" => $current["store_id"],
        "customer_group_id" => $current["customer_group_id"],
        "name" => $current["vip_name"],
        "discount" => $current["discount"],
        "image" => $this->model_tool_image->resize($current["image"], 100, 100),
        "spending" => $current["spending"],
        "total" => $current["vip_total"],
        "discount_end_date" => $current_discount_end_date,
        "next_id" => $next["vip_id"],
        "next_name" => $next["name"],
        "next_discount" => $next["discount"],
        "next_discount_start_date" => $next_discount_start_date,
        "next_image" => $this->model_tool_image->resize($next["image"], 100, 100),
        "next_spending" => $next["spending"],
        "amount_to_next" => $next["spending"] - $current["vip_total"],
        "email" => $current["email"],
        "store_name" => $current["store_name"],
        "store_url" => $current["url"]
      );

      $this->cache->set("vip_customer" . $customer_id . $this->config->get("config_language_id"), $customer_data);
    }
    return $customer_data;
  } //getVip end

  public function setVip($customer_id=0) {
    if (!$customer_id) return;

    $files = glob(DIR_CACHE . "cache.vip_customer" . $customer_id . "*.*");
		if ($files) {
  		foreach ($files as $file) unlink($file);
  }

    $customer_data = $this->db->query("SELECT customer_group_id, store_id, vip_id, vip_init FROM " . DB_PREFIX . "customer WHERE customer_id=" . (int)$customer_id . "")->row;

    $this->load->model("setting/setting");
    $setting = $this->model_setting_setting->getSetting("vip_customer_general");

    if (!in_array($customer_data["customer_group_id"], $setting["customer_group_id"]) || !in_array($customer_data["store_id"], $setting["store_id"])) {
      $this->db->query("UPDATE " . DB_PREFIX . "customer SET vip_id=0, vip_total=0 WHERE customer_id='" . (int)$customer_id . "'");
      return;
    }
    
    $daterange = $this->getDateRange($setting["type"]);
    $vip_datequery = empty($daterange) ? 1 : "o.date_added BETWEEN '" . $daterange[0] . "' AND '" . $daterange[1] . "'";
    $datequery = empty($daterange) ? 1 : "o.date_added BETWEEN '" . $daterange[2] . "' AND '" . $daterange[3] . "'";

    $orderquery = "ot.code='sub_total'";
    if ($setting["shipping"]) $orderquery .= " OR ot.code='shipping'";
    if ($setting["tax"]) $orderquery .= " OR ot.code='tax'";
    if ($setting["credit"]) $orderquery .= " OR ot.code='credit'";
    if ($setting["reward"]) $orderquery .= " OR ot.code='reward'";
    if ($setting["coupon"]) $orderquery .= " OR ot.code='coupon'";
    
    $orderstatusquery = "o.order_status_id IN (" . implode(",", $setting["order_statuses"]) . ")";

    $vip_ordertotal = $this->db->query("SELECT SUM(ot.value) AS total FROM " . DB_PREFIX . "order_total ot JOIN `" . DB_PREFIX . "order` o ON ot.order_id=o.order_id WHERE o.customer_id='" . (int)$customer_id . "' AND ($orderquery) AND ($vip_datequery) AND ($orderstatusquery)")->row["total"] + $customer_data["vip_init"];

    $ordertotal = $this->db->query("SELECT SUM(ot.value) AS total FROM " . DB_PREFIX . "order_total ot JOIN `" . DB_PREFIX . "order` o ON ot.order_id=o.order_id WHERE o.customer_id='" . (int)$customer_id . "' AND ($orderquery) AND ($datequery) AND ($orderstatusquery)")->row["total"] + $customer_data["vip_init"];

    $this->db->query("UPDATE " . DB_PREFIX . "customer SET vip_id=(SELECT vip_id FROM " . DB_PREFIX . "customer_vip WHERE spending <= '" . (float)$vip_ordertotal . "' ORDER BY spending DESC LIMIT 1), vip_total='" . (float)$ordertotal . "' WHERE customer_id='" . (int)$customer_id . "'");

    $new_customer_data = $this->getVip($customer_id);

    if ($new_customer_data["vip_id"] && $new_customer_data["vip_id"] != $customer_data["vip_id"]) {
      $this->load->model("setting/setting");
      $config = $this->model_setting_setting->getSetting("config");

      $emails = "";
      if ($new_customer_data["setting"]["email_admin"]) $emails .= $config['config_email'] . "," . $config['config_alert_emails'];
      if ($new_customer_data["setting"]["email_customer"]) $emails .= "," . $new_customer_data["email"];

      if ($emails) {
        $this->load->language("module/vip_customer");

        $email_subject = str_replace("{store_name}", $new_customer_data["store_name"], $this->language->get("email_subject"));

        $find = array("{customer_name}", "{vip_name}", "{store_name}", "{store_logo_url}", "{store_url}", "{vip_discount}");
        $replace = array($new_customer_data["fullname"], $new_customer_data["name"], $new_customer_data["store_name"], HTTP_SERVER . "image/" . $config["config_logo"], $new_customer_data["store_url"], $new_customer_data["discount"] . "%");
        $email_message = str_replace($find, $replace, $this->language->get("email_message"));

        $mail = new Mail();
        $mail->protocol = $config['config_mail_protocol'];
        $mail->parameter = $config['config_mail_parameter'];
        $mail->hostname = $config['config_smtp_host'];
        $mail->username = $config['config_smtp_username'];
        $mail->password = $config['config_smtp_password'];
        $mail->port = $config['config_smtp_port'];
        $mail->timeout = $config['config_smtp_timeout'];
        $mail->setFrom($config['config_email']);
        $mail->setSender($config['config_name']);
        $mail->setSubject(html_entity_decode($email_subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($email_message);

        foreach (explode(",", $emails) as $email) {
          if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
            $mail->setTo($email);
            $mail->send();
          }
        }
      }
    }
  } //setVip end

  public function getDateRange($type="") {
    $daterange = array();

    if ($type == "annually") {
      $daterange[0] = date("Y")-1 . "-01-01 00:00:00";
      $daterange[1] = date("Y")-1 . "-12-31 23:59:59";
      $daterange[2] = date("Y") . "-01-01 00:00:00";
      $daterange[3] = date("Y") . "-12-31 23:59:59";
    }
    elseif ($type == "semiannually") {
      if (date("m") > 6) {
        $daterange[0] = date("Y") . "-01-01 00:00:00";
        $daterange[1] = date("Y") . "-06-30 23:59:59";
        $daterange[2] = date("Y") . "-07-01 00:00:00";
        $daterange[3] = date("Y") . "-12-31 23:59:59";
      }
      else {
        $daterange[0] = date("Y")-1 . "-07-01 00:00:00";
        $daterange[1] = date("Y")-1 . "-12-31 23:59:59";
        $daterange[2] = date("Y") . "-01-01 00:00:00";
        $daterange[3] = date("Y") . "-06-30 23:59:59";
      }
    }
    elseif ($type == "monthly") {
      $daterange[0] = date("Y-m-d", strtotime("first day of last month")) . " 00:00:00";
      $daterange[1] = date("Y-m-d", strtotime("last day of last month")) . " 23:59:59";
      $daterange[2] = date("Y-m-d", strtotime("first day of this month")) . " 00:00:00";
      $daterange[3] = date("Y-m-d", strtotime("last day of this month")) . " 23:59:59";
    }
    elseif ($type == "quarterly") {
      $q0 = ceil(date("m")/3);
      if ($q0 == 1) {
        $daterange[0] = date("Y")-1 . "-10-01 00:00:00";
        $daterange[1] = date("Y")-1 . "-12-31 23:59:59";
        $daterange[2] = date("Y") . "-01-01 00:00:00";
        $daterange[3] = date("Y") . "-03-31 23:59:59";
      }
      elseif ($q0 == 2) {
        $daterange[0] = date("Y") . "-01-01 00:00:00";
        $daterange[1] = date("Y") . "-03-31 23:59:59";
        $daterange[2] = date("Y") . "-04-01 00:00:00";
        $daterange[3] = date("Y") . "-06-31 23:59:59";
      }
      elseif ($q0 == 3) {
        $daterange[0] = date("Y") . "-04-01 00:00:00";
        $daterange[1] = date("Y") . "-06-31 23:59:59";
        $daterange[2] = date("Y") . "-07-01 00:00:00";
        $daterange[3] = date("Y") . "-09-31 23:59:59";
      }
      elseif ($q0 == 4) {
        $daterange[0] = date("Y") . "-07-01 00:00:00";
        $daterange[1] = date("Y") . "-09-31 23:59:59";
        $daterange[2] = date("Y") . "-10-01 00:00:00";
        $daterange[3] = date("Y") . "-12-31 23:59:59";
      }
    }

    return $daterange;
  } //getDateRange end

  public function getVipPrices($price=0, $tax_class_id=0) {
    $language_id = $this->config->get("config_language_id");
    $tax = $this->config->get("config_tax");
    $price_format = $this->config->get("price_format");
    $vip_prices = array();

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_vip ORDER BY discount DESC");
    foreach ($query->rows as $vip) {
      if ($price_format) {
        $vip_price = $price * (1 - $vip["discount"] / 100);
        $vip_price = $this->currency->format($this->tax->calculate($vip_price, $tax_class_id, $tax));
      } else {
        $vip_price = $vip["discount"] . "%";
      }
      $vip_name = unserialize($vip["names"]);
      $vip_prices[] = array(
        "name" => $vip_name[$language_id],
        "price" => $vip_price,
        "id" => $vip["vip_id"]
      );
    }
    return $vip_prices;
  } //getVipPrices end

} //class end
?>
