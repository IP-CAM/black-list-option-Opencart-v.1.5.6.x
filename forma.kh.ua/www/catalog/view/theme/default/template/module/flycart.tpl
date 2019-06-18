<div id="cart">
    <div class="heading">
        <div class="headingright">
           <!--  <a href="index.php?route=checkout/cart"><?php echo $heading_title; ?></a> -->
            <a class="cart-btn"><span id="cart-total"><?php echo $text_items; ?></span></a>
        </div>
        <div class="headingrightp">
            <div class="buttons">
                <a class="button" id="button-flyclose"><?php echo $text_close; ?></a>
            </div>
        </div>
    </div>
    <div class="content">
        <?php if ($products || $vouchers) { ?>

        <div class="mini-cart-info">

            <table>
                <thead>
                <tr>
                    <td class="image"><?php echo $column_image; ?></td>
                    <td class="name"><?php echo $column_name; ?></td>
                    <td class="model"><?php echo $column_model; ?></td>
                    <td class="quantity"><?php echo $column_quantity; ?></td>
                    <td class="price"><?php echo $column_price; ?></td>
                    <td class="total"><?php echo $column_total; ?></td>
                </tr>
                </thead>
                <tbody>

                <?php foreach($products as $product){?>
                <tr>
                    <td class="image"><?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                        <?php } ?>
                    </td>
                    <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        <?php if (!$product['stock']) { ?>
                        <span class="stock">***</span>
                        <?php } ?>
                        <div>
                            <?php foreach ($product['option'] as $option) { ?>
                            - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                            <?php } ?>
                        </div>
                    </td>
                    <td class="model"><?php echo $product['model']; ?></td>
                    <td class="quantity">
                        <span class="price"><?php echo $product['price']; ?>/<?php echo $product['sku']; ?></span>
                        <input class="product_key" type="hidden" name="product_id" value="<?php echo $product['key']; ?>"/>
                        <input class="product_quantity" type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
                        <span class="sku">&nbsp;<?php echo $product['sku']; ?></span>
                        <input class="button_update" type="image" src="catalog/view/theme/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />

                        <input class="button_delete" type="image" src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" />

                    </td>
                    <td class="price"><?php echo $product['price']; ?></td>
                    <td class="total"><?php echo $product['total']; ?></td>
                </tr>
                <?php }?>

                <?php foreach ($vouchers as $vouchers) { ?>
                <tr>
                    <td class="image"></td>
                    <td class="name"><?php echo $vouchers['description']; ?></td>
                    <td class="model"></td>
                    <td class="quantity"><input type="text" name="" value="1" size="1" disabled="disabled" />
                        &nbsp;<a href="<?php echo $vouchers['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $text_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
                    <td class="price"><?php echo $vouchers['amount']; ?></td>
                    <td class="total"><?php echo $vouchers['amount']; ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="cart-total">
                <table id="total">
                    <?php foreach ($totals as $total) { ?>
                    <tr>
                        <?php
					if (strpos($total['title'], '- ') !== false) 
					{ ?>
                        <td class="right"><?php echo str_replace('- ', '<span id="ttldesc">', $total['title']).'</span>'; ?>:&nbsp;<?php echo $total['text']; ?></td>
                        <?php } else
					{ ?>
                        <td class="right"><b><?php echo $total['title']; ?>:</b>&nbsp;<b><?php echo $total['text']; ?></b></td>
                        <?php	}
				?>

                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="buttons">
                <div class="right"><a href="<?php echo $checkout; ?>" class="button" id="button-checkout"><?php echo $button_checkout; ?></a></div>
                <div class="left"><a class="button" id="button-cont"><?php echo $button_shopping; ?></a></div>
            </div>

        </div>
        <?php } else { ?>
        <div class="empty"><?php echo $text_empty; ?></div>
        <?php } ?>
    </div>
</div>
