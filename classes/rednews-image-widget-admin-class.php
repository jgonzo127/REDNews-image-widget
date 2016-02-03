<?php
/**
 * REDNews Image Wiget Admin Class
 *
 * @since  1.0.0
 */

class Rednews_Image_Widget_Admin {

	/**
	 * The constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up our admin hooks.
		$this->initialize();
	}

	/**
	 * Set up our admin hooks.
	 *
	 * @since  1.0.0
	 */
	public function initialize() {

		// Include our JS and CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since  1.0.0
	 */
	function admin_enqueue( $hook ) {

		// REDNews Image widget Admin CSS.
		wp_register_style(
			'mm-components-admin',
			REDNEWSIMAGE_URL . 'css/rednews-image-widget-admin.css',
			array(),
			REDNEWSIMAGE_VERSION
		);

		// REDNews Image Widget Admin JS.
		wp_register_script(
			'rednews-image-widget-admin',
			REDNEWSIMAGE_URL . 'js/rednews-image-widget-admin.js',
			array(),
			REDNEWSIMAGE_VERSION,
			true
		);

		// Only enqueue on specific admin pages.
		if ( 'widgets.php' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script( 'rednews-image-widget-admin' );
		}
	}
}