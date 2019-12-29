<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'travis_slider_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __DIR__ ) . '/includes/lib/cmb2/init.php' ) ) {
	require_once dirname( __DIR__ ) . '/includes/lib/cmb2/init.php';
} elseif ( file_exists( dirname( __DIR__ ) . '/includes/lib/CMB2/init.php' ) ) {
	require_once dirname( __DIR__ ) . '/includes/lib/CMB2/init.php';
}
function travis_slider_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template.
	if ( get_option( 'page_on_front' ) !== $cmb->object_id ) {
		return false;
	}

	return true;
}

add_action( 'cmb2_admin_init', 'travis_slider_register_main_options_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */

function travis_slider_register_main_options_metabox() {
	/**
	 * Registers main options page menu item and form.
	 */

	$args = [
		'id'           => 'travis_slider_slides_options_page',
		'title'        => 'Travis Slider',
		'object_types' => [ 'options-page' ],
		'option_key'   => 'travis_slider_slides_options',
		'tab_group'    => 'travis_slider_slides_options',
		'tab_title'    => 'Slides',
		'menu_title'   => esc_html__( 'Travis Slider', 'cmb2' ), // Falls back to 'title' (above).
		'icon_url'     => 'dashicons-format-gallery',
	];
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'travis_slider_options_display_with_tabs';
	}
	$main_options = new_cmb2_box( $args );
	/**
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */

	$main_options->add_field( [
		'name' => 'Slides',
		'desc' => 'Shortcode: [myslideshow]',
		'type' => 'title',
		'id'   => 'title_slides',
	] );

	$main_options->add_field( [
		'name'         => 'Insert Slides',
		'desc'         => 'Can be dragged and dropped to reorder',
		'id'           => 'file_list',
		'type'         => 'file_list',
		'preview_size' => [ 180, 180 ], // Default: array( 50, 50 )
		'query_args'   => [ 'type' => 'image' ], // Only images attachment
		// Optional, override default text strings
		'text'         => [
			'add_upload_files_text' => 'Select Slide', // default: "Add or Upload Files"
			'remove_image_text'     => 'Replacement', // default: "Remove Image"
			'file_text'             => 'Replacement', // default: "File:"
			'file_download_text'    => 'Replacement', // default: "Download"
			'remove_text'           => 'Replacement', // default: "Remove"
		],
	] );
	/**
	 * Registers secondary options page, and set main item as parent.
	 */

	$args = [
		'id'           => 'travis_slider_settings_options_page',
		'title'        => 'Travis Slider',
		'menu_title'   => 'Settings Options', // Use menu title, & not title to hide main h2.
		'object_types' => [ 'options-page' ],
		'option_key'   => 'travis_slider_settings_options',
		'parent_slug'  => 'travis_slider_slides_options',
		'tab_group'    => 'travis_slider_slides_options',
		'tab_title'    => 'Settings',
	];
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'travis_slider_options_display_with_tabs';
	}
	$settings_options = new_cmb2_box( $args );

	$settings_options->add_field( [
		'name' => 'Features',
		'desc' => 'Enable and disable features',
		'type' => 'title',
		'id'   => 'title_attributes',
	] );

	$settings_options->add_field( [
		'name'    => 'Loop',
		'id'      => 'loop',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Infinity loop - Duplicate last and first items to get loop illusion',
	] );

	$settings_options->add_field( [
		'name'    => 'Nav',
		'id'      => 'nav',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Show next/prev buttons',
	] );

	$settings_options->add_field( [
		'name'    => 'Dots',
		'id'      => 'dots',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'false',
		'desc'    => 'Show dots navigation',
	] );

	$settings_options->add_field( [
		'name'    => 'Autoplay',
		'id'      => 'autoplay',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Carousel item autoplay by default',
	] );

	$settings_options->add_field( [
		'name'    => 'Autoplay Hover Pause',
		'id'      => 'autoplayHoverPause',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Pause on mouse hover',
	] );

	$settings_options->add_field( [
		'name'    => 'Lazyload',
		'id'      => 'lazyLoad',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'false',
		'desc'    => 'Lazy load images - data-src and data-src-retina for highres. Also load images into background inline style if element is not',
	] );

	$settings_options->add_field( [
		'name' => 'Values',
		'desc' => 'Change values, times and others',
		'type' => 'title',
		'id'   => 'title_values',
	] );

	$settings_options->add_field( [
		'name'    => 'Autoplay Speed',
		'desc'    => 'millisecond',
		'default' => '500',
		'id'      => 'autoplaySpeed',
		'type'    => 'text_medium',
	] );

	$settings_options->add_field( [
		'name'    => 'Nav Speed',
		'desc'    => 'millisecond',
		'default' => '500',
		'id'      => 'navSpeed',
		'type'    => 'text_medium',
	] );

	$settings_options->add_field( [
		'name'    => 'Dots Speed',
		'desc'    => 'millisecond',
		'default' => '500',
		'id'      => 'dotsSpeed',
		'type'    => 'text_medium',
	] );

	$settings_options->add_field( [
		'name'    => 'Margin Slides',
		'desc'    => 'px',
		'default' => '20',
		'id'      => 'margin',
		'type'    => 'text_medium',
	] );

	/**
	 * Registers responsive options page, and set main item as parent.
	 */
	$args = [
		'id'           => 'travis_slider_responsive_options_page',
		'title'        => 'Travis Slider',
		'menu_title'   => 'Responsive Options', // Use menu title, & not title to hide main h2.
		'object_types' => [ 'options-page' ],
		'option_key'   => 'travis_slider_responsive_options',
		'parent_slug'  => 'travis_slider_slides_options',
		'tab_group'    => 'travis_slider_slides_options',
		'tab_title'    => 'Responsive',
	];
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'travis_slider_options_display_with_tabs';
	}
	$responsive_options = new_cmb2_box( $args );

	$responsive_options->add_field( [
		'name' => 'Large Desktop',
		'desc' => '(1200px and above)',
		'type' => 'title',
		'id'   => 'large_desktop',
	] );

	$responsive_options->add_field( [
		'name'    => 'Number Items',
		'default' => '4',
		'id'      => 'large_item',
		'type'    => 'text_medium',
	] );

	$responsive_options->add_field( [
		'name'    => 'Nav',
		'id'      => 'large_nav',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Show Nav in Large Desktops',
	] );

	$responsive_options->add_field( [
		'name' => 'Desktop',
		'desc' => '(992px to 1200px)',
		'type' => 'title',
		'id'   => 'title_desktop',
	] );

	$responsive_options->add_field( [
		'name'    => 'Number Items',
		'default' => '3',
		'id'      => 'desktop_item',
		'type'    => 'text_medium',
	] );

	$responsive_options->add_field( [
		'name'    => 'Nav',
		'id'      => 'desktop_nav',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'true',
		'desc'    => 'Show Nav in Desktops',
	] );

	$responsive_options->add_field( [
		'name' => 'Tablet',
		'desc' => '(768px to 992px)',
		'type' => 'title',
		'id'   => 'title_tablet',
	] );

	$responsive_options->add_field( [
		'name'    => 'Number Items',
		'default' => '2',
		'id'      => 'tablet_item',
		'type'    => 'text_medium',
	] );

	$responsive_options->add_field( [
		'name'    => 'Nav',
		'id'      => 'tablet_nav',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'false',
		'desc'    => 'Show Nav in Tablet',
	] );

	$responsive_options->add_field( [
		'name' => 'Mobile',
		'desc' => '(768px and less)',
		'type' => 'title',
		'id'   => 'title_mobile',
	] );

	$responsive_options->add_field( [
		'name'    => 'Number Items',
		'default' => '1',
		'id'      => 'mobile_item',
		'type'    => 'text_medium',
	] );

	$responsive_options->add_field( [
		'name'    => 'Nav',
		'id'      => 'mobile_nav',
		'type'    => 'radio_inline',
		'options' => [
			'true'  => __( 'Enable', 'cmb2' ),
			'false' => __( 'Disable', 'cmb2' ),
		],
		'default' => 'false',
		'desc'    => 'Show Nav in Mobile',
	] );
}

/**
 * A CMB2 options-page display callback override which adds tab navigation among
 * CMB2 options pages which share this same display callback.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 */
function travis_slider_options_display_with_tabs( $cmb_options ) {
	$tabs = travis_slider_options_page_tabs( $cmb_options );
	?>
    <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
		<?php if ( get_admin_page_title() ) : ?>
            <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
		<?php endif; ?>
        <h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>"
                    href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
        </h2>
        <form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST"
            id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php $cmb_options->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
        </form>
    </div>
	<?php
}

/**
 * Gets navigation tabs array for CMB2 options pages which share the given
 * display_cb param.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 *
 * @return array Array of tab information.
 */
function travis_slider_options_page_tabs( $cmb_options ) {
	$tab_group = $cmb_options->cmb->prop( 'tab_group' );
	$tabs      = [];
	foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
		if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
			$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
				? $cmb->prop( 'tab_title' )
				: $cmb->prop( 'title' );
		}
	}

	return $tabs;
}

function travis_slider_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'travis_slider_options', $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'travis_slider_options', $default );
	$val  = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}