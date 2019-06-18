<style>
h1
{    
	font-family: "Trebuchet MS","Arial";
	color: #0099FF;
    font-size: 24px;
	padding-left: 5px;
}

p
{
	font-family: "Trebuchet MS","Arial";
	color: #999999;
	padding-left: 5px;
}

td
{
	font-family: "Trebuchet MS","Arial";
	color: #000;
	padding: 5px;
}

input[type=submit]
{
	  background-color: #F0F0F0;
    background-image: url("/img/widget/button_bg.gif");
    background-repeat: repeat-x;
    border: 1px solid #C4C4C4;
    color: #838383;
    font-family: "Arial";
    font-size: 18px;
    font-weight: bold;
    padding: 5px;
}

input[type=text]
{
	border: 1px #ccc solid;
	width: 160px;
}


input[type=submit]:hover
{
	background-color: #F4FAFD;
    background-image: url("http://loginza.ru/img/widget/button_hover_bg.gif");
    border: 1px solid #D3ECFD;
    color: #2E9CD8;
}

.err
{
	color: red;
	font-family: "Trebuchet MS","Arial";
	font-weight: bold;
	padding-left: 5px;
}

</style>
<h1><? echo $header; ?></h1>
<p><? echo $header_notice; ?></p>
<form action="<? echo $action; ?>" method="POST">
<table>
<? if( $is_firstname ) { ?>
<tr>
	<td><? echo $entry_firstname; ?></td>
	<td><input type="text" name="firstname" value="<? echo $firstname; ?>"><? if( $error_firstname ) { ?><span class="err"><? echo $error_firstname; 
	?></span><? } ?></td>
</tr>
<? } ?>

<? if( $is_lastname ) { ?>
<tr>
	<td width=80><? echo $entry_lastname; ?></td>
	<td><input type="text" name="lastname" value="<? echo $lastname; ?>"><? if( $error_lastname ) { ?><span class="err"><? echo $error_lastname; 
	?></span><? } ?></td>
</tr>
<? } ?>

<? if( $is_email ) { ?>
<tr>
	<td width=80><? echo $entry_email; ?></td>
	<td><input type="text" name="email" value="<? echo $email; ?>"><? if( $error_email ) { ?><span class="err"><? echo $error_email; 
	?></span><? } ?></td>
</tr>
<? } ?>

<? if( $is_telephone ) { ?>
<tr>
	<td width=80><? echo $entry_telephone; ?></td>
	<td><input type="text" name="telephone" value="<? echo $telephone; ?>"><? if( $error_telephone ) { ?><span class="err"><? echo $error_telephone; 
	?></span><? } ?></td>
</tr>
<? } ?>
<tr>
	<td width=80></td>
	<td><input type="submit" value="<? echo $text_submit; ?>"></td>
</tr>
</table>
</form>