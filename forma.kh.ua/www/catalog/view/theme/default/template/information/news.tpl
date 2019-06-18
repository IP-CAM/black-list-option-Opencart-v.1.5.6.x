<?php
//-----------------------------------------------------
// News Module for Opencart v1.5.5   							
// Modified by villagedefrance                          			
// contact@villagedefrance.net                         			
//-----------------------------------------------------
?>

<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	<?php if(isset($news_info)) { ?>
		<div>
			<div class="news" <?php if($image) { echo 'style="min-height:' . $min_height . 'px;"'; } ?>>
				<?php if ($image) { ?>
					<div class="image">
					<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
					</div>
				<?php } ?>
				<!--<h3><?php echo $heading_title; ?></h3>-->
				<?php echo $description; ?>
			</div>
			<div>
			<?php if($addthis) { ?>
			<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_email"></a>
				<a class="addthis_button_print"></a>
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
			<!-- AddThis Button END -->
			<?php } ?>
			</div>
		</div>

        <!--added-->
        <br/>

        <div id="review"></div>
        <h2 id="review-title"><?php echo $text_write; ?></h2>
        <b><?php echo $entry_name; ?></b><br />
        <input type="text" name="name" value="" />
        <br />
        <br />
        <b><?php echo $entry_review; ?></b><br />
        <textarea name="text" cols="40" rows="8" style="width: 60%;"></textarea><br/>
        <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
        <br />
        <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
        <input type="radio" name="rating" value="1" />
        &nbsp;
        <input type="radio" name="rating" value="2" />
        &nbsp;
        <input type="radio" name="rating" value="3" />
        &nbsp;
        <input type="radio" name="rating" value="4" />
        &nbsp;
        <input type="radio" name="rating" value="5" />
        &nbsp;<span>Хорошо</span><br />
        <br />
        <b><?php echo $entry_captcha; ?></b><br />
        <input type="text" name="captcha" value="" />
        <br />
        <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
        <br />
        <div class="buttons">
            <div class="left"><a id="button-review" class="button">Оставить отзыв</a></div>
            <div class="right">
                <a onclick="location='<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a>
                <!--<a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a>-->
            </div>
        </div>

        <!--added-->

<!--		<div class="buttons">
			<div class="right">
				<a onclick="location='<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a>
				&lt;!&ndash;<a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a>&ndash;&gt;
			</div>
		</div>-->
	<?php } elseif (isset($news_data)) { ?>
		<?php foreach ($news_data as $news) { ?>
			<div class="panelcollapsed">
				<h2><?php echo $news['title']; ?></h2>
				<div class="panelcontent">
					<?php echo $news['description']; ?> .. <br />
					<a href="<?php echo $news['href']; ?>"> <?php echo $text_more; ?></a>
					</p>
					<a href="<?php echo $news['href']; ?>"><img src="catalog/view/theme/default/image/message-news.png" alt="" /></a> <b><?php echo $text_posted; ?></b><?php echo $news['posted']; ?>
				</div>
			</div>
		<?php } ?>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><span>На главную</span></a></div>
		</div>
	<?php } ?>
	<?php echo $content_bottom; ?>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');

    return false;
});

$('#review').load('index.php?route=information/news/review&news_id=<?php echo $news_id; ?>');

$('#button-review').bind('click', function() {
    $.ajax({
        url: 'index.php?route=information/news/write&news_id=<?php echo $news_id; ?>',
        type: 'post',
        dataType: 'json',
        data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
        beforeSend: function() {
            $('.success, .warning').remove();
            $('#button-review').attr('disabled', true);
            $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> ...</div>');
        },
        complete: function() {
            $('#button-review').attr('disabled', false);
            $('.attention').remove();
        },
        success: function(data) {
            if (data['error']) {
                $('#review-title').after('<div class="warning">' + data['error'] + '</div>');
            }

            if (data['success']) {
                $('#review-title').after('<div class="success">' + data['success'] + '</div>');

                $('input[name=\'name\']').val('');
                $('textarea[name=\'text\']').val('');
                $('input[name=\'rating\']:checked').attr('checked', '');
                $('input[name=\'captcha\']').val('');
            }
        }
    });
});
//--></script>

<?php echo $footer; ?>