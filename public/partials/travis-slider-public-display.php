<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://khzri.ir/
 * @since      1.0.0
 *
 * @package    Travis_Slider
 * @subpackage Travis_Slider/public/partials
 */
?>

<?php
$slides = get_option( 'travis_slider_slides_options' );
$slides = $slides['file_list'];
?>
<div class="travis-slider owl-carousel">
	<?php foreach ( $slides as $slide ) : ; ?>
        <div><img src="<?= $slide ?>"></div>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.owl-carousel').owlCarousel({
			<?= $data;?>
        })
    })
</script>
