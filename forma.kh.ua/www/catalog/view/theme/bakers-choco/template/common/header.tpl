<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=6LdoW6AUAAAAALVQHhxZgcYQF3M4ib645Zx4udhb"></script>
    <meta name="google-site-verification" content="KG1c1UzL0iNPVcrjN02S6rAdpgl-qu2udEvyGrb2TfQ" />
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/bakers-choco/stylesheet/stylesheet.css?<?php echo filemtime( 'catalog/view/theme/bakers-choco/stylesheet/stylesheet.css' ); ?>" />
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/bakers-choco/stylesheet/adaptive.css?<?php echo filemtime( 'catalog/view/theme/bakers-choco/stylesheet/adaptive.css' ); ?>" />
    <?php foreach ($styles as $style) { ?>
    <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="catalog/view/javascript/jquery.sticky-kit.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/common.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/spinedit.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/spinedit/spinedit.css" />
    <?php foreach ($scripts as $script) { ?>
    <script type="text/javascript" src="<?php echo $script; ?>?<?php echo filemtime($script); ?>"></script>
    <?php } ?>
    <!-- Autofill search -->
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/livesearch.css" />
    <script src="catalog/view/javascript/jquery/livesearch.js"></script>
    <!-- Autofill search END-->
    <link href='https://fonts.googleapis.com/css?family=Cuprum:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'/>
    <script src="catalog/view/javascript/device.min.js?<?php echo filemtime( 'catalog/view/javascript/device.min.js' ); ?>"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/arcticmodal/jquery.arcticmodal.css" />
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/arcticmodal/themes/dark.css" />
    <script src="catalog/view/javascript/jquery/arcticmodal/jquery.arcticmodal.js"></script>


    <!-- Global site tag (gtag.js) - AdWords: 1011677888 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-1011677888"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'AW-1011677888');
    </script>

    <script type="text/javascript">
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdoW6AUAAAAALVQHhxZgcYQF3M4ib645Zx4udhb', {action: 'spcallmeback'}).then(function(token) {
                $('[name="spcallmeback-recaptcha"]').val(token);
            });
        });
    </script>

    <? if($gtag_event){?>
    <script>
        gtag('event', 'page_view', {
        'send_to': 'AW-1011677888',
        'dynx_itemid': 'replace with value',
        'dynx_itemid2': 'replace with value',
        'dynx_pagetype': 'replace with value',
        'user_id': 'replace with value'
        });
    </script>
    <? } ?>
    <style>.grecaptcha-badge{bottom: 87px !important;}</style>

    <?php /*<script src="https://yastatic.net/jquery/cookie/1.0/jquery.cookie.min.js"></script>*/?>
    <?php /*
    <!--
    <script type="text/javascript">
    $(document).ready(function () {
      if (!$.cookie('smartCookies')) {

        $(document).mouseleave(function (e) {
          function getWindow() {
            $('.offer').arcticmodal({
              closeOnOverlayClick: true,
              closeOnEsc: true
            });
          };
          setTimeout(getWindow, 1);
          $.cookie('smartCookies', true, {
            expires: 7,
            path: '/'
          });
        });

      };
    });
    </script>
    -->*/ ?>
    
    <?php /*
    <!--
    <link rel="stylesheet" type="text/css" href="basic.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="jquery.simplemodal.js"></script>
    <script type="text/javascript" src="init.js"></script>
    --> */?>
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/bakers-choco/stylesheet/ie7.css" />
    <![endif]-->
    <!--[if lt IE 7]>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/bakers-choco/stylesheet/ie6.css" />
    <script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript">
    DD_belatedPNG.fix('#logo img');
    </script>
    <![endif]-->
    <?php echo $google_analytics; ?>

    <?php /*
    <!-- <script type="text/javascript">
    _shcp = []; _shcp.push({widget_id : 629906, widget : "Chat"}); (function() { var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true; hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.siteheart.com/apps/js/sh.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); })();
    </script>-->
    */ ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter24602252 = new Ya.Metrika({id:24602252,
                        webvisor:true,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        //s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
        s.src = "https://d31j93rd8oukbv.cloudfront.net/metrika/watch_ua.js";
        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
    </script>

    <script type="text/javascript">
    $(function(){
    $(window).scroll(function() {
    var top = $(document).scrollTop();
    if (top < 200) $("#menu1").css({top: '0', display: 'none'});
    else $("#menu1").css({top: '0', position: 'fixed', display: 'flex'});
    if (top < 200) $("#search").removeClass("sticky-search");
    else $("#search").addClass("sticky-search");
    });
    });
    </script>
    <script type="text/javascript">
    </script>

    <?php /*
    <!--
    <div style="display: none; padding: 10px; font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px; text-align: center;" id="exit_content" class="simplemodal-data">
    <h1><?php echo 'Вы уже уходите? <br> Подпишитесь на наши новости';?></h1>
    </div>
    -->
    <noscript><div><img src="//mc.yandex.ru/watch/24602252" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
     */ ?>
    <style type="text/css">
        .grecaptcha-badge{display: none;}
    </style>
</head>
<body>
    
    <? echo $json_ld_website;?>
    <? echo $json_ld_organization;?>
    
    <div class="modalInner">
    <section class="offer">
    <h5 style="margin: auto;"><?php echo 'Зарегистрировавшись сейчас! <br>
     Вы получите персональный доступ в секретный раздел сайта <br>
     с ограниченным количеством товара по супернизким ценам!' ?>
     </h5>
    <?php
    $simple_page = 'simpleregister';
    ?>
    </section>
    </div>
<!-- Модальное Окно  -->
       <div id="overlay">
            <div class="popup">
                Modalka
                <button class="close" title="Закрыть" onclick="document.getElementById('overlay').style.display='none';"></button>
            </div>
        </div>
<div id="top-wrapper">
<div id="container">
<div id="top-header">
<a class="btn-menu"><img src="/catalog/view/theme/bakers-choco/image/btn-menu.png"></a>
<div class="links">
	<a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
	<a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a>
	<a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
	<a href="<?php echo $checkout; ?>" class="cart-btn"><?php echo $cart; ?></a>
</div>
 <div id="welcome" class="fmkh">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
</div>
<div id="header">
    <div class="header-1"></div>
    <div class="header-2"></div>
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <?php echo $currency; ?>
 <div>
    <?php echo $language; ?>
    <div class="heading phone" id="head_phon">
        <div class="call"><div class="callimg"><img src="/catalog/view/theme/bakers-choco/image/phone.png" alt="" height="25px" width="25px"></div><div class="calltext"><?php echo $text_call; ?>:</div></div>
        <div class="numphone"><?php /*<!--+38(095) 058-59-53<br>+38(096) 427-42-59-->*/ ?><?php echo $clientsource ?></div>
        <?php /*<!-- <?php echo $text_call; ?>&nbsp;&nbsp;&nbsp;+38(095) 058-59-53<br>+38(096) 427-42-59 --> */ ?>
    </div>
 </div>
  <div id="search">
    <div class="button-search"></div>
    <?php if ($filter_name) { ?>
    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
    <?php } else { ?>
    <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
    <?php } ?>
  </div>
</div>
    <?php if ($categories) { ?>
    <div id="menu">
        <div class="phones">
        <div  style="grid-column: 1/ 3;"><?php echo $text_call . '!'; ?></div><a href="tel:+38(095) 058-59-53">+38(095) 058-59-53</a><a href="tel:+38(096) 427-42-59">+38(096) 427-42-59</a><?php echo $language; ?><?php echo $currency; ?>
        </div>
        <ul>
            <?php foreach ($categories as $category) { ?>
            <li><?php if ($category['active']) { ?>
                <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $category['href']; ?>" onmouseover="cboxOverlay(true);" onmouseout="cboxOverlay(false);"><?php echo $category['name']; ?></a>
                <?php } ?>

                <?php if ($category['children']) { ?>
                <div <?php if (!$category['active']) {?>onmouseover="cboxOverlay(true);" onmouseout="cboxOverlay(false);"<?php } ?>>
                    <?php for ($i = 0; $i < count($category['children']);) { ?>
                    <ul>
                        <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                        <?php for (; $i < $j; $i++) { ?>
                        <?php if (isset($category['children'][$i])) { ?>
                            <li><a href="<?php echo $category['children'][$i]['href']; ?>"<?php if($category['children'][$i]['active']){ echo ' class="active"'; }?>>
                            <?php if ($category['children'][$i]['image']) { ?>
                                <img class="category-image" src="<?php echo $category['children'][$i]['image']; ?>"/>
                            <?php } ?>
                                <span style="vertical-align:middle;"><?php echo $category['children'][$i]['name']; ?></span>
                                <?php if($category['children'][$i]['grand_childs']){ ?> <span class="right-arrow" style="float: right;margin-right: 5px;">»</span> <?php } ?>
                                </a>

                                <?php if($category['children'][$i]['grand_childs']){
                                    echo '<ul class="dropdown-menu sub-menu third-lvl-menu">';
                                    foreach($category['children'][$i]['grand_childs'] as $grand_child){ ?>
                                        <li>
                                            <a href="<?php echo $grand_child['href']; ?>" ><?php echo '- ' . $grand_child['name']; ?></a>
                                        </li>
                                    <?php }
                                    echo '</ul>';
                                } ?>

                            </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
                <?php } ?>
            </li>
            <?php } ?>
		    <?php foreach($catinfopages as $page){?>
		    <li><a href="<?php echo $page['href']; ?>"><?php echo $page['title']; ?></a>
		    <?php } ?>
        </ul>
    </div>
    <?php } ?>
    <?php if ($categories) { ?>
    <div id="menu1" class="menu-top" style="max-height: 60px;">
        <ul>
           <li><a href=""><img src="/image/data/logo2.png" alt="Харьков-форм"></a></li>
            <?php foreach ($categories as $category){
				if(!$logged)
				{
					if($category['name'] == 'Секретный раздел')
					{
						unset($category);
						$dspn = "style='display: none;'";
					}
					else{
						$dspn = "";
					}
				}
				?>
            <li><?php if ($category['active']) { ?>
                <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $category['href']; ?>" onmouseover="cboxOverlay(true);" onmouseout="cboxOverlay(false);"><?php echo $category['name']; ?></a>
                <?php } ?>

                <?php if ($category['children']) { ?>
                <div <?php if (!$category['active']) {?>onmouseover="cboxOverlay(true);" onmouseout="cboxOverlay(false);"<?php } ?>>
                    <?php for ($i = 0; $i < count($category['children']);) { ?>
                    <ul>
                        <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                        <?php for (; $i < $j; $i++) { ?>
                        <?php if (isset($category['children'][$i])) { ?>
<li><a href="<?php echo $category['children'][$i]['href']; ?>"<?php if($category['children'][$i]['active']){ echo ' class="active"'; }?>>
    <?php if ($category['children'][$i]['image']) { ?>
    <img class="category-image" src="<?php echo $category['children'][$i]['image']; ?>"/>
    <?php } ?>
    <span style="vertical-align:middle;"><?php echo $category['children'][$i]['name']; ?></span>
    <?php if($category['children'][$i]['grand_childs']){ ?> <span class="right-arrow" style="float: right;margin-right: 5px;">»</span> <?php } ?></a>

    <?php if($category['children'][$i]['grand_childs']){
                                    echo '<ul class="dropdown-menu sub-menu third-lvl-menu">';
    foreach($category['children'][$i]['grand_childs'] as $grand_child){ ?>
                        <li>
                            <a href="<?php echo $grand_child['href']; ?>" ><?php echo $grand_child['name']; ?></a>
                        </li>
                        <?php }
                                    echo '</ul>';
                        } ?>

</li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
                <?php } ?>
            </li>
            <?php } ?>
<?php /*
<!--
		    <?php foreach($catinfopages as $page){?>
		    <li><a href="<?php echo $page['href']; ?>"><?php echo $page['title']; ?></a>
		    <?php } ?>
--> */ ?>

        </ul>
        <div class="clone-search">

        </div>
        <div class="phones">
        <ul>
            <li style="margin: 15px 0px 12px 0px;left: 12%;float: right;">
                <a href="tel:+38(095) 058-59-53">+38(095) 058-59-53</a>
                <a href="tel:+38(096) 427-42-59"style="margin-left:10px;">+38(096) 427-42-59</a>
        </li>
        </ul>
        </div>
    </div>
    <?php } ?>
    <script type="text/javascript">
    {
        function cboxOverlay (bool) {
            var styles = {
                "-moz-opacity": "1",
                "-khtml-opacity": "1",
                "opacity": "1",
                "-ms-filter": "\"progid:DXImageTransform.Microsoft.Alpha\"(Opacity=100)",
                "filter": "progid:DXImageTransform.Microsoft.Alpha(opacity=100)",
                "filter": "alpha(opacity=100)"
            }
            var stylesOpacity = {
                "-moz-opacity": "0.50",
                "-khtml-opacity": "0.50",
                "opacity": "0.50",
                "-ms-filter": "\"progid:DXImageTransform.Microsoft.Alpha\"(Opacity=50)",
                "filter": "progid:DXImageTransform.Microsoft.Alpha(opacity=50)",
                "filter": "alpha(opacity=50)"
            }
            if (!isMobile() && !isTablet()) {
                if (bool) {
                    $('#cboxOverlay').css('display', 'block');
                    $('#menu .active').next().css(stylesOpacity);
                } else {
                    $('#cboxOverlay').css('display', 'none');
                    $('#menu .active').next().css(styles);
                }
            }
        }
    }
    </script>
<span class="padd-top"></span>
<div id="notification"></div>
