<?php
// text enclosed in curly bracket {} will be replaced by actual value.  For example, {vip_name} will be replaced by Gold

$_["title"] = "VIP Status";

$_["text_login"] = "Please <a href='index.php?route=account/login'>login</a> to view your VIP status.";

$_["text_vip_name"] = "Current VIP Level:";
$_["text_vip_total"] = "Current VIP Order Total:";
$_["text_vip_discount"] = "Current VIP Discount:";
$_["text_vip_discount_end_date"] = "Current VIP Discount End Date:";
$_["text_next_vip_discount_start_date"] = "Start Date For Next Level:";
$_["text_next_vip_name"] = "Next VIP Level:";
$_["text_next_vip_discount"] = "Next VIP Discount:";
$_["text_amount_to_next"] = "Amount To Next VIP Level:";
$_["text_next_cutoff_date"] = "Cutoff Date For Achieving Next Level:";
$_["text_highest_level"] = "Congratulation!  You have reached the highest VIP level.";
$_["text_highest_vip_price"] = "Highest VIP Level Price:";
$_["text_footer_message"] = "* VIP discount only apply to regular price products.";

$_["vip_discount_total"] = "VIP Discount ({vip_discount})";
$_["vip_price"] = "<div style='color:green;'>Your Price: {vip_price}</div>";
$_["vip_price_html"] = "
<style>
.vip_price_table {font-weight:normal; font-size:12px; margin-top:10px;}
.vip_row {display:table-row; background:#F0F0F0;}
.vip_name, .vip_price {display:table-cell; padding:2px 20px; border:1px solid white;}
.vip_title {font-weight:bold; font-size:14px;}
</style>
<div class='vip_price_table'><span class='vip_title'>VIP Pricing:</span> {vip_price_html}</div>
";

$_["email_subject"] = "{store_name} VIP Membership Notification";
$_["email_message"] = "
<div><a href='{store_url}'><img src='{store_logo_url}' alt='{store_name}'></a></div>
<div>
  Dear {customer_name},<br/>
  Your VIP level is now <b>{vip_name}</b> and your discount on regular price product is <b>{vip_discount}</b>.  Please <a href='{store_url}'>visit the store</a> and enjoy your new saving.<br/>
  Best Regards,<br/>
  {store_name}
</div>
";

?>
