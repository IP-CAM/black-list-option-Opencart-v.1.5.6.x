var isMenu = false;

$(document).ready(function() {
    $('.btn-menu').bind('click', function() {
        if (!isMenu) {
            $('#menu').css('display', 'block');
            $('#cboxOverlay').css('display', 'block');
            isMenu = true;
        } else {
            $('#cboxOverlay').css('display', 'none');
            isMenu = false;
            $('#menu').css('display', 'none');
        }
    });
	/* скрывает опции "От..." */
	$('.category-product-option>span>label:contains("От")').css('display', 'none');
	
    /* Search */
    $('.button-search').bind('click', function() {
        url = $('base').attr('href') + 'index.php?route=product/search';

        var filter_name = $('input[name=\'filter_name\']').attr('value');

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        location = url;
    });

    $('#header input[name=\'filter_name\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            url = $('base').attr('href') + 'index.php?route=product/search';

            var filter_name = $('input[name=\'filter_name\']').attr('value');

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            location = url;
        }
    });



    /* Mega Menu */
    $('#menu ul > li > a + div').each(function(index, element) {
        // IE6 & IE7 Fixes
        if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
            var category = $(element).find('a');
            var columns = $(element).find('ul').length;

            $(element).css('width', (columns * 143) + 'px');
            $(element).find('ul').css('float', 'left');
        }

        var menu = $('#menu').offset();
        var dropdown = $(this).parent().offset();

        i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

        if (i > 0) {
            $(this).css('margin-left', '-' + (i + 5) + 'px');
        }
    });

    // IE6 & IE7 Fixes
    if ($.browser.msie) {
        if ($.browser.version <= 6) {
            $('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');

            $('#column-right + #content').css('margin-right', '195px');

            $('.box-category ul li a.active + ul').css('display', 'block');
        }

        if ($.browser.version <= 7) {
            $('#menu > ul > li').bind('mouseover', function() {
                $(this).addClass('active');
            });

            $('#menu > ul > li').bind('mouseout', function() {
                $(this).removeClass('active');
            });
        }
    }

    $('.success img, .warning img, .attention img, .information img').live('click', function() {
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
        });
    });
});

    function showCartFly(show_hide) {
    if (show_hide == 'show') {
        $('#cart').load('index.php?route=module/cart #cart > *');
        $('#cartfly').load('index.php?route=module/cart #cart > *');
        $('#cartfly').addClass('active');
        $.cookie('showcart', 'show');
    } else {
        $('#cartfly').removeClass('active');
        $.cookie('showcart', 'hide');
    }
}

function updateCart(product_key, quantity) {

    $.ajax({
        url: 'index.php?route=checkout/cart/updatefly',
        type: 'post',
        data: 'product_id=' + product_key + '&quantity=' + quantity,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information, .error').remove();

            if (json['redirect']) {
                location = json['redirect'];
            }

            $('#cart').load('index.php?route=module/cart #cart > *');
            $('#cartfly').load('index.php?route=module/cart #cart > *');
            $('#cart-total').html(json['total']);

        }
    });
}

function getURLVar(urlVarName) {
    var urlHalves = String(document.location).toLowerCase().split('?');
    var urlVarValue = '';

    if (urlHalves[1]) {
        var urlVars = urlHalves[1].split('&');

        for (var i = 0; i <= (urlVars.length); i++) {
            if (urlVars[i]) {
                var urlVarPair = urlVars[i].split('=');

                if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
                    urlVarValue = urlVarPair[1];
                }
            }
        }
    }

    return urlVarValue;
}

function addToCart(product_id) {
    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: $('#product_'+product_id).find('input[type=\'text\'], input[type=\'hidden\'], input[type=\'radio\']:checked, input[type=\'checkbox\']:checked, select, textarea'),
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information, .error').remove();

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['success']) {

                $('#cart-total').html(json['total']);

                showCartFly('show');

            }
        }
    });
}


function deleteFromCart(product_id) {

    $.ajax({
        url: 'index.php?route=checkout/cart/remove',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information, .error').remove();

            if (json['redirect']) {
                location = json['redirect'];
            }

            $('#cart').load('index.php?route=module/cart #cart > *');
            $('#cartfly').load('index.php?route=module/cart #cart > *');
            $('#cart-total').html(json['total']);

        }
    });
}

//flycart
    $('#button-cont').live('click', function() {
        showCartFly('hide');
    });


    $('#button-checkout').live('click', function() {
        showCartFly('hide');
        location = 'index.php?route=checkout/checkout';
    });


    $('#button-flyclose').live('click', function() {
        showCartFly('hide');
    });


    $('.button_update').live('click', function() {
        var str_key = '';
        str_key = $(this).parent().find('.product_key').val();
        var str_q = '';
        str_q = $(this).parent().find('.product_quantity').val();
        updateCart(str_key, str_q);
        showCartFly('hide');
        showCartFly('show');
        showCartFly('show');
    });

    $('.button_delete').live('click', function() {
        var str_key = '';
        str_key = $(this).parent().find('.product_key').val();
        deleteFromCart(str_key);
        showCartFly('hide');
        showCartFly('show');
        showCartFly('show');
    });


function addToWishList(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').after('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#wishlist-total').html(json['total']);

                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        }
    });
}

function addToCompare(product_id) {
    $.ajax({
        url: 'index.php?route=product/compare/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#compare-total').html(json['total']);

                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        }
    });
}

    /* Ajax Cart */
    $('#cart > .heading a').live('click', function() {
		showCartFly('show');
    });

function updateImages (updater) {
    var images = null;
    var image_num = 0;
    var input = $('input[name="' + updater + '"]:checked');
    images = $(input.parents()[3]).find('.image');
    if (images.children().length !== 1) {
        image_num = Number(input.attr('image-num')) + 1;
        images.find('a').css('display', 'none');
        images.find('a:nth-child(' + image_num + ')').css('display', 'block');
    }
}