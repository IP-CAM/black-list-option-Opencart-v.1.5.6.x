<?php

global $session, $languages;
$code = $session->data['language'];

require(DIR_APPLICATION.'/../admin/language/'.$languages[$code]['directory'].'/module/spcallmeback.php');

$_['text_error_title'] = 'Error sending a request!';
$_['text_error_recaptcha'] = 'Error in reCaptcha refresh page F5 go to reCaptcha and send again.';

?>