<?php
/*
* simonfilters - 2.11.0 Build 0013
*/

if(!$isThereAnyFilterEnabled && $simonfilters_front_end_diagnostics=="true") {
    echo "<fieldset style='border:1px solid red;color:red;border-radius: 5px 5px 5px 5px;'><legend><b>SimonFilters Front End Diagnostics Says:</b></legend>
    <b>Warning:</b> You haven't enabled any filters on the admin side!<br><br>
    Go to admin and enable some filters or you won't see the module!<br><br>
    (Disable 'frontend diagnostics' under the Debug Tab to disable this message.)
</fieldset>";
}elseif(!$filterCount && $simonfilters_front_end_diagnostics=="true") {
    echo "<fieldset style='border:1px solid red;color:red;border-radius: 5px 5px 5px 5px;'><legend><b>SimonFilters Front End Diagnostics Says:</b></legend>
    <b>Warning:</b> There aren't enough selected filters on the admin side to present the filter module!<br><br>
    Go to admin and enable some more filters or you won't see the module!<br><br>
    (Disable 'frontend diagnostics' under the Debug Tab to disable this message.)
</fieldset>";
}

echo '<style type="text/css">'. $css ."</style>";

?>
<style type="text/css">
    div.box.simonfilters .radiounchecked{
        padding: 2px 3px 3px 20px;
        background-repeat: no-repeat !important;
        background: url("image/simonfilters/circle_small_off.png");
    }
    div.box.simonfilters .radiochecked{
        padding: 2px 3px 3px 20px;
        background-repeat: no-repeat !important;
        background: url("image/simonfilters/circle_small_on.png");
    }
    a.simonmoreswitch{
        font-weight:bold;
        font-size:12px;
    }


    div.simonscroll{
        overflow-y: scroll;
        height:80px;
    }

    li.simonleftli{
        float:left;
        width:42px;
    }
    li.simonrightli{
        float:right;
        clear:both;
        width:42px;
    }       
    <?php if($orientation!='horizontal') {?>
    li.filter_group{
        clear:both;
    }
        <?php }?>
</style>
<?php


if($filterCount>0) {
    $urlAjax = preg_replace('/amp;/','',$urlAjax);

    if($XRequestedWith!='SimonFilters') {
        ?>

<script><!--

    // simonfilters - 2.11.0 Build 0013
    $(function(){
        var urlAjax = '<?php echo $urlAjax;?>';
        urlAjax = urlAjax.replace(/page=[0-9]*/,'page=1');

        var display_type = '<?php echo $display_type;?>';
        var display_totals = '<?php echo $display_totals;?>';
        var containerID = '<?php echo $containerID; ?>';
        var containerIDtarget = '<?php echo $containerIDtarget; ?>';

        var currency_symbol_left = '<?php echo $activeCurrency['symbol_left'];?>';
        var currency_symbol_right = '<?php echo $activeCurrency['symbol_right'];?>';
        var currency_code = '<?php echo $activeCurrency['code'];?>';

        var position = '<?php echo $position;?>';
        var force_siblings = <?php echo $simonfilters_force_siblings;?>;
        var simonfilters_front_end_diagnostics = <?php echo $simonfilters_front_end_diagnostics;?>;

        var showprice = <?php echo $showprice?'true':'false';?>;
        var pricetype = <?php echo $activefilter_simonfilters_price['type'];?>;
        var simonfilters_dynamic = <?php echo $this->config->get("simonfilters_dynamic")=='1'?'true':'false';?>;
        var debug_mode = <?php echo $debug_mode?'true':'false';?>;

        var minprice = <?php echo isset($prices['minprice'])?$prices['minprice']:0;?>;
        var maxprice = <?php echo isset($prices['maxprice'])?$prices['maxprice']:0;?>;
        var minprice_slider = <?php echo isset($minprice_slider)?$minprice_slider:0;?>;
        var maxprice_slider = <?php echo isset($maxprice_slider)?$maxprice_slider:0;?>;

        var orientation = '<?php echo $orientation;?>';

        var ignore_text = <?php echo $this->config->get("simonfilters_script_ignore_text")!=''?'/'.trim(preg_replace('/;/','|',$this->config->get("simonfilters_script_ignore_text"))).'/gim':'null';?>;
        var ignore_scripts_names = <?php echo $this->config->get("simonfilters_ignore_scripts_names")!=''?'/'.trim(preg_replace('/;/','|',$this->config->get("simonfilters_ignore_scripts_names"))).'/gim':'null';?>;
        var ignore = <?php echo $this->config->get("simonfilters_script_ignore")=='1'?'true':'false';?> && ignore_text!=null;
        var execute_external_js = <?php echo $this->config->get("simonfilters_execute_external_js")=='1'?'true':'false';?>;

        var sortlis = <?php echo $this->config->get('client_side_items_sorting')=='1'?'true':'false'?>;

        var simonfilters_disableajax = <?php echo $this->config->get('simonfilters_disableajax')=='1'?'true':'false'?>;

        var simon_cache_buster = <?php echo $this->config->get('simonfilters_cache_buster')=='1'?'true':'false'?>;

        var simonfilters_custom_mod_more_text_more       = "<?php echo $simon_more;?>";
        var simonfilters_custom_mod_more_text_less       = "<?php echo $simon_less;?>";

        var came_with_payload =false;
        var persistent_sliders = [];

        var $checkedfilters = "";

        if ($("li.add_filter[class~='checked']").length>0){
            came_with_payload = true;
        }

        $("a.simonmoreswitch").live('click',function(event){
            event.preventDefault();
            $ul = $(this).closest(".filter_ul");
            switch($(this).data("simonmore")){
                case false:
                    $(this).text( simonfilters_custom_mod_more_text_less );
                    $ul.find(".simonmore").show();
                    break;
                case true:
                    $(this).text( simonfilters_custom_mod_more_text_more );
                    $ul.find(".simonmore").hide();
                    break;
            }
            $(this).data("simonmore", !$(this).data("simonmore") );
        });

        function doColumns(){

            $("div.filter_ul[data-cols='2']").each(function(i,o){
                $(this).find("li").filter(":even").addClass("simonleftli")
                $(this).find("li").filter(":odd").addClass("simonrightli")
            });
        }


        function domore(){
            $(".filter_ul").each(function(i,o){

                $lis = $(this).find("li");
                $len = $lis.length;
                var more= $(this).data("more");
                var more_number = $(this).data("more_number");

                switch(more){
                    case 1:
                        if( $(this).find("li.simonmore").length==0 && $len>more_number && !$lis.hasClass("checked")){
                            $2hide = $len-more_number;
                            e = $(this).find("li").slice(-$2hide);
                            e.hide().addClass("simonmore");
                            $(this).append("<a href='#' class='simonmoreswitch' data-simonmore='false'>"+ simonfilters_custom_mod_more_text_more +"</a>");
                        }
                        break;
                    case 2:
                        if( $(this).find("li.simonmore").length==0 && $len>more_number){
                            $2hide = $len-more_number;
                            e = $(this).find("li").slice(-$2hide);
                            e.hide();
                            height = $(this).height();
                            $(this).addClass('simonscroll');
                            e.show();
                            $(this).height(height);

                        }
                        break;

                }
            });
        }

        function doCacheBuster(){
            if(simon_cache_buster){
                getdata = {
                    i:$checkedfilters
                }
                $.get("index.php?route=module/simonfilters/gethash",getdata, function(simonhash){
                    $("a[href*='page']").each(function(){
                        $(this).attr("href", $(this).attr("href")+'&cacheBuster='+ simonhash);
                    });
                });
            }
        }

        function sortli(){
            $('li.filter_group').each(function(){
                if(orientation=='horizontal')$(this).find("ul").children("li[class='separator']").remove();
                var elems = $(this).find("ul").children("li").remove();
                elems.sort(function(a,b){

                    sortvals = [];
                    if(sortlis){

                        if(!isNaN($(a).text()) && !isNaN($(b).text())){
                            sortvals=[parseFloat($(a).text()),parseFloat($(b).text())];
                        }else if($(a).text().match(/[0-9]* to /)){
                            sortvals=[$(a).data('sort_order'), $(b).data('sort_order')];
                        }else{
                            sortvals=[$(a).text().toLowerCase(),$(b).text().toLowerCase()];
                        }

                    }else{
                        sortvals=[$(a).data('sort_order'), $(b).data('sort_order')];
                    }
                    if(sortvals[0]<sortvals[1])return -1;
                    if(sortvals[0]>sortvals[1])return 1;
                    return 0;
                });
                $(this).find("ul").append(elems);
                if(orientation=='horizontal'){
                    $(this).find("ul").children("li").each(function(){
                        $(this).after("<li class='separator'>-</li>");
                    });
                }
            });
        }

        function doDropdowns(){
            $("li.sfdropdown ").each(function(){
                $("li.sfdropdown").removeClass("collapsible expanded");
                var $_this = $(this);
                var filterid = $_this.attr("filterid");
                $_this.find(".filter_ul li").hide();

                var $uldata = [];
                var $i=0;

                $_this.find(".filter_ul li.add_filter").each(function(){
                    $uldata.push($(this).text())
                });

                $_this.find(".filter_ul").append("<ul><li><div class='simondrop'><select style='width:100%'></select></div></li></ul");

                $drop = $_this.find(".simondrop select");

                $drop.append("<option value='simon_none'>-</option>");
                $.each($uldata,function(i,o){
                    $drop.append("<option value='"+ i +"'>"+ o +"</option>");
                });

                $drop.change(function(){
                    var drop_val = $(this).find("option:selected").text();
                    $(".clear_filters_global").show();
                    $_this.find(".filter_ul li.add_filter").removeClass("checked");

                    $_this.find(".filter_ul li.add_filter").filter(function(){
                        return ($(this).text() == drop_val);
                    }).addClass("checked");

                    simondoit();
                });

            });
        }

        function doSliders(){
            $("li.sfslider").each(function(){
                $("li.sfslider").removeClass("collapsible expanded");
                var $_this = $(this);
                var filterid = $_this.attr("filterid");

                /*
                var cookie = $.cookie("simonfilters_sliders_<?php echo $index;?>_"+filterid);

                if(cookie){
                    $_this.find(".filter_ul ul").empty();
                    $jsonObject = JSON.parse(cookie);
                    $.each($jsonObject,function(i,o){
                        $_this.find(".filter_ul ul").append("<li class='"+ o.cssclass +"' id='"+ o.id +"'>"+ o.text +"</li>")
                    });
                }
                                */
                var $uldata = [];
                $_this.find(".filter_ul li").hide();

                var $checked_lower=-1;
                var $checked_upper=-1;

                var $i=0;

                if($_this.find(".filter_ul li.add_filter[class~='checked']").length==0){
                    $_this.find(".filter_ul li.add_filter:first").addClass("checked").removeClass("unchecked");
                    $_this.find(".filter_ul li.add_filter:last").addClass("checked").removeClass("unchecked");
                }

                $_this.find(".filter_ul li.add_filter").each(function(){
                    $uldata.push($(this).text())
                    if($(this).hasClass("checked")){
                        if($checked_lower==-1){
                            $checked_lower = $i;
                        }else{
                            $checked_upper = $i;
                        }
                    }
                    $i++;
                });

                if($checked_lower==-1)$checked_lower=0;
                if($checked_upper==-1)$checked_upper=$checked_lower;

                $_this.find(".filter_ul").append("<ul><li><div class='simonrange'></div><div id='amount"+ filterid +"' class='sfsamount'></div></li></ul");

                //$_this.find(".filter_ul").parent().after("<div id='amount"+ filterid +"' class='sfsamount'></div>");
                $("#amount"+ filterid).html( $uldata[$checked_lower] +' - '+ $uldata[$checked_upper]);

                /*
                persistent_sliders["simonfilters_sliders_<?php echo $index;?>_"+filterid]={
                    'cookie':"simonfilters_sliders_<?php echo $index;?>_"+filterid,
                    'html': ''
                }
                                */

                $_this.find(".filter_ul .simonrange").slider({
                    min: 0,
                    max: $uldata.length - 1,
                    values: [$checked_lower, $checked_upper],
                    range:true,
                    slide: function(event, ui) {
                        $("#amount"+ filterid).html( $uldata[ui.values[0]] + ' - '+ $uldata[ui.values[1]] );
                    },
                    change: function(event, ui) {
                        $( "#simonfilters_price-range" ).addClass("checked_price");
                        $(".clear_filters_global").show();
                        $_this.find(".filter_ul li.add_filter").removeClass("checked");

                        $_this.find(".filter_ul li.add_filter").filter(function(){
                            return ($(this).text() == $uldata[ui.values[0]] || $(this).text() == $uldata[ui.values[1]]);
                        }).addClass("checked");

                        var $jsonObject = [];
                        $_this.find(".filter_ul li.add_filter").each(function(){
                            $jsonObject.push({
                                'id':$(this).attr("id"),
                                'cssclass':$(this).attr("class"),
                                'text':$(this).text()
                            });
                            $uldata.push($(this).text())
                            $i++;
                        });
                        //$.cookie("simonfilters_sliders_<?php echo $index;?>_"+filterid,JSON.stringify($jsonObject));
                        simondoit();
                    }
                });
            });
        }

        sortli();
        doColumns();
        domore();
        doCacheBuster();
        doSliders();
        doDropdowns();

        <?php if($simondebugjs1) {
            echo($simondebugjs1);
        }?>

                $("ul.text_type_horizontal li.add_filter").live("mouseover mouseout",function (event) {if (event.type == "mouseover"){$(this).addClass("simonover");}else{$(this).removeClass("simonover")};})

                if(simonfilters_front_end_diagnostics){
                    if($(containerID).length==0){
                        alert('Warning: Simonfilters is incorrectly configured.\n\nGo to admin and enter valid HTML Object IDs.\n\nIf problem persists, disable SimonFilters and contact Simon: oc@simonoop.com');
                        return false;
                    }
                }

                $(".add_filter, .clear_filters_local, .clear_filters_global").die();
                $(".add_filter, .clear_filters_local, .clear_filters_global").unbind();
                $("li.collapsible div.filter_title").die();
                $("li.collapsible div.filter_title").unbind();

                $(".add_filter, .clear_filters_local, .clear_filters_global").live('click', function(){
                    switch($(this).hasClass('clear_filters_local')||$(this).hasClass('clear_filters_global')){
                        case true:
                            if($(this).hasClass('clear_filters_local')){

                                if($(this).closest("li").find("li.add_filter").hasClass('radiochecked') || $(this).closest("li").find("li.add_filter").hasClass('radiounchecked')){
                                    $(this).closest("li").find("li.add_filter").removeClass('radiochecked').addClass('radiounchecked');
                                }

                                $(this).closest("li").find("li.add_filter").removeClass('checked').addClass('unchecked');
                                $(this).fadeOut(100);
                                $checkedfilters = $($(".add_filter[class~='checked']").map(function() {  return this.id; })).toArray() ;
                                if($checkedfilters.length==0)$(".clear_filters_global").fadeOut(100);
                            }
                            if($(this).hasClass('clear_filters_global')){
                                $(".add_filter:not([class*='simonfilters_price'])").removeClass('checked').addClass('unchecked');

                                //if($(this).closest("li").find("li.add_filter").hasClass('radiochecked') || $(this).closest("li").find("li.add_filter").hasClass('radiounchecked')){
                                $(".add_filter[class*='radiochecked']").removeClass('radiochecked').addClass('radiounchecked');
                                //}

                                $(".clear_filters_local").hide();
                                $(this).fadeOut(100);

                                if(pricetype==1 && showprice){
                                    minprice_slider = <?php echo isset($prices['minprice'])?$prices['minprice']:0;?>;
                                    maxprice_slider = <?php echo isset($prices['maxprice'])?$prices['maxprice']:0;?>;
                                    $( "#simonfilters_price-range" ).removeClass("checked_price");
                                }
                            }
                            break;
                        case false:
                            $(this).toggleClass('checked').toggleClass('unchecked');

                            if($(this).hasClass('radiochecked') || $(this).hasClass('radiounchecked')){

                                $(this).siblings()
                                .removeClass("checked").addClass("unchecked")
                                .removeClass("radiochecked").addClass("radiounchecked");

                                $(this).toggleClass('radiochecked').toggleClass('radiounchecked');
                            }

                            $checkedfilters = $($(".add_filter[class~='checked']").map(function() {  return this.id; })).toArray() ;

                            if($(this).hasClass('checked' )){
                                    $(this).closest('li.filter_group').find('.clear_filters_local').show();
                            }else{
                                    if($(this).siblings(".checked").length==0)$(this).closest('li.filter_group').find('.clear_filters_local').hide();
                            }
                            $checkedfilters.length==0?$(".clear_filters_global").hide():$(".clear_filters_global").show();
                            break;
                        }
                        simondoit();

                    });

                    var simondoit = function(){

                        if(force_siblings || position=='content_top'){
                            $(".simonfilters").siblings().not("style,script").fadeTo(200, 0.20);
                        }else{
                            $(containerID).fadeTo(200, 0.20);
                        }

                        $checkedfilters =  $($(".add_filter[class~='checked']").map(function() {  return this.id; })).toArray() ;

                        $pricefilters = {};
                        if($( "#simonfilters_price-range" ).length>0 && $( "#simonfilters_price-range" ).hasClass('checked_price')){
                            $priceRange = $( "#simonfilters_price-range" ).slider("values");
                            $priceRange[0]=$priceRange[0].toString().replace(/[\.,]/,'SFS');
                            $priceRange[1]=$priceRange[1].toString().replace(/[\.,]/,'SFS');
                            $checkedfilters.push('p.1.1.'+ $priceRange);
                        }

                        function getURL(theUrl, extraParameters) {
                            var extraParametersEncoded = $.param(extraParameters);
                            var seperator = theUrl.indexOf('?') == -1 ? "?" : "&";

                            return(theUrl + seperator + extraParametersEncoded);
                        }

                        if(simonfilters_disableajax){
                            url = urlAjax;
                            url = url.replace(/&forcefiltersupdate=.*/,'');
                            url = url.replace(/forcefiltersupdate=.*/,'');
                            url = getURL(url, {forcefiltersupdate:true, checkedfilters:$checkedfilters});
                            document.location.href=url;
                        }else{
                            $.ajax({
                                type: 'GET',
                                data: {forcefiltersupdate:true, checkedfilters:$checkedfilters},
                                url: urlAjax,
                                headers: {"X-Requested-With":"SimonFilters"},
                                success: function(data){
                                    $data =$(data);
                                    var has_checked_price = false;

                                    if(simonfilters_dynamic || came_with_payload){
                                        $(containerID).html($data.find(containerIDtarget).html());
                                        $filterHTML = $data.find("div.simonfilters").html();
                                        $("div.simonfilters").html( $filterHTML );
                                        came_with_payload = false;
                                    }else{
                                        simonfilters_current_html = $(".simonfilters").html();
                                        $data.find(".simonfilters").html(simonfilters_current_html);
                                        $(containerID).html($data.find(containerIDtarget).html());
                                    }

                                    if(force_siblings || position=='content_top'){
                                        $(".simonfilters").siblings().not("style,script").fadeTo(100, 1);
                                    }else{
                                        $(containerID).fadeTo(100, 1);
                                    }

                                    $data.filter("script").each(function(idx, val) {
                                        if($(val).attr("src")!=undefined){
                                            if(execute_external_js){
                                                if(!$(val).attr("src").match(ignore_scripts_names)){
                                                    if(!$(val).attr("src").match(/jquery/)){
                                                        $.getScript($(val).attr("src"));
                                                    }
                                                }
                                            }
                                        }else{
                                            if(val.text!=''){
                                                if(!ignore || (ignore && !val.text.match(ignore_text))){
                                                    evaltext =val.text;
                                                    if(!evaltext.match(/document.write|jCarouselLite/)){
                                                        evaltext = evaltext.replace(/\/\/-->/,'');
                                                        evaltext = evaltext.replace(/<!--/,'');
                                                        try{
                                                            eval(evaltext);
                                                        }catch(err){}
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    if(simonfilters_dynamic){
                                        sortli();
                                        doColumns();
                                        doSliders();
                                        doDropdowns();
                                    }

                                    domore();
                                    doCacheBuster();


                                    if(pricetype==1 && showprice) {
                                        if(simonfilters_dynamic){
                                            minprice = parseFloat($data.find("#simonfilters_transported_minprice").val());
                                            minprice_slider = parseFloat($data.find("#simonfilters_transported_minprice_slider").val());
                                            maxprice = parseFloat($data.find("#simonfilters_transported_maxprice").val());
                                            maxprice_slider = parseFloat($data.find("#simonfilters_transported_maxprice_slider").val());
                                        }
                                        if(minprice!=minprice_slider || maxprice!=maxprice_slider){
                                            $( "#simonfilters_price-range" ).addClass('checked_price')
                                        }
                                        doPriceStuff();
                                    }
        <?php echo $simondebugjs2;?>
                                }
                            });
                        }
                    }


                    if(pricetype==1 && showprice){
                        doPriceStuff();
                    }
                    function doPriceStuff(){
                        $("li[filtertype='p']").show();
                        if(minprice!=minprice_slider || maxprice!=maxprice_slider){$(".clear_filters_global").show()}
                        $( "#simonfilters_price_amount" ).text( currency_symbol_left + minprice_slider + currency_symbol_right +" - " + currency_symbol_left + maxprice_slider + currency_symbol_right)
                        $( "#simonfilters_price-range" ).slider({
                            range: true,
                            min: minprice,
                            max: maxprice,
                            step: <?php echo $this->config->get("simonfilters_slider_step");?>,
                            animate: true,
                            values: [ minprice_slider,  maxprice_slider ],
                            slide: function( event, ui ) {
                                $( "#simonfilters_price_amount" ).text( currency_symbol_left + ui.values[ 0 ] + currency_symbol_right +" - " + currency_symbol_left + ui.values[ 1 ] + currency_symbol_right );
                            },
                            change: function(event, ui) {
                                $( "#simonfilters_price-range" ).addClass("checked_price");
                                $(".clear_filters_global").show();

                                slidervalues = $( "#simonfilters_price-range" ).slider("values");
                                minprice_slider = slidervalues[0];
                                maxprice_slider = slidervalues[1];
                                if (event.originalEvent) {
                                    simondoit();
                                }
                            }
                        });
                        if(minprice == maxprice){
                            $("li[filtertype='p']").hide();
                        }
                    }
                    $("li.collapsible div.filter_title").live('click', function(){
                        $(this).fadeTo(100,.2, function(){$(this).fadeTo(100,1)});
                        $(this).parent().toggleClass('collapsed').toggleClass('expanded');
                        $(this).parent().hasClass('collapsed')?
                            $(this).next("div.filter_ul").fadeOut(200):$(this).next("div.filter_ul").fadeIn(200);
                    });

                var autoDo=false;
                <?php

                $simonfilters_manufacturers_autoselect = $this->config->get("simonfilters_manufacturers_autoselect");
                if(isset($_GET['manufacturer_id']) && $simonfilters_manufacturers_autoselect=='1' ){
                    if(!isset($_GET['checkedfilters'])&&(!isset($_GET['forcefiltersupdate']))){
                ?>
                    var simon_manufacturer_id = <?php echo $_GET['manufacturer_id'];?>;
                    $("li.add_filter[id='m.1."+ simon_manufacturer_id +".0']").removeClass("unchecked").addClass("checked");
                    autoDo=true;
                <?php
                    }
                }
                ?>

                <?php
                $simonfilters_categories_autoselect = $this->config->get("simonfilters_categories_autoselect");
                if($category_id!='0' && $simonfilters_categories_autoselect=='1' ){
                    if(!isset($_GET['checkedfilters']) && (!isset($_GET['forcefiltersupdate']))){
                ?>
                    var category_id = '<?php echo $category_id;?>';
                    $("li.add_filter[id*='c.1."+ category_id +"']").removeClass("unchecked").addClass("checked");
                    autoDo=true;
                <?php
                    }
                }
                ?>
                if(autoDo){
                    simondoit();
                }
                });


                //--></script>
        <?php
    }

    ?>

<div class="box simonfilters">
<!--    <div class="box-heading"><?php echo $heading_title; ?>
        <span class="clear_filters_global_container">
            <span class='clear_filters_global' <?php echo $clear_filters_global_display;?>><?php echo $clear_filters_title;?></span>
        </span>
    </div>-->
    <div class="box-content">
        <div class="box-filter">
                <?php echo $HTML;?>
                <?php if($activefilter_simonfilters_price['type']==1) {?>
                    <?php if($showprice) {?>
            <input type="hidden" id="simonfilters_transported_minprice" value="<?php echo $prices['minprice'];?>">
            <input type="hidden" id="simonfilters_transported_minprice_slider" value="<?php if($minprice_slider>$prices['minprice'] && $minprice_slider<$prices['maxprice']) {
                            echo $minprice_slider;
                        }else {
                            echo $prices['minprice'];
                               }?>">
            <input type="hidden" id="simonfilters_transported_maxprice" value="<?php echo $prices['maxprice'];?>">
            <input type="hidden" id="simonfilters_transported_maxprice_slider" value="<?php if($maxprice_slider<$prices['maxprice'] && $maxprice_slider>$prices['minprice']) {
                            echo $maxprice_slider;
                        }else {
                            echo $prices['maxprice'];
                               }?>">
                               <?php }?>
                           <?php }?>
        </div>
    </div>
</div>
    <?php
}else {
    if($debug_mode) {
        ?>
<script><!--
        <?php echo $simondebugjs1;?>
                //--></script>
        <?php
    }
}
?>