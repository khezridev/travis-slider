<?php

/**
 * Fired during plugin activation
 *
 * @link       https://khzri.ir/
 * @since      1.0.0
 *
 * @package    Travis_Slider
 * @subpackage Travis_Slider/includes
 */
class Travis_Slider_Activator {
	/**
	 * Set Default Settings
	 */
	public static function activate() {
		if ( ! get_option( 'travis_slider_settings_options' ) && ! get_option( 'travis_slider_responsive_options' ) ) {
			$settings = [
				'loop'               => 'true',
				'nav'                => 'true',
				'dots'               => 'false',
				'autoplay'           => 'true',
				'autoplayHoverPause' => 'true',
				'lazyLoad'           => 'false',
				'rtl'                => 'false',
				'autoplaySpeed'      => '500',
				'navSpeed'           => '500',
				'dotsSpeed'          => '500',
				'margin'             => '20',
			];

			$responsive = [
				'large_item'   => '4',
				'large_nav'    => 'true',
				'desktop_item' => '3',
				'desktop_nav'  => 'true',
				'tablet_item'  => '2',
				'tablet_nav'   => 'false',
				'mobile_item'  => '1',
				'mobile_nav'   => 'false',
			];

			add_option( 'travis_slider_settings_options', $settings );
			add_option( 'travis_slider_responsive_options', $responsive );
		}
	}
}
