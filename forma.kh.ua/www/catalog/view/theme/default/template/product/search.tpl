<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <!--<b><?php echo $text_critea; ?></b>
  <div class="content">
    <p><?php echo $entry_search; ?>
      <?php if ($filter_name) { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
      <?php } ?>
      <select name="filter_category_id">
        <option value="0"><?php echo $text_category; ?></option>
        <?php foreach ($categories as $category_1) { ?>
        <?php if ($category_1['category_id'] == $filter_category_id) { ?>
        <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
        <?php } ?>
        <?php foreach ($category_1['children'] as $category_2) { ?>
        <?php if ($category_2['category_id'] == $filter_category_id) { ?>
        <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        <?php } ?>
        <?php foreach ($category_2['children'] as $category_3) { ?>
        <?php if ($category_3['category_id'] == $filter_category_id) { ?>
        <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>-->
      <!--<?php if ($filter_sub_category) { ?>
      <input type="checkbox" name="filter_sub_category" value="1" id="sub_category" checked="checked" />
      <?php } else { ?>
      <input type="checkbox" name="filter_sub_category" value="1" id="sub_category" />
      <?php } ?>
      <label for="sub_category"><?php echo $text_sub_category; ?></label>
    </p>
    <?php if ($filter_description) { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" />
    <?php } ?>
    <label for="description"><?php echo $entry_description; ?></label>
  </div>
  <div class="buttons">
    <div class="right"><input type="button" value="<?php echo $button_search; ?>" id="button-search" class="button" /></div>
  </div>-->
  <h2><?php echo $text_search; ?></h2>
  <?php if ($products) { ?>
  <div class="product-filter">
    <div class="display"><b><?php echo $text_display; ?></b><img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?></a></div>
    <div class="limit"><?php echo $text_limit; ?>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><?php echo $text_sort; ?>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
  <div class="product-list">
      <?php foreach ($products as $product) { ?>
      <div data-width="<?php echo $product['ean']; ?>" id="product_<?php echo $product['product_id']; ?>" <?php if ($product['special']) { ?>class="special"<?php } ?> >
          <?php if ($product['thumb']) { ?>
            <?php if (is_array($product['thumb'])) { ?>
            <div class="image">
              <?php foreach ($product['thumb'] as $image) { ?>
              <a href="<?php echo $product['href']; ?>" style="display:none;"><img src="<?php echo $image; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a>
              <?php } ?>
            </div>
            <script type="text/javascript">
            $('#product_'+<?php echo $product['product_id']; ?>+' .image a:first-child').css('display', 'block');
            </script>
            <?php } else { ?>
              <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
            <?php } ?>
          <?php } ?>
          <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
          <div class="description"><?php echo $product['description']; ?><br><br>

          </div>

          <div class="category-product-options">
          <?php if ($product['upc']) { ?>
            <?php $isUPCOption = false;
              foreach ($product['options'] as $option) {
                if ($option['name'] == $text_option_upc) {
                  $isUPCOption = true;
                  break;
                }
              }
              if (!$isUPCOption) { ?>
              <div class="category-product-option">
                <span class="title"><?php echo $text_option_upc; ?>:</span>
                <span><label style="background:#565656;color:#FFF;"><?php echo $product['upc']; ?></label></span>
              </div>
              <?php } ?>
          <?php } ?>
          <?php if ($product['options']) { ?>
              <?php foreach ($product['options'] as $option) { ?>
              <?php if ($option['type'] == 'select') { ?>
                <div class="category-product-option" onclick="recalculateprice('<?php echo $product['product_id']; ?>');<?php if ($product['images_option'] && $product['images_option']=="option[".$option['product_option_id']."]" && is_array($product['thumb'])) { ?>
                  updateImages('option[<?php echo $option['product_option_id']; ?>]'); <?php } ?>">
                  <span class="title"><?php echo $option['name']; ?>:</span>
                  <?php $option_checked = "checked"; ?>

                  <?php
                  if (count($option['option_value']) >= 5) {
                    ?>
                    <select class="" name="option[<?php echo $option['product_option_id']; ?>]" >
                      <?php
                      foreach ($option['option_value'] as $option_value) { ?>
                        <option value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" price="<?php echo $option_value['price']; ?>" price-prefix="<?php echo $option_value['price_prefix']; ?>" <?php echo $option_checked; $option_checked=""; ?> image-num="<?php echo $option_value['image_num']; ?>"/>
                         <label>
                           <?php if (isset($_COOKIE["language"]) && $_COOKIE["language"] == "en") { ?>
                             <?php echo str_replace("мм", "mm", $option_value['name']); ?>
                           <?php } else { ?>
                             <?php echo $option_value['name']; ?>
                           <?php } ?>
                         </label>
                       </option>
                     <?php } ?>
                    </select>
                    <?php
                  }
                  else {
                     foreach ($option['option_value'] as $option_value) { ?>
                       <span>
                         <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" price="<?php echo $option_value['price']; ?>" price-prefix="<?php echo $option_value['price_prefix']; ?>" <?php echo $option_checked; $option_checked=""; ?> image-num="<?php echo $option_value['image_num']; ?>"/>
                         <label>
                           <?php if (isset($_COOKIE["language"]) && $_COOKIE["language"] == "en") { ?>
                             <?php echo str_replace("мм", "mm", $option_value['name']); ?>
                           <?php } else { ?>
                             <?php echo $option_value['name']; ?>
                           <?php } ?>
                         </label>
                       </span>
                    <?php } ?>
                  <?php } ?>


                </div>
              <?php }} ?>
          <?php } ?>
          </div>

          <?php if ($product['jan']) { ?>
          <span class="upc">Вес: <?php echo $product['jan']; ?>
		  </span>
          <?php } ?>
          <?php if ($product['price']) { ?>
      <div class="price"  data-price="<?php echo $product['priceSourse']; ?>" data-currency="<? echo $currency['symbol_right'];?>" data-pc="<? echo $text_pc ?>">
        <?php if (!$product['special']) { ?>
        <span id="formated_price_<?php echo $product['product_id']; ?>" price="<?php echo $product['price_value']; ?>" sku="/<?php echo $product['sku']; ?>"><?php echo $product['price']; ?>/<?php echo $product['sku']; ?>
          
        </span><span class="alt_t">*<span class="alt"><?php echo $currency_alt; ?></span></span>
        <?php } else { ?>
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?>/<?php echo $product['sku']; ?></span>
        <?php } ?>
        <?php if ($product['tax']) { ?>
        <br />
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <?php } ?>
        <span class="msg"><?php echo $text_min_quant; ?> <?php echo $product['minimum']; ?><?php echo $product['sku']; ?></span>
	     </div>
      <?php } ?>
          <?php if ($product['rating']) { ?>
          <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
          <?php } ?>
          <div class="cart">
              <div id="otherdiv">
<div class="quant-wrap">
                      <span class="quant-text">Количество:</span>
            <span class="minus-q" onclick="btnminus('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"></span>
                      <input type="text" name="quantity" size="2" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" id="quantity_<?php echo $product['product_id']; ?>" oninput="recalculateprice('<?php echo $product['product_id']; ?>')"/>
<!--            searh-->
            <span class="plus-q" onclick="btnplus('<?php echo $product['product_id']; ?>');"></span>

</div>
                  <span class="unit"><?php if ($this->config->get('config_display_sku') && $product['sku']) { ?><?php echo $product['sku']; ?><?php } ?></span>
                  <input type="hidden" name="product_id" size="2" value="<?php echo $product['product_id']; ?>" />
                  <input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>',document.getElementById('quantity_<?php echo $product['product_id']; ?>').value);" class="button" />
              </div>
          </div>
          <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
          <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div>
      </div>
      <?php } ?>
  </div>

  <div class="pagination" style="position:static;display:inline-block;"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#content input[name=\'filter_name\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';

	var filter_name = $('#content input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_category_id = $('#content select[name=\'filter_category_id\']').attr('value');

	if (filter_category_id > 0) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

	var filter_sub_category = $('#content input[name=\'filter_sub_category\']:checked').attr('value');

	if (filter_sub_category) {
		url += '&filter_sub_category=true';
	}

	var filter_description = $('#content input[name=\'filter_description\']:checked').attr('value');

	if (filter_description) {
		url += '&filter_description=true';
	}

	location = url;
});

    function btnplus(id) {
        $('#quantity_' + id).val(function(i, oldval){
            return ++oldval;

        });
        recalculateprice(id)
    }

    function btnminus(id, min) {
        if (($('#quantity_' + id).val()) > min) {
            $('#quantity_' + id).val(function(i, oldval){
                return --oldval;
            });
            recalculateprice(id)
        };
        return false;
    }


    function price_format(n)
    {
        c = <?php echo (empty($currency['decimals']) ? "0" : $currency['decimals'] ); ?>;
        d = '<?php echo $currency['decimal_point']; ?>'; // decimal separator
        t = '<?php echo $currency['thousand_point']; ?>'; // thousands separator
        s_left = '<?php echo $currency['symbol_left']; ?>';
        s_right = '<?php echo $currency['symbol_right']; ?>';

        n = n * <?php echo $currency['value']; ?>;

        //sign = (n < 0) ? '-' : '';

        //extracting the absolute value of the integer part of the number and converting to string
        i = parseInt(n = Math.abs(n).toFixed(c)) + '';

        j = ((j = i.length) > 3) ? j % 3 : 0;
        return s_left + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '') + s_right;
    }


    function recalculateprice(id)
    {
        var main_price = Number($('#formated_price_' + id).attr('price'));

        console.log(main_price);
        var input_quantity = Number($('#quantity_' + id).attr('value'));

        $('#product_' + id + ' input:checked').each(function (e) {
          main_price += eval($(this).attr('price-prefix') + Number($(this).attr('price')));
        });

        main_price *= input_quantity;

        $('#formated_price_' + id).html( price_format(main_price) );
    }

function display(view) {
  if (view == 'list') {
    $('.product-grid').attr('class', 'product-list');

    $('.product-list > div').each(function(index, element) {
      html = '';

      html += '<div class="top">'

      var image = $(element).find('.image').html();

      if (image != null) {
        html += '<div class="image">' + image + '</div>';
      }

      html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
      html += '  <div class="description" style="display:none;">' + $(element).find('.description').html() + '</div>';

      var priceEl = $(element).find('.price');
            
        var price = priceEl.html();

        if (price != null) {
            html += '<div class="price" data-price="'+priceEl.attr('data-price')+'" data-currency="'+priceEl.attr('data-currency')+'" data-pc="'+priceEl.attr('data-pc')+'">' + price + '</div>';
        }

      var rating = $(element).find('.rating').html();

      if (rating != null) {
        html += '<div class="rating">' + rating + '</div>';
      }

      html += '</div>'
      html += '<div class="bottom">';

      var upc = $(element).find('.upc').html();

      if (upc != null) {
        html += '<span class="upc">' + upc  + '</span>';
      }

      var options = $(element).find('.category-product-options').html();

      if (options != null) {
        html += '<div class="category-product-options" style="width:auto;display:inline;">' + options  + '</div>';
      }


      html += '<div class="right">';
      html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
      html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
      html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
      html += '</div>';

      html += '</div>';

      $(element).removeAttr('style');
      $(element).html(html);
    });

    $('.display').html('<b><?php echo $text_display; ?></b> <img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?> <b>/</b> <a onclick="javascript:display(\'grid\');"><img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?></a>');

    $.cookie('display', 'list');
  } else {
    $('.product-list').attr('class', 'product-grid');

    $('.product-grid > div').each(function(index, element) {
      html = '';

      var w = ($(element).data('width')) + 8 ;

      var image = $(element).find('.image').html();

      if (image != null) {
        html += '<div class="image">' + image + '</div>';
      }

      var upc = $(element).find('.upc').html();

      if (upc != null) {
        html += '<span class="upc">' + upc  + '</span>';
      }

      var options = $(element).find('.category-product-options').html();

      if (options != null) {
        html += '<div class="category-product-options">' + options  + '</div>';
      }

      html += '<div class="name">' + $(element).find('.name').html() + '</div>';
      html += '<div class="description">' + $(element).find('.description').html() + '</div>';



      var priceEl = $(element).find('.price');
            
			var price = priceEl.html();
            
			if (price != null) {
				html += '<div class="price" data-price="'+priceEl.attr('data-price')+'" data-currency="'+priceEl.attr('data-currency')+'" data-pc="'+priceEl.attr('data-pc')+'">' + price + '</div>';
			}

      //html += '<div style="clear: both;"></div>'

      var rating = $(element).find('.rating').html();

      if (rating != null) {
        html += '<div class="rating">' + rating + '</div>';
      }

      html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';

      html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
      html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';

      $(element).css('width', w + 'px');
      $(element).html(html);
    });


    $('.display').html('<b><?php echo $text_display; ?></b> <a onclick="javascript:display(\'list\');"> <img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?></a> <b>/</b> <img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?>');

    $.cookie('display', 'grid');
  }
}

view = $.cookie('display');

$(document).ready(function(){
if (view) {
  display(view);
}
});
//--></script>
<?php echo $footer; ?>
