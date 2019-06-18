<div style="background: #f7f7f7; border: 1px solid #dddddd; padding: 10px; margin-bottom: 10px;">
<h3><?php echo $text_payment; ?></h3>
<h5><center><?php echo $text_payment_coment; ?></center></h5>
</div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({
    type: 'GET',
    url: 'index.php?route=payment/sberbank_transfer/confirm',
    success: function() {
      location = '<?php echo $continue; ?>';
    }
  });
});
//--></script>
