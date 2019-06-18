<div id="footer">



  <div class="column">
    <span class="title"><?php echo $text_information; ?></span>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>

	  </ul>
  </div>
  <div class="column">
    <span class="title"><?php echo $text_service; ?></span>
    <ul>
        <li><a href="<?php echo $contact; ?>"><?php echo $text_contacts; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        <li><a href="<?php echo $articles; ?>"><?php echo $text_articles; ?></a></li>    </ul>
  </div>

  <div class="column">
    <span class="title"><?php echo $text_account; ?></span>
    <ul>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
</div>
<script>
    $(document).ready(function(){
        <?php /*
        var fpheight = $('#filterpro_box').height();
        var fppos = $('#filterpro_box').offset().top;
        function sticky() {
            if (($('#footer').offset().top - fppos) < fpheight) {
              $('#filterpro_box').addClass('maxh');
            } else {
              $('#filterpro_box').removeClass('maxh');
            }
            var a = $(window).scrollTop() + $('#filterpro_box').height();
            var b = $('#footer').offset().top;
            var c = $(window).scrollTop() + $(window).height() - $('.pagination').height() - 5;
            if (!isMobile() && !isTablet()) {
              if (c > b) {
                $('.pagination').css('position', 'absolute');
                $('.pagination').css('top', parseInt(b - $('.pagination').height() - 40));
              } else {
                $('.pagination').removeAttr('style');
                $('.pagination').css('display', 'block');
              }
              if (a > b) {
                $('#filterpro_box').css('position', 'absolute');
                $('#filterpro_box').css('top', parseInt(b - $('#filterpro_box').height() - 30 - 10));
              } else {
                $('#filterpro_box').removeAttr('style');
                var y = $(window).scrollTop();
                var h = ($('#column-left').height())+400;
                if( y > h ){
                    $('#filterpro_box').css({
                        'position': 'fixed',
                        'top': '5pt'
                    });
                    $('.pagination').css('display', 'block');
                } else {
                    $('#filterpro_box').removeAttr('style');
                    $('.pagination').removeAttr('style');
                }
              }
            }

            /*var anchors = $('a[name^=anchor]');
            var loc = $(window).scrollTop();
            for(var i=0; i<anchors.length; i++) {
              var anchor = anchors.eq(i);
              if ((loc - anchor.offset().top) < 0 && (loc - anchor.offset().top) > -2200) {
                var currentPage = anchors.attr('name').substr(-1);
                var pagination = $('.pagination .links');
                if (currentPage !== "1") {
                  pagination.find('a').slice(2, 5).remove();

                } 
              }
            }*
        };

        <?php if (array_key_exists('route', $this->request->get) && $this->request->get['route'] != "product/product") { ?>
        $(window).scroll(sticky);
        $(window).resize(sticky);
        <?php } ?>
  */ ?>
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var url=document.location.href;
        $.each($("#menu ul li a"),function(){
            if(this.href==url){$(this).addClass('active');};
        });
        //setTimeout(function() {
          var clh = $("#content").height();
          $("#column-left").height(clh);
        //}, 1000);
    });
    $( window ).scroll(function() {
        var clh = $("#content").height() - 160;
        $("#column-left").height(clh);
    });
    $(document).ready(function(){
        $("#filterpro_box1").stick_in_parent();
    });
</script>


<div id="powered"><?php echo $powered; ?></div>

</div>
</div>
<div id="tooltip"></div>
<style type="text/css">
#cboxOverlay {
  background: rgba(0,0,0,0.5) none repeat scroll 0% 0%;
  z-index: 14;
}
</style>
<script type="text/javascript" src="catalog/view/javascript/notificationl.js"></script>
</body></html>