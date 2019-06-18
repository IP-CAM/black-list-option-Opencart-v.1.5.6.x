<div>
<?php if ($on_off == true) {?>	  
<!-- start slyle and js -->
	<script src="catalog/view/javascript/jquery/arcticmodal/jquery.arcticmodal.js"></script>  
	<link rel="stylesheet" href="catalog/view/javascript/jquery/arcticmodal/jquery.arcticmodal.css"> 
	<link rel="stylesheet" href="catalog/view/javascript/jquery/arcticmodal/themes/simple.css"> 
	<script src="catalog/view/javascript/jquery/arcticmodal/jquery.cookie.min.js"></script>
	<style><?php if(!empty($plpopup_style)){?><?php } echo $plpopup_style; ?></style>
		<style>
	@media screen and (max-width:<?php if(!empty($plpopup_sr)){?><?php } echo $plpopup_sr; ?>px){
	.box-modal{display: none;}
	.arcticmodal-overlay{display: none !important;}
	}
	.box-modal {
	background: <?php if(!empty($plpopup_bg)){?><?php } echo $plpopup_bg; ?> url('catalog/view/javascript/jquery/arcticmodal/plpopup/<?php if(!empty($plpopup_pic)){?><?php } echo $plpopup_pic; ?>') no-repeat;
	width: <?php if(!empty($plpopup_amw)){?><?php } echo $plpopup_amw; ?>;
	height: <?php if(!empty($plpopup_amh)){?><?php } echo $plpopup_amh; ?>;
	}
	.plp1 {
	font-size: <?php if(!empty($plpopup_headtopsize)){?><?php } echo $plpopup_headtopsize; ?> !important;
	font-style: <?php if(!empty($plpopup_headtoptype)){?><?php } echo $plpopup_headtoptype; ?> !important;
	font-weight: <?php if(!empty($plpopup_headtopfw)){?><?php } echo $plpopup_headtopfw; ?> !important;
	color: <?php if(!empty($plpopup_headtopcolor)){?><?php } echo $plpopup_headtopcolor; ?> !important;
	margin-left: <?php if(!empty($plpopup_headtopleft)){?><?php } echo $plpopup_headtopleft; ?> !important;
	margin-top: <?php if(!empty($plpopup_headtopheight)){?><?php } echo $plpopup_headtopheight; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp1 a {
	position: relative;
	}
	.plp2 {
	font-size: <?php if(!empty($plpopup_headtopsize2)){?><?php } echo $plpopup_headtopsize2; ?> !important;
	font-style: <?php if(!empty($plpopup_headtoptype2)){?><?php } echo $plpopup_headtoptype2; ?> !important;
	font-weight: <?php if(!empty($plpopup_headtopfw2)){?><?php } echo $plpopup_headtopfw2; ?> !important;
	color: <?php if(!empty($plpopup_headtopcolor2)){?><?php } echo $plpopup_headtopcolor2; ?> !important;
	margin-left: <?php if(!empty($plpopup_headtopleft2)){?><?php } echo $plpopup_headtopleft2; ?> !important;
	margin-top: <?php if(!empty($plpopup_headtopheight2)){?><?php } echo $plpopup_headtopheight2; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp2 a {
	position: relative;
	}
	.plp3 {
	font-size: <?php if(!empty($plpopup_homesize)){?><?php } echo $plpopup_homesize; ?> !important;
	font-style: <?php if(!empty($plpopup_hometype)){?><?php } echo $plpopup_hometype; ?> !important;
	font-weight: <?php if(!empty($plpopup_homefw)){?><?php } echo $plpopup_homefw; ?> !important;
	color: <?php if(!empty($plpopup_homecolor)){?><?php } echo $plpopup_homecolor; ?> !important;
	margin-left: <?php if(!empty($plpopup_homeleft)){?><?php } echo $plpopup_homeleft; ?> !important;
	margin-top: <?php if(!empty($plpopup_homeheight)){?><?php } echo $plpopup_homeheight; ?> !important;
	text-decoration: none !important;
	position: absolute;	
	}
	.plp3 a {
	position: relative;	
	}
	.plp4 {
	font-size: <?php if(!empty($plpopup_homesize2)){?><?php } echo $plpopup_homesize2; ?> !important;
	font-style: <?php if(!empty($plpopup_hometype2)){?><?php } echo $plpopup_hometype2; ?> !important;
	font-weight: <?php if(!empty($plpopup_homefw2)){?><?php } echo $plpopup_homefw2; ?> !important;
	color: <?php if(!empty($plpopup_homecolor2)){?><?php } echo $plpopup_homecolor2; ?> !important;
	margin-left: <?php if(!empty($plpopup_homeleft2)){?><?php } echo $plpopup_homeleft2; ?> !important;
	margin-top: <?php if(!empty($plpopup_homeheight2)){?><?php } echo $plpopup_homeheight2; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp4 a {
	position: relative;
	}
	.plp5 {
	font-size: <?php if(!empty($plpopup_homesize3)){?><?php } echo $plpopup_homesize3; ?> !important;
	font-style: <?php if(!empty($plpopup_hometype3)){?><?php } echo $plpopup_hometype3; ?> !important;
	font-weight: <?php if(!empty($plpopup_homefw3)){?><?php } echo $plpopup_homefw3; ?> !important;
	color: <?php if(!empty($plpopup_homecolor3)){?><?php } echo $plpopup_homecolor3; ?> !important;
	margin-left: <?php if(!empty($plpopup_homeleft3)){?><?php } echo $plpopup_homeleft3; ?> !important;
	margin-top: <?php if(!empty($plpopup_homeheight3)){?><?php } echo $plpopup_homeheight3; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp5 a {
	position: relative;
	}
	.plp6 {
	font-size: <?php if(!empty($plpopup_homesize4)){?><?php } echo $plpopup_homesize4; ?> !important;
	font-style: <?php if(!empty($plpopup_hometype4)){?><?php } echo $plpopup_hometype4; ?> !important;
	font-weight: <?php if(!empty($plpopup_homefw4)){?><?php } echo $plpopup_homefw4; ?> !important;
	color: <?php if(!empty($plpopup_homecolor4)){?><?php } echo $plpopup_homecolor4; ?> !important;
	margin-left: <?php if(!empty($plpopup_homeleft4)){?><?php } echo $plpopup_homeleft4; ?> !important;
	margin-top: <?php if(!empty($plpopup_homeheight4)){?><?php } echo $plpopup_homeheight4; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp6 a {
	position: relative;
	}
	.plp7 {
	font-size: <?php if(!empty($plpopup_footersize)){?><?php } echo $plpopup_footersize; ?> !important;
	font-style: <?php if(!empty($plpopup_footertype)){?><?php } echo $plpopup_footertype; ?> !important;
	font-weight: <?php if(!empty($plpopup_footerfw)){?><?php } echo $plpopup_footerfw; ?> !important;
	color: <?php if(!empty($plpopup_footercolor)){?><?php } echo $plpopup_footercolor; ?> !important;
	margin-left: <?php if(!empty($plpopup_footerleft)){?><?php } echo $plpopup_footerleft; ?> !important;
	margin-top: <?php if(!empty($plpopup_footerheight)){?><?php } echo $plpopup_footerheight; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp7 a {
	position: relative;
	}
	.plp8 {
	font-size: <?php if(!empty($plpopup_footersize2)){?><?php } echo $plpopup_footersize2; ?> !important;
	font-style: <?php if(!empty($plpopup_footertype2)){?><?php } echo $plpopup_footertype2; ?> !important;
	font-weight: <?php if(!empty($plpopup_footerfw2)){?><?php } echo $plpopup_footerfw2; ?> !important;
	color: <?php if(!empty($plpopup_footercolor2)){?><?php } echo $plpopup_footercolor2; ?> !important;
	margin-left: <?php if(!empty($plpopup_footerleft2)){?><?php } echo $plpopup_footerleft2; ?> !important;
	margin-top: <?php if(!empty($plpopup_footerheight2)){?><?php } echo $plpopup_footerheight2; ?> !important;	
	text-decoration: none !important;
	position: absolute;
	}
	.plp8 a {
	position: relative;
	}
	</style>
	
	<script>  
	setTimeout(function() {
	(function($) {  
	$(function() {  
	if (!$.cookie('plpopup')) {  
    $('#boxUserFirstInfo').arcticmodal({  
      closeOnOverlayClick: true,  
      closeOnEsc: true  
    });  
	}  
	$.cookie('plpopup', true, {  
    expires: <?php if(!empty($plpopup_cookie)){?><?php } echo $plpopup_cookie; ?>,  
    path: '/'  
	});  
	})  
	})(jQuery)  }, <?php if(!empty($plpopup_tm)){?><?php } echo $plpopup_tm; ?>000);
	</script>   
<!-- end slyle and js -->

<!-- start content -->
	<div style="display: none;">  
		<div class="box-modal" id="boxUserFirstInfo">  
			<div class="box-modal_close arcticmodal-close">закрыть</div>
    
    <div class="plp1">
		<?php if ($on_offlt == true) {?>
	<a class="plp1" href="<?php if(!empty($plpopup_linktop)){?><?php } echo $plpopup_linktop; ?>">
		<?php }?>
	<?php if(!empty($plpopup_headtop)){?><?php } echo $plpopup_headtop; ?>
	<?php if ($on_offlt == true) {?></a><?php }?>
    </div>
    
    <div class="plp2">
		<?php if ($on_offlt2 == true) {?>
	<a class="plp2" href="<?php if(!empty($plpopup_linktop2)){?><?php } echo $plpopup_linktop2; ?>">
		<?php }?>
	<?php if(!empty($plpopup_headtop2)){?><?php } echo $plpopup_headtop2 ?>
	<?php if ($on_offlt2 == true) {?></a><?php }?>
    </div>
    
    <div class="plp3">
		<?php if ($on_offlh == true) {?>
	<a class="plp3" href="<?php if(!empty($plpopup_linkhome)){?><?php } echo $plpopup_linkhome; ?>">
		<?php }?>
	<?php if(!empty($plpopup_home)){?><?php } echo $plpopup_home; ?>
	<?php if ($on_offlh == true) {?></a><?php }?>
    </div>
    
    <div class="plp4">
		<?php if ($on_offlh2 == true) {?>
	<a class="plp4" href="<?php if(!empty($plpopup_linkhome2)){?><?php } echo $plpopup_linkhome2; ?>">
		<?php }?>
	<?php if(!empty($plpopup_home2)){?><?php } echo $plpopup_home2; ?>
	<?php if ($on_offlh2 == true) {?></a><?php }?>
    </div>
    
    <div class="plp5">
		<?php if ($on_offlh3 == true) {?>
	<a class="plp5" href="<?php if(!empty($plpopup_linkhome3)){?><?php } echo $plpopup_linkhome3; ?>">
		<?php }?>
	<?php if(!empty($plpopup_home3)){?><?php } echo $plpopup_home3; ?>
	<?php if ($on_offlh3 == true) {?></a><?php }?>
    </div>
    
    <div class="plp6">
		<?php if ($on_offlh4 == true) {?>
	<a class="plp6" href="<?php if(!empty($plpopup_linkhome4)){?><?php } echo $plpopup_linkhome4; ?>">
		<?php }?>
	<?php if(!empty($plpopup_home4)){?><?php } echo $plpopup_home4; ?>
	<?php if ($on_offlh4 == true) {?></a><?php }?>
    </div>
    
    <div class="plp7">
		<?php if ($on_offlf == true) {?>
	<a class="plp7" href="<?php if(!empty($plpopup_linkfooter)){?><?php } echo $plpopup_linkfooter; ?>">
		<?php }?>
	<?php if(!empty($plpopup_footer)){?><?php } echo $plpopup_footer; ?>
	<?php if ($on_offlf == true) {?></a><?php }?>
    </div>
	
	<div class="plp8">
		<?php if ($on_offlf2 == true) {?>
	<a class="plp8" href="<?php if(!empty($plpopup_linkfooter2)){?><?php } echo $plpopup_linkfooter2; ?>">
		<?php }?>
	<?php if(!empty($plpopup_footer2)){?><?php } echo $plpopup_footer2; ?>
	<?php if ($on_offlf2 == true) {?></a><?php }?>
    </div>
	
		</div>  
	</div> 

<!-- end content -->
<?php }?>
</div>
