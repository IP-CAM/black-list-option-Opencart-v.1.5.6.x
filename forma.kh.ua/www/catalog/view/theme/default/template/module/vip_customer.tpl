<style type="text/css">
.vip_customer .vip_info {display:inline-block; vertical-align:text-top;}
.vip_customer .vip_image {display:inline-block; vertical-align:text-top;}
.vip_customer .vip_footer {display:block; font-style:italic; margin-top:10px; font-size:small;}
.vip_customer .bold {font-weight:bold;}
.vip_customer .column_display {text-align:center;}
</style>
<div class="box">
  <div class="box-heading"><?php echo $lng['title']; ?></div>
  <div class="box-content">
    <?php if ($this->customer->isLogged()) { ?>
    <div class="vip_customer">
      <?php if ($position == "content_top" || $position == "content_bottom") { ?>
      <div class="vip_info">
        <table>
          <tr>
            <td><?php echo $lng['text_vip_name']; ?></td>
            <td class="bold"><?php echo $customer_data["name"]; ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_vip_discount']; ?></td>
            <td class="bold"><?php if ($customer_data["discount"]) echo $customer_data["discount"] . "%"; ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_vip_discount_end_date']; ?></td>
            <td><?php if ($customer_data["discount"] && $customer_data["discount_end_date"]) echo date($lng["date_format_long"], strtotime($customer_data["discount_end_date"])); ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_vip_total']; ?></td>
            <td><?php echo $this->currency->format($customer_data["total"]); ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_next_vip_name']; ?></td>
            <td><?php echo ($customer_data["next_name"] ? $customer_data["next_name"] : $lng["text_highest_level"]); ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_next_vip_discount']; ?></td>
            <td><?php if ($customer_data["next_discount"]) echo $customer_data["next_discount"] . "%"; ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_amount_to_next']; ?></td>
            <td><?php if ($customer_data["amount_to_next"] > 0) echo $this->currency->format($customer_data["amount_to_next"]); ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_next_cutoff_date']; ?></td>
            <td><?php if ($customer_data["discount_end_date"]) echo date($lng["date_format_long"], strtotime($customer_data["discount_end_date"])); ?></td>
          </tr>
          <tr>
            <td><?php echo $lng['text_next_vip_discount_start_date']; ?></td>
            <td><?php if ($customer_data["next_discount_start_date"]) echo date($lng["date_format_long"], strtotime($customer_data["next_discount_start_date"])); ?></td>
          </tr>
        </table>
      </div>
      <div class="vip_image"><img src="<?php echo $customer_data['image']; ?>" alt="" ></div>
      <div class="vip_footer"><?php echo $lng["text_footer_message"]; ?></div>
      <?php } else { ?>
      <div class="column_display">
        <div><img src="<?php echo $customer_data['image']; ?>" alt="" ></div>
        <div class="bold"><?php if ($customer_data["discount"]) echo $customer_data["name"] . " - " . $customer_data["discount"] . "%"; ?></div>
      </div>
      <?php } ?>
    </div><!--class vip_customer end-->
    <?php } else echo $lng["text_login"]; ?>
  </div><!--class box-content end-->
</div><!--class box end-->