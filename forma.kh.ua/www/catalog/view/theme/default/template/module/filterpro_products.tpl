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
              <div class="category-product-option" onclick="recalculateoneprice('<?php echo $product['product_id']; ?>');<?php if ($product['images_option'] && $product['images_option']=="option[".$option['product_option_id']."]" && is_array($product['thumb'])) { ?>
                updateImages('option[<?php echo $option['product_option_id']; ?>]'); <?php } ?>">
                <span class="title"><?php echo $option['name']; ?>:</span>
                <?php $option_checked = "checked"; ?>


                <?php
                if (count($option['option_value']) >= 5) {
                  ?>
                  <select class="" name="option[<?php echo $option['product_option_id']; ?>]" >
                    <?php
                    foreach ($option['option_value'] as $option_value) { ?>
                       <option value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" price="<?php echo $option_value['price']; ?>" price-prefix="<?php echo $option_value['price_prefix']; ?>" <?php echo $option_checked; $option_checked=""; ?> image-num="<?php echo $option_value['image_num']; ?>">
                       <label><?php echo $option_value['name']; ?></label>
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

        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <div class="description"><?php echo $product['description']; ?><br><br>

        </div>

        <?php if ($product['jan']) { ?>
          <span class="upc">Вес: <?php echo $product['jan']; ?>
		  </span>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <div class="price">
            <?php if (!$product['special']) { ?>
            <span id="formated_price_<?php echo $product['product_id']; ?>" price="<?php echo $product['price_value']; ?>" sku="/<?php echo $product['sku']; ?>"><?php echo $product['price']; ?>/<?php echo $product['sku']; ?>
              <span class="alt_t">*<span class="alt"><?php echo $currency_alt; ?></span></span>
            </span>
            <?php } else { ?>
            <span class="price-old"><?php echo $product['price']; ?>/<?php echo $product['sku']; ?></span> <span class="price-new"><?php echo $product['special']; ?>/<?php echo $product['sku']; ?></span>
            <?php } ?>
            <?php if ($product['tax']) { ?>
            <br />
            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
            <?php } ?>
            <span class="msg"><?php echo $text_min_quant; ?> <?php echo $product['minimum']; ?><?php echo $product['sku']; ?></span>
        </div>
        <?php } ?>
    <?php if ($product["attribute_group"]){ ?>
    <div class="view-more-atr"><a class="view-more-btn" href="">Показать больше</a></div>
    <div class="products_attrs">
        <div class="attr-block">
            <?php foreach ($product["attribute_group"] as $attributes){
             foreach ($attributes["attribute"] as $attribute){ ?>
            <?php if ($attribute["attr_check"] == true){ ?>
            <div class="attr-section cute-section">
                <span class="attr-name"><?= $attribute["name"]; ?></span>
                <span class="attr-text"><?= $attribute["text"]; ?></span>
            </div>
            <?php } else { ?>
            <div class="attr-section full-section">
                <span class="attr-name"><?= $attribute["name"]; ?></span>
                <span class="attr-text"><?= $attribute["text"]; ?></span>
            </div>
            <?php } ?>

            <?php }
          } ?>
        </div>
    </div>
    <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <div class="cart">
		<div style="position: relative; height: 20px;"></div>
            <div id="otherdiv">
                <div class="quant-wrap">

<!--1searh-->
              <span class="minus-q" style="float:left;" onclick="btnminus('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"></span>
              <input type="text" style="width: 42px;
height: 38px;margin: 0 0px 0px 0px;" name="quantity" size="2" min="<?php echo $product['minimum']; ?>" value="<?php echo $product['minimum']; ?>" id="quantity_<?php echo $product['product_id']; ?>" oninput="verifyQuantity('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>', true);"/>
                    <span class="plus-q" style="float:left;" onclick="btnplus('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"></span>
					<div class="wishlist"></div>
					<div class="compare"></div>
                </div>  <span class="unit"><?php if ($this->config->get('config_display_sku') && $product['sku']) { ?><?php echo $product['sku']; ?><?php } ?></span>
                <input type="hidden" name="product_id" size="2" value="<?php echo $product['product_id']; ?>" />
                <div id="buycart" style=" float: right; "><input type="button" value="<?php echo $button_cart; ?>" style="height: 38px;
                    padding: 2px 6px;
                    font-size: 22px;
                    font-weight: bold;" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
            </div>
        </div>

    </div>
    <?php } ?>
