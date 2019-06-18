<?php

/*
 * simonfilters - 2.11.0 Build 0013
*/

class ControllerModuleSimonFilters extends Controller {

    public function gethash() {
        echo md5($_SERVER['QUERY_STRING']);
    }

    public function checkvalidity() {

        if (isset($_SESSION['simonfilters']['filterstimestamp'])) {
            $this->settingLastModified = $this->config->get('simonfilterstimestamp');
            if ($_SESSION['simonfilters']['filterstimestamp'] < $this->settingLastModified) {
                $_SESSION['simonfilters'] = null;
                $_SESSION['simonfilters']['filterstimestamp'] = time();
            }
        } else {
            $_SESSION['simonfilters']['filterstimestamp'] = time();
        }

        if ($this->config->get("simonfilters_delete_cache_for_debug") == 1) {
            $this->cache->delete("simonfilters");
            $this->cache->delete("product");

            $vqmoddir = preg_replace('/system[\\|\/]$/', 'vqmod/vqcache', DIR_SYSTEM);
            $files = glob("$vqmoddir/*product*");
            if ($files) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }

        }
    }

    function get_clear_filters_status($filter_type_name, $group_id, $filter_group, $currentfilters) {
        if (!is_array($currentfilters)) {
            die("SimonFilters has detected a major problem in CurrentFilters.<br><br>");
        }
        $clear_filters_display = 'style=\'display:none\'';
        foreach ($filter_group as $filter_id => $filter_group_item) {
            if (is_numeric($filter_id)) {
                foreach ($filter_group_item['filter_name'] as $key => $filter_item) {
                    if (in_array("{$filter_type_name}.{$group_id}.{$filter_id}.{$key}", $currentfilters)) {
                        $clear_filters_display = '';
                    }
                }
            }
        }
        return $clear_filters_display;
    }

    protected function generateHTML($display_type) {

        $sfs = $this->model_catalog_simonfilters->data;

        $logicalRelations['o'] = $this->config->get("activefilter_simonfilters_options");
        $logicalRelations['a'] = $this->config->get("activefilter_simonfilters_attribute");

        $this->data['filterCount'] = count($sfs);
        $this->data['showprice'] = false;
        $this->data['debug_mode'] = $this->model_catalog_simonfilters->debugmode;
        $this->data['simondebugjs1'] = '';
        $this->data['simondebugjs2'] = '';
        if ($this->data['debug_mode']) {
            $this->data['simondebugjs1'] = "
                    $('li.add_filter').each(function(){ $(this).attr('title',$(this).attr('id')); });
                var sfprex=400;
                var sfprey=10;
                $('pre').prepend( $('<a href=\'#\'>[x]</a>').click(function(event){event.preventDefault();$(this).parent().remove();}) )
                $('pre').appendTo('body');
                $('pre').draggable();
                $('pre').each(function(){
                    $(this).css('top', sfprey +'px');
                    $(this).css('left', sfprex +'px');
                    sfprey+=20;
                    sfprex+=20;
                });
                if($('pre').length>0){
                    $('body').prepend( $('<pre style=\'top:0px;left:0px\'></pre>').draggable().append(
                        '<a href=\'#\'>Close PRES</a>').click(function(event){event.preventDefault();$('.simondebug').remove();}
                    ));
                }
            ";
            $this->data['simondebugjs2'] = "
                if(data.match(/<pre class='simondebug'>(\\n|.)*<\/pre>/mg)){
                    $.each(data.match(/<pre class='simondebug'>(\\n|.)*<\/pre>/mg), function(i,o){
                        $(o)
                        .prepend( $('<a href=\'#\'>[x] (AJAX)</a>').click(function(event){event.preventDefault();$(this).parent().remove();}) )
                        .draggable()
                        .appendTo('body');
                    });
                    var sfprex=400;var sfprey=10;
                    
                    $('.simondebug').each(function(){ $(this).css('position','absolute'); $(this).css('top', sfprey +'px');$(this).css('left', sfprex +'px');sfprey+=20;sfprex+=20;});
                }
                ";
        }

        if ($this->data['filterCount'] > 0) {

            $currentfilters = $this->model_catalog_simonfilters->currentfilters;

            $this->model_catalog_simonfilters->debug(__FILE__ . ';' . __LINE__, 'currentfilters', $currentfilters);
            $this->model_catalog_simonfilters->debug(__FILE__ . ';' . __LINE__, 'sfs', $sfs);


            $out = "";
            $out = "<ul class='filter_grouper'>";
            if (is_array($currentfilters)) {
                $currentfiltersFlat = implode(',', $currentfilters);
            } else {
                $currentfiltersFlat = '';
            }
            $clear_filters_display = array();

            $simonfilters_sort = preg_split('/,/', $this->config->get('simonfilters_sort'));
            $sfs_temp = array();
            foreach ($simonfilters_sort as $sorted_key) {
                if (isset($sfs[$sorted_key])) {
                    $sfs_temp[$sorted_key] = $sfs[$sorted_key];
                }
            }
            $sfs = $sfs_temp;
            $count=0;
            foreach ($sfs as $filter_type_name => $filter_type) {
                foreach ($filter_type as $group_id => $filter_group) {
                    $count++;
                    $filter_group_name = $filter_group['filter_group_name'];


                    $isRadio = isset($logicalRelations[$filter_type_name][$group_id]['l']) && $logicalRelations[$filter_type_name][$group_id]['l']==1;
                    $isSlider = isset($logicalRelations[$filter_type_name][$group_id]['l']) && $logicalRelations[$filter_type_name][$group_id]['l']==3;
                    $isSliderMINMAX = isset($logicalRelations[$filter_type_name][$group_id]['l']) && $logicalRelations[$filter_type_name][$group_id]['l']==4;
                    $isDropDown = isset($logicalRelations[$filter_type_name][$group_id]['l']) && $logicalRelations[$filter_type_name][$group_id]['l']==5;

                    switch ($filter_type_name) {
                        case 'a':case 'o':case 'm':case 's':case 'c':case 't':$clear_filters_display = $this->get_clear_filters_status($filter_type_name, $group_id, $filter_group, $currentfilters);
                            break;
                        case 'p':$clear_filters_display = '';
                            break;
                    }

                    
                    if ($isSlider == 1) {
                        $isSlider = "sfslider";
                        $tempData = array();
                        $tempData["begin"]="0";
                        asort($filter_group[$group_id]['filter_name']);
                        foreach($filter_group[$group_id]['filter_name'] as $k=>$v) {
                            $tempData[$k]=$v;
                        }
                        $tempData["end"]="&infin;";
                        $filter_group[$group_id]['filter_name']=$tempData;
                    }

                    if ($isSliderMINMAX == 1) {
                        $isSlider = "sfslider";
                        $tempData = array();
                        asort($filter_group[$group_id]['filter_name']);
                        foreach($filter_group[$group_id]['filter_name'] as $k=>$v) {
                            $tempData[$k]=$v;
                        }
                        $filter_group[$group_id]['filter_name']=$tempData;
                    }

                    if ($isDropDown == 1) {
                        $isDropDown = "sfdropdown";
                        $tempData = array();
                        foreach($filter_group[$group_id]['filter_name'] as $k=>$v) {
                            $tempData[$k]=$v;
                        }
                        $filter_group[$group_id]['filter_name']=$tempData;

                    }else {
                        $isDropDown = "";
                    }

                    $activefilter_simonfilters_price = $this->config->get("activefilter_simonfilters_price");

                    $cols = "";
                    switch ($this->data['orientation']) {
                        case 'vertical':
                            if (($this->data['collapsible'] == '1') && ($filter_type_name != 'p') || ($filter_type_name == 'p' && $activefilter_simonfilters_price['type'] == 0)) {
                                $behavior = isset($filter_group['b']) ? ($filter_group['b'] == '1' ? 'expanded' : 'collapsed') : 'expanded';
                                $collapsible = 'collapsible';
                            } else {
                                $behavior = '';
                                $collapsible = '';
                            }
                            $cols = isset($filter_group['col']) ? $filter_group['col'] : 1;
                            $more = isset($filter_group['more']) ? " data-more='" . $filter_group['more'] . "'" : '';
                            $more_number = isset($filter_group['more_number']) ? " data-more_number='" . $filter_group['more_number'] . "'" : '';

                            $out .= "<li class='filter_group {$isSlider} {$isDropDown} {$collapsible} {$behavior}' filtertype='{$filter_type_name}' filterid='". md5($filter_group_name) ."'>";
                            $out .= "<div class='filter_title'>{$filter_group_name}";
                            if ($filter_type_name != 'p')
                                $out .= "<span class='clear_filters_local_container'><span class='clear_filters_local' $clear_filters_display>[X]</span></span>";
                            $out .= "</div>";
                            $out .= "<div class='filter_ul' " . ($behavior == 'collapsed' ? "style='display:none'" : "") . " data-cols='$cols' $more $more_number><ul>";
                            break;
                        case 'horizontal':
                            if ($filter_type_name == 'p') {
                                $out .= "<li class='filter_group'>";
                                $out .= "<table><tr><td style='width:1px;'>";
                                $out .= "<div class='filter_title'>{$filter_group_name}</div>";
                                $out .= "</td><td>";
                                $out .= "<div class='filter_ul'><ul>";
                            } else {
                                $out .= "<li class='filter_group'>";
                                $out .= "<table><tr><td style='width:1px;'>";
                                $out .= "<div class='filter_title'>{$filter_group_name}</div>";
                                $out .= "</td><td>";
                                $out .= "<div class='filter_ul'><ul>";
                            }
                            break;
                    }


                    foreach ($filter_group as $filter_id => $filter_group_item) {

                        if (is_numeric($filter_id)) {
                            foreach ($filter_group_item['filter_name'] as $key => $filter_item) {


                                $sort_order = isset($filter_group_item['sort_order']) ? $filter_group_item['sort_order'] : '1';
                                $md5filter_item = md5($filter_item);
                                #$totals = (($this->data['display_totals'] == 'yes' && isset($filter_group_item['filter_totals'][$key])) ? ' (' . $filter_group_item['filter_totals'][$key] . ')' : '');
                                $totals = ((($this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals") == '2') && isset($filter_group_item['filter_totals'][$key])) ? ' (' . $filter_group_item['filter_totals'][$key] . ')' : '');

                                if(in_array($key, array("begin","end"))) {
                                    $checked = in_array("{$filter_type_name}.{$group_id}.{$filter_id}.{$key}", $currentfilters) ? 'checked' : 'unchecked';
                                }else {
                                    $checked = in_array("{$filter_type_name}.{$group_id}.{$filter_id}.{$md5filter_item}", $currentfilters) ? 'checked' : 'unchecked';
                                }

                                $radiochecked = "";
                                if ($isRadio == 1 && !in_array($this->config->get("simonfilters_display_type"),array(0,3)) ) {
                                    $radiochecked = $checked == "checked" ? "radiochecked" : "radiounchecked";
                                }


                                switch ($this->data['orientation']) {
                                    case 'vertical':
                                    //Price
                                        if ($filter_type_name == 'p') {
                                            $this->data['showprice'] = true;
                                            $this->data['prices'] = $sfs['p'][1]['prices'];


                                            if ($activefilter_simonfilters_price['type'] == 1) {

                                                list($this->data['minprice_slider'], $this->data['maxprice_slider']) = array($this->data['prices']['minprice'], $this->data['prices']['maxprice']);

                                                foreach ($currentfilters as $currentfilter) {
                                                    $currentfilterAri = preg_split('/\./', $currentfilter);
                                                    if ($currentfilterAri[0] == 'p') {
                                                        $localeconv = localeconv();
                                                        $local_decimal_point = $localeconv['decimal_point'];
                                                        $currentfilterAri[3] = preg_replace('/SFS/', $local_decimal_point, $currentfilterAri[3]);
                                                        list($this->data['minprice_slider'], $this->data['maxprice_slider']) = preg_split('/,/', $currentfilterAri[3]);
                                                    }
                                                }
                                                $out .= "<li class='add_filter_price simonfilters_price' id='p.1.1.1'>";
                                                $out .= "<div id='simonfilters_price-range'></div><div id='simonfilters_price_amount'></div></li>";

                                            } else {

                                                $localeconv = localeconv();
                                                $local_decimal_point = $localeconv['decimal_point'];

                                                $minprice = $this->data['prices']['minprice'];
                                                $maxprice = $this->data['prices']['maxprice'];

                                                $step = (int) $this->config->get("simonfilters_slider_step");

                                                $vals = ($maxprice - $minprice) / $step;

                                                for ($i = 0; $i < $step; $i++) {
                                                    $price1 = $minprice + $vals * $i;
                                                    $price2 = $minprice + $vals * $i + $vals - ($i == $step - 1 ? 0 : .01);

                                                    $price1txt = $this->data['activeCurrency']['symbol_left'] . round($price1, 2) . $this->data['activeCurrency']['symbol_right'];
                                                    $price2txt = $this->data['activeCurrency']['symbol_left'] . round($price2, 2) . $this->data['activeCurrency']['symbol_right'];

                                                    $price1 = str_replace($local_decimal_point, 'SFS', $price1);
                                                    $price2 = str_replace($local_decimal_point, 'SFS', $price2);

                                                    $checked = in_array("{$filter_type_name}.1.1.{$price1},{$price2}", $currentfilters) ? 'checked' : 'unchecked';
                                                    $radiochecked = $checked == "checked" ? "radiochecked" : "radiounchecked";

                                                    $out .= "<li class='add_filter {$radiochecked} {$checked}' id='{$filter_type_name}.1.1.$price1,$price2' data-sort_order='{$sort_order}'>$price1txt - $price2txt</li>";
                                                }
                                            }
                                        } else {
                                            if (trim($filter_item) != '') {
                                                switch($key) {
                                                    case 'begin':
                                                    case 'end':
                                                        $out .= "<li class='add_filter {$radiochecked} {$checked}' id='{$filter_type_name}.{$group_id}.{$filter_id}.{$key}' data-sort_order='{$sort_order}'>{$filter_item}{$totals}</li>";
                                                        break;
                                                    default:
                                                        $out .= "<li class='add_filter {$radiochecked} {$checked}' id='{$filter_type_name}.{$group_id}.{$filter_id}.{$md5filter_item}' data-sort_order='{$sort_order}'>{$filter_item}{$totals}</li>";
                                                        break;
                                                }
                                            }
                                        }
                                        break;
                                    case 'horizontal':
                                        if ($filter_type_name == 'p') {
                                            $this->data['showprice'] = true;
                                            $this->data['prices'] = $sfs['p'][1]['prices'];
                                            if ($activefilter_simonfilters_price['type'] == 1) {
                                                list($this->data['minprice_slider'], $this->data['maxprice_slider']) = array($this->data['prices']['minprice'], $this->data['prices']['maxprice']);
                                                foreach ($currentfilters as $currentfilter) {
                                                    $currentfilterAri = preg_split('/\./', $currentfilter);
                                                    if ($currentfilterAri[0] == 'p') {
                                                        $localeconv = localeconv();
                                                        $local_decimal_point = $localeconv['decimal_point'];
                                                        $currentfilterAri[3] = preg_replace('/SFS/', $local_decimal_point, $currentfilterAri[3]);
                                                        list($this->data['minprice_slider'], $this->data['maxprice_slider']) = preg_split('/,/', $currentfilterAri[3]);
                                                    }
                                                }
                                                #$out .= "<li class='add_filter simonfilters_price' id='p.1.1.1'>";
                                                $out .= "<li class='add_filter_price simonfilters_price' id='p.1.1.1'>";
                                                $out .= "<table><tr><td style='width:200px'><div id='simonfilters_price-range'></div></td><td><div id='simonfilters_price_amount'></div></td></tr></table></li>";
                                            } else {

                                                $localeconv = localeconv();
                                                $local_decimal_point = $localeconv['decimal_point'];

                                                $minprice = $this->data['prices']['minprice'];
                                                $maxprice = $this->data['prices']['maxprice'];

                                                $step = (int) $this->config->get("simonfilters_slider_step");

                                                $vals = ($maxprice - $minprice) / $step;

                                                for ($i = 0; $i < $step; $i++) {
                                                    $price1 = $minprice + $vals * $i;
                                                    $price2 = $minprice + $vals * $i + $vals - ($i == $step - 1 ? 0 : .01);

                                                    $price1txt = $this->data['activeCurrency']['symbol_left'] . round($price1, 2) . $this->data['activeCurrency']['symbol_right'];
                                                    $price2txt = $this->data['activeCurrency']['symbol_left'] . round($price2, 2) . $this->data['activeCurrency']['symbol_right'];

                                                    $price1 = str_replace($local_decimal_point, 'SFS', $price1);
                                                    $price2 = str_replace($local_decimal_point, 'SFS', $price2);

                                                    $checked = in_array("{$filter_type_name}.1.1.{$price1},{$price2}", $currentfilters) ? 'checked' : 'unchecked';
                                                    //$radiochecked = $checked == "checked" ? "radiochecked" : "radiounchecked";

                                                    $out .= "<li class='add_filter {$radiochecked} {$checked}' id='{$filter_type_name}.1.1.$price1,$price2' data-sort_order='{$sort_order}'>$price1txt - $price2txt</li>";
                                                }
                                            }
                                        } else {
                                            $out .= "<li class='add_filter {$checked}' id='{$filter_type_name}.{$group_id}.{$filter_id}.{$md5filter_item}'>{$filter_item} {$totals}</li>";
                                        }
                                        break;
                                }
                            }
                        }
                    }

                    switch ($this->data['orientation']) {
                        case 'vertical':
                            $out .="</ul></div>";
                            break;
                        case 'horizontal':
                            if ($filter_type_name != '1p') {
                                $out .="</ul></div></td></tr></table>";
                            }
                            break;
                    }


                    $out .="</li>";
                }
            }
            $out .="</ul>";
            if (count($currentfilters) > 0) {
                $this->data['clear_filters_global_display'] = 'style=\'display:inline\'';
            } else {
                $this->data['clear_filters_global_display'] = 'style=\'display:none\'';
            }
            return $out;
        } else {
            return '';
        }
    }

    protected function index($setting) {
        $this->load->model('catalog/simonfilters');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/simonfilters_stylesheet.css');
        switch ($this->config->get('config_template')) {
            case 'shoppica':
                $this->document->addStyle('catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css');
                break;
            default:
                break;
        }

        $this->data['display_type'] = "text_type_default";


        switch ($this->model_catalog_simonfilters->getRoute()) {
            case "product/product":
                $simonfilters_product_domid = $this->config->get("simonfilters_product_domid");
                $simonfilters_search_domid = $this->config->get("simonfilters_search_domid");
                $this->data['containerID'] = isset($simonfilters_product_domid[$this->config->get('config_template')]) ? $simonfilters_product_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = isset($simonfilters_search_domid[$this->config->get('config_template')]) ? $simonfilters_search_domid[$this->config->get('config_template')] : '#content';
                break;
            case "product/category":
                $simonfilters_category_domid = $this->config->get("simonfilters_category_domid");
                $this->data['containerID'] = isset($simonfilters_category_domid[$this->config->get('config_template')]) ? $simonfilters_category_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = $this->data['containerID'];
                break;
            case "product/manufacturer/product":
                $simonfilters_manufacturer_domid = $this->config->get("simonfilters_manufacturer_domid");
                $this->data['containerID'] = isset($simonfilters_manufacturer_domid[$this->config->get('config_template')]) ? $simonfilters_manufacturer_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = $this->data['containerID'];
                break;
            case "product/manufacturer/info":
                $simonfilters_manufacturer_domid = $this->config->get("simonfilters_manufacturer_domid");
                $this->data['containerID'] = isset($simonfilters_manufacturer_domid[$this->config->get('config_template')]) ? $simonfilters_manufacturer_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = $this->data['containerID'];
                break;
            case "product/search":
                $simonfilters_search_domid = $this->config->get("simonfilters_search_domid");
                $this->data['containerID'] = isset($simonfilters_search_domid[$this->config->get('config_template')]) ? $simonfilters_search_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = $this->data['containerID'];
                break;
            case "homepage":
                $simonfilters_homepage_domid = $this->config->get("simonfilters_homepage_domid");
                $simonfilters_search_domid = $this->config->get("simonfilters_search_domid");
                $this->data['containerID'] = isset($simonfilters_homepage_domid[$this->config->get('config_template')]) ? $simonfilters_homepage_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = isset($simonfilters_search_domid[$this->config->get('config_template')]) ? $simonfilters_search_domid[$this->config->get('config_template')] : '#content';
                break;
            case "product/special":
                $simonfilters_special_domid = $this->config->get("simonfilters_homepage_domid");
                $this->data['containerID'] = isset($simonfilters_special_domid[$this->config->get('config_template')]) ? $simonfilters_special_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = isset($simonfilters_special_domid[$this->config->get('config_template')]) ? $simonfilters_special_domid[$this->config->get('config_template')] : '#content';
                break;
            case "account/wishlist":
                $simonfilters_special_domid = $this->config->get("simonfilters_homepage_domid");
                $this->data['containerID'] = isset($simonfilters_special_domid[$this->config->get('config_template')]) ? $simonfilters_special_domid[$this->config->get('config_template')] : '#content';
                $this->data['containerIDtarget'] = isset($simonfilters_special_domid[$this->config->get('config_template')]) ? $simonfilters_special_domid[$this->config->get('config_template')] : '#content';
                break;
        }
        $simonfilters_force_siblings = $this->config->get("simonfilters_force_siblings");
        $this->data['simonfilters_force_siblings'] = isset($simonfilters_force_siblings[$this->config->get('config_template')]) ? $simonfilters_force_siblings[$this->config->get('config_template')] : 'false';
        $this->data['css'] = '';

        foreach ($this->config->get("simonfilters_module") as $simonfilters_module) {

            if ($simonfilters_module['layout_id'] == $setting['layout_id']) {

                $this->data['display_totals'] = $this->config->get("simonfilters_display_totals") == '1' || $this->config->get("simonfilters_display_totals") == '2'; //isset($simonfilters_module['display_totals']) ? $simonfilters_module['display_totals'] : 'no';
                $this->data['position'] = isset($simonfilters_module['position']) ? $simonfilters_module['position'] : 'left';
                $simonfilters_styles = $this->config->get('simonfilters_styles');
                $simonfilters_display_type = $this->config->get("simonfilters_display_type");
                $this->data['display_type'] = isset($simonfilters_display_type) ? $simonfilters_styles[$simonfilters_display_type]['name'] : 'default';
                $this->data['css'] = (isset($simonfilters_display_type) ? html_entity_decode($simonfilters_styles[$simonfilters_display_type]['css']) : 'n/a');
                $this->data['orientation'] = (isset($simonfilters_display_type) ? $simonfilters_styles[$simonfilters_display_type]['orientation'] : 'vertical');
                $this->data['collapsible'] = (isset($simonfilters_styles[$simonfilters_display_type]['collapsible']) ? $simonfilters_styles[$simonfilters_display_type]['collapsible'] : 'vertical');
                $this->data['activefilter_simonfilters_price'] = $this->config->get("activefilter_simonfilters_price");

                if ($this->model_catalog_simonfilters->debugmode) {
                    $this->data['css'] .="pre{    border:1px solid #eaeaea;    padding: 2px;    background-color: yellow;    position:absolute;    z-index: 1000;    word-wrap: break-word !important;}pre.small{    width:20px;    height:20px;    font-size: 0.0em;}";
                }
            }
        }
        $this->language->load('module/simonfilters');
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['clear_filters_title'] = $this->language->get('clear_filters_title');
        $this->data['language_id'] = $this->config->get('config_language_id');
        $this->data['route'] = $this->model_catalog_simonfilters->getRoute();
        $this->data['category_id'] = $this->model_catalog_simonfilters->getCategoryID();
        $this->data['index'] = $this->model_catalog_simonfilters->getSimonIndex();
        $this->data['isThereAnyFilterEnabled'] = $this->model_catalog_simonfilters->isThereAnyFilterEnabled();

        $this->data['simon_more'] = $this->language->get('more');
        $this->data['simon_less'] = $this->language->get('less');

        switch ($this->model_catalog_simonfilters->getRoute()) {
            case "homepage":
            case "product/product":
                $this->data['urlAjax'] = "index.php?route=product/search&filter_name=";
                break;
            default:
                $this->data['urlAjax'] = preg_replace('/\&page=[0-9]*/', '', $_SERVER["REQUEST_URI"]);
                break;
        }

        $this->data['simonfilters_front_end_diagnostics'] = $this->config->get('simonfilters_front_end_diagnostics') == '1' ? 'true' : 'false';
        $this->data['XRequestedWith'] = $this->model_catalog_simonfilters->getHeader('X-Requested-With');
        $this->data['activeCurrency'] = $this->model_catalog_simonfilters->getActiveCurrency();

        $this->data['HTML'] = $this->generateHTML($this->data['display_type']);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/simonfilters.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/simonfilters.tpl';
        } else {
            $this->template = 'default/template/module/simonfilters.tpl';
        }
        $this->render();
//}
    }

}

?>