<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <!-- added -->
  <?php if (isset($customer_group_id)) { ?>
    <a class="colorbox fancybox" href="/index.php?route=information/information/info&information_id=9" alt="Условия продаж">
	<img style="margin-top: -20px;" src = "image/custom-<?php echo $customer_group_id; ?>.png" />
	</a>
	<script>
		$(".colorbox").colorbox({
            width: 560,
            height: 560
        });
	</script>
		<?php if ($customer_group_id == 1) { ?>
			<br/>Вам нужно сделать покупку с 10% скидкой для перехода в следующую группу.<br/>
		<?php } ?>
		<?php if ($customer_group_id == 3) { ?>
			<br/>Вам еще нужно сделать <b><?php echo $discount_count; ?></b> покупки с 10% скидкой для перехода в следующую группу.<br/>
		<?php } ?>
	<?php } ?>
	<!-- added -->
  <h2><br/><?php echo $text_my_account; ?></h2>
  <div class="content">

    <ul>
      <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
      <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
      <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
    </ul>
  </div>
  <h2><?php echo $text_my_orders; ?></h2>
  <div class="content">
    <ul>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
<!--      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <?php if ($reward) { ?>
      <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
      <?php } ?>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>-->
    </ul>
  </div>
  <h2><?php echo $text_my_newsletter; ?></h2>
  <div class="content">
    <ul>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 