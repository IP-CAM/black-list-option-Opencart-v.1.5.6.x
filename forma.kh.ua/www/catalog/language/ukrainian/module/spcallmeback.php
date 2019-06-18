<?php

global $session, $languages;
$code = $session->data['language'];

require(DIR_APPLICATION.'/../admin/language/'.$languages[$code]['directory'].'/module/spcallmeback.php');

$_['text_error_title'] = 'Помилка при відправці запроса!';
$_['text_error_recaptcha'] = 'Помилка в reCaptcha оновіть сторінку F5 пройдіть reCaptcha і відправте знову.';

?>