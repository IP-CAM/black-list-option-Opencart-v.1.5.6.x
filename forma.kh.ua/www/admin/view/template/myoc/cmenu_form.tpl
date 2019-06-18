<?php echo $header; ?>
<style type="text/css">
select
{
  margin: 3px;
}
</style>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    	<table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="cmenu_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($cmenu_description[$language['language_id']]) ? $cmenu_description[$language['language_id']]['name'] : ''; ?>" size="40" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_link; ?></td>
            <td>
              <input type="text" id="link" name="link" value="<?php echo $link; ?>" size="100" />
              <br />
              <?php if (isset($error_link) && $error_link) { ?>
              <span class="error"><?php echo $error_link; ?></span><br />
              <?php } ?>
              <table>
                <tr>
                  <td><strong><?php echo $entry_route; ?></strong></td>
                  <td><select id="route">
                      <option value="http://"><?php echo $text_select; ?></option>
                      <?php foreach ($routes as $val => $route) { ?>
                      <option value="<?php echo $route; ?>"><?php echo $val; ?></option>
                      <?php } ?>
                    </select></td>
                </tr>
                <tr>
                  <td><strong><?php echo $entry_information; ?></strong></td>
                  <td><select id="information">
                      <option value="http://"><?php echo $text_select; ?></option>
                      <?php foreach ($informations as $val => $route) { ?>
                      <option value="<?php echo $route; ?>"><?php echo $val; ?></option>
                      <?php } ?>
                    </select></td>
                </tr>
                <tr>
                  <td><strong><?php echo $entry_category; ?></strong></td>
                  <td>
                    <select id="category">
                      <option value="http://"><?php echo $text_select; ?></option>
                      <?php foreach ($categories as $category) { ?>
                      <option value="<?php echo $http_catalog; ?>index.php?route=product/category&path=<?php echo $category['path']; ?>"><?php echo $category['name']; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><strong><?php echo $entry_product; ?></strong></td>
                  <td>
                    <select id="product-category">
                      <option value="http://"><?php echo $text_select; ?></option>
                      <?php foreach ($categories as $category) { ?>
                      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                      <?php } ?>
                    </select>
                    <br />
                    <select id="product"><option value="http://"><?php echo $text_select; ?></option>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_popup; ?></td>
            <td><input type="radio" name="popup" id="popup1" value="1"<?php if ($popup) { ?> checked="checked"<?php } ?> /> <label for="popup1"><?php echo $text_yes; ?></label>
              <input type="radio" name="popup" id="popup0" value="0"<?php if (!$popup) { ?> checked="checked"<?php } ?> /> <label for="popup0"><?php echo $text_no; ?></label></td>
          </tr>
          <tr>
            <td><?php echo $entry_parent_cmenu; ?></td>
            <td>
              <select name="parent_cmenu_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($cmenus as $cmenu) { ?>
                <?php if($cmenu['cmenu_id'] != $cmenu_id) { ?>
                  <option value="<?php echo $cmenu['cmenu_id']; ?>"<?php if ($cmenu['cmenu_id'] == $parent_cmenu_id) { ?> selected="selected"<?php } ?>><?php echo $cmenu['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_parent_category; ?></td>
            <td>
              <select name="parent_category_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($categories as $category) { ?>
                  <option value="<?php echo $category['category_id']; ?>"<?php if ($category['category_id'] == $parent_category_id) { ?> selected="selected"<?php } ?>><?php echo $category['name']; ?></option>
                  <?php } ?>
                </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_column; ?></td>
            <td><input type="text" name="column" value="<?php echo $column; ?>" size="3" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_login; ?></td>
            <td><input type="radio" name="login" id="login1" value="1"<?php if ($login) { ?> checked="checked"<?php } ?> /> <label for="login1"><?php echo $text_yes; ?></label>
              <input type="radio" name="login" id="login0" value="0"<?php if (!$login) { ?> checked="checked"<?php } ?> /> <label for="login0"><?php echo $text_no; ?></label></td>
          </tr>
          <tr>
            <td><?php echo $entry_customer; ?></td>
            <td><div class="scrollbox">
              <?php $class = 'even'; ?>
              <?php foreach ($customer_groups as $customer_group) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (!empty($cmenu_customer_groups) && in_array($customer_group['customer_group_id'], $cmenu_customer_groups)) { ?>
                <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php echo $customer_group['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php echo $customer_group['name']; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
          </tr>
          <tr>
            <td><?php echo $entry_store; ?></td>
            <td><div class="scrollbox">
              <?php $class = 'even'; ?>
              <div class="<?php echo $class; ?>">
                <?php if ((!empty($cmenu_stores) && in_array('0', $cmenu_stores)) || empty($cmenu_description)) { ?>
                <input type="checkbox" name="store[]" value="0" checked="checked" />
                <?php echo $text_default; ?>
                <?php } else { ?>
                <input type="checkbox" name="store[]" value="0" />
                <?php echo $text_default; ?>
                <?php } ?>
              </div>
              <?php foreach ($stores as $store) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (!empty($cmenu_stores) && in_array($store['store_id'], $cmenu_stores)) { ?>
                <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                <?php echo $store['name']; ?>
                <?php } else { ?>
                <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" />
                <?php echo $store['name']; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="3" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <option value="1"<?php if ($status) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                <option value="0"<?php if (!$status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
              </select></td>
          </tr>
        </table>
    </form>
    <div style="font-size:11px;color:#666;">
      <?php echo $myoc_copyright; ?>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("select#route").change(function() {
      $("input#link").val($(this).val());
    });
    $("select#information").change(function() {
      $("input#link").val($(this).val());
    });
    $("select#category").change(function() {
      $("input#link").val($(this).val());
    });
    var category_products = [];
    <?php foreach ($category_products as $category_id => $products) { ?>
    category_products[<?php echo $category_id; ?>] = [];
    <?php foreach ($products as $product_id => $product) { ?>
    category_products[<?php echo $category_id; ?>][<?php echo $product_id; ?>] = "<?php echo $product; ?>";
    <?php } ?>
    <?php } ?>
    $("select#product-category").change(function() {
      $("select#product option").remove();
      $('<option value="http://"><?php echo $text_select; ?></option>').appendTo($("select#product"));
      for(var product_id in category_products[$(this).val()])
      {
        $('<option value="<?php echo $http_catalog; ?>index.php?route=product/product&product_id=' + product_id + '">' + category_products[$(this).val()][product_id] + '</option>').appendTo($("select#product"));
      }
      $("select#product").change(function() {
        $("input#link").val($(this).val());
      });
    });
});
</script>
<?php echo $footer; ?>