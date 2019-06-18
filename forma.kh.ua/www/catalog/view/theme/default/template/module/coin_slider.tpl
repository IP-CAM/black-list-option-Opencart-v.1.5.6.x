<?php if ($slider_config['status'] == 1) { ?>
	<style>
	<?php if (!empty($slider_config['width_title'])) { ?>
	.coin-slider .title {
		width: <?php echo $slider_config['width_title']; ?>;
	}	
	<?php } ?>

	<?php if (!empty($slider_config['width_subtitle'])) { ?>
	.coin-slider .subtitle {
		width: <?php echo $slider_config['width_subtitle']; ?>;
	}	
	<?php } ?>

	<?php if (!empty($slider_config['padding_top'])) { ?>
	.cs-title {
		padding-top: <?php echo $slider_config['padding_top']; ?>;
	}	
	<?php } ?>

	<?php if (!empty($slider_config['padding_left'])) { ?>
	.cs-title {
		padding-left: <?php echo $slider_config['padding_left']; ?>;
	}	
	<?php } ?>

	<?php if (!empty($slider_config['distance'])) { ?>
	.coin-slider .subtitle {
		padding-top: <?php echo $slider_config['distance']; ?>;
	}	
	<?php } ?>

	<?php if (!empty($slider_config['text_color'])) { ?>
	.cs-title .subtitle, .cs-title .title{
		color: #<?php echo $slider_config['text_color']; ?>;
	}	
	<?php } ?>

	<?php if (empty($slider_config['show_buttons_prev_next'])) { ?>
	.cs-prev, .cs-next{
		display: none;
	}	
	<?php } ?>

	<?php if (empty($slider_config['show_buttons_bottom'])) { ?>
	.cs-buttons{
		display: none;
	}	
	<?php } ?>

    .coin-slider {
        z-index: 1;
    }
	@media (max-width: 767px){
		.hidden-xs {
    		display: none!important;
		}
	}
	</style>

	<div id="coin-slider<?php echo $module; ?>" class="hidden-xs">
	  <?php foreach ($sliders as $slider) { ?>
		  <?php if ($slider['link']) { ?>
		  	<a href="<?php echo $slider['link']; ?>" <?php if ($slider_config['link_new_tab'] == 0) { ?> target="_blank" <?php } ?> >
				<img src="<?php echo $slider['image']; ?>">
				<span>
					<span class="title"><?php echo $slider['title']; ?></span>
					<span class="subtitle">
						<?php echo $slider['subtitle']; ?>
					</span>
				</span>
			</a>
		  <?php } else { ?>
		  	<img src="<?php echo $slider['image']; ?>">
			<span>
				<span class="title"><?php echo $slider['title']; ?></span>
				<span class="subtitle">
					<?php echo $slider['subtitle']; ?>
				</span>
			</span>
		  <?php } ?>
	  <?php } ?>
	</div>

	<script type="text/javascript"><!--
		$(document).ready(function() {
			$('#coin-slider<?php echo $module; ?>').coinslider({ 
				width: <?php echo $sliders[0]['width'] ?>,
				height: <?php echo $sliders[0]['height'] ?>,
				spw: <?php echo $slider_config['spw'] ?>,
				sph: <?php echo $slider_config['sph'] ?>,
				delay: <?php echo $slider_config['delay'] ?>,
				sDelay: <?php echo $slider_config['s_delay'] ?>,
				opacity: <?php echo $slider_config['opacity'] ?>,
				titleSpeed: <?php echo $slider_config['title_speed'] ?>,
				effect: '<?php echo $slider_config['effect'] ?>',
				navigation: <?php echo $slider_config['navigation'] ?>,
				links: <?php echo $slider_config['links'] ?>,
				hoverPause: <?php echo $slider_config['hover_pause'] ?>,
			});
			$(".cs-prev").text('<?php echo $text_prev; ?>');
			$(".cs-next").text('<?php echo $text_next; ?>');
		});
	//--></script>
<?php } ?>