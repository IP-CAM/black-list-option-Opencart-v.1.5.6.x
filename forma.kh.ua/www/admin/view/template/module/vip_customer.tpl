<?php
echo $header;
$this->load->model("tool/image");
$no_image = $this->model_tool_image->resize("no_image.jpg", 100, 100);
?>
<style type="text/css">
.checkbox_entry {display:inline-block; margin-right:10px;}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb["separator"]; ?><a href="<?php echo $breadcrumb["href"]; ?>"><?php echo $breadcrumb["text"]; ?></a>
    <?php } ?>
  </div>
  <div id="message"><?php if (!$vip_general["type"]) echo "<div class='attention'>" . $lng["attn_setup"] . "</div>"; ?></div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <a href="mailto:trile7@gmail.com?Subject=<?php echo urlencode($heading_title); ?>" style="float: right; margin-top: 10px;">Support Email</a>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
        <a href="#tab_settings"><?php echo $lng["tab_settings"]; ?></a>
        <a href="#tab_general"><?php echo $lng["tab_general"]; ?></a>
        <a href="#tab_data" onclick="getvips();"><?php echo $lng["tab_data"]; ?></a>
      </div>
      <div id="tab_settings">
        <h2><?php echo $lng["heading_vip_level"]; ?></h2>
        <table id="vip_level" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $lng["column_level_name"]; ?></td>
              <td class="left"><?php echo $lng["column_level_spending"]; ?></td>
              <td class="left"><?php echo $lng["column_level_discount"]; ?></td>
              <td class="left"><?php echo $lng["column_level_image"]; ?></td>
              <td><?php echo $lng["column_action"]; ?></td>
            </tr>
          </thead>
          <?php foreach ($vip_levels as $vip) { ?>
          <tbody>
            <tr>
              <td class="left">
                <?php
                $vip_name = unserialize($vip["names"]);
                $vip_id = $vip["vip_id"];
                foreach ($languages as $l) {
                  $lng_id = $l["language_id"];
                  echo "<input type='text' name='vip_level[$vip_id][name][$lng_id]' value='$vip_name[$lng_id]' />";
                  echo "<img src='view/image/flags/$l[image]' title='$l[name]' align='top' /><br/>";
                  }
                ?>
              </td>
              <td class="left"><input type="text" name="vip_level[<?php echo $vip_id; ?>][spending]" value="<?php echo $vip['spending']; ?>" /></td>
              <td class="left"><input type="text" name="vip_level[<?php echo $vip_id; ?>][discount]" value="<?php echo $vip['discount']; ?>" /></td>
              <td class="left">
                <img src="<?php echo $this->model_tool_image->resize($vip['image'], 100, 100); ?>" alt="" id="thumb-<?php echo $vip_id; ?>" /><br/>
                <input type="hidden" name="vip_level[<?php echo $vip_id; ?>][image]" value="<?php echo $vip['image']; ?>" id="image-<?php echo $vip_id; ?>" />
                <a onclick="image_upload('image-<?php echo $vip_id; ?>', 'thumb-<?php echo $vip_id; ?>');"><?php echo $lng["text_browse"]; ?></a> |
                <a onclick="$('#thumb-<?php echo $vip_id; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image-<?php echo $vip_id; ?>').attr('value', '');"><?php echo $lng["text_clear"]; ?></a>
              </td>
              <td class="left"><a onclick="$(this).parents('tr').remove();" class="button"><?php echo $lng["button_remove"]; ?></a></td>
            </tr>
          </tbody>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4" class="left">
                <a onclick="saveviplevels();" id="saveviplevels" class="button"><?php echo $lng["button_save"]; ?></a>
              </td>
              <td class="left"><a onclick="addVipLevel();" class="button"><?php echo $lng["button_add_level"]; ?></a></td>
            </tr>
          </tfoot>
        </table>
        <h2><?php echo $lng["heading_vip_info"]; ?></h2>
        <table id="vip_module" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $lng["column_layout"]; ?></td>
                <td class="left"><?php echo $lng["column_position"]; ?></td>
                <td class="left"><?php echo $lng["column_status"]; ?></td>
                <td class="right"><?php echo $lng["column_sort_order"]; ?></td>
                <td><?php echo $lng["column_action"]; ?></td>
              </tr>
            </thead>
            <?php $module_row = 0; ?>
            <?php foreach ($modules as $module) { ?>
            <tbody id="module-row<?php echo $module_row; ?>">
              <tr>
                <td class="left"><select name="vip_customer_module[<?php echo $module_row; ?>][layout_id]">
                  <?php
                  foreach ($layouts as $layout) {
                    echo "<option value='$layout[layout_id]' " . ($layout['layout_id'] == $module['layout_id'] ? "selected" : "") . ">$layout[name]</option>";
                    }
                  ?>
                </select></td>
                <td class="left"><select name="vip_customer_module[<?php echo $module_row; ?>][position]">
                  <?php
                  echo "<option value='content_top' " . ($module['position'] == 'content_top' ? "selected" : "") . ">" . $lng["text_content_top"] . "</option>";
                  echo "<option value='content_bottom' " . ($module['position'] == 'content_bottom' ? "selected" : "") . ">" . $lng["text_content_bottom"] . "</option>";
                  echo "<option value='column_left' " . ($module['position'] == 'column_left' ? "selected" : "") . ">" . $lng["text_column_left"] . "</option>";
                  echo "<option value='column_right' " . ($module['position'] == 'column_right' ? "selected" : "") . ">" . $lng["text_column_right"] . "</option>";
                  ?>
                </select></td>
                <td class="left"><select name="vip_customer_module[<?php echo $module_row; ?>][status]">
                  <?php
                  echo "<option value='1' " . ($module['status'] ? "selected" : "") . ">" . $lng["text_enabled"] . "</option>";
                  echo "<option value='0' " . (!$module['status'] ? "selected" : "") . ">" . $lng["text_disabled"] . "</option>";
                  ?>
                </select></td>
                <td class="right"><input type="text" name="vip_customer_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $lng["button_remove"]; ?></a></td>
              </tr>
            </tbody>
            <?php $module_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="4" class="left">
                  <a onclick="savevipmodules();" class="button" id ="savevipmodules"><?php echo $lng["button_save"]; ?></a>
                </td>
                <td class="left"><a onclick="addModule();" class="button"><?php echo $lng["button_add_module"]; ?></a></td>
              </tr>
            </tfoot>
          </table>
      </div><!--id tab_setting end-->
      <div id="tab_general">  
        <div class="help"><?php echo $lng["help_general"]; ?></div>
        <table class="form" id="vip_customer_general">
          <tr>
            <td><?php echo $lng["entry_customer_group"]; ?></td>
            <td><?php
              foreach ($customer_groups as $cg) {
                $checked = isset($vip_general["customer_group_id"]) && in_array($cg["customer_group_id"], $vip_general["customer_group_id"]) ? "checked" : "";
                echo "<div class='checkbox_entry'><input type='checkbox' name='vip_customer_general[customer_group_id][]' id='cg-" . $cg["customer_group_id"] . "' value='" . $cg["customer_group_id"] . "' $checked/><label for='cg-" . $cg["customer_group_id"] . "'>" . $cg["name"] . "</label></div>";
                }
              ?></td>
            <td><div class="help"><?php echo $lng["help_customer_group"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_store"]; ?></td>
            <td><?php
              foreach ($stores as $s) {
                $checked = isset($vip_general["store_id"]) && in_array($s["store_id"], $vip_general["store_id"]) ? "checked" : "";
                echo "<div class='checkbox_entry'><input type='checkbox' name='vip_customer_general[store_id][]' id='s-" . $s["store_id"] . "' value='" . $s["store_id"] . "' $checked/><label for='s-" . $s["store_id"] . "'>" . $s["name"] . "</label></div>";
                }
              ?></td>
            <td><div class="help"><?php echo $lng["help_store"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_spending_basis"]; ?></td>
            <td>
              <select name="vip_customer_general[type]">
                <option value="all" <?php echo ($vip_general["type"] == "all" ? "selected" : ""); ?>><?php echo $lng["text_spending_basis_all"]; ?></option>
                <option value="annually" <?php echo ($vip_general["type"] == "annually" ? "selected" : ""); ?>><?php echo $lng["text_spending_basis_annually"]; ?></option>
                <option value="semiannually" <?php echo ($vip_general["type"] == "semiannually" ? "selected" : ""); ?>><?php echo $lng["text_spending_basis_semiannually"]; ?></option>
                <option value="quarterly" <?php echo ($vip_general["type"] == "quarterly" ? "selected" : ""); ?>><?php echo $lng["text_spending_basis_quarterly"]; ?></option>
                <option value="monthly" <?php echo ($vip_general["type"] == "monthly" ? "selected" : ""); ?>><?php echo $lng["text_spending_basis_monthly"]; ?></option>
              </select>
            </td>
            <td><div class="help"><?php echo $lng["help_spending_basis"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_order_status"]; ?></td>
            <td><?php
            foreach ($order_statuses as $os) {
              $checked = isset($vip_general["order_statuses"]) && in_array($os["order_status_id"], $vip_general["order_statuses"]) ? "checked" : "";
              echo "<div class='checkbox_entry'><input type='checkbox' name='vip_customer_general[order_statuses][]' id='os-" . $os["order_status_id"] . "' value='" . $os["order_status_id"] . "' $checked /><label for='os-" . $os["order_status_id"] . "'>" . $os["name"] . "</label></div>";
              }
            ?></td>
            <td><div class="help"><?php echo $lng["help_order_status"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_shipping"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[shipping]" value="1" <?php if (!empty($vip_general["shipping"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[shipping]" value="0" <?php if (empty($vip_general["shipping"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_shipping"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_tax"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[tax]" value="1" <?php if (!empty($vip_general["tax"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[tax]" value="0" <?php if (empty($vip_general["tax"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_tax"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_credit"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[credit]" value="1" <?php if (!empty($vip_general["credit"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[credit]" value="0" <?php if (empty($vip_general["credit"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_credit"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_reward"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[reward]" value="1" <?php if (!empty($vip_general["reward"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[reward]" value="0" <?php if (empty($vip_general["reward"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_reward"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_coupon"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[coupon]" value="1" <?php if (!empty($vip_general["coupon"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[coupon]" value="0" <?php if (empty($vip_general["coupon"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_coupon"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_show_vip_product_price"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[show_product_price]" value="1" <?php if (!empty($vip_general["show_product_price"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[show_product_price]" value="0" <?php if (empty($vip_general["show_product_price"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_show_vip_product_price"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_show_price"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[show_price]" value="1" <?php if (!empty($vip_general["show_price"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[show_price]" value="0" <?php if (empty($vip_general["show_price"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_show_price"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["text_price_format"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[price_format]" value="1" <?php if (!empty($vip_general["price_format"])) echo "checked"; ?>/><?php echo $lng["text_vip_price"]; ?>
              <input type="radio" name="vip_customer_general[price_format]" value="0" <?php if (empty($vip_general["price_format"])) echo "checked"; ?>/><?php echo $lng["text_vip_discount"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_price_format"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_email_customer"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[email_customer]" value="1" <?php if (!empty($vip_general["email_customer"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[email_customer]" value="0" <?php if (empty($vip_general["email_customer"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_email_customer"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["entry_email_admin"]; ?></td>
            <td>
              <input type="radio" name="vip_customer_general[email_admin]" value="1" <?php if (!empty($vip_general["email_admin"])) echo "checked"; ?>/><?php echo $lng["text_yes"]; ?>
              <input type="radio" name="vip_customer_general[email_admin]" value="0" <?php if (empty($vip_general["email_admin"])) echo "checked"; ?>/><?php echo $lng["text_no"]; ?>
            </td>
            <td><div class="help"><?php echo $lng["help_email_admin"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["text_discount_product"]; ?></td>
            <td><input type="checkbox" name="vip_customer_general[product_discount]" value="1" <?php if (isset($vip_general["product_discount"])) echo "checked"; ?> /></td>
            <td><div class="help"><?php echo $lng["help_discount_product"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["text_special_product"]; ?></td>
            <td><input type="checkbox" name="vip_customer_general[product_special]" value="1" <?php if (isset($vip_general["product_special"])) echo "checked"; ?> /></td>
            <td><div class="help"><?php echo $lng["help_special_product"]; ?></div></td>
          </tr>
          <tr>
            <td><?php echo $lng["text_disable_module"]; ?></td>
            <td><input type="checkbox" name="vip_customer_general[disabled]" value="1" <?php if (isset($vip_general["disabled"])) echo "checked"; ?> /></td>
          </tr>
          <tr>
            <td colspan="3"><a onclick="savevipgeneral();" class="button" id ="savevipgeneral"><?php echo $lng["button_save"]; ?></a></td>
          </tr>
        </table>
      </div><!--tab_general end-->
      <div id="tab_data">
        <table class="list" >
          <thead>
            <tr>
              <td class="left"><?php echo "<a onclick='sortdata(this);' id='sort_name' class='asc'>" . $lng["column_customer_name"] . "</a>"; ?></td>
              <td class="left"><?php echo "<a onclick='sortdata(this);' id='sort_email'>" . $lng["column_customer_email"] . "</a>"; ?></td>
              <td class="left"><?php echo "<a onclick='sortdata(this);' id='sort_level'>" . $lng["column_customer_level"] . "</a>"; ?></td>
              <td class="right"><?php echo "<a onclick='sortdata(this);' id='sort_init'>" . $lng["column_customer_init"] . "</a>"; ?></td>
              <td class="right"><?php echo "<a onclick='sortdata(this);' id='sort_total'>" . $lng["column_customer_total"] . "</a>"; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td><input type="text" name="filter_name" /></td>
              <td><input type="text" name="filter_email" /></td>
              <td><select name="filter_level">
                <option value="*"></option>
                <?php
                $lng_id = $this->config->get("config_language_id");
                foreach ($vip_levels as $vip) {
                  $vip_name = unserialize($vip["names"]);
                  echo "<option value='" . $vip["vip_id"] . "'>" . $vip_name[$lng_id] . "</option>";
                  }
                ?>
              </select></td>
              <td class="right"><select name="filter_init">
                <option value="*"></option>
                <option value="1"><?php echo $lng["text_yes"]; ?></option>
                <option value="0"><?php echo $lng["text_no"]; ?></option>
              </select></td>
              <td class="right">
                <select name="filter_total_mode">
                  <option value="greaterthan">&gt;</option>
                  <option value="lessthan">&lt;</option>
                  <option value="equal">=</option>
                </select>
                <input type="text" name="filter_total" />
              </td>
              <td>
                <a class="button" id="filter" onclick="getvips();"><?php echo $lng["button_filter"]; ?></a>
                <a class="button" id="remove_filter" onclick="$('.filter input').val(''); $('.filter option').prop('selected', false); getvips();"><?php echo $lng["button_remove"] . " " . $lng["button_filter"]; ?></a>
                <input type="hidden" name="sort" id="sort" value="sort_name"/>
                <input type="hidden" name="order" id="order" value="asc"/>
                <input type="hidden" name="page" id="page" value="1"/>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="pagination"></div>
      </div><!--id tab_data end-->
      <div class="help" style="margin-top:10px;"><?php echo $lng["text_note"]; ?></div>
    </div><!--class content end-->
  </div><!--class box end-->
  <div id="upgrade">
    Upgrade:
    <ol>
      <li><a href="http://tlecoding.gurleegirl.com/<?php echo $mod_name; ?>" target="_blank">Check for new upgrade</a></li>
      <li>Download, unzip, and upload file to opencart installation root</li>
      <li><a onclick="upgrade();">Complete upgrade process</a></li>
    </ol>
    <a href="http://tlecoding.gurleegirl.com" target="_blank">My other extensions</a>
  </div>
</div><!--id content end-->
<script type="text/javascript">
function sortdata(obj) {
  $("#sort").val($(obj).attr("id"));
  if ($("#order").val() == "asc") $("#order").val("desc");
  else $("#order").val("asc");
  $(".asc, .desc").removeClass();
  $(obj).addClass($("#order").val());
  getvips();
  }

function getvips() {
  $.ajax({
    url: "index.php?route=module/vip_customer/getvips&token=<?php echo $token; ?>",
    data: $(".filter input, .filter select"),
    type: "post",
    dataType: "html",
    beforeSend: function() {
      $("#remove_filter").after("<span class='wait'><img src='view/image/loading.gif' alt='' /></span>");
      },
    complete: function() {
      $(".wait").remove();
      },
    success: function(html) {
      $(".data").remove();
      $(".filter").after(html);
      getpagination();
      }
    });
  }

function getpagination() {
  $.ajax({
    url: "index.php?route=module/vip_customer/getpagination&token=<?php echo $token; ?>",
    data: $(".filter input, .filter select"),
    type: "post",
    dataType: "html",
    success: function(html) {
      $(".pagination").html(html);
      }
    });
  }

var level_row = <?php echo (empty($vip_id) ? 1 : $vip_id + 1); ?>;
function addVipLevel() {
  html = '<tr>';
  html += '  <td class="left">';
  <?php foreach ($languages as $l) { ?>
  html += '    <input type="text" name="vip_level[' + level_row + '][name][<?php echo $l["language_id"]; ?>]" />';
  html += '    <img src="view/image/flags/<?php echo $l["image"]; ?>" title="<?php echo $l["name"]; ?>" align="top" /><br/>';
  <?php } ?>
  html += '  </td>';
  html += '  <td class="left"><input type="text" name="vip_level[' + level_row + '][spending]" /></td>';
  html += '  <td class="left"><input type="text" name="vip_level[' + level_row + '][discount]" /></td>';
  html += '  <td class="left">';
  html += '    <img src="<?php echo $no_image; ?>" alt="" id="thumb-' + level_row + '" /><br/>';
  html += '    <input type="hidden" name="vip_level[' + level_row + '][image]" id="image-' + level_row + '" />';
  html += '    <a onclick="image_upload(\'image-' + level_row + '\', \'thumb-' + level_row + '\');"><?php echo $lng["text_browse"]; ?></a> | ';
  html += '    <a onclick="$(\'#thumb-' + level_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image-' + level_row + '\').attr(\'value\', \'\');"><?php echo $lng["text_clear"]; ?></a>';
  html += '  </td>';
  html += '  <td class="left"><a onclick="$(this).parents(\'tr\').remove();" class="button"><?php echo $lng["button_remove"]; ?></a></td>';
  html += '</tr>';

  $("#vip_level tfoot").before(html);

  level_row++;
  }

function saveviplevels() {
  if ($("#message").html()) {
    alert("<?php echo $lng['attn_setup']; ?>");
    return false;
    }
  $("#saveviplevels").siblings(".wait, .success").remove();
  $.ajax({
    url: "index.php?route=module/vip_customer/saveviplevels&token=<?php echo $token; ?>",
    data: $("#vip_level input"),
    type: "post",
    beforeSend: function() {
      $("#saveviplevels").after("<span class='wait' style='margin-left:5px'><img src='view/image/loading.gif' alt='' /></span>");
      },
    success: function(html) {
      $("#saveviplevels").siblings(".wait").remove();
      $("#saveviplevels").after(html);
      window.setTimeout(function() {
        $("#saveviplevels").siblings(".success").remove();
        }, 10000);
      }
    });
  }

function savevipmodules() {
  $("#savevipmodules").siblings(".wait, .success").remove();
  $.ajax({
    url: "index.php?route=module/vip_customer/savevipmodules&token=<?php echo $token; ?>",
    data: $("#vip_module input, #vip_module select"),
    type: "post",
    beforeSend: function() {
      $("#savevipmodules").after("<span class='wait' style='margin-left:5px'> <img src='view/image/loading.gif' alt='' /></span>");
      },
    success: function(html) {
      $("#savevipmodules").siblings(".wait").remove();
      $("#savevipmodules").after(html);
      window.setTimeout(function() {
        $("#savevipmodules").siblings(".success").remove();
        }, 10000);
      }
    });
  }

function savevipgeneral() {
  $("#savevipgeneral").siblings(".wait, .success").remove();
  $.ajax({
    url: "index.php?route=module/vip_customer/savevipgeneral&token=<?php echo $token; ?>",
    data: $("#vip_customer_general [type=checkbox]:checked, #vip_customer_general [type=radio]:checked, #vip_customer_general [type=text], #vip_customer_general select"),
    type: "post",
    beforeSend: function() {
      $("#savevipgeneral").after("<span class='wait' style='margin-left:5px'> <img src='view/image/loading.gif' alt='' /></span>");
      },
    success: function(html) {
      $("#savevipgeneral").siblings(".wait").remove();
      $("#message").html("");
      $("#savevipgeneral").after(html);
      window.setTimeout(function() {
        $("#savevipgeneral").siblings(".success").remove();
        }, 10000);
      }
    });
  }

var module_row = <?php echo $module_row; ?>;
function addModule() {
  html  = '<tbody id="module-row' + module_row + '">';
  html += '  <tr>';
  html += '    <td class="left"><select name="vip_customer_module[' + module_row + '][layout_id]">';
  <?php foreach ($layouts as $layout) { ?>
  html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
  <?php } ?>
  html += '    </select></td>';
  html += '    <td class="left"><select name="vip_customer_module[' + module_row + '][position]">';
  html += '      <option value="content_top"><?php echo $lng["text_content_top"]; ?></option>';
  html += '      <option value="content_bottom"><?php echo $lng["text_content_bottom"]; ?></option>';
  html += '      <option value="column_left"><?php echo $lng["text_column_left"]; ?></option>';
  html += '      <option value="column_right"><?php echo $lng["text_column_right"]; ?></option>';
  html += '    </select></td>';
  html += '    <td class="left"><select name="vip_customer_module[' + module_row + '][status]">';
  html += '      <option value="1" selected="selected"><?php echo $lng["text_enabled"]; ?></option>';
  html += '      <option value="0"><?php echo $lng["text_disabled"]; ?></option>';
  html += '    </select></td>';
  html += '    <td class="right"><input type="text" name="vip_customer_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
  html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $lng["button_remove"]; ?></a></td>';
  html += '  </tr>';
  html += '</tbody>';

  $('#vip_module tfoot').before(html);

  module_row++;
  }

function image_upload(field, thumb) {
  $('#dialog').remove();

  $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

  $('#dialog').dialog({
    title: 'Image Manager',
    close: function (event, ui) {
      if ($('#' + field).attr('value')) {
        $.ajax({
          url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
          dataType: 'text',
          success: function(data) {
            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
          }
        });
      }
    },
    bigframe: false,
    width: 800,
    height: 400,
    resizable: false,
    modal: false
  });
};

function upgrade() {
  $("#upgrade").find(".success, .warning").remove();
  $.get("index.php?route=module/<?php echo $mod_name; ?>/install&token=<?php echo $token; ?>", function(html) {
    $("#upgrade").append(html);
  });
}

$(function() {
  $('#tabs a').tabs();
});
</script>
<?php echo $footer; ?>
