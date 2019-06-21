<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" style="padding-left:235px;"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="product-info">
    <?php if ($thumb || $images) { ?>
    <div class="left">
      <?php if ($images && $images_option) { ?>
      <div class="image">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox" style="display:none;" rel="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
      <script type="text/javascript">
        $('.image a:first-child').css('display', 'block');
      </script>
      <?php } else  if ($thumb) { ?>
      <div class="image <?php if ($special) { ?>special<?php } ?>" ><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
      <?php if ($images && !$images_option) { ?>
        <div class="image-additional">
          <?php foreach ($images as $image) { ?>
          <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
          <?php } ?>
        </div>
      <?php } ?>
      <?php } ?>
    </div>
    <?php } ?>
    <div class="right">
      <div class="description">
        <?php if ($manufacturer) { ?>
        <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br />
        <?php } ?>
  <!-- <span><?php // echo $text_model; ?></span> <?php // echo $model; ?><br /> -->
        <?php if ($reward) { ?>
        <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br />
        <?php } ?>
        <!--
          <?php if ($upc) { ?>
            <span><? echo $text_dimensions ?></span> <?php echo $upc; ?><br />
          <?php } ?>
        -->
		 <?php if ($jan) { ?>
            <span><? echo $text_weight ?></span> <?php echo $jan; ?><br />
          <?php } ?>
		 <?php if ($location) { ?>
            <span><? echo $text_amount ?></span> <?php echo $location; ?><br />
          <?php } ?>

        <span><?php echo $text_stock; ?></span> <?php echo $stock; ?></div>
        <?php if ($price) { ?>
        <div class="price" data-price="<?php echo $priceSourse; ?>" data-currency="<? echo $currency['symbol_right'];?>" data-pc="<? echo $text_pc ?>">
            <span>
            <?php if (!$special) { ?>
                <?php echo $price; ?>/<?php echo $text_pc; ?>
            <?php } else { ?>
            <span class="price-old"><?php echo $price; ?>/<?php echo $text_pc; ?></span> <span class="price-new"><?php echo $special; ?>/<?php echo $text_pc; ?></span>
            <?php } ?>
            </span>
            <br />
            <?php if ($tax) { ?>
            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br />
            <?php } ?>
            <?php if ($points) { ?>
            <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />
            <?php } ?>
            <?php if ($discounts) { ?>
            <br />
            <div class="discount">
              <?php foreach ($discounts as $discount) { ?>
              <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?>/<?php echo $text_pc; ?><br />
              <?php } ?>
            </div>
            <?php } ?>
            <?php if ($customer_group_id == 3) { ?>
            <span class="foryou"><a title="Ваши преимущества" class="colorbox" rel="table" href="image/custom-<?php echo $customer_group_id; ?>.png">Только для вас</a></span>
            <?php } ?>
            <?php if ($customer_group_id == 4) { ?>
            <span class="foryou"><a title="Ваши преимущества" class="colorbox" rel="table" href="image/custom-<?php echo $customer_group_id; ?>.png">Только для вас</a></span>
            <?php } ?>
        </div>
      

      <?php } ?>
      <?php if ($options) { ?>
      <div class="options">
        <?php foreach ($options as $option) { ?>
          <?php if ($option['type'] == 'select') { ?>
          <div class="category-product-option category-product-option-<?php echo $option['product_option_id']; ?>" id="option-<?php echo $option['product_option_id']; ?>">
            <span class="title">
              <?php echo $option['name']; ?>:
            </span>

            <?php $option_checked = "checked"; ?>
            <?php
            if (count($option['option_value']) >=5) {
              ?>
              <select class="op_proid" name="option[<?php echo $option['product_option_id']; ?>]" >
                <?php
                foreach ($option['option_value'] as $option_value) { ?>
                   <option value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" price="<?php echo $option_value['price']; ?>" price-prefix="<?php echo $option_value['price_prefix']; ?>" <?php echo $option_checked; $option_checked=""; ?> image-num="<?php echo $option_value['image_num']; ?>"/>
                   <label><?php echo $option_value['name']; ?></label>
                 </option>
               <?php } ?>
              </select>
              <?php
            }
            else {
               foreach ($option['option_value'] as $option_value) { ?>
                <span>
                  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" price="<?php echo $option_value['price']; ?>" price-prefix="<?php echo $option_value['price_prefix']; ?>" <?php echo $option_checked; $option_checked=""; ?> image-num="<?php echo $option_value['image_num']; ?>"/>
                  <label><?php echo $option_value['name']; ?></label>
                </span>
              <?php } ?>
            <?php } ?>
          </div>

          <?php } ?>
          <?php if ($option['type'] == 'radio') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
              <?php if ($option_value['price']) { ?>
              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
              <?php } ?>
            </label>
            <br />
            <?php } ?>
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'checkbox') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
              <?php if ($option_value['price']) { ?>
              (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
              <?php } ?>
            </label>
            <br />
            <?php } ?>
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'image') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <table class="option-image">
              <?php foreach ($option['option_value'] as $option_value) { ?>
              <tr>
                <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label></td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'text') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'textarea') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'file') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
            <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'date') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'datetime') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
          </div>
          <br />
          <?php } ?>
          <?php if ($option['type'] == 'time') { ?>
          <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
          </div>
          <br />
          <?php } ?>
      <?php } ?>
      </div>
      <?php } ?>
      <div class="cart" style="font-size:20px;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;">
        <div><?php echo $text_qty; ?><?php if ($sku) { ?>, <?php echo $sku; ?><?php } ?>:
            <div class="quant-wrap">
				<input type="button" id="minusQtythis" value="" class="minus-q" style="vertical-align: middle;position: relative;top: 0px;"/>
				<input type="text" name="quantity" id="quantity" size="2" min="<?php echo $minmin2; ?>" value="<?php echo $minmin2; ?>" style="vertical-align: middle;top: 16px;position: relative;" />
				<input type="button" class="plus-q" id="plusQtythis" value="" style="vertical-align: middle;position: relative;top: -2px;"/>
			</div>
<span class="unit"><?php if ($this->config->get('config_display_sku') && $sku) { echo $sku; } ?></span>


          <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
          &nbsp;
          <input type="button" value="<?php echo $button_cart; ?>" id="button-cart" class="button" />
        </div>

      </div>
      <?php if ($review_status) { ?>
      <div class="review">
        <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<!--        <a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a>-->
        </div>
        <div class="share">
         <script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
<div class="pluso" data-background="transparent" data-options="big,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,email"></div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <div class="tabs">
    <div class="tab-content">
      <h2 style="margin-bottom:10pt;"><?php echo $tab_description; ?></h2>
      <div id="tab-description" style="margin-bottom:20pt;"><?php echo $description; ?></div>
      <?php if ($attribute_groups) { ?>
      <div id="tab-attribute">
        <table class="attribute">
          <?php foreach ($attribute_groups as $attribute_group) { ?>
          <thead>
            <tr>
              <td colspan="2"><?php echo $attribute_group['name']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
            <tr>
              <td><?php echo $attribute['name']; ?></td>
              <td><?php echo $attribute['text']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
          <?php } ?>
        </table>
      </div>
      <?php } ?>
    </div>
      <?php if ($review_status) { ?>
      <div id="tab-review" class="tab-content">
        <h2 style="margin-bottom:10pt;"><?php echo $tab_review; ?></h2>
        <div id="review"></div>
        <h2 id="review-title"><?php echo $text_write; ?></h2>
        <input type="hidden" name="title" value="review" />
        <b><?php echo $entry_name; ?></b><br />
        <input type="text" name="name" value="<?php if ($logged) { ?><?php echo $text_username; ?><?php } ?>" />
        <br />
        <br />
        <div class="rating">
                <label class="entry-rating">Оценка:</label>
                <div class="radio-div" style="margin-left:45px;">
                    <input class="radio-star" type="radio" name="rating" value="1">
                    <input class="radio-star" type="radio" name="rating" value="2">
                    <input class="radio-star" type="radio" name="rating" value="3">
                    <input class="radio-star" type="radio" name="rating" value="4">
                    <input class="radio-star" type="radio" name="rating" value="5">
                </div>
            <div class="star-div"><span class="icstars-0"></span></div>
        </div>
        <br/><br/>
        <b><?php echo $entry_review; ?></b>
        <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
        
        <br />
        <b><?php echo $entry_captcha; ?></b><br />
        <input type="text" name="captcha" value="" />
        <br />
        <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
        <br />
        <div class="buttons">
          <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
        </div>
      </div>
      <?php } ?>
  </div>
  <style>
.review-list .author{margin:0!important;}
.review-list .text{margin-bottom: 5px;}
#tab-review .minus {padding:0 0 8px 22px;background-image:url(image/minus.png);background-repeat: no-repeat;}
#tab-review .plus {padding:0 0 8px 22px;background-image:url(image/plus.png);background-repeat: no-repeat;}
#tab-review input[type='text']{height:17px;}
#tab-review input[type='text'], textarea{color: #333;box-shadow: inset 0px 2px 8px rgba(32, 74, 96, 0.2), 0px 0px 3px white;-moz-box-shadow: inset 0px 2px 8px hsla(200, 50%, 25%, 0.2), 0px 0px 3px white;-webkit-box-shadow: inset 0px 2px 8px rgba(32, 74, 96, 0.2), 0px 0px 3px white;-webkit-transition: all 200ms;-moz-transition: all 200ms;-ms-transition: all 200ms;-o-transition: all 200ms;transition: all 200ms;padding:3px !important;margin-top:2px;margin-bottom:2px;}
#tab-review input:focus, textarea:focus {box-shadow: inset 0px 2px 8px rgba(255, 255, 255, 0), 0px 0px 5px #209FDF;-moz-box-shadow: inset 0px 2px 8px hsla(0, 100%, 100%, 0), 0px 0px 5px hsl(200, 75%, 50%);-webkit-box-shadow: inset 0px 2px 8px rgba(255, 255, 255, 0), 0px 0px 5px #209FDF;background-color: #FFF;outline: none;border-color: rgba(255, 255, 255, 0);}
#tab-review .entry-b{display:block;float:left;width:150px;padding:10px 0 10px 0;}
#tab-review textarea{height:108px;min-height:108px;min-width:306px;max-width:540px;margin-left:40px;}
#tab-review textarea:focus,input:focus{outline:none;}
#tab-review #captcha{margin-top:2px;margin-left:-4px;}
#tab-review > div .radio-div {width: 77px;float: left;margin-left: 5px;}
#tab-review > div .radio-div input[type=radio] {position:relative;margin:0 0 0 -4px;padding:0;width:16px;height: 17px;opacity:0;z-index:2;cursor:pointer;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter: alpha(opacity=0);}
#tab-review .star-div{width:77px;height:17px;float:left;margin:1px 0 0 -84px;}
#tab-review .entry-rating{cursor:default;display:block;float:left;width:70px;padding:4px 0 4px 0;font-weight:bold;}
.star-div span{width:77px;height:17px;display:inline-block;background: url(image/stars.png) no-repeat;}
span.icstars-0{background-position:0 0}
span.icstars-1{background-position:0 -16px}
span.icstars-2{background-position:0 -32px}
span.icstars-3{background-position:0 -48px}
span.icstars-4{background-position:0 -64px}
span.icstars-5{background-position:0 -80px}
</style>
<script>
    jQuery('.radio-star').hover(
            function(){
                var stars = jQuery(this).val();
                jQuery('.star-div').html('<span class="icstars-'+ stars +'"></span>')
            },
            function(){
                var start = jQuery('input:radio[name=rating]:checked').val()
                if(typeof  start == 'undefined' ){start = 0; } 
                jQuery('.star-div').html('<span class="icstars-'+ start +'"></span>')
            })
    jQuery('.radio-star').click(function(){
        jQuery('.radio-star').each(function(){
            jQuery(this).attr( 'checked', false )
        })
        jQuery(this).attr( 'checked', true )
        jQuery(this).each(function(){
            if(jQuery(this).attr("checked")=="checked"){
                var s = jQuery(this).val();
                //alert (s);
                jQuery('.star-div').stop().html('<span class="icstars-'+ s +'"></span>')
            }})})
</script>
  <div style="clear:both;"></div>

  <?php if ($tags) { ?>
  <div class="tags">
    <?php for ($i = 0; $i < count($tags); $i++) { ?>
    <?php if ($i < (count($tags) - 1)) { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
    <?php } else { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?>
  <?php if ($products) { ?>
<div class="box">
  <div class="box-heading"><?php /* echo $tab_related; */ ?>Рекомендуемые товары</div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php
        } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php
            } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php
            } ?>
        </div>
        <?php
        } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php
        } ?>
        <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
      </div>
      <?php
    } ?>
    </div>
  </div>
</div>
<?php
} ?>
<?php echo $footer; ?>
  </div>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	overlayClose: true,
	opacity: 0.5
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').bind('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
			}

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#cart-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		}
	});
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);

		$('.error').remove();

		if (json['success']) {
			alert(json['success']);

			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
		}

		if (json['error']) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
		}

		$('.loading').remove();
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');

	$('#review').load(this.href);

	$('#review').fadeIn('slow');

	return false;
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('#tab-review input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('#tab-review textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('#tab-review input[name=\'rating\']:checked').val() ? $('#tab-review input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('#tab-review input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}

			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');

				$('#tab-review input[name=\'name\']').val('');
				$('#tab-review textarea[name=\'text\']').val('');
				$('#tab-review input[name=\'rating\']:checked').attr('checked', '');
				$('#tab-review input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script>

<script type="text/javascript"><!--
$(function(){
    $("#quantity").parent().children().css("vertical-align","middle")
});
function btnminus(a){document.getElementById("product_buy_quantity").value>a?document.getElementById("product_buy_quantity").value--:document.getElementById("product_buy_quantity").value=a;recalculateprice();}
function btnplus(){document.getElementById("product_buy_quantity").value++;recalculateprice();};
//--></script>

<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}

$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script>
    $(document).ready(function(){


        var h = ($('#menu > ul > li > a.active + div').height())+20;
        if( h > 0 ){
            $('#column-left').css({
                'padding-top': h+'px'
            });
        };


    });
</script>
<script type="text/javascript">
function updateImages (updater) {
    var images = null;
    var image_num = 0;
    var input = $('input[name="' + updater + '"]:checked');
    images = $(input.parents()[4]).find('.image');
    if (images.children().length !== 1) {
        image_num = Number(input.attr('image-num')) + 1;
        images.find('a').css('display', 'none');
        images.find('a:nth-child(' + image_num + ')').css('display', 'block');
    }
}
console.log('product_id=<? echo $product_id;?>');
</script>


<style type="text/css">
.product-info .review .share {
  line-height: 1.5;
}

.at4-icon, .addthis_default_style .at4-icon {
  width: 25px;
  height: 25px;
  line-height: 25px;
  background-size: 25px auto !important;
}

.at4-icon.aticon-compact {
  margin-right: 1pt;
}

.tab-content {
  border-top: 1px solid #DDD;
}

.pagination {
  padding-top: 0;
}
</style>


<script type="text/javascript">
$(document).ready(productparam_refreshEvent());	
</script>