<?php

/**
 * Class Travis_Slider_Shortcode
 */
class Travis_Slider_Shortcode {
	/**
	 * Travis_Slider_Shortcode constructor.
	 */
	public function __construct() {
		$this->create();
	}

	/**
	 * Create Shortcode Slider
	 */
	public function create() {
		add_shortcode( 'myslideshow', [ $this, 'content' ] );
	}

	/**
	 * Callable Function for Myslideshow Shortcode
	 */
	public function content() {
		if ( ! get_option( 'travis_slider_slides_options' ) ) {
			return '<p class="travis-slider-error">' . __( 'No Image! Please Add Image in the Travis Slider Panel.',
					'travis-slider' ) . '</p>';
		};
		$this->enqueue_assets();
		$data = $this->set_option();
		ob_start();
		include plugin_dir_path( __DIR__ ) . 'public/partials/travis-slider-public-display.php';

		return ob_get_clean();
	}

	/**
	 * Enqueue CSS and JS Owl Carousel files
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'travis-slider-owl-carousel-min',
			plugin_dir_url( __DIR__ ) . 'public/components/owl-carousel/owl.carousel.min.css',
			[],
			TRAVIS_SLIDER_VERSION,
			'all'
		);
		wp_enqueue_style(
			'travis-slider-owl-theme-default-min',
			plugin_dir_url( __DIR__ ) . 'public/components/owl-carousel/owl.theme.default.min.css',
			[],
			TRAVIS_SLIDER_VERSION,
			'all'
		);

		wp_enqueue_script(
			'travis-slider-owl-carousel',
			plugin_dir_url( __DIR__ ) . 'public/components/owl-carousel/owl.carousel.min.js',
			[ 'jquery' ],
			TRAVIS_SLIDER_VERSION,
			false
		);
	}

	/**
	 * Get setting and responsive option
	 * convert array to string
	 * convert string to parameter Owl Carousel Library
	 */
	public function set_option() {
		$settings   = get_option( 'travis_slider_settings_options' );
		$responsive = get_option( 'travis_slider_responsive_options' );
		$settings   = array_filter( $settings );
		$responsive = array_filter( $responsive );
		$settings   = http_build_query( $settings, '', ',' );
		$settings   = str_replace( '=', ':', $settings );
		( is_rtl() ) ? $settings .= ',rtl:true' : '';

		$settings .= ',
		responsiveClass:true,
		responsive:{
		1200:{
			items:' . $responsive['large_item'] . ',
			nav:' . $responsive['large_nav'] . '
			},
		992:{
			items:' . $responsive['desktop_item'] . ',
			nav:' . $responsive['desktop_nav'] . '
			},
		768:{
			items:' . $responsive['tablet_item'] . ',
			nav:' . $responsive['tablet_nav'] . '
			},
		0:{
			items:' . $responsive['mobile_item'] . ',
			nav:' . $responsive['mobile_nav'] . '
			}
		}';

		return $settings;
	}
}