<?php

global $session, $languages;
$code = $session->data['language'];

require(DIR_APPLICATION.'/../admin/language/'.$languages[$code]['directory'].'/module/spcallmeback.php');

$_['text_error_title'] = 'Ошибка при отправке запроса!';
$_['text_error_recaptcha'] = 'Ошибка в reCaptcha обновите страницу F5 пройдите reCaptcha и отправьте снова.';

?>