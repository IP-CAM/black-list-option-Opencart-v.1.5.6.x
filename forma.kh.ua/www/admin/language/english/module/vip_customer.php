<?php

$_['heading_title'] = 'VIP Customer Program';
$_["text_module"] = "Modules";
$_["text_success"] = "Your changes have been saved!";

$_["text_vip_notification"] = "VIP level for {customer_name} is now {vip_name}";
$_["text_note"] = "Note: To manually assign customer to certain VIP level, edit customer (sales->customers->customers->edit) and set the 'Initial VIP Amount' value to the same value as the 'Qualified Spending' for that level";
$_['error_permission'] = 'Warning: You do not have permission to modify!';
$_["entry_init_vip"] = "Initial VIP Amount:";
$_["help_init_vip"] = "This amount will be added to customer's order total to determine his/her VIP level";
$_["success"] = "Your changes were saved!";
$_["attn_setup"] = "Please setup your General Settings first!";
$_["text_disable_module"] = "Disable Module";

$_["tab_settings"] = "Levels and Modules Settings";
$_["heading_vip_level"] = "VIP Levels";
$_["column_level_name"] = "Name";
$_["column_level_spending"] = "Spending";
$_["column_level_discount"] = "Discount %";
$_["column_level_image"] = "Badge";
$_["column_action"] = "Action";
$_['button_remove'] = 'Remove';
$_['button_add_level'] = 'Add Level';
$_["text_browse"] = "Browse Images";
$_["text_clear"] = "Clear Image";

$_["heading_vip_info"] = "VIP Information Module";
$_['column_layout'] = 'Layout';
$_['column_position'] = 'Position';
$_['column_status'] = 'Status';
$_['column_sort_order'] = 'Sort Order';
$_['text_content_top'] = 'Content Top';
$_['text_content_bottom'] = 'Content Bottom';
$_['text_content_bottom'] = 'Content Bottom';
$_['text_column_left'] = 'Column Left';
$_['text_column_right'] = 'Column Right';

$_["tab_general"] = "General Settings";
$_["help_general"] = "The below settings apply to all VIP levels";
$_["text_spending_basis_all"] = "All";
$_["text_spending_basis_annually"] = "Annually";
$_["text_spending_basis_semiannually"] = "Semi-Annually";
$_["text_spending_basis_quarterly"] = "Quarterly";
$_["text_spending_basis_monthly"] = "Monthly";
$_["entry_spending"] = "Qualified Spending:";
$_["help_spending"] = "Amount customer must meet to qualify for this VIP level";
$_["entry_order_status"] = "Order Statuses:";
$_["help_order_status"] = "Orders with selected order statuses will be included in the calculation of customer spending";
$_["entry_spending_basis"] = "Order Period:";
$_["help_spending_basis"] = "All: Use all orders <br/>Annually: Use order since last year <br/>Semi-Annually: Use orders since last half of the year<br/>Quarterly: Use orders since last quarter (Q1:1-3, Q2:4-6, Q3:7-9, Q4:10-12) <br/>Monthly: Use orders since last month";
$_["entry_shipping"] = "Include Shipping:";
$_["help_shipping"] = "Include shipping cost in the calculation of customer spending (yes would increase order total)";
$_["entry_tax"] = "Include Tax:";
$_["help_tax"] = "Include tax in the calculation of customer spending (yes would increase order total)";
$_["entry_credit"] = "Include Store Credit:";
$_["help_credit"] = "Include store credit in the calculation of customer spending (yes would decrease order total)";
$_["entry_reward"] = "Include Reward:";
$_["help_reward"] = "Include reward point in the calculation of customer spending (yes would decrease order total)";
$_["entry_coupon"] = "Include Coupon:";
$_["help_coupon"] = "Include coupon discount in the calculation of customer spending (yes would decrease order total)";
$_["entry_show_vip_product_price"] = "Show VIP Price:";
$_["help_show_vip_product_price"] = "Show VIP price for each product";
$_["entry_show_price"] = "Show VIP Prices Table:";
$_["help_show_price"] = "Show VIP prices table on product page";
$_["entry_customer_group"] = "Customer Groups:";
$_["help_customer_group"] = "VIP calculation will be applied only to selected customer groups";
$_["entry_store"] = "Stores:";
$_["help_store"] = "VIP calculation will be applied only to selected stores";
$_["entry_email_customer"] = "Email Customer:";
$_["help_email_customer"] = "Email customer when VIP level changes.  Email will not be sent if VIP level is changed to none";
$_["entry_email_admin"] = "Email Admin:";
$_["help_email_admin"] = "Email admin when VIP level changes.  Email will not be sent if VIP level is changed to none";
$_["text_price_format"] = "VIP Price Table Format:";
$_["text_vip_price"] = "VIP Dicounted Price";
$_["text_vip_discount"] = "VIP Discounted Percent";
$_["help_price_format"] = "If product has options, option price is not reflected in the table discounted price";
$_["text_discount_product"] = "Apply VIP discount on discount product";
$_["help_discount_product"] = "Checked to apply VIP discount on discount product (product > discount)";
$_["text_special_product"] = "Apply VIP discount on special product";
$_["help_special_product"] = "Checked to apply VIP discount on special product (product > special)";

$_["tab_data"] = "Customer Data";
$_["column_customer_name"] = "Customer Name";
$_["column_customer_email"] = "Customer Email";
$_["column_customer_level"] = "VIP Level";
$_["column_customer_init"] = "Init. VIP Amount";
$_["column_customer_total"] = "VIP Current Total";

$_["heading_detail_title"] = "{customer_name} VIP Data";
$_["text_detail_current"] = "Data For Current VIP Level";
$_["text_detail_next"] = "Data For Next Period VIP Level";
$_["text_next_effective_date"] = "Effective Date:";
$_["text_vip_generals"] = "VIP Calculation Parameters";
$_["text_vip_levels"] = "VIP Levels";
$_["text_grand_total"] = "Grand Total";
$_["column_order_id"] = "Order ID";
$_["column_order_date"] = "Order Date";
$_["column_order_total"] = "Order Total";
$_["column_vip_total"] = "VIP Total";
$_["column_vip_level"] = "VIP Level";
$_["column_vip_init"] = "VIP Init.";
$_["column_vip_amt"] = "Vip Amt.";
$_["text_vip_na"] = "This customer is not in the store or group enable for VIP.";

$_["email_subject"] = "{store_name} VIP Membership Notification";
$_["email_message"] = "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body>
<div><a href='{store_url}'><img src='{store_logo_url}' alt='{store_name}'></a></div>
<div>
  Dear {customer_name},<br/>
  Your VIP level is now <b>{vip_name}</b> and your discount on regular price product is <b>{vip_discount}</b>.  Please <a href='{store_url}'>visit the store</a> and enjoy your new saving.<br/>
  Best Regards,<br/>
  {store_name}
</div>
</body>
</html>
";

?>
