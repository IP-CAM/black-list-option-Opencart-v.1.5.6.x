<?php echo $header; ?>
<style>
.social_block{
    width: 295px;float:right;border-left: 2px solid #eee;padding-left: 4px;
}
.social_block_title{
    padding-bottom: 8px;padding-top: 8px;color: #5f607c;font-weight: bold;line-height: 23px;font-size: 14px;height: 23px;border-bottom: 2px dashed #eee;
}
.social_block_socs{
    margin: 8px 0;
    min-height: 50px;
}
.social_network{
      padding: 12px 8px;
      cursor: pointer;
      background: #fff;
      line-height: 17px;
      color: #5f607c;
      font-weight: bold;
      font-size: 14px;
}
.social_network:hover{
      background: #F5F5F5;
}

.soc_check{
  float: left;
  width: 11px;
  height: 11px;
  margin-right: 10px;
  border: 1px solid #ADADAD;
  background: #eee;
  padding: 2px;
}

.soc_check_link{
  height: 11px;
}
.soc_check_link_act{
  height: 11px;
  background: #B5E568;
}
.social_icons{
      float: left;
      width: 16px;
      height: 16px;
      background-image: url(view/image/module_icon/ulogin/social_small.png);
      margin-right: 5px;
}
#social_icon_vkontakte { background-position: 0 -19px; }
#social_icon_facebook { background-position: 0 -88px; }
#social_icon_odnoklassniki { background-position: 0 -42px; }
#social_icon_mailru { background-position: 0 -65px; }
#social_icon_yandex { background-position: 0 -157px; }
#social_icon_google { background-position: 0 -134px; }
#social_icon_twitter {  background-position: 0 -111px; }
#social_icon_livejournal { background-position: 0 -180px; }
#social_icon_openid { background-position: 0 -203px; }
#social_icon_lastfm { background-position: 0 -272px; }
#social_icon_linkedin { background-position: 0 -295px; }
#social_icon_liveid { background-position: 0 -318px; }
#social_icon_soundcloud { background-position: 0 -341px; }
#social_icon_steam { background-position: 0 -364px; }
#social_icon_flickr { background-position: 0 -249px; }
#social_icon_uid { background-position: 0 -387px; }
#social_icon_youtube { background-position: 0 -433px; }
#social_icon_webmoney { background-position: 0 -410px; }
#social_icon_foursquare { background-position: 0 -456px; }
#social_icon_tumblr { background-position: 0 -479px; }
#social_icon_googleplus { background-position: 0 -502px; }
#social_icon_dudu { background-position: 0 -525px; }
#social_icon_vimeo {   background-position: 0 -548px; }
#social_icon_instagram { background-position: 0 -571px; }
#social_icon_wargaming { background-position: 0 -594px; }
.social_tip{
  font-size: 11px;
  color: #3D3D3D;
  padding: 5px 3px;
  background: #eee;
}
</style>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content" style="overflow: visible;">
    <div class="social_block">
        <div class="social_tip"><?php echo $text_social_tip; ?></div>
        <div class="social_block_title"><?php echo $entry_providers; ?></div>
        <div id="providers_soc" class="social_block_socs">
        <?php foreach($ulogin_social_networks as $key => $social_network){ ?>
            <?php if($social_network['providers']==1){ ?>
            <div class="social_network" data-key="<?php echo $key; ?>" data-on="<?php echo $social_network['on']; ?>">
            <div class="soc_check"><div class="soc_check_link<?php if($social_network['on']){ ?>_act<?php } ?>"></div></div>
            <div class="social_icons" id="social_icon_<?php echo $key; ?>"></div>
            <?php echo $social_network['name']; ?>
            </div>
            <?php } ?>
        <?php } ?>
        </div>
        <div class="social_block_title"><?php echo $entry_hidden; ?></div>
        <div id="hidden_soc" class="social_block_socs">
        <?php foreach($ulogin_social_networks as $key => $social_network){ ?>
            <?php if($social_network['providers']!=1){ ?>
            <div class="social_network" data-key="<?php echo $key; ?>" data-on="<?php echo $social_network['on']; ?>">
            <div class="soc_check"><div class="soc_check_link<?php if($social_network['on']){ ?>_act<?php } ?>"></div></div>
            <div class="social_icons" id="social_icon_<?php echo $key; ?>"></div>
            <?php echo $social_network['name']; ?>
            </div>
            <?php } ?>
        <?php } ?>
        </div>
    </div>
    <div style="margin-right: 305px;">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
              <table class="form">
              <tr>
                  <td><?php echo $entry_status; ?></td>
                  <td>
                      <select name="ulogin_status">
                        <?php if ($ulogin_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td><?php echo $entry_mobilebuttons; ?></td>
                  <td>
                      <select name="ulogin_mobilebuttons" id="ulogin_mobilebuttons" onchange="update_social_networks();">
                        <?php if ($ulogin_mobilebuttons) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                      <img class="tooltip" id="tooltip1" src="view/image/module_icon/ulogin/info.png">
                  </td>
              </tr>
              <tr>
                  <td><?php echo $entry_sort; ?></td>
                  <td>
                      <select name="ulogin_sort" id="ulogin_sort" onchange="update_social_networks();">
                        <?php if ($ulogin_sort=="relevant") { ?>
                        <option value="relevant" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="default"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="relevant"><?php echo $text_enabled; ?></option>
                        <option value="default" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td><?php echo $text_preview; ?></td>
                  <td>
<script src="//ulogin.ru/js/ulogin.js"></script>
<div id="uLogin_1" data-ulogin="display=panel;fields=first_name,last_name;mobilebuttons=<?php echo $ulogin_mobilebuttons; ?>;sort=<?php echo $ulogin_sort; ?>;providers=<?php echo $ulogin_providers; ?>;hidden=<?php echo $ulogin_hidden; ?>;redirect_uri=http%3A%2F%2F"></div>
                  </td>
              </tr>
              </table>
              <input type="hidden" name="ulogin_providers" id="ulogin_providers_input" value="<?php echo $ulogin_providers; ?>">
              <input type="hidden" name="ulogin_hidden" id="ulogin_hidden_input" value="<?php echo $ulogin_hidden; ?>">
        </form>
    </div>
    <div style="clear: both;"></div>
  </div>
  <div id='module_copyright'>
    <div style="text-align: center;"><?php echo $text_copyright; ?></div>
    <?php echo $module_copyright; ?>
  </div>
</div>
<script type="text/javascript" src="view/javascript/panda_modules/easyTooltip.js"></script>
<style type="text/css">
  #easyTooltip{
   padding:5px;
   border:1px solid #ccc;
   background:#f1f1f1;
   box-shadow: 0px 0px 0px 10000px rgba(0, 0, 0, 0.4);
   z-index: 999;
}
.tooltip {
   vertical-align: middle;
}
</style>
<script type="text/javascript">
$(document).ready(function(){ 
  $("#tooltip1").easyTooltip({
    tooltipId: "easyTooltip",
    content: '<img src="view/image/module_icon/ulogin/ulogin_mobilebuttons.png">'
  });
});

$(function() {
    $( "#providers_soc, #hidden_soc" ).sortable({
      axis: 'y',
      connectWith: ".social_block_socs",
      update: function(event, ui) { update_social_networks(); }
    }).disableSelection();
});

$(".social_network").on("click", function(){
    var data_on = $(this).attr("data-on");
    if(data_on==1){
        $(this).find("div.soc_check_link_act").removeClass().addClass("soc_check_link");
        $(this).attr("data-on", "0");
    }else{
        $(this).find("div.soc_check_link").removeClass().addClass("soc_check_link_act");
        $(this).attr("data-on", "1");
    }
    update_social_networks();
});

function update_social_networks(){
    $("#ulogin_providers_input").val("");
    $("#ulogin_hidden_input").val("");
    
    var providers = "";
    $("#providers_soc .social_network").each(function() {
        if($(this).attr("data-on")==1){
            if(providers){
                providers += ",";
            }
            providers += $(this).attr("data-key");
        }
    });
    $("#ulogin_providers_input").val(providers);
    
    var hidden = "";
    $("#hidden_soc .social_network").each(function() {
        if($(this).attr("data-on")==1){
            if(hidden){
                hidden += ",";
            }
            hidden += $(this).attr("data-key");
        }
    });
    $("#ulogin_hidden_input").val(hidden);
    
    uLogin_update();
}

function uLogin_update(){
    var providers = $("#ulogin_providers_input").val();
    var hidden = $("#ulogin_hidden_input").val();
    var mobilebuttons = $("#ulogin_mobilebuttons").val();
    var sort = $("#ulogin_sort").val();
    var data_ulogin = "display=panel;fields=first_name,last_name;mobilebuttons=" + mobilebuttons + ";sort=" + sort + ";providers=" + providers + ";hidden=" + hidden + ";redirect_uri=http%3A%2F%2F"
    
    $("#uLogin_1").html("");
    $("#uLogin_1").attr("data-ulogin", data_ulogin);
    
    uLogin.customInit('uLogin_1');
}
</script>
<?php echo $footer; ?> 
