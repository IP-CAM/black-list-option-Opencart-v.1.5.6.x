<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/loginza2.css" />
<? /* ==================== Крупные значки таблицей ======================= */ ?>

<? /* ====================  / END Крупные значки таблицей ======================= */ ?>

<? /* ==================== Кнопка Логинзы ============== */ ?>
<? if( $loginza2_format == 'button' ) { ?>
	<div class="simplecheckout-block-heading simple_loginza_button_content">	
			<div  class="simple_button_right">
				<a href="http://loginza.ru/api/widget?token_url=<? echo urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?route=account/loginza2'); 
				?><? if( $loginza2_default ) 
				echo '&provider='.$loginza2_default;
				?>&providers_set=<? echo $providers; ?>" class="loginza">
				<img src="http://loginza.ru/img/sign_in_button_gray.gif" 
				alt="<? echo $loginza2_label; ?>" />
				</a>
			</div>
			<div class="simple_button_left">
				<? echo $loginza2_label; ?>
			</div>
	</div>	  
<? } ?>
<? /* ====================  / END Кнопка Логинзы ============== */ ?>		  

<? /* ==================== Маленькие иконки ============== */ ?>
		  
		  <? if( $loginza2_format == 'icons' ) { 
		  ?>
		  <div class="simplecheckout-block-heading simple_loginza_icons_content">
			<? if( $loginza2_label ) { ?>
				<div class="simple_icons_header"><? echo $loginza2_label; ?></div><br>
			<? } ?>
		  
		  
		  <div class="simple_icons_links">
		  <? foreach( $res_socnets as $row ) { ?>
		  <a href="https://loginza.ru/api/widget?token_url=<? 
			echo urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?route=account/loginza2'); 
			?>&provider=<? echo $row['name']; ?>&providers_set=<? echo $providers; ?>"><img src="/image/loginza/icons/<? echo $row['name']; ?>.png" alt="<? echo $row['label']; ?>"
			></a>&nbsp;
			
		  <? } ?>
		  </div>
</div>		  
		<? } ?>
<? /* ==================== / END Маленькие иконки ============== */ ?>