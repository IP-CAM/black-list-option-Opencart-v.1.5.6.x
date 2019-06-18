<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" style="padding-left:235px;">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($thumb || $description) { ?>
  <div class="category-info">
    <!--<?php if ($thumb) { ?>
    <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>-->
    <?php if ($description) { ?>
    <?php echo $description; ?>

    <?php } ?>
    <br>
    <? echo $text_download_price; ?>
  </div>
  <?php } ?>

<!--  <?php /* if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
    <?php if (count($categories) <= 5) { ?>
    <ul>
      <?php foreach ($categories as $category) { ?>
      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } */ ?>-->
  <?php echo $content_top; ?>
  <!--<?php if($category_id == 62) { ?>
  <style type="text/css">
    .product-grid > div {
      width: 45% !important;
    }
  </style>
  <?php } ?>-->
  <?php if ($products) { ?>
  <div class="product-filter">
    <div class="display"><b><?php echo $text_display; ?></b><img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?></a></div>
    <p class="price-download" style="display: inline-block;margin-top: 9px;margin-bottom: 0;">
      <?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
      <a href="<?php echo $actual_link; ?>?export=true" class="price-download-button"><?php echo $button_price; ?></a>
      <a href="<?php echo $actual_link; ?>?export=true" class="price-download-text"><?php if($category_id == 59){?>
          <?php echo $category_name; ?>
          <?php }elseif($category_parent_id == 59){ ?>
        <?php echo $category_name; ?>
        <?php }else{?>
        <?php echo $category_name; ?>
        <?php } ?></a>
    </p>
    <div class="limit hide"><b><?php echo $text_limit; ?></b>
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
    <div class="limit">

      <?php
        $page_url_alls = "http:google.com";
      ?>

	<button id='all_products' value='<?php echo $product_total; ?>'><? echo $text_products_all; ?></button>

    </div>
    <div class="sort"><b><?php echo $text_sort; ?></b>
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
  <div class="product-grid">
    <?php foreach ($products as $product) { ?>
      <div data-width="<?php echo $product['ean']; ?>" <?php if ($product['special']) { ?>class="special"<?php } ?> id="product_<?php echo $product['product_id']; ?>">
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
            <span>
              <label style="background:#565656;color:#FFF;">
                <?php if (isset($_COOKIE["language"]) && $_COOKIE["language"] == "en") { ?>
                  <?php echo str_replace("мм", "mm", $product['upc']); ?>
                <?php } else { ?>
                  <?php echo $product['upc']; ?>
                <?php } ?>
              </label>
            </span>
          </div>
          <?php } ?>
      <?php } ?>

      <?php if ($product['options']) { ?>
          <?php foreach ($product['options'] as $option) { ?>
          <?php if ($option['type'] == 'select') { ?>
            <div class="category-product-option category-product-option-<?php echo $option['product_option_id']; ?>" onclick="recalculateoneprice('<?php echo $product['product_id']; ?>');<?php if ($product['images_option'] && $product['images_option']=="option[".$option['product_option_id']."]" && is_array($product['thumb'])) { ?>
              updateImages('option[<?php echo $option['product_option_id']; ?>]'); <?php } ?>">
              <span class="title"><?php echo $option['name']; ?>:</span>
              <?php $option_checked = "checked"; ?>


              <?php
              if (count($option['option_value']) >= 5) {
                ?>
                <select class="op_proid" name="option[<?php echo $option['product_option_id']; ?>]" >
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

      <div class="description" style="display:none;"><?php echo $product['description']; ?></div><br><br>
        <?php if ($product['jan']) { ?>
          <span class="upc">Вес: <?php echo $product['jan']; ?></span>
        <?php } ?>
      <?php if ($product['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
      <?php } //var_dump($product);?>
      <?php if ($product['attribute_group']){ ?>
        <div class="view-more-atr"><a class="view-more-btn" href="">Показать больше</a></div>
        <div class="products_attrs">
          <div class="attr-block">
          <?php foreach ($product['attribute_group'] as $attributes){
             foreach ($attributes['attribute'] as $attribute){ ?>
                <?php if ($attribute["attr_check"] == true){ ?>
                  <div class="attr-section cute-section">
                    <span class="attr-name">
                      <?php if(substr($attribute['name'], -1) == ':'){
                       echo $attribute['name'];
                      } else {
                        echo $attribute['name'] . ':';
                      }?></span>
                    <span class="attr-text"><?= $attribute['text']; ?></span>
                  </div>
                <?php } else { ?>
                  <div class="attr-section full-section">
                    <span class="attr-name">
                      <?php if(substr($attribute['name'], -1) == ':'){
                       echo $attribute['name'];
                      } else {
                        echo $attribute['name'] . ':';
                      }?></span>
                    <span class="attr-text"><?= $attribute['text']; ?></span>
                  </div>
                <?php } ?>

             <?php }
          } ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>
        <span id="formated_price_<?php echo $product['product_id']; ?>" price="<?php echo $product['price_value']; ?>" sku="/<?php echo $product['sku']; ?>"><?php echo $product['price']; ?>/<?php echo $product['sku']; ?>
          <span class="alt_t">*<span class="alt"><?php echo $currency_alt; ?></span></span>
        </span>
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

      <div class="right">
      <div class="cart">
          <div id="otherdiv">
              <div class="quant-wrap">
              <span class="quant-text"><?php echo $text_quantity; ?></span>
              <!-- cartProdackt-->
              <span class="minus-q" style="float:left;" onclick="btnminus('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"></span>
              <input type="text" name="quantity" size="2" style="width: 42px;
height: 38px;margin: 0 0px 0px 0px;" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" id="quantity_<?php echo $product['product_id']; ?>" oninput="verifyQuantity('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>', true);"/>
              <span class="plus-q" style="margin-bottom: 0px;" onclick="btnplus('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"></span>
              </div>  <span class="unit" style="margin-right: 0px;"><?php if ($this->config->get('config_display_sku') && $product['sku']) { ?><?php echo $product['sku']; ?><?php } ?></span>
              <input type="hidden" name="product_id" size="2" value="<?php echo $product['product_id']; ?>" />
              <div id="buycart" style=" float: right; "><input type="button" value="<?php echo $button_cart; ?>"
              style="height: 38px;
                    padding: 2px 6px;
                    font-size: 24px;
                    font-weight: bold;
                    margin-top: 9px;"
                    onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
					</div>
          </div>
      </div>
	  <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a></div>
      <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a></div></div>
    </div>
    <?php } ?>
  </div>
	<?php if($current_page < $total_page){ ?>
	<button class="autoload-btn" onclick=""><span class="" style=""><? echo $button_show_more ?></span> <div class='uil-reload-css' style='-webkit-transform:scale(0.13)'><div></div></div></button>
	<?php } ?>
	<div  class="pagination"><?php echo $pagination; ?></div>
	  <?php } ?>
	  <?php if (!$categories && !$products) { ?>
	  <div class="content"><?php echo $text_empty; ?></div>
	  <div class="buttons">
		<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
	  </div>
	  <?php } ?>
	  <?php echo $content_bottom; ?></div>

<script type="text/javascript"><!--

function btnplus(id, min) {
    $('#quantity_' + id).val(function(i, oldval){
        return ++oldval;
    });
    verifyQuantity(id, min);
}

function btnminus(id, min) {
    if (parseInt($('#quantity_' + id).val()) > parseInt(min)) {
        $('#quantity_' + id).val(function(i, oldval){
            return --oldval;
        });
        verifyQuantity(id, min, true);
    } else {
        verifyQuantity(id, min);
    }
    return false;
}

function verifyQuantity (id, min, isIn) {
  if (parseInt($('#quantity_' + id).val()) <= parseInt(min)) {
    if (isIn && parseInt($('#quantity_' + id).val()) >= parseInt(min)) {
      $('#product_'+id).find('.msg').css('display', 'none');
      $('#product_'+id).find('.cart input.button').attr('disabled', false);
      recalculateprice(id);
    } else {
      $('#product_'+id).find('.msg').css('display', 'block');
      $('#product_'+id).find('.cart input.button').attr('disabled', true);
    }
  } else {
    $('#product_'+id).find('.msg').css('display', 'none');
    $('#product_'+id).find('.cart input.button').attr('disabled', false);
    recalculateprice(id);
  }
}
    function openMore(el){
        var proAttr = $(el).parent().find('.products_attrs');
        var vm = 'visible-mobile';
        if (proAttr.hasClass(vm)){
            $(el).text('Показать атрибуты');
            $(proAttr).removeClass(vm);
        } else {
            $(el).text('Скрыть');
            $(proAttr).addClass(vm);
        }
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
	console.log(1);
    var main_price = Number($('#formated_price_' + id).attr('price'));

    //console.log(main_price);
    var input_quantity = Number($('#quantity_' + id).attr('value'));

    $('#product_' + id + ' input:checked').each(function (e) {
      main_price += eval($(this).attr('price-prefix') + Number($(this).attr('price')));
    });

    $('#product_' + id + ' select option:selected').each(function (e) {
      main_price += eval($(this).attr('price-prefix') + Number($(this).attr('price')));
    });

    main_price *= input_quantity;



    $('#formated_price_' + id).html( price_format(main_price) );
}

function recalculateoneprice(id)
{
    if ($('#formated_price_' + id).html().indexOf($('#formated_price_' + id).attr('sku')) + 1)
	{
      var main_price = Number($('#formated_price_' + id).attr('price'));
      $('#product_' + id + ' input:checked').each(function (e) {
        main_price += eval($(this).attr('price-prefix') + Number($(this).attr('price')));
      });

      $('#product_' + id + ' select option:selected').each(function (e) {
        main_price += eval($(this).attr('price-prefix') + Number($(this).attr('price')));
      });

      $('#formated_price_' + id).html( price_format(main_price) + $('#formated_price_' + id).attr('sku'));
    }
	else {
      recalculateprice(id);
      recalculateprice2(id);
    }
}

function display(view) {
    screen_width=$(document).width();

	if (view == 'list' && screen_width>992 ) {

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
      var price = $(element).find('.price').html();
      if (price != null) {
        html += '<div class="price">' + price  + '</div>';
      }

      var rating = $(element).find('.rating').html();
      if (rating != null) {

        html += '<div class="rating">' + rating + '</div>';
      }



      html += '</div>';

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
      html += '</div>';
        var attribs = $(element).find('.products_attrs').html();
        //console.log(attribs);
        if (attribs != null) {
        html += '<div class="products_attrs" style="display:none">' + attribs + '</div>';
        }
      html += '</div>';
      $(element).removeAttr('style');
			$(element).html(html);
		});
		$('.display').html('<b><?php echo $text_display; ?></b> <img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?> <b>/</b> <a onclick="javascript:display(\'grid\');"><img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?></a>');
		$.cookie('display', 'list', { path: '/' });
	} else {
        /*GRID*/
        console.log(view);
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
            var price = $(element).find('.price').html();
            if (price != null) {
               html += '<div class="price">' + price  + '</div>';
            }

            //flag price js
            //html += '<div style="clear: both;"></div>'

            var rating = $(element).find('.rating').html();
            if (rating != null) {
                html += '<div class="rating">' + rating + '</div>';
            }



            html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
            /*html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
            html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';*/
            $(element).css('width', w + 'px');
            $(element).css('max-width', '45%');
            //$(element).find(".full-section").css("display","none");
            //$(element).height($(element).height());


            var attribs = $(element).find('.products_attrs').html();
            //var view_more_btn = $(element).find('.view-more-atr').html();
            //console.log(view_more_btn);
            if (attribs != null) {
                html += '<a class="view-more-btn" onclick="openMore(this);" href="javascript:void(0);">Показать атрибуты</a>';
                html += '<div class="products_attrs" >' + attribs + '</div>';
            }
//            var itemBlock = '<div class="itemBlock">';
//            itemBlock += html;
//            itemBlock += '</div>';
//            $(element).html(itemBlock);

            //$(element).html(html);

            var itemBlock = '';
            itemBlock += '<div class="itemBlock">' + html + '</div>';
            $(element).html(itemBlock);
            $(element).addClass("category-item");
		});
		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="javascript:display(\'list\');"> <img src="/catalog/view/theme/default/image/product-list.png" /><?php echo $text_list; ?></a> <b>/</b> <img src="/catalog/view/theme/default/image/product-grid.png" /><?php echo $text_grid; ?>');
		$.cookie('display', 'grid');
	}
    $('.opened').removeClass('opened');
    if(screen_width > 992){
        $('.category-item').hover(
                function(event){
                    event.preventDefault();
                    hover_product(this);
                },
                function(event){
                    event.preventDefault();
                    hover_product_out(this);
                }
        );

//        setTimeout(function() {
//          var clh = $("#content").height()s - 160;
//          $("#column-left").height(clh);
//        }, 1000);
    }

}
view = $.cookie('display');
$(document).ready(function(){
if (view) {
	display(view);
}
});
screen_width=$(document).width();

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

function hover_product(item){
    setTimeout(function(){
        curr_height=$(item).height();
        $(item).height(curr_height);
        $(item).addClass('opened');
    },50);
}
function hover_product_out(item){
    setTimeout(function(){
        $(item).height('auto');
        $(item).removeClass('opened');
    },200);
}
</script>


<?php echo $footer; ?>
