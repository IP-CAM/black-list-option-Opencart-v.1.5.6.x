<div class="content">
<!--<h3><?php echo $text_payment; ?></h3>
<h5><center><?php echo $text_payment_coment; ?></center></h5>-->
Вы сможете распечатать счет-фактуру после нажатия на кнопку Подтверждение заказа.<br>Также ссылка на печать счета будет выслана на указанный e-mail.<br>Зарегисрированым пользователям доступна печать счета в личном кабинете.
</div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({
    type: 'GET',
    url: 'index.php?route=payment/payment_schet/confirm',
    success: function() {
      location = '<?php echo $continue; ?>';
    }
  });
});
//--></script>
