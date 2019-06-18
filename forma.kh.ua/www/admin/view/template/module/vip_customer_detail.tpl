<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo str_replace("{customer_name}", $customer_data["fullname"], $lng["heading_detail_title"]); ?></title>
<style type="text/css">
body {width:680px;}
table {border-collapse:collapse;}
.border th, .border td {border:1px solid grey;}
th, td {padding:2px 10px;}
.right {text-align:right;}
.total td {font-weight:bold; border-top:2px solid black;}
.content {border:1px solid grey; padding:10px; margin:20px 0;}
.heading {font-weight:bold; font-size:large; margin:5px 0;}
</style>
</head>
<body>
<div><a href="<?php echo $customer_data['store_url']; ?>" title="<?php echo $customer_data['store_name']; ?>"><img src="<?php echo $store_logo; ?>" alt="<?php echo $customer_data['store_name']; ?>" /></a></div>
<?php echo date($lng["date_format_long"]); ?>
<h1><?php echo str_replace("{customer_name}", $customer_data["fullname"], $lng["heading_detail_title"]); ?></h1>
<?php if ($customer_data["vip_status"]) { ?>
<div class="content">
  <div class="heading"><?php echo $lng["text_vip_generals"] ?></div>
  <table>
    <tr>
      <td><?php echo $lng["entry_spending_basis"]; ?></td>
      <td><?php echo ucwords($general_settings["type"]); ?></td>
    </tr>
    <tr>
      <td><?php echo $lng["entry_shipping"]; ?></td>
      <td><?php echo ($general_settings["shipping"] ? $lng["text_yes"] : $lng["text_no"]); ?></td>
    </tr>
    <tr>
      <td><?php echo $lng["entry_tax"]; ?></td>
      <td><?php echo ($general_settings["tax"] ? $lng["text_yes"] : $lng["text_no"]); ?></td>
    </tr>
    <tr>
      <td><?php echo $lng["entry_credit"]; ?></td>
      <td><?php echo ($general_settings["credit"] ? $lng["text_yes"] : $lng["text_no"]); ?></td>
    </tr>
    <tr>
      <td><?php echo $lng["entry_reward"]; ?></td>
      <td><?php echo ($general_settings["reward"] ? $lng["text_yes"] : $lng["text_no"]); ?></td>
    </tr>
    <tr>
      <td><?php echo $lng["entry_coupon"]; ?></td>
      <td><?php echo ($general_settings["coupon"] ? $lng["text_yes"] : $lng["text_no"]); ?></td>
    </tr>
  </table>
</div>
<div class="content">
  <div class="heading"><?php echo $lng["text_vip_levels"]; ?></div>
  <table class="border">
    <tr>
      <th><?php echo $lng["column_level_name"]; ?></th>
      <th><?php echo $lng["column_level_spending"]; ?></th>
      <th><?php echo $lng["column_level_discount"]; ?></th>
    </tr>
    <?php
    $lng_id = $this->config->get("config_language_id");
    foreach ($vip_levels as $vip) {
      $vip_name = unserialize($vip["names"]);
      $td = "";
      $td .= "<td>" . $vip_name[$lng_id] . "</td>";
      $td .= "<td class='right'>" . $this->currency->format($vip["spending"]) . "</td>";
      $td .= "<td>" . $vip["discount"] . "</td>";
      echo "<tr>" . $td . "</tr>";
      }
    ?>
  </table>
</div>
<div class="content">
  <div class="heading"><?php echo $lng["text_detail_current"]; ?></div>
  <table class="border">
    <tr>
      <th><?php echo $lng["column_order_id"]; ?></th>
      <th><?php echo $lng["column_order_date"]; ?></th>
      <th><?php echo $lng["column_order_total"]; ?></th>
      <th><?php echo $lng["column_vip_amt"]; ?></th>
      <th><?php echo $lng["column_vip_init"]; ?></th>
      <th><?php echo $lng["column_vip_total"]; ?></th>
      <th><?php echo $lng["column_vip_level"]; ?></th>
    </tr>
    <?php
    if ($current_details) {
      foreach ($current_details as $detail) {
        $td = "";
        $td .= "<td>" . $detail["order_id"] . "</td>";
        $td .= "<td>" . date($lng["date_format_short"], strtotime($detail["order_date"])) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["order_total"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["vip_total"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($customer_data["vip_init"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["grand_vip_total"]) . "</td>";
        $td .= "<td>" . $detail["vip_level"] . "</td>";
        echo "<tr>" . $td . "</tr>";
        }
      echo "<tr class='total'>
        <td colspan='2' class='right'>" . $lng["text_grand_total"] . "</td>
        <td class='right'>" . $this->currency->format($detail["grand_order_total"]) . "</td>
        <td class='right'>" . $this->currency->format($detail["sub_vip_total"]) . "</td>
        <td class='right'>" . $this->currency->format($customer_data["vip_init"]) . "</td>
        <td class='right'>" . $this->currency->format($detail["grand_vip_total"]) . "</td>
        <td>" . $detail["vip_level"] . "</td>
      </tr>";
      }
    ?>
  </table>
</div>
<?php if ($general_settings["type"] != "all") { ?>
<div class="content">
  <div class="heading"><?php echo $lng["text_detail_next"]; ?></div>
  <div><?php echo $lng["text_next_effective_date"] . " " . date($lng["date_format_long"], strtotime($customer_data["next_discount_start_date"])); ?></div>
  <table class="border">
    <tr>
      <th><?php echo $lng["column_order_id"]; ?></th>
      <th><?php echo $lng["column_order_date"]; ?></th>
      <th><?php echo $lng["column_order_total"]; ?></th>
      <th><?php echo $lng["column_vip_amt"]; ?></th>
      <th><?php echo $lng["column_vip_init"]; ?></th>
      <th><?php echo $lng["column_vip_total"]; ?></th>
      <th><?php echo $lng["column_vip_level"]; ?></th>
    </tr>
    <?php
    if ($new_details) {
      foreach ($new_details as $detail) {
        $td = "";
        $td .= "<td>" . $detail["order_id"] . "</td>";
        $td .= "<td>" . date($lng["date_format_short"], strtotime($detail["order_date"])) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["order_total"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["vip_total"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($customer_data["vip_init"]) . "</td>";
        $td .= "<td class='right'>" . $this->currency->format($detail["grand_vip_total"]) . "</td>";
        $td .= "<td>" . $detail["vip_level"] . "</td>";
        echo "<tr>" . $td . "</tr>";
        }
      echo "<tr class='total'>
        <td colspan='2' class='right'>" . $lng["text_grand_total"] . "</td>
        <td class='right'>" . $this->currency->format($detail["grand_order_total"]) . "</td>
        <td class='right'>" . $this->currency->format($detail["sub_vip_total"]) . "</td>
        <td class='right'>" . $this->currency->format($customer_data["vip_init"]) . "</td>
        <td class='right'>" . $this->currency->format($detail["grand_vip_total"]) . "</td>
        <td>" . $detail["vip_level"] . "</td>
      </tr>";
      }
    ?>
  </table>
</div>
<?php } ?>
<?php } else echo $lng["text_vip_na"]; ?>
</body>
</html>
