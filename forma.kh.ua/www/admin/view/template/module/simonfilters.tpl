<?php
/*
* simonfilters - 2.11.0 Build 0013
*/
?>
<?php echo $header; ?>
<script>
    $(function(){
        ajax_action_client_side_rebuild = "<?php echo preg_replace('/amp;/','',$ajax_action_client_side_rebuild);?>";
        ajax_action_get_domids = "<?php echo preg_replace('/amp;/','',$ajax_action_get_domids);?>";
        ajax_action_get_default_style = "<?php echo preg_replace('/amp;/','',$ajax_action_get_default_style);?>";
        ajax_action_main_index_button = "<?php echo preg_replace('/amp;/','',$ajax_action_main_index_button);?>";
        ajax_action_admin_index_button = "<?php echo preg_replace('/amp;/','',$ajax_action_admin_index_button);?>";
        ajax_action_db_index_button = "<?php echo preg_replace('/amp;/','',$ajax_action_db_index_button);?>";
        ajax_action_change_product = "<?php echo preg_replace('/amp;/','',$ajax_action_change_product);?>";
        ajax_action_search_updates = "<?php echo preg_replace('/amp;/','',$ajax_action_search_updates);?>";
        ajax_action_invalid_tags_button = "<?php echo preg_replace('/amp;/','',$ajax_action_invalid_tags_button);?>";

        $("input[name*='simonfilters_hard']").keydown(function(){
            if( $(this).next("a.reload").length ==0){
                $(this).after("<a href='#' class='reload'><img src='<?php echo HTTP_SERVER;?>/view/image/simonoop/reload.png'></a>");
            }
        });

        $("a.reload").live('click',function(event){
            event.preventDefault();
            $(this).prev("input").val($(this).prev("input").data("default"));
            $(this).remove();
        })

        <?php
        $_max_input_vars = ini_get("max_input_vars")!=''?ini_get("max_input_vars"):1000;
        ?>
        if( $("input, select").length > <?php echo $_max_input_vars; ?> ){
            $(".breadcrumb").after('<div class="warning">Warning!<br><br>There are currently '+ $("input, select").length +' input/radio/checkboxes/select elements in this page.<br><br>Your <b>max_input_vars</b> setting only allows <?php echo ini_get("max_input_vars"); ?>!<br><br>Edit your php.ini file and set <b>max_input_vars</b> accordingly!</div>');
        }

        $("#help_button").click(function(event){
            event.preventDefault();
            if(confirm("Need help?\n\nThe number of options can be overwhelming and some combinations of OC versions and third party extensions can cause the module to misbehave.\n\nMost of the times it will be a simple config option but others can more challenging.\n\nSend an email to oc@simonoop.com describing the problem! If you\'d like me to access your FTP site to upload fixes then please remember to create credentials just for this so you can either disable or delete them after the work is done.\n\nRead/write access credentials to the modules relevant to the problem should also be useful! Oh, and... BACKUP both your database and your filesystem!!\n\nWould you like to open a new window on the support forum?")){
                window.open('http://opencartmodules.simonoop.com/forum/index.php');
            }
        });

        $("#db_index_button").click(function(event){
            event.preventDefault();
            if(confirm('I\ll try to add the indexes to your database. Please take some minutes to create a full backup of your database!\n\nAre you sure you want me to this?')){
                if(confirm('It is highly advisable to create a database backup before adding the indexes.\n\nDid you make a fullbackup?\n\nAre you sure you want to do this?')){
                    $.post(ajax_action_db_index_button, function(data){
                        alert(data);
                        location.reload();
                    });
                }
            }
        });

        $("#main_index_button").click(function(event){
            event.preventDefault();
            if(confirm('I\ll try to add the changes to your main index file.\n\nA backup will be made under the name index_backup_simonfilters.php\n\nPlease also make your own backup of all the files!')){
                if(confirm('It is highly advisable to create a full backup before making the changes.\n\nDid you make a fullbackup?\n\nAre you sure you want to do this?')){
                    $.post(ajax_action_main_index_button, function(data){
                        alert(data);
                        if(data=='Done!'){
                            location.reload();
                        }
                    });
                }
            }
        });

        $("#admin_index_button").click(function(event){
            event.preventDefault();
            if(confirm('I\ll try to add the changes to your admin index file.\n\nA backup will be made under the name admin/index_backup_simonfilters.php\n\nPlease also make your own backup of all the files!')){
                if(confirm('It is highly advisable to create a full backup before making the changes.\n\nDid you make a fullbackup?\n\nAre you sure you want to do this?')){
                    $.post(ajax_action_admin_index_button, function(data){
                        alert(data);
                        if(data=='Done!'){
                            location.reload();
                        }
                    });
                }
            }
        });

        $("#invalid_tags_button").click(function(event){
            event.preventDefault();
            if(confirm('I\ll try to correct your tags.\n\n')){
                if(confirm('It is highly advisable to create a full backup before making the changes.\n\nDid you make a fullbackup?\n\nAre you sure you want to do this?')){
                    $.post(ajax_action_invalid_tags_button, function(data){
                        alert(data);
                        if(data=='Done!'){
                            location.reload();
                        }
                    });
                }
            }
        });

        $("#change_product").click(function(event){
            event.preventDefault();
            if(confirm('I\ll try to add the changes to your /catalog/model/catalog/product.php.\n\nA backup will be made under the name catalog/model/catalog/product_backup_simonfilters.php\n\nPlease also make your own backup of all the files!')){
                if(confirm('It is highly advisable to create a full backup before making the changes.\n\nDid you make a fullbackup?\n\nAre you sure you want to do this?')){
                    alert('I\'ll now send your catalog/model/catalog/product.php file to a remote service on Simon\'s server and try to make the necessary changes to the file.');
                    $.post(ajax_action_change_product, function(data){
                        alert(data);
                        if(data=='Done!'){
                            //location.reload();
                        }
                    });
                }
            }
        });

        $("#select_all_placement").click(function(){
            $("#placement_checkboxes input[type='checkbox']").attr("checked",$(this).is(":checked"));
        });

        $(".filter_selector").click(function(){
            if($(this).is(':checked')){
                $(this).closest("tr").find("select,input[type='text']").show().removeAttr("disabled");
            }else{
                $(this).closest("tr").find("select,input[type='text']").hide().attr("disabled","disabled");
            }
        });

        $(".filter_selector_selector").click(function(){
            if($(this).is(':checked')){
                $(this).closest('table').find('.filter_selector').attr("checked", true );
                $(this).closest('table').find("select,input[type='text']").show().removeAttr("disabled");
            }else{
                $(this).closest('table').find('.filter_selector').attr("checked", false );
                $(this).closest('table').find("select,input[type='text']").hide().attr("disabled","disabled");
            }
        });

        $("#rebuild_filters").click(function(event){
            event.preventDefault();
            $(".content").fadeTo(100, .2);
            $.get(ajax_action_client_side_rebuild, function(){
                $(".content").fadeTo(100, 1);
            });
        });

        $("#search_updates").click(function(event){
            event.preventDefault();
            $.get(ajax_action_search_updates, function(data){

            });
        });

        $("#get_domids").click(function(event){
            event.preventDefault();
            $(".content").fadeTo(100, .2);
            $.getJSON(ajax_action_get_domids, function(data){
                $f1=$('input[name="simonfilters_category_domid[<?php echo $config_template;?>]"]');
                $f2=$('input[name="simonfilters_manufacturer_domid[<?php echo $config_template;?>]"]');
                $f4=$('input[name="simonfilters_homepage_domid[<?php echo $config_template;?>]"]');
                $f3=$('input[name="simonfilters_force_siblings[<?php echo $config_template;?>]"]');
                $f5=$('input[name="simonfilters_search_domid[<?php echo $config_template;?>]"]');
                $f6=$('input[name="simonfilters_product_domid[<?php echo $config_template;?>]"]');
                $f7=$('input[name="simonfilters_special_domid[<?php echo $config_template;?>]"]');
                alert(data.message);
                $f1.val(data.data.category);
                $f2.val(data.data.manufacturer);
                $f4.val(data.data.homepage);
                $f3.attr("checked", data.data.forcesiblings );
                $f5.val(data.data.search);
                $f6.val(data.data.product);
                $f7.val(data.data.special);
                $(".content").fadeTo(100, 1, function(){
                    $f1.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f2.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f4.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f3.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f5.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f6.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f7.wrap("<div style='border:2px solid red;padding:0px'></div>");
                    $f1.parent().fadeTo(500, .2, function(){$f1.unwrap();});
                    $f2.parent().fadeTo(500, .2, function(){$f2.unwrap();});
                    $f4.parent().fadeTo(500, .2, function(){$f4.unwrap();});
                    $f3.parent().fadeTo(500, .2, function(){$f3.unwrap();});
                    $f5.parent().fadeTo(500, .2, function(){$f5.unwrap();});
                    $f6.parent().fadeTo(500, .2, function(){$f6.unwrap();});
                    $f7.parent().fadeTo(500, .2, function(){$f7.unwrap();});
                });

            });
        });

        htmlpreview = {
            'vertical':'<div class="box simonfilters"><div class="box-heading">&nbsp;<span class="clear_filters_global_container"><span class="clear_filters_global">Clear Filters</span></span></div><div class="box-content"><div class="box-filter"><ul class="filter_grouper"><li class="filter_group collapsible expanded"><div class="filter_title">No. of Cores<span class="clear_filters_local_container"><span class="clear_filters_local">[X]</span></span></div><div class="filter_ul"><ul><li id="a.2.2.0" class="add_filter unchecked">1 </li><li id="a.2.2.1" class="add_filter unchecked">4 </li></ul></div></li><li class="filter_group collapsible expanded"><div class="filter_title" style="opacity: 1;">test 1<span class="clear_filters_local_container"><span class="clear_filters_local">[X]</span></span></div><div class="filter_ul"><ul><li id="a.4.4.0" class="add_filter unchecked">16GB </li><li id="a.4.4.1" class="add_filter unchecked">32GB </li></ul></div></li><li class="filter_group collapsible expanded"><div class="filter_title">Manufacturer<span class="clear_filters_local_container"><span class="clear_filters_local">[X]</span></span></div><div class="filter_ul"><ul><li id="m.1.7.0" class="add_filter unchecked">Hewlett-Packard </li><li id="m.1.8.0" class="add_filter unchecked">Apple </li><li id="m.1.10.0" class="add_filter unchecked">Sony </li></ul></div></li><li class="filter_group collapsible expanded"><div class="filter_title">Stock<span class="clear_filters_local_container"><span class="clear_filters_local">[X]</span></span></div><div class="filter_ul"><ul><li id="s.1.5.0" class="add_filter unchecked">Out Of Stock </li></ul></div></li></ul></div></div></div>'
            ,'horizontal':'<div class="box simonfilters"><div class="box-heading">&nbsp;<span class="clear_filters_global_container"><span class="clear_filters_global">Clear Filters</span></span></div><div class="box-content"><div class="box-filter"><ul class="filter_grouper"><li class="filter_group"><table><tbody><tr><td style="width:1px;"><div class="filter_title">No. of Cores</div></td><td><div class="filter_ul"><ul><li id="a.2.2.0" class="add_filter unchecked">1 (1)</li><li class="separator">-</li><li id="a.2.2.1" class="add_filter unchecked">4 (1)</li><li class="separator">-</li></ul></div></td></tr></tbody></table></li><li class="filter_group"><table><tbody><tr><td style="width:1px;"><div class="filter_title">Clockspeed</div></td><td><div class="filter_ul"><ul><li id="a.3.3.0" class="add_filter unchecked">800MHZ (1)</li><li class="separator">-</li></ul></div></td></tr></tbody></table></li><li class="filter_group"><table><tbody><tr><td style="width:1px;"><div class="filter_title">simon1</div></td><td><div class="filter_ul"><ul><li id="a.13.13.0" class="add_filter unchecked">1 (1)</li><li class="separator">-</li></ul></div></td></tr></tbody></table></li><li class="filter_group"><table><tbody><tr><td style="width:1px;"><div class="filter_title">Stock</div></td><td><div class="filter_ul"><ul><li id="s.1.6.0" class="add_filter unchecked">2 - 3 Days </li><li class="separator">-</li><li id="s.1.7.0" class="add_filter unchecked">In Stock </li><li class="separator">-</li><li id="s.1.5.0" class="add_filter unchecked">Out Of Stock </li><li class="separator">-</li></ul></div></td></tr></tbody></table></li></ul></div></div></div>'
        }

        $.fn.showcsseditor = function(){
            $csseditor = $(this)
            orientation = $csseditor.find("select.orientation option:selected").val();
            $divpreview = $($csseditor).find(".divpreview[orientation='"+ orientation +"']");
            $style = $divpreview.find("style");
            $simonfilters_styles_textarea = $csseditor.find(".simonfilters_styles_textarea");
            $(this).assigncss();
            $(".csseditor").hide();
            $(".divpreview").fadeOut();
            $divpreview.fadeIn();
            $csseditor.show();
        }

        $.fn.assigncss = function(){
            $(".htmlpreview").empty();
            $(".stylepreview").empty();
            $csseditor = $(this)
            orientation = $csseditor.find("select.orientation option:selected").val();
            collapsible = $csseditor.find("select.collapsible option:selected").val();
            $divpreview = $($csseditor).find(".divpreview[orientation='"+ orientation +"']");
            $style = $divpreview.find("style");
            $htmlpreview = $divpreview.find(".htmlpreview");
            $simonfilters_styles_textarea = $csseditor.find(".simonfilters_styles_textarea");
            if($simonfilters_styles_textarea.length>0)$style.text( $simonfilters_styles_textarea.val().replace(/image/img,'<?php echo HTTP_CATALOG;?>image'));
            $htmlpreview.html(collapsible==1?htmlpreview[orientation]:htmlpreview[orientation].replace(/(collapsible|expanded|collapsed)/img,''));
        }


        $(".cssitem a").live('click',function(event){
            event.preventDefault();
            $(".cssitem").removeClass("cssitemchecked");
            $(this).parent().addClass("cssitemchecked");
            this_style_row = $(this).closest(".cssitem").attr("style_row");
            $(".csseditor[style_row='"+ this_style_row +"']").showcsseditor();
        })

        $(".cssitem:eq(0)").addClass("cssitemchecked");
        if($(".csseditor").length>0)$(".csseditor[style_row='0']").showcsseditor();

        $("#iaddstyle").live('click',function(){
            $sn = $(".cssitem a[stylename='"+ $("#addstyle").val() +"']");
            if($sn.length==0){
                addStyle();
            }else{
                $sn.wrap("<span style='background-color:red;width:100%'></span>");
                $sn.parent().fadeTo(500, .2, function(){$sn.unwrap();});
            }
        });

        $(".idelstyle").live('click',function(){
            this_style_row = $(this).parent().attr("style_row");
            val2del = $(this).prev("a").text();
            $(".select_display_type option").each(function(i,o){
                if($(o).text()==val2del){
                    $(o).remove();
                }
            });
            $(".csseditor[style_row='"+ this_style_row +"']").remove();
            $(this).parent().remove();
        });

        $("textarea.simonfilters_styles_textarea").live('keyup click', function(){
            $(this).closest(".csseditor").assigncss();
        })

        $("select.orientation").change(function(){
            $(this).closest(".csseditor").assigncss();
        });

        /* SCRIPT TO MAKE FILTERS PREVIEW */

        $(".add_filter, .clear_filters_local, .clear_filters_global").live('click', function(){

            switch($(this).hasClass('clear_filters_local')||$(this).hasClass('clear_filters_global')){
                case true:
                    if($(this).hasClass('clear_filters_local')){
                        $(this).closest("li").find("li.add_filter").removeClass('checked').addClass('unchecked');
                        $(this).fadeOut(100);
                        $checkedfilters = $($(".add_filter[class~='checked']").map(function() {  return this.id; })).toArray() ;
                        if($checkedfilters.length==0)$(".clear_filters_global").fadeOut(100);
                    }
                    if($(this).hasClass('clear_filters_global')){
                        $(".add_filter").removeClass('checked').addClass('unchecked');
                        $(".clear_filters_local").hide();
                        $(this).fadeOut(100);
                    }
                    break;
                case false:
                    $(this).toggleClass('checked').toggleClass('unchecked');

                    $checkedfilters = $($(".add_filter[class~='checked']").map(function() {  return this.id; })).toArray() ;
                    $(this).hasClass('checked')?$(this).closest('li.filter_group').find('.clear_filters_local').show():$(this).closest('li.filter_group').find('.clear_filters_local').hide();
                    $checkedfilters.length==0?$(".clear_filters_global").hide():$(".clear_filters_global").show();
                    break;
            }

        });

        $("li.collapsible div.filter_title").live('click', function(){
            $(this).fadeTo(100,.2, function(){$(this).fadeTo(100,1)});
            $(this).parent().toggleClass('collapsed').toggleClass('expanded');
            $(this).parent().hasClass('collapsed')?
                $(this).next("div.filter_ul").fadeOut(200):$(this).next("div.filter_ul").fadeIn(200);
        });

        $(".getDefault").click(function(event){
            if(confirm('All your changes will be lost and will be replaced by the default CSS for this Display Type!\n\nAre you sure?')){
                event.preventDefault();
                $(".content").fadeTo(100, .2);
                stylename = $(this).attr("stylename");
                $this_button = $(this);
                $.post(ajax_action_get_default_style, {stylename:stylename}, function(data){
                    $this_button.closest("table").find("textarea").val(data);
                    $(".content").fadeTo(100, 1);
                });
            }
        });

        $(".csseditor[canchange='0']").find("input").each(function(i,o){
            $(this).hide().after("<div class='fakeselect'>"+ $(this).val() +"</div>");
        })
        $(".csseditor[canchange='0']").find("select").each(function(i,o){
            $(this).hide().after("<div class='fakeselect'>"+ $(this).find("option:selected").text() +"</div>");
        })

        $( "#attr-sortable" ).sortable({
            update: function(event, ui){
                $ids = $($("#attr-sortable li>span").map(function() {  return this.id; })).toArray() ;
                $("input[name='simonfilters_sort']").val( $ids.join(',') );
            }
        });
        $( "#attr-sortable" ).disableSelection();


        $("#disable10").live('click',function(event){
            event.preventDefault();

            key = $(this).attr("key");
            nitems = parseInt($(this).attr("nitems"));

            var tab='';
            switch(key){
                case 'activefilter_simonfilters_attribute':
                    tab = 'tab-attributes'
                    break;
                case 'activefilter_simonfilters_options':
                    tab = 'tab-options'
                    break;
                case 'activefilter_simonfilters_tags':
                    tab = 'tab-tags';
                    break;
                case 'activefilter_simonfilters_manufacturer':
                    tab='tab-manufacturers';
                    break;
            }
            if(tab!=''){
                $("#"+ tab).find("input[type='checkbox']:checked").slice(-nitems).attr("checked", false );
                alert(nitems +' Items were disabled. Please try to save again.')
            }
        });
    });
</script>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
    </div>
    <?php if (!isset($simonfilters_category_domid[$config_template])||!isset($simonfilters_category_domid[$config_template])) { ?>
    <div class="warning"><?php echo $warning_unsupported_theme; ?></div>
        <?php } ?>
    <?php if (!$valid_main_index) { ?>
    <div class="warning"><?php echo $warning_main_index; ?>
        <br><br><div class="buttons"><a id="main_index_button" class="button"><span>Let me try to do that for you!</span></a></div>    
    </div>
        <?php } ?>
    <?php if (!$valid_admin_index) { ?>
    <div class="warning"><?php echo $warning_admin_index; ?>
        <br><br><div class="buttons"><a id="admin_index_button" class="button"><span>Let me try to do that for you!</span></a></div>    
    </div>
        <?php } ?>

    <?php
    //simonnew
    if (!$valid_invalid_tags) { ?>
    <div class="warning"><?php echo $warning_invalid_tags; ?>
        <br><br><div class="buttons"><a id="invalid_tags_button" class="button"><span>Let me try to do that for you!</span></a></div>
    </div>
        <?php } ?>

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
        <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?>
                <a href="http://opencartmodules.simonoop.com/forum" target="_blank">
                    <img src="http://images.simonoop.com/simon/logosimon101x22.png" alt="Simon Logo" />
                </a>
                -
                <?php echo $entry_SFs_version;?>

            </h1>
            <div class="buttons">

                <a id="simon_save_and_stay" class="button"><span>Save And Stay</span></a>
                <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
                <a id="help_button" class="button"><span>Help!!!</span></a>
            </div>
        </div>
        <div class="content">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <input type="hidden" name="simonfilterstimestamp">
                <div class="simonfilters">

                    <div id="tabs" class="htabs">
                        <a href="#tab-general">General</a>
                        <a href="#tab-debug">Debug</a>
                        <a href="#tab-javascript">Javascript</a>
                        <a href="#tab-sorting">Sorting</a>
                        <a href="#tab-theme"><?php echo $entry_theme_config;?></a>
                        <a href="#tab-styles"><?php echo $entry_styles;?></a>
                        <a href="#tab-placement">Placement</a>
                        <a href="#tab-filters">Filters</a>

                    </div>



                    <div class="simonfilters_content">

                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Placement
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-placement">
                            <h2>Placement - <span>If you're using the module in the <i>Category</i> Layout, then you can choose in which categories to show the module. <b>Tip:</b> If not sure, select all!</span></h2>
                            <input type="checkbox" id="select_all_placement">Select All
                            <?php
                            echo '<div class="scrollbox" style="height:300px" id="placement_checkboxes">';
                            $class = 'odd';
                            foreach ($categories as $category) {
                                $class = ($class == 'even' ? 'odd' : 'even');
                                ?>
                            <div class='<?php echo $class;?>'>
                                <input type='checkbox' name='sfspc[<?php echo $category['category_id'];?>]' value='1' <?php if (isset($sfspc[$category['category_id']]))echo "CHECKED"?>>
                                    <?php echo $category['name'];?>

                            </div>
                                <?php
                            }
                            echo "</div>";
                            ?>
                        </div>                         


                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Filters
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->                        
                        <div id="tab-filters" style="display:">
                            <h2>Filters - <span>Configure the filters and their behavior</span></h2>
                            <div id="tabsfilters" class="htabs">
                                <a href="#tab-attributes"><?php echo $entry_active_filters_attribute;?></a>
                                <a href="#tab-categories"><?php echo $entry_active_filters_categories;?></a>
                                <a href="#tab-options"><?php echo $entry_active_filters_options;?></a>
                                <a href="#tab-tags"><?php echo $entry_active_filters_tags;?></a>
                                <a href="#tab-manufacturers"><?php echo $entry_active_filters_manufacturer;?></a>
                                <a href="#tab-stocks"><?php echo $entry_active_filters_stock;?></a>
                                <a href="#tab-prices"><?php echo $entry_active_filters_price;?></a>                                
                            </div>
                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Attributes
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-attributes">
                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_attributes_behavior; ?></td>
                                        <td>
                                            <input value="1"  type="checkbox" name="activefilter_simonfilters_attributes_behavior" <?php if(isset($activefilter_simonfilters_attributes_behavior))echo " CHECKED"?>>
                                            <hr>
                                            <p class="hint"><span>Hint:</span>
                                                Select if you want the filter expanded or collapsed. Then select number of columns. Then if you want a "more.../less..." button or a scrollbar, enter the max number of items you want to show before showing the "more/less" button or scrollbar.
                                                <br>
                                                You must go to <i><a href="<?php echo str_replace('LINK','catalog/attribute',$generic_link);?>" target="_blank">Catalog->Attributes->Attributes</a></i> and enable the Attributes you wish to see here!
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <?php
                                            if (count($allfilters['attribute'])>0) {
                                                ?>
                                                <?php
                                                $attr_groups=array();

                                                foreach($allfilters['attribute'] as $attr) {
                                                    $attr_groups[$attr['attribute_group_id']][] = $attr;
                                                }
                                                ?>
                                            <!--
                                            </select>
                                            -->
                                                <?php
                                                foreach($attr_groups as $attribute_group_id=>$attribute) {
                                                    ?>
                                            <table class='form'>
                                                <tr><td><strong><?php echo $attribute[0]['groupname'];?></strong></td><td><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td></tr>
                                                        <?php
                                                        foreach($attribute as $k=>$filter) {
                                                            $style_display = isset($activefilter_simonfilters_attribute[$filter['id']]['a'])?'':"style='display:none'";
                                                            $disabled = isset($activefilter_simonfilters_attribute[$filter['id']]['a'])?'':"disabled='disabled'";
                                                            ?>
                                                <tr>
                                                    <td>
                                                        <input value='1'  type='checkbox' class='filter_selector' name='activefilter_simonfilters_attribute[<?php echo $filter['id'];?>][a]' <?php echo (isset($activefilter_simonfilters_attribute[$filter['id']]['a'])?'CHECKED':'');?>>
                                                        
                                                        <input name="simonfilters_hard[attribute][<?php echo $filter['id'];?>]" data-default='<?php echo $filter['name'];?>' value="<?php echo isset($simonfilters_hard['attribute'][$filter['id']])?$simonfilters_hard['attribute'][$filter['id']]:$filter['name'] ?>">
                                                                    <?php
                                                                    if( isset($simonfilters_hard['attribute'][$filter['id']]) && $simonfilters_hard['attribute'][$filter['id']]!= $filter['name']) {?>
                                                        <a href='#' class='reload'><img src='<?php echo HTTP_SERVER;?>/view/image/simonoop/reload.png'></a>
                                                                        <?php }?>
                                                    </td><td>
                                                        <select name="activefilter_simonfilters_attribute[<?php echo $filter['id']?>][b]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                            <option value="1" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['b']) && $activefilter_simonfilters_attribute[$filter['id']]['b']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                            <option value="0" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['b']) && $activefilter_simonfilters_attribute[$filter['id']]['b']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                                        </select>

                                                        <select name="activefilter_simonfilters_attribute[<?php echo $filter['id']?>][l]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                            <option value="1" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['l']) && $activefilter_simonfilters_attribute[$filter['id']]['l']=='1') echo " SELECTED" ?>>RadioButtons</option>
                                                            <option value="0" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['l']) && $activefilter_simonfilters_attribute[$filter['id']]['l']=='0') echo " SELECTED" ?>>CheckBoxes</option>
                                                            <option value="3" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['l']) && $activefilter_simonfilters_attribute[$filter['id']]['l']=='3') echo " SELECTED" ?>>Slider</option>
                                                            <!--
                                                            <option value="4" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['l']) && $activefilter_simonfilters_attribute[$filter['id']]['l']=='4') echo " SELECTED" ?>>Drop Down</option>
                                                            -->
                                                        </select>

                                                        <select name="activefilter_simonfilters_attribute[<?php echo $filter['id']?>][col]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                            <option value="1" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['col']) && $activefilter_simonfilters_attribute[$filter['id']]['col']=='1') echo " SELECTED" ?>>1 Column</option>
                                                            <option value="2" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['col']) && $activefilter_simonfilters_attribute[$filter['id']]['col']=='2') echo " SELECTED" ?>>2 Columns</option>
                                                        </select>          

                                                        <select name="activefilter_simonfilters_attribute[<?php echo $filter['id']?>][more]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                            <option value="0" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['more']) && $activefilter_simonfilters_attribute[$filter['id']]['more']=='0') echo " SELECTED" ?>>Normal</option>
                                                            <option value="1" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['more']) && $activefilter_simonfilters_attribute[$filter['id']]['more']=='1') echo " SELECTED" ?>>more.../less...</option>
                                                            <option value="2" <?php if(isset($activefilter_simonfilters_attribute[$filter['id']]['more']) && $activefilter_simonfilters_attribute[$filter['id']]['more']=='2') echo " SELECTED" ?>>Scrollbar</option>
                                                        </select>                                               
                                                        <input type="text" name="activefilter_simonfilters_attribute[<?php echo $filter['id']?>][more_number]" value="<?php echo isset($activefilter_simonfilters_attribute[$filter['id']]['more_number'])?$activefilter_simonfilters_attribute[$filter['id']]['more_number']:4;?>" <?php echo $style_display;?> <?php echo $disabled;?>>                                                            
                                                    </td></tr>
                                                            <?php
                                                        }
                                                        ?>
                                            </table>
                                                    <?php
                                                }
                                                ?>
                                                <?php }else {?>
                                            <h1>There are no currently active Attributes! Make sure you go to <a href="<?php echo str_replace('LINK','catalog/attribute',$generic_link);?>" target="_blank">Catalog->Attributes->Attributes</a> and enable some!</h1>
                                                <?php }?>
                                        </td>
                                    </tr>
                                </table></div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Cats
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-categories">
                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_expanded_collapsed; ?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_expanded[categories]">
                                                <option value="1" <?php if(isset($activefilter_simonfilters_expanded['categories']) && $activefilter_simonfilters_expanded['categories']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                <option value="0" <?php if(isset($activefilter_simonfilters_expanded['categories']) && $activefilter_simonfilters_expanded['categories']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Categories display<span class="help">Choose which categories are displayed</span></td>
                                        <td>
                                            <select name="simonfilters_categories_behavior">
                                                <option value="0" <?php if(isset($simonfilters_categories_behavior) && $simonfilters_categories_behavior=='0') echo " SELECTED" ?>>Show all categories</option>
                                                <option value="1" <?php if(isset($simonfilters_categories_behavior) && $simonfilters_categories_behavior=='1') echo " SELECTED" ?>>Show only current & child categories</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Auto-select<span class="help">Should the category be auto-selected</span></td>
                                        <td>
                                            <select name="simonfilters_categories_autoselect">
                                                <option value="0" <?php if(isset($simonfilters_categories_autoselect) && $simonfilters_categories_autoselect=='0') echo " SELECTED" ?>>Normal</option>
                                                <option value="1" <?php if(isset($simonfilters_categories_autoselect) && $simonfilters_categories_autoselect=='1') echo " SELECTED" ?>>Auto Select</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <table class="form">
                                                <tr>
                                                    <td colspan="2"><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td>
                                                </tr>

                                                <?php foreach($allfilters['categories'] as $filter) {?>
                                                <tr>
                                                    <td>
                                                        <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_categories[<?php echo $filter['category_id']?>][a]" <?php if(isset($activefilter_simonfilters_categories[$filter['category_id']]['a']))echo " CHECKED"?>><?php echo $filter['name'];?>
                                                    </td>
                                                </tr>
                                                    <?php }?>
                                            </table>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Options
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-options">
                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_options_behavior; ?></td>
                                        <td>
                                            <input value="1"  type="checkbox" name="activefilter_simonfilters_options_behavior" <?php if(isset($activefilter_simonfilters_options_behavior))echo " CHECKED"?>>
                                            <hr>
                                            <p class="hint"><span>Hint:</span>
                                                Select if you want the filter expanded or collapsed. Then select number of columns. Then if you want a "more.../less..." button or a scrollbar, enter the max number of items you want to show before showing the "more/less" button or scrollbar.
                                                <br>
                                                You must go to <i><a href="<?php echo str_replace('LINK','catalog/option',$generic_link);?>" target="_blank">Catalog->Options</a></i> and enable the Options you wish to see here!
                                            </p>                                                   
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <?php
                                            if (count($allfilters['options'])>0) {
                                                ?>
                                            <fieldset class='others_list'>
                                                <table class="form">
                                                    <tr>
                                                        <td colspan="2"><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td>
                                                    </tr>


                                                        <?php foreach($allfilters['options'] as $filter) {

                                                            $style_display = isset($activefilter_simonfilters_options[$filter['id']]['a'])?'':"style='display:none'";
                                                            $disabled = isset($activefilter_simonfilters_options[$filter['id']]['a'])?'':"disabled='disabled'";

                                                            ?>
                                                    <tr>
                                                        <td>
                                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_options[<?php echo $filter['id']?>][a]" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['a']))echo " CHECKED"?>>
                                                            <input name="simonfilters_hard[options][<?php echo $filter['id'];?>]" data-default="<?php echo $filter['name'];?>" value="<?php echo isset($simonfilters_hard['options'][$filter['id']])?$simonfilters_hard['options'][$filter['id']]:$filter['name'] ?>">
                                                                    <?php
                                                                    if( isset($simonfilters_hard['options'][$filter['id']]) && $simonfilters_hard['options'][$filter['id']]!= $filter['name']) {?>
                                                            <a href='#' class='reload'><img src='<?php echo HTTP_SERVER;?>/view/image/simonoop/reload.png'></a>
                                                                        <?php }?>
                                                        </td>
                                                        <td>
                                                            <select name="activefilter_simonfilters_options[<?php echo $filter['id']?>][b]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                                <option value="1" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['b']) && $activefilter_simonfilters_options[$filter['id']]['b']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                                <option value="0" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['b']) && $activefilter_simonfilters_options[$filter['id']]['b']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                                            </select>

                                                            <select name="activefilter_simonfilters_options[<?php echo $filter['id']?>][l]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                                <option value="1" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['l']) && $activefilter_simonfilters_options[$filter['id']]['l']=='1') echo " SELECTED" ?>>RadioButtons</option>
                                                                <option value="0" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['l']) && $activefilter_simonfilters_options[$filter['id']]['l']=='0') echo " SELECTED" ?>>CheckBoxes(OR)</option>
                                                                <option value="2" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['l']) && $activefilter_simonfilters_options[$filter['id']]['l']=='2') echo " SELECTED" ?>>CheckBoxes(AND)</option>
                                                            </select>

                                                            <select name="activefilter_simonfilters_options[<?php echo $filter['id']?>][col]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                                <option value="1" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['col']) && $activefilter_simonfilters_options[$filter['id']]['col']=='1') echo " SELECTED" ?>>1 Column</option>
                                                                <option value="2" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['col']) && $activefilter_simonfilters_options[$filter['id']]['col']=='2') echo " SELECTED" ?>>2 Columns</option>
                                                            </select>  
                                                            <select name="activefilter_simonfilters_options[<?php echo $filter['id']?>][more]" <?php echo $style_display;?> <?php echo $disabled;?>>
                                                                <option value="0" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['more']) && $activefilter_simonfilters_options[$filter['id']]['more']=='0') echo " SELECTED" ?>>Normal</option>
                                                                <option value="1" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['more']) && $activefilter_simonfilters_options[$filter['id']]['more']=='1') echo " SELECTED" ?>>more.../less...</option>
                                                                <option value="2" <?php if(isset($activefilter_simonfilters_options[$filter['id']]['more']) && $activefilter_simonfilters_options[$filter['id']]['more']=='2') echo " SELECTED" ?>>Scrollbar</option>
                                                            </select>                                               
                                                            <input type="text" name="activefilter_simonfilters_options[<?php echo $filter['id']?>][more_number]" value="<?php echo isset($activefilter_simonfilters_options[$filter['id']]['more_number'])?$activefilter_simonfilters_options[$filter['id']]['more_number']:4;?>" <?php echo $style_display;?> <?php echo $disabled;?>>                                                                                                                                
                                                        </td>
                                                    </tr>
                                                            <?php }?>
                                                </table>
                                            </fieldset>
                                                <?php }else {?>
                                            <h1>There are no currently active Options. Make sure you go to <a href="<?php echo str_replace('LINK','catalog/option',$generic_link);?>" target="_blank">Catalog->Options</a> and enable some!</h1>
                                                <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Tags
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-tags">

                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_expanded_collapsed; ?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_expanded[tags]">
                                                <option value="1" <?php if(isset($activefilter_simonfilters_expanded['tags']) && $activefilter_simonfilters_expanded['tags']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                <option value="0" <?php if(isset($activefilter_simonfilters_expanded['tags']) && $activefilter_simonfilters_expanded['tags']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <?php
                                            if (count($allfilters['tags'])>0) {
                                                ?>
                                            <fieldset class='others_list'>
                                                <table class="form">
                                                    <tr>
                                                        <td colspan="2"><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td>
                                                    </tr>
                                                        <?php foreach($allfilters['tags'] as $filter) {?>
                                                    <tr>
                                                        <td>
                                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_tags[<?php echo $filter['product_tag_id']?>][a]" <?php if(isset($activefilter_simonfilters_tags[$filter['product_tag_id']]['a']))echo " CHECKED"?>><?php echo $filter['tag'];?>
                                                        </td>

                                                    </tr>
                                                            <?php }?>
                                                </table>
                                            </fieldset>
                                                <?php }else {?>
                                            <h1>There are no currently active Tags.</h1>
                                                <?php }?>
                                        </td>
                                    </tr>
                                </table>

                            </div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Manufacturers
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-manufacturers">
                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_expanded_collapsed; ?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_expanded[manufacturers]">
                                                <option value="1" <?php if(isset($activefilter_simonfilters_expanded['manufacturers']) && $activefilter_simonfilters_expanded['manufacturers']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                <option value="0" <?php if(isset($activefilter_simonfilters_expanded['manufacturers']) && $activefilter_simonfilters_expanded['manufacturers']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                            </select>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Auto-select<span class="help">Should the manufacturer be auto-selected</span></td>
                                        <td>
                                            <select name="simonfilters_manufacturers_autoselect">
                                                <option value="0" <?php if(isset($simonfilters_manufacturers_autoselect) && $simonfilters_manufacturers_autoselect=='0') echo " SELECTED" ?>>Normal</option>
                                                <option value="1" <?php if(isset($simonfilters_manufacturers_autoselect) && $simonfilters_manufacturers_autoselect=='1') echo " SELECTED" ?>>Auto Select</option>
                                            </select>
                                            <hr>
                                            <p class="hint"><span>Hint:</span>
                                                Select the Manufacturer you want to see in the filter
                                                <br>
                                                You must go to <i><a href="<?php echo str_replace('LINK','catalog/manufacturer',$generic_link);?>" target="_blank">Catalog->Manufacturers</a></i> and enable the Manufacturer you wish to see here!<br>
                                                Forgive the redundancy.
                                            </p> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <?php
                                            if (count($allfilters['manufacturer'])>0) {
                                                ?>
                                            <fieldset class='others_list'>
                                                <table class="form">
                                                    <tr>
                                                        <td><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td>
                                                    </tr>
                                                        <?php foreach($allfilters['manufacturer'] as $filter) {?>
                                                    <tr><td>
                                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_manufacturer[<?php echo $filter['id']?>][a]" <?php if(isset($activefilter_simonfilters_manufacturer[$filter['id']]['a']))echo " CHECKED"?>><?php echo $filter['name'];?>
                                                        </td></tr>
                                                            <?php }?>
                                                </table>
                                            </fieldset>
                                                <?php }else {?>
                                            <h1>There are no currently active Manufacturers.  Make sure you go to <a href="<?php echo str_replace('LINK','catalog/manufacturer',$generic_link);?>" target="_blank">Catalog->Manufacturers</a> and enable some!</h1>
                                                <?php }?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Stocks
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-stocks">
                                <table class="attr_item">
                                    <col style="width:220px">
                                    <tr>
                                        <td><?php echo $entry_expanded_collapsed; ?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_expanded[stocks]">
                                                <option value="1" <?php if(isset($activefilter_simonfilters_expanded['stocks']) && $activefilter_simonfilters_expanded['stocks']=='1') echo " SELECTED" ?>><?php echo $text_type_expanded; ?></option>
                                                <option value="0" <?php if(isset($activefilter_simonfilters_expanded['stocks']) && $activefilter_simonfilters_expanded['stocks']=='0') echo " SELECTED" ?>><?php echo $text_type_collapsed; ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <?php
                                            if (count($allfilters['stock'])>0) {
                                                ?>
                                            <fieldset class='others_list'>
                                                <table class="form">
                                                    <tr>
                                                        <td><input type='checkbox' class='filter_selector_selector'>Select / Unselect All</td>
                                                    </tr>
                                                        <?php foreach($allfilters['stock'] as $filter) {?>
                                                    <tr><td>
                                                            <input value="1" type="checkbox" class="filter_selector" name="activefilter_simonfilters_stock[<?php echo $filter['id']?>][a]" <?php if(isset($activefilter_simonfilters_stock[$filter['id']]['a']))echo " CHECKED"?>><?php echo $filter['name'];?>
                                                        </td></tr>
                                                            <?php }?>
                                                </table>
                                            </fieldset>
                                                <?php }else {?>
                                            <h1>There are no currently active Stock filters.</h1>
                                                <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_activefilter_simonfilters_stock_instock?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_stock_instock">
                                                <?php foreach ($stock_statuses as $stock_status) { ?>
                                                    <?php if ($stock_status['stock_status_id'] == $activefilter_simonfilters_stock_instock) { ?>
                                                <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                                                        <?php } else { ?>
                                                <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                            </select>

                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-------------------------------------------------------------------------------------------------------------------------------------
                            #######################################################################################################################################
                            #
                            # Prices
                            #
                            #######################################################################################################################################
                            -------------------------------------------------------------------------------------------------------------------------------------->
                            <div id="tab-prices">
                                <table class="form">
                                    <col style="width:200px">
                                    <tr>
                                        <td><?php echo $entry_active_filters; ?></td>
                                        <td>
                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_price[0][a]" <?php if(isset($activefilter_simonfilters_price[0]['a']))echo " CHECKED"?>><?php echo $entry_active_filters_price;?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_force_currency; ?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_price[force_currency]">
                                                <option value="user-defined"<?php if(isset($activefilter_simonfilters_price['force_currency']) && $activefilter_simonfilters_price['force_currency']==0)echo " SELECTED"?>>Use whatever currency the customer is using</option>
                                                <?php foreach($currencies as $currency) {?>
                                                    <?php if ($currency['status']) {?>
                                                <option value="<?php echo $currency['code'];?>"<?php if(isset($activefilter_simonfilters_price['force_currency']) && $activefilter_simonfilters_price['force_currency']==$currency['code'])echo " SELECTED"?>><?php echo $currency['code'];?></option>
                                                        <?php }?>
                                                    <?php }?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><?php echo $entry_active_filters_price_type?></td>
                                        <td>
                                            <select name="activefilter_simonfilters_price[type]">
                                                <option value="1"<?php if(isset($activefilter_simonfilters_price['type']) && $activefilter_simonfilters_price['type']==1)echo " SELECTED"?>>Slider</option>
                                                <option value="0"<?php if(isset($activefilter_simonfilters_price['type']) && $activefilter_simonfilters_price['type']==0)echo " SELECTED"?>>Intervals</option>
                                            </select>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td><?php echo $entry_active_filters_price_tax; ?></td>
                                        <td>
                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_price[tax]" <?php if(isset($activefilter_simonfilters_price['tax']))echo " CHECKED"?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_active_filters_price_special; ?></td>
                                        <td>
                                            <input value="1"  type="checkbox" class="filter_selector" name="activefilter_simonfilters_price[special]" <?php if(isset($activefilter_simonfilters_price['special']))echo " CHECKED"?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_filters_slider_step; ?></td>
                                        <td>
                                            <input type="text" name="simonfilters_slider_step" value="<?php echo $simonfilters_slider_step;?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_filters_hide_products_less; ?></td>
                                        <td>
                                            <input type="text" name="activefilter_simonfilters_price[min_products]" value="<?php echo isset($activefilter_simonfilters_price['min_products'])?$activefilter_simonfilters_price['min_products']:2;?>" />
                                        </td>
                                    </tr>       
                                    <tr>
                                        <td><?php echo $entry_filters_hide_products_less_price; ?></td>
                                        <td>
                                            <input type="text" name="activefilter_simonfilters_price[min_price]" value="<?php echo isset($activefilter_simonfilters_price['min_price'])?$activefilter_simonfilters_price['min_price']:-1;?>" />
                                        </td>
                                    </tr>                                    
                                </table>
                            </div>                            
                        </div>

                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Styles
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-styles" style="display:">
                            <h2>CSS config - <span>Configure the css layout. Edit the defaults or create your own!</span></h2>
                            <table class="attr_item">
                                <col style="width:220px">
                                <tr valign="top">
                                    <td class="voption">
                                        <span id="style_menu_container">
                                            <?php $style_row = 0; ?>
                                            <?php foreach ($simonfilters_styles as $style) { ?>
                                                <?php $canChange = !in_array($style['name'], array('default','checkbox','dropdown','horizontal'))?>
                                            <div class="cssitem" style_row="<?php echo $style_row;?>">
                                                <a href='#' stylename="<?php echo $style['name'];?>"><?php echo $style['name'];?></a>
                                                    <?php if($canChange) {?>
                                                <img alt="Delete Style" alt="Delete Style" class="idelstyle" src="view/image/delete.png">
                                                        <?php }?>
                                            </div>
                                                <?php
                                                $style_row++;
                                            }?>
                                        </span>
                                        <div class="cssitemclone" style="float:right;padding-right: 15px">
                                            <input id="addstyle"/>
                                            <img title="Add Style" alt="Add Style" id="iaddstyle" src="view/image/add.png">
                                        </div>
                                    </td>
                                    <td id="style_form_container">
                                        <?php $style_row = 0; ?>
                                        <?php foreach ($simonfilters_styles as $style) { ?>
                                            <?php $canChange = !in_array($style['name'], array('default','checkbox','dropdown','horizontal'))?>
                                        <div class="csseditor" style="display:none" style_row="<?php echo $style_row;?>" canchange="<?php echo $canChange==''?'0':'1';?>">
                                            <table class="form" id="style_table">
                                                <col style="width:100px">
                                                <col style="width:400px">
                                                <col style="width:400px">
                                                <tr>
                                                    <td>Name</td>
                                                    <td><input type="text" name="simonfilters_styles[<?php echo $style_row;?>][name]" value="<?php echo $style['name'];?>" />
                                                            <?php if(!$canChange) {?>
                                                        &nbsp;<a class="button getDefault" stylename="<?php echo $style['name'];?>"><span>Get default CSS</span></a>
                                                                <?php }?>
                                                    </td>
                                                    <td>Preview</td>
                                                </tr>
                                                <tr>
                                                    <td>Css</td>
                                                    <td>
                                                        <textarea class="simonfilters_styles_textarea" name="simonfilters_styles[<?php echo $style_row;?>][css]"><?php echo $style['css'];?></textarea>
                                                    </td>
                                                    <td rowspan="3" class="simonpreviewpane" style="padding:10px">
                                                        <div class="divpreview" orientation="vertical" style="display:none">
                                                            <style type="text/css" class="stylepreview"></style>
                                                            <span class="htmlpreview"></span>
                                                        </div>
                                                        <div class="divpreview" orientation="horizontal" style="display:none">
                                                            <style type="text/css" class="stylepreview"></style>
                                                            <span class="htmlpreview"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Orientation</td>
                                                    <td>
                                                        <select name="simonfilters_styles[<?php echo $style_row;?>][orientation]" class="orientation">
                                                            <option value="vertical" <?php if(isset($simonfilters_styles[$style_row]['orientation']) && $simonfilters_styles[$style_row]['orientation']=='vertical')echo "SELECTED"?>>Vertical</option>
                                                            <option value="horizontal" <?php if(isset($simonfilters_styles[$style_row]['orientation']) && $simonfilters_styles[$style_row]['orientation']=='horizontal')echo "SELECTED"?>>Horizontal</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Collapsible</td>
                                                    <td>
                                                        <select name="simonfilters_styles[<?php echo $style_row;?>][collapsible]" class="collapsible">
                                                            <option value="0" <?php if(isset($simonfilters_styles[$style_row]['collapsible']) && $simonfilters_styles[$style_row]['collapsible']=='0')echo "SELECTED"?>>No</option>
                                                            <option value="1" <?php if(isset($simonfilters_styles[$style_row]['collapsible']) && $simonfilters_styles[$style_row]['collapsible']=='1')echo "SELECTED"?>>Yes</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                            <?php
                                            $style_row++;
                                        } ?>
                                    </td>
                                </tr>

                            </table>
                        </div>

                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Javascript
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-javascript">
                            <h2>Javascript - <span>When Ajax is enabled the module will try to execute the page's JavaScript. Tell the module what to do. Ask <a href='mailto:oc@simonoop.com'>Simon</a> if javascript related things stop working!</span></h2>
                            <table class="attr_item">
                                <col style="width:220px">

                                <tr valign="top">
                                    <td><?php echo $entry_script_ignore; ?></td>
                                    <td colspan="4">
                                        <input type="checkbox" name="simonfilters_script_ignore" value="1" <?php if($simonfilters_script_ignore=='1') echo "CHECKED";?>>
                                        <input type="text" style="width:90%" name="simonfilters_script_ignore_text" value="<?php echo $simonfilters_script_ignore_text;?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_execute_external_js; ?></td>
                                    <td colspan="4">
                                        <input type="checkbox" name="simonfilters_execute_external_js" value="1" <?php if($simonfilters_execute_external_js=='1') echo "CHECKED";?>>
                                        <input type="text" style="width:90%" name="simonfilters_ignore_scripts_names" value="<?php echo $simonfilters_ignore_scripts_names;?>">
                                    </td>
                                </tr>  
                            </table>

                        </div>                                                    


                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Sorting
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-sorting">
                            <h2>Sorting - <span>Drag and drop the groups!</span></h2>
                            <table class="attr_item">
                                <col style="width:220px">

                                <tr valign="top">
                                    <td><?php echo $entry_client_side_sorting; ?></td>
                                    <td>
                                        <ul id="attr-sortable">
                                            <?php
                                            $attrnames = array('a'=>'Attributes','o'=>'Options', 't'=>'Tags', 'm'=>'Manufacturers', 's'=>'Stocks', 'p'=>'Price' ,'c'=>'Categories');
                                            foreach(preg_split('/,/',$simonfilters_sort) as $attrid) {?>
                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" id="<?php echo $attrid;?>"></span><?php echo $attrnames[$attrid];?></li>
                                                <?php }?>
                                        </ul>
                                        <input type="hidden" name="simonfilters_sort" value="<?php echo isset($simonfilters_sort)?$simonfilters_sort:''?>"/>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_client_side_items_sorting; ?></td>
                                    <td>
                                        <select name="client_side_items_sorting">
                                            <option value="0" <?php if($client_side_items_sorting=='0') echo " SELECTED" ?>>OpenCart's sort_order</option>
                                            <option value="1" <?php if($client_side_items_sorting=='1') echo " SELECTED" ?>>Alphabetically</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # General
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-general">
                            <h2>General - <span>Generic config options!</span></h2>
                            <table class="attr_item" style="width:890px">
                                <col style="width:220px">
                                <col style="width:220px">
                                <col style="width:2px">
                                <col style="width:220px">
                                <col style="width:220px">
                                <!--
                                <tr valign="top">
                                    <td><?php echo $entry_search_updates ; ?></td>
                                    <td colspan="4">
                                        <a href="#" class="button" id="search_updates"><span><?php echo $entry_search_updates_button; ?></span></a>
                                    </td>                                                                       
                                </tr>   
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>        
                                -->
                                <tr valign="top">
                                    <td><?php echo $entry_client_side_attributes; ?></td>
                                    <td>
                                        <a href="#" class="button" id="rebuild_filters"><span><?php echo $entry_client_side_attributes_button; ?></span></a>
                                    </td>
                                    <td></td>
                                    <td><?php echo $entry_dynamic; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_dynamic" value="1" <?php if($simonfilters_dynamic=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_filter_persist; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_persist" value="1" <?php if($simonfilters_persist=='1') echo "CHECKED";?>>
                                    </td>
                                    <td></td>                                 
                                    <td><?php echo $entry_attribute_separator; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_enable_attribute_separator" value="1" <?php if($simonfilters_enable_attribute_separator=='1') echo "CHECKED";?>>
                                        <input type="text" name="simonfilters_attribute_separator_char" value="<?php echo $simonfilters_attribute_separator_char;?>" maxlength="50">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_option_quantity_zero; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_option_zero_quantity" value="1" <?php if($simonfilters_option_zero_quantity=='1') echo "CHECKED";?>>
                                    </td>
                                    <td></td>
                                    <td><?php echo $entry_force_all; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_force_all" value="1" <?php if($simonfilters_force_all=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_display_totals ; ?></td>
                                    <td>
                                        <select name="simonfilters_display_totals">
                                            <option value="0" <?php if($simonfilters_display_totals=='0') echo " SELECTED";?>><?php echo $text_type_no; ?></option>
                                            <option value="1" <?php if($simonfilters_display_totals=='1') echo " SELECTED";?>><?php echo $text_type_yes; ?> (No Cache)</option>
                                            <option value="2" <?php if($simonfilters_display_totals=='2') echo " SELECTED";?>><?php echo $text_type_yes; ?> (With Cache)</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td><?php echo $entry_disableajax ; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_disableajax" value="1" <?php if($simonfilters_disableajax=='1') echo "CHECKED";?>>
                                    </td>                                                                     
                                </tr>
                                <tr valign="top">
                                    <td colspan="5"><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_display_type ; ?></td>
                                    <td>
                                        <select name="simonfilters_display_type" class="select_display_type">
                                            <?php foreach ($simonfilters_styles as $stylekey=>$style) { ?>
                                            <option value="<?php echo $stylekey;?>" <?php if(isset($simonfilters_display_type) && $simonfilters_display_type==$stylekey) {
                                                    echo "SELECTED";
                                                        }?>><?php echo $style['name'];?></option>
                                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td><?php echo $entry_cache_buster ; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_cache_buster" value="1" <?php if($simonfilters_cache_buster=='1') echo "CHECKED";?>>
                                    </td>                                                                     
                                </tr>


                            </table>
                        </div>
                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Debug
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-debug">
                            <h2>Debug - <span>These could be useful if you need to debug the site. Remember that adding <i>(& or ?)debug=itsmesimon</i> to any store url will show the debug console! The console will always be shown until you replace itsmesimon with something else!</span></h2>
                            <table class="attr_item" style="width:440px">
                                <col style="width:220px">
                                <col style="width:220px">
                                <tr valign="top">
                                    <td><?php echo $entry_allow_debug_data; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_allow_debug_data" value="1" <?php if($simonfilters_allow_debug_data=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><hr /></td>
                                    <td><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_enable_front_end_diagnostics; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_front_end_diagnostics" value="1" <?php if($simonfilters_front_end_diagnostics=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><hr /></td>
                                    <td><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_delete_cache_for_debug; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_delete_cache_for_debug" value="1" <?php if($simonfilters_delete_cache_for_debug=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><hr /></td>
                                    <td><hr /></td>
                                </tr>                                
                                <tr valign="top">
                                    <td><?php echo $entry_prevent_admin_delete_cache_on_save; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_prevent_admin_delete_cache_on_save" value="1" <?php if($simonfilters_prevent_admin_delete_cache_on_save=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-------------------------------------------------------------------------------------------------------------------------------------
                        #######################################################################################################################################
                        #
                        # Theme config
                        #
                        #######################################################################################################################################
                        -------------------------------------------------------------------------------------------------------------------------------------->
                        <div id="tab-theme">
                            <h2>Theme config - <span>If Ajax is enabled, the module needs to know the ids or class names of the relevant container used by your theme. Ask <a href='mailto:oc@simonoop.com'>Simon</a> if not sure! Try entering "#content" in all fields as it'll work on some 90% of the times!</span></h2>
                            <table class="attr_item">
                                <col style="width:220px">
                                <tr valign="top">
                                    <td><?php echo $entry_category_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_category_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_category_domid['default']=='')? '#content' : (isset($simonfilters_category_domid[$config_template])?$simonfilters_category_domid[$config_template]:''));?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_manufacturer_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_manufacturer_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_manufacturer_domid['default']=='')? '#content' : (isset($simonfilters_manufacturer_domid[$config_template])?$simonfilters_manufacturer_domid[$config_template]:''));?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_homepage_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_homepage_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_homepage_domid['default']=='')? '#content' : (isset($simonfilters_homepage_domid[$config_template])?$simonfilters_homepage_domid[$config_template]:''));?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_search_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_search_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_search_domid['default']=='')? '#content' : (isset($simonfilters_search_domid[$config_template])?$simonfilters_search_domid[$config_template]:''));?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_product_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_product_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_product_domid['default']=='')? '#content' : (isset($simonfilters_product_domid[$config_template])?$simonfilters_product_domid[$config_template]:''));?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_special_domid; ?></td>
                                    <td>
                                        <input name="simonfilters_special_domid[<?php echo $config_template;?>]" value="<?php echo ( ($config_template=='default'&& $simonfilters_special_domid['default']=='')? '#content' : (isset($simonfilters_special_domid[$config_template])?$simonfilters_special_domid[$config_template]:''));?>">
                                    </td>
                                </tr>                                
                                <tr valign="top">
                                    <td><hr /></td>
                                    <td><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_force_siblings; ?></td>
                                    <td>
                                        <input type="checkbox" name="simonfilters_force_siblings[<?php echo $config_template;?>]" value="true" <?php if( isset($simonfilters_force_siblings[$config_template]) && $simonfilters_force_siblings[$config_template]=='1') echo "CHECKED";?>>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td><hr /></td>
                                    <td><hr /></td>
                                </tr>
                                <tr valign="top">
                                    <td><?php echo $entry_get_domids; ?></td>
                                    <td>
                                        <a href="#" class="button" id="get_domids"><span><?php echo $entry_get_domids_button; ?></span></a>
                                    </td>
                                </tr>
                            </table>
                        </div>



                    </div>
                </div>

                <!-------------------------------------------------------------------------------------------------------------------------------------
                #######################################################################################################################################
                #
                # Module List
                #
                #######################################################################################################################################
                -------------------------------------------------------------------------------------------------------------------------------------->
                <div class="warning" id="homepagedefaultwarning" style="display:none">When "Home" Layout is selected you must also select "Default" Layout.</div>
                <table id="module" class="list">
                    <thead>
                        <tr>
                            <td class="left"><?php echo $entry_layout; ?></td>
                            <td class="left"><?php echo $entry_position; ?></td>
                            <td class="left"><?php echo $entry_status; ?></td>
                            <td class="right"><?php echo $entry_sort_order; ?></td>
                            <td></td>
                        </tr>
                    </thead>
                    <?php $module_row = 0; ?>
                    <?php foreach ($modules as $module) { ?>
                    <tbody id="module-row<?php echo $module_row; ?>">
                        <tr>
                            <td class="left"><select name="simonfilters_module[<?php echo $module_row; ?>][layout_id]"  class="filter_module_layout_id">
                                        <?php foreach ($layouts as $layout) { ?>
                                            <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                                <?php } else { ?>
                                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                </select></td>
                            <td class="left"><select name="simonfilters_module[<?php echo $module_row; ?>][position]"  class="filter_module_position">
                                        <?php if ($module['position'] == 'content_top') { ?>
                                    <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                                            <?php } else { ?>
                                    <option value="content_top"><?php echo $text_content_top; ?></option>
                                            <?php } ?>
                                        <?php if ($module['position'] == 'content_bottom') { ?>
                                    <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                                            <?php } else { ?>
                                    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                                            <?php } ?>
                                        <?php if ($module['position'] == 'column_left') { ?>
                                    <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                                            <?php } else { ?>
                                    <option value="column_left"><?php echo $text_column_left; ?></option>
                                            <?php } ?>
                                        <?php if ($module['position'] == 'column_right') { ?>
                                    <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                                            <?php } else { ?>
                                    <option value="column_right"><?php echo $text_column_right; ?></option>
                                            <?php } ?>
                                </select></td>
                            <td class="left"><select name="simonfilters_module[<?php echo $module_row; ?>][status]">
                                        <?php if ($module['status']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                </select></td>
                            <td class="right"><input type="text" name="simonfilters_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                        </tr>
                    </tbody>
                        <?php $module_row++; ?>
                        <?php } ?>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>

    <!-------------------------------------------------------------------------------------------------------------------------------------
    #######################################################################################################################################
    #
    # Scripts
    #
    #######################################################################################################################################
    -------------------------------------------------------------------------------------------------------------------------------------->

    <script type="text/javascript"><!--
        var module_row = <?php echo $module_row; ?>;
        var style_row = <?php echo $style_row; ?>;
        function addStyle() {

            html ='<div class="cssitem" style_row="'+ style_row +'">';
            html +='<a href="#" stylename="'+ $("#addstyle").val() +'">'+ $("#addstyle").val() +'</a>';
            html +='<img alt="Delete Style" alt="Delete Style" class="idelstyle" src="view/image/delete.png">';
            html +='</div>';

            $('#style_menu_container').append(html);

            html ='<div class="csseditor" style="display:none" style_row="'+ style_row +'">';
            html +='<table class="form">';
            html +='<col style="width:auto">';
            html +='<col style="width:40%">';
            html +='<col style="width:auto">';
            html +='<tr>';
            html +='<td>Name</td>';
            html +='<td><input type="text" name="simonfilters_styles['+ style_row +'][name]" value="'+ $("#addstyle").val() +'" /></td>';
            html +='<td>Preview</td>';
            html +='</tr>';
            html +='<tr>';
            html +='<td>Css</td>';
            html +='<td>';
            html +='<textarea class="simonfilters_styles_textarea" name="simonfilters_styles['+ style_row +'][css]"></textarea>';
            html +='</td>';
            html +='<td rowspan="3" class="simonpreviewpane">';
            html +='<div class="divpreview" orientation="vertical" style="display:none">';
            html +='<style type="text/css" class="stylepreview"></style>';
            html +='<span class="htmlpreview"></span>';
            html +='</div>';
            html +='<div class="divpreview" orientation="horizontal" style="display:none">';
            html +='<style type="text/css" class="stylepreview"></style>';
            html +='<span class="htmlpreview"></span>';
            html +='</div>';
            html +='</td>';
            html +='</tr>';
            html +='<tr>';
            html +='<td>Orientation</td>';
            html +='<td>';
            html +='<select name="simonfilters_styles['+ style_row +'][orientation]" class="orientation">';
            html +='<option value="vertical">Vertical</option>';
            html +='<option value="horizontal">Horizontal</option>';
            html +='</select>';
            html +='</td>';
            html +='</tr>';
            html +='<tr>';
            html +='<td>Collapsible</td>';
            html +='<td>';
            html +='<select name="simonfilters_styles['+ style_row +'][collapsible]" class="collapsible">';
            html +='<option value="0">No</option>';
            html +='<option value="1">Yes</option>';
            html +='</select>';
            html +='</td>';
            html +='</tr>';
            html +='</table>';
            html +='</div>';
            $('#style_form_container').append(html);

            $("#addstyle").val('');
            $(".cssitem[style_row='"+ style_row +"'] a").click();
            style_row++;
        }

        function addModule() {
            html = '<tbody id="module-row' + module_row + '">';
            html += '  <tr>';
            html += '    <td class="left"><select name="simonfilters_module[' + module_row + '][layout_id]" class="filter_module_layout_id">';
<?php foreach ($layouts as $layout) { ?>
        html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
    <?php } ?>
            html += '    </select></td>';
            html += '    <td class="left"><select name="simonfilters_module[' + module_row + '][position]" class="filter_module_position">';
            html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
            html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
            html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
            html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
            html += '    </select></td>';


            html += '    <td class="left"><select name="simonfilters_module[' + module_row + '][status]">';
            html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
            html += '      <option value="0"><?php echo $text_disabled; ?></option>';
            html += '    </select></td>';
            html += '    <td class="right"><input type="text" name="simonfilters_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
            html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
            html += '  </tr>';
            html += '</tbody>';

            $('#module tfoot').before(html);

            module_row++;
        }
        //--></script>

    <script type="text/javascript"><!--
        $('#tabs a').tabs();
        $('#tabsfilters a').tabs();
        $("#content").show();

        $("a[href='#tab-debug']").addClass("tab_red");
        $("a[href='#tab-javascript']").addClass("tab_red");

        $("a[href='#tab-attributes']").addClass("tab_filters");
        $("a[href='#tab-options']").addClass("tab_filters");
        $("a[href='#tab-tags']").addClass("tab_filters");
        $("a[href='#tab-manufacturers']").addClass("tab_filters");
        $("a[href='#tab-stocks']").addClass("tab_filters");
        $("a[href='#tab-prices']").addClass("tab_filters");
        $("a[href='#tab-categories']").addClass("tab_filters");
        $("a[href='#tab-filters']").addClass("tab_filters");

        $(".filter_module_layout_id").live('change',function(){
            if($(this).find("option:selected").val()==1){
                existsdefault=false;
                $(".filter_module_layout_id").each(function(){
                    if($(this).find("option:selected").val()==4){
                        existsdefault = true;
                    }
                });
                if(!existsdefault){
                    $("#homepagedefaultwarning").show();
                }
            }
            if($(this).find("option:selected").val()==4){
                $("#homepagedefaultwarning").hide();
            }
        });

        $(".filter_module_layout_id").each(function(){
            if($(this).find("option:selected").val()==1){
                existsdefault=false;
                $(".filter_module_layout_id").each(function(){
                    if($(this).find("option:selected").val()==4){
                        existsdefault = true;
                    }
                });
                if(!existsdefault){
                    $("#homepagedefaultwarning").show();
                }
            }
        });

        //--></script>
</div>
<?php echo $footer; ?>