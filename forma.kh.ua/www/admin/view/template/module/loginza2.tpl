<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
	  
	  <a onclick="document.getElementById('stay_field').value='0'; $('#form').submit();" class="button"><?php echo $button_save_and_go; ?></a>
	  <a onclick="document.getElementById('stay_field').value='1'; $('#form').submit();" class="button"><?php echo $button_save_and_stay; ?></a>
	  
	  <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
	  
	  </div>
    </div>
	<? /*
google, yandex, mailruapi, mailru, vkontakte, facebook, odnoklassniki, livejournal, twitter, linkedin,
 loginza, myopenid, webmoney, rambler, flickr, lastfm, verisign, aol, steam, openid. */ ?>
	
	
	  
	  
	  
	  <style>
	  .clist 
	  {
		border-top:  1px #ccc solid;
		border-left:  1px #ccc solid;
	  }
	  
	  .clist td
	  {
		padding: 5px;
		border-right: 1px #ccc solid;
		border-bottom: 1px #ccc solid;
	  }
	  
	  .plus
	  {
		background: green;
		text-align: center;
	  }
	  
	  .minus
	  {
		background: #F58C6C;
		text-align: center;
	  }
	  
	  .vopros
	  {
		text-align: center;
	  }
	  </style>
	  
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="stay_field" name="stay" value="1">
        <table class="form">
		<tr>
			<td><? echo $entry_status; ?></td>
			<td><select name="loginza2_status">
                  <?php if ( $loginza2_status ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		</tr>
		<tr>
			<td><? echo $entry_format; ?></td>
			<td>
				<table>
				<tr>
					<td width=200><input type="radio" 
					name="loginza2_format" 
					value="table" 
					id="loginza2_format_table"
					<? if( $loginza2_format=='table' ) { ?> checked <? } ?>
					><label for="loginza2_format_table"><?php echo $text_format_table; ?></label></td>
					<td width=200><input type="radio" name="loginza2_format" 
					value="button" 
					id="loginza2_format_button"
					<? if( $loginza2_format=='button' ) { ?> checked <? } ?> 
					><label for="loginza2_format_button"><?php echo $text_format_button; ?></label></td>
					
					<td width=200><input type="radio" name="loginza2_format" 
					value="icons" id="loginza2_format_icons"
					<? if( $loginza2_format=='icons' ) { ?> checked <? } ?>
					><label for="loginza2_format_icons"><?php echo $text_format_icons; ?></label></td>
				</tr>
				<tr>
					<td valign=top><img src="/image/loginza/table.gif"></td>
					<td valign=top><img src="/image/loginza/button.gif"></td>
					<td valign=top><img src="/image/loginza/icons.gif"></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><? echo $entry_label; ?></td>
			<td><input type="text" name="loginza2_label" value="<? echo $loginza2_label; ?>" style="width: 300px;"></td>
		</tr>
		<tr>
			<td><? echo $entry_default; ?></td>
			<td><select name="loginza2_default">
					<option value=""><? echo $text_none; ?></option>
					<? foreach($socnets_list as $value=>$label) { ?>
						<option value="<? echo $value; ?>"
						<? if( $value==$loginza2_default ) echo 'selected'; ?>
						
						><? echo $label; ?></option>
					<? } ?>
                </select></td>
		</tr>
		
		
		<? /* start update: a1 */ ?>
		<tr>
			<td colspan=2><b><? echo $entry_confirm_data; ?></b></td>
		</tr>
		<tr>
			<td colspan=2><i><? echo $entry_confirm_data_notice; ?></i></td>
		</tr>
		<tr>
			<td><? echo $entry_confirm_firstname; ?></td>
			<td><select name="loginza2_confirm_firstname_status">
                  <?php if ( $loginza2_confirm_firstname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $loginza2_confirm_firstname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select></td>
		</tr>
		<tr>
			<td><? echo $entry_confirm_lastname; ?></td>
			<td><select name="loginza2_confirm_lastname_status">
                <?php if ( $loginza2_confirm_lastname_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $loginza2_confirm_lastname_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select></td>
		</tr>
		<tr>
			<td><? echo $entry_confirm_email; ?></td>
			<td><select name="loginza2_confirm_email_status">
                <?php if ( $loginza2_confirm_email_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $loginza2_confirm_email_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                </select></td>
		</tr>
		<tr>
			<td><? echo $entry_confirm_phone; ?></td>
			<td><select name="loginza2_confirm_phone_status">
                 <?php if ( $loginza2_confirm_phone_status == 1  ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" selected="selected" ><?php echo $text_confirm_none; ?></option>
					<option value="2" ><?php echo $text_confirm_allways; ?></option>
                  <?php } elseif( $loginza2_confirm_phone_status == 2 ) { ?>
					<option value="0"><?php echo $text_confirm_disable; ?></option>
					<option value="1" ><?php echo $text_confirm_none; ?></option>
					<option value="2" selected="selected" ><?php echo $text_confirm_allways; ?></option>
				  <?php } else { ?>
					<option value="0" selected="selected"><?php echo $text_confirm_disable; ?></option>
					<option value="1"><?php echo $text_confirm_none; ?></option>
					<option value="2"><?php echo $text_confirm_allways; ?></option>
				  <?php } ?>
                 </select></td>
		</tr>
		<? /* end update: a1 */ ?>
		
		
		
		<tr>
			<td colspan=2><b><? echo $entry_widget_header; ?></b></td>
		</tr>
		<tr>
			<td colspan=2><i><? echo $entry_widget_notice; ?></i></td>
		</tr>
		<tr>
			<td><? echo $entry_id; ?></td>
			<td><input type="text" name="loginza2_widget_id" value="<? echo $loginza2_widget_id; ?>" style="width: 300px;"></td>
		</tr>
		<tr>
			<td><? echo $entry_sig; ?></td>
			<td><input type="text" name="loginza2_widget_sig" value="<? echo $loginza2_widget_sig; ?>" style="width: 300px;"></td>
		</tr>
		<tr>
			<td><? echo $entry_safemode; ?></td>
			<td><select name="loginza2_safemode">
                  <?php if ( $loginza2_safemode ) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0" ><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected" ><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
		</tr>
		<tr>
			<td colspan=2><b><? echo $filter_header; ?></b></td>
		</tr>
		<tr>
			<td colspan=2><i><? echo $filter_notice; ?></i></td>
		</tr>		
		</table>
	  
	  
        <table class="clist" cellpadding=0 cellspacing=0>
		<tr>
			<td></td>
			<td></td>
			<td colspan=12 align=center><b><? echo $col_data; ?></b></td>
			<td></td>
		</tr>
		<tr>
            <td><b><? echo $col_socnet; ?></b></td>
            <td><b>URL</b></td>
            <td><b><? echo $col_link; ?></b></td>
			<td><b><? echo $col_nickname; ?></b></td>   
            <td><b><? echo $col_email; ?></b></td>
            <td><b><? echo $col_gender; ?></b></td>
            <td><b><? echo $col_photo; ?></b></td>
            <td><b><? echo $col_name; ?></b></td>
            <td><b><? echo $col_dob; ?></b></td>
            <td><b><? echo $col_work; ?></b></td>
            <td><b><? echo $col_address; ?></b></td>
            <td><b><? echo $col_phone; ?></b></td>
            <td><b><? echo $col_im; ?></b></td>
            <td><b><? echo $col_biography; ?></b></td>
            <td><b><? echo $col_enable; ?></b></td>
        </tr>
		<tr>
            <td><? echo $entry_google; ?></td>
            <td><a href="<? echo $url_google; ?>" target=_blank><? echo $url_google; ?></a></td>         
            <td class="plus">+</td>                    
            <td class="minus">-</td>          
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus"><? echo $text_country; ?></td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>			
            <td align=center><input type="checkbox" name="loginza2_google" value="y"
				<? if( $loginza2_google=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_yandex; ?></td>
            <td><a href="<? echo $url_yandex; ?>" target=_blank><? echo $url_yandex; ?></a></td>          
            <td class="minus">-</td>          
			<td class="plus">+</td>   
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_yandex" value="y"
				<? if( $loginza2_yandex=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_mailruapi; ?></td>
            <td><a href="<? echo $url_mailruapi; ?>" target=_blank><? echo $url_mailruapi; ?></a></td>          
            <td class="minus">-</td>    
			<td class="minus">-</td>         
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_mailruapi" value="y"
				<? if( $loginza2_mailruapi=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_mailru; ?></td>
            <td><a href="<? echo $url_mailru; ?>" target=_blank><? echo $url_mailru; ?></a></td>    
			<td class="plus">+</td>          
            <td class="plus">+</td>          
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
			<td class="plus">регион РФ</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_mailru" value="y"
				<? if( $loginza2_mailru=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_vkontakte; ?></td>
            <td><a href="<? echo $url_vkontakte; ?>" target=_blank><? echo $url_vkontakte; ?></a></td>          
            <td class="plus">+</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_vkontakte" value="y"
				<? if( $loginza2_vkontakte=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_facebook; ?></td>
            <td><a href="<? echo $url_facebook; ?>" target=_blank><? echo $url_facebook; ?></a></td>          
            <td class="plus">+</td>          
			<td class="plus">+</td>    
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_facebook" value="y"
				<? if( $loginza2_facebook=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_odnoklassniki; ?></td>
            <td><a href="<? echo $url_odnoklassniki; ?>" target=_blank><? echo $url_odnoklassniki; ?></a></td>          
            <td class="minus">-</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_odnoklassniki" value="y"
				<? if( $loginza2_odnoklassniki=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_livejournal; ?></td>
            <td><a href="<? echo $url_livejournal; ?>" target=_blank><? echo $url_livejournal; ?></a></td>          
            <td class="plus">+</td>          
			<td class="plus">+</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_livejournal" value="y"
				<? if( $loginza2_livejournal=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_twitter; ?></td>
            <td><a href="<? echo $url_twitter; ?>" target=_blank><? echo $url_twitter; ?></a></td>          
            <td class="plus">+</td>          
			<td class="plus">+</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td align=center><input type="checkbox" name="loginza2_twitter" value="y"
				<? if( $loginza2_twitter=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_linkedin; ?></td>
            <td><a href="<? echo $url_linkedin; ?>" target=_blank><? echo $url_linkedin; ?></a></td>          
            <td class="plus">+</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_linkedin" value="y"
				<? if( $loginza2_linkedin=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_loginza; ?></td>
            <td><a href="<? echo $url_loginza; ?>" target=_blank><? echo $url_loginza; ?></a></td>          
        	<td class="minus">-</td>  
			<td class="minus">-</td>    
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_loginza" value="y"
				<? if( $loginza2_loginza=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_myopenid; ?></td>
            <td><a href="<? echo $url_myopenid; ?>" target=_blank><? echo $url_myopenid; ?></a></td>          
            <td class="minus">-</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_myopenid" value="y"
				<? if( $loginza2_myopenid=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_webmoney; ?></td>
            <td><a href="<? echo $url_webmoney; ?>" target=_blank><? echo $url_webmoney; ?></a></td>          
            <td class="plus">+</td>          
			<td class="plus">+</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus"><? echo $text_country; ?></td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_webmoney" value="y"
				<? if( $loginza2_webmoney=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_rambler; ?></td>
            <td><a href="<? echo $url_rambler; ?>" target=_blank><? echo $url_rambler; ?></a></td>          
            <td colspan=12 align=center>Не работало в момент тестирования</td>          
            <td align=center><input type="checkbox" name="loginza2_rambler" value="y"
				<? if( $loginza2_rambler=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_flickr; ?></td>
            <td><a href="<? echo $url_flickr; ?>" target=_blank><? echo $url_flickr; ?></a></td>          
            <td colspan=12 align=center>Не работало в момент тестирования</td>          
            <td align=center><input type="checkbox" name="loginza2_flickr" value="y"
				<? if( $loginza2_flickr=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_lastfm; ?></td>
            <td><a href="<? echo $url_lastfm; ?>" target=_blank><? echo $url_lastfm; ?></a></td>          
            <td class="plus">+</td>          
			<td class="plus">+</td>    
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_lastfm" value="y"
				<? if( $loginza2_lastfm=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_verisign; ?></td>
            <td><a href="<? echo $url_verisign; ?>" target=_blank><? echo $url_verisign; ?></a></td>          
            <td class="vopros">?</td>          
			<td class="vopros">?</td>    
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td class="vopros">?</td>
            <td align=center><input type="checkbox" name="loginza2_verisign" value="y"
				<? if( $loginza2_verisign=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_aol; ?></td>
            <td><a href="<? echo $url_aol; ?>" target=_blank><? echo $url_aol; ?></a></td>          
            <td class="plus">+</td>          
			<td class="minus">-</td>    
            <td class="plus">+</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="plus">+</td>
            <td class="minus">-</td>
            <td class="plus"><? echo $text_country; ?></td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_aol" value="y"
				<? if( $loginza2_aol=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_steam; ?></td>
            <td><a href="<? echo $url_steam; ?>" target=_blank><? echo $url_steam; ?></a></td>          
            <td class="minus">-</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_steam" value="y"
				<? if( $loginza2_steam=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
		<tr>
            <td><? echo $entry_openid; ?></td>
            <td><a href="<? echo $url_openid; ?>" target=_blank><? echo $url_openid; ?></a></td>          
            <td class="minus">-</td>          
			<td class="minus">-</td>    
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td class="minus">-</td>
            <td align=center><input type="checkbox" name="loginza2_openid" value="y"
				<? if( $loginza2_openid=='y' ) { ?>
				checked
				<? } ?>
			></td>
          </tr>
        </table>		
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>