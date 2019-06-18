<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div style="width: calc(40% - 20px); min-width: 300px;height: 400px; margin: 0; float: left; padding-left: 15px; font-size: 20px; ">
    <div style="text-align: center;"><span style="margin: 0 auto;">Контактная информация:</span><br><br>
  <span style="font-weight: bold;">Компания "Харьков-Форма"</span><br><br></div>
    <div style="width: 50%; height: 400px; float: left;">
    <span style="font-weight: bold;">Телефоны:</span><br><br>
    
    <?php echo $clientsource ?>


    <br><br>
    <span style="font-weight: bold;">Эл. почта:</span><br> FORMA@ua.fm<br><br></div>
    <div style="width: 50%; height: 400px; float: left;">
    <span style="font-weight: bold;">Адрес:</span><br><br>
    Украина, г. Харьков,<br>
    ул. Котлова, 67<br>
    <br>
    <span style="font-weight: bold;">График работы:</span><br>
    ПН - ПТ с 9:00 до 18:00
  </div>

  </div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?keyAIzaSyAZZRSQInZ5cma94Rw2zwRJkUnWRUNOcB4&sensor=false"></script>
<script>
function rePaintMap(){
  var geocoder;
  var map;
  var position = new google.maps.LatLng(49.996110, 36.203544);
  geocoder = new google.maps.Geocoder();
  var mapOptions = {
    zoom: 17,
    center: position,
	panControl: true,
   	zoomControl: true,
   	scaleControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
   map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

	var address = "<?php echo trim(preg_replace('/\s+/', ' ', strip_tags($address))); ?>";
	var content = "<h2><?php echo $text_address; ?></h2><?php echo trim(preg_replace('/\s+/', ' ', $address)); ?><?php if ($telephone) { ?><h2><?php echo $text_telephone; ?></h2><?php echo $telephone; ?><?php } ?>";

        var marker = new google.maps.Marker({
            map: map,
            position: position,
            title: address
        });
        var infowindow = new google.maps.InfoWindow({
     			content: "<div style='width:300px;height:110px;'>"+content+"</div>"
 		});
		google.maps.event.addListener(marker, "click", function (e) {
			infowindow.open(map,marker);
		});
		infowindow.open(map,marker);
}
$(document).ready(function(){
	rePaintMap();
});
</script>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1282.4102074322832!2d36.20258665830971!3d49.99597629485387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4127a10124d943eb%3A0xe813a51f92086cc4!2z0KXQsNGA0YzQutC-0LIt0KTQvtGA0LzQsA!5e0!3m2!1sru!2sru!4v1551981077410" width="60%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
<!--     <h2><?php echo $text_location; ?></h2>
    <div class="contact-info">
      <div class="content"><div class="left"><b><?php echo $text_address; ?></b><br />
        <?php echo $store; ?><br />
        <?php echo $address; ?></div>
      <div class="right">
        <?php if ($telephone) { ?>
        <b><?php echo $text_telephone; ?></b><br />
        <?php echo $telephone; ?><br />
        <br />
        <?php } ?>
        <?php if ($fax) { ?>
        <b><?php echo $text_fax; ?></b><br />
        <?php echo $fax; ?>
        <?php } ?>
      </div>
    </div>
    </div> -->
    <h2><?php echo $text_contact; ?></h2>
    <div class="content">
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <b><?php echo $entry_email; ?></b><br />
    <input type="text" name="email" value="<?php echo $email; ?>" />
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php } ?>
    <br />
    <b><?php echo $entry_file; ?></b><br />
    <input type="file" name="file" />
    <br />
    <br />
    <b><?php echo $entry_enquiry; ?></b><br />
    <textarea name="enquiry" cols="40" rows="10" style="width: 99%;"><?php echo $enquiry; ?></textarea>
    <br />
    <?php if ($error_enquiry) { ?>
    <span class="error"><?php echo $error_enquiry; ?></span>
    <?php } ?>
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
    <br />
    <img src="index.php?route=information/contact/captcha" alt="" />
    <?php if ($error_captcha) { ?>
    <span class="error"><?php echo $error_captcha; ?></span>
    <?php } ?>
    </div>
    <div class="buttons">
      <div class="right"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>