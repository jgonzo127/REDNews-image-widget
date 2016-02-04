<?php

/*
Plugin Name: REDNews Image Widget
Plugin URI:http://jgonzo127.com
Description: An Image Upload Widget with a PDF link input field for REDNews Properties.
Version: 1.0.0
Author:  Jordan Gonzales
Author URI: http://jgonzo127.com
License:
*/

define( 'REDNEWSIMAGE_VERSION', '1.0.0' );
define( 'REDNEWSIMAGE_PATH', plugin_dir_path( __FILE__ ) );
define( 'REDNEWSIMAGE_URL', plugin_dir_url( __FILE__ ) );

// Include general functionality.
require_once REDNEWSIMAGE_PATH . 'functions.php';

add_action( 'wp_enqueue_scripts', 'rednews_image_widget_scripts_and_styles' );
/**
 * Enqueue front-end scripts and styles.
 *
 * @since  1.0.0
 */
function rednews_image_widget_scripts_and_styles() {

	// General styles.
	wp_enqueue_style(
		'rednews-image-widget',
		REDNEWSIMAGE_URL . 'css/rednews-image-widget-public.css',
		array(),
		REDNEWSIMAGE_VERSION
	);
}

// Maybe include admin class.
if ( is_admin() ) {

	require_once REDNEWSIMAGE_PATH . 'classes/rednews-image-widget-admin-class.php';

	new Rednews_Image_Widget_Admin();
}

function rednews_image_widget( $args ) {

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'             => '',
		'image'             => '',
		'image_link'        => '',
		'image_link_target' => '_self',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$title             = $args['title'];
	$image             = $args['image'];
	$image_link        = $args['image_link'];
	$image_link_target = $args['image_link_target'];

	// Support the image being an ID or a URL.
	if ( is_numeric( $image ) ) {
		$image_array = wp_get_attachment_image_src( $image, 'full' );
		$image_url   = $image_array[0];
	} else {
		$image_url = esc_url( $image );
	}

	ob_start(); ?>

	<div class="rednews-image-widget-wrap">

		<?php

		echo '<h2>';

		echo $title;

		echo '</h2>';

		printf(
			'<a href="%s" target="%s"><img src="%s"></a>',
			esc_url( $image_link ),
			esc_attr( $image_link_target ),
			esc_attr( $image_url )
		);

		?>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'rednews_image_widget', 'rednews_image_widget_shortcode' );
/**
 * RedNews Image Widget shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function rednews_image_widget_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return rednews_image_widget( $atts );
}


add_action( 'widgets_init', 'rednews_register_image_wiget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function rednews_register_image_wiget() {

	register_widget( 'rednews_image_widget' );
}

/**
 * REDNews Image widget.
 *
 * @since  1.0.0
 */
class Rednews_Image_Widget extends WP_Widget {

	/**
	 * Global options for this widget.
	 *
	 * @since  1.0.0
	 */
	protected $options;

	/**
	 * Initialize an instance of the widget.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up the options to pass to the WP_Widget constructor.
		$this->options = array(
			'classname'   => 'rednews-image-widget',
			'description' => __( 'An Image Upload Widget with a PDF link input field for REDNews Properties.', 'rednews-image-widget' ),
		);

		parent::__construct(
			'rednews_image_widget',
			__( 'RedNews Image Widget', 'rednews-image-widget' ),
			$this->options
		);
	}

	/**
	 * Output the widget.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $args      The global options for the widget.
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function widget( $args, $instance ) {

		$defaults = array(
			'title'             => '',
			'image'             => '',
			'image_link'        => '',
			'image_link_target' => '_self',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo rednews_image_widget( $instance );

		echo $args['after_widget'];
	}

	/**
	 * Output the Widget settings form.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'             => '',
			'image'             => '',
			'image_link'        => '',
			'image_link_target' => '_self',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title             = $instance['title'];
		$image             = $instance['image'];
		$image_link        = $instance['image_link'];
		$image_link_target = $instance['image_link_target'];

		// Title.
		$this->field_text(
			__( 'Title', 'rednews-image-widget' ),
			'',
			'rednews-title widefat',
			'title',
			$title
		);

		// Image.
		$this->field_single_media(
			__( 'Property Image:', 'rednews-image-widget' ),
			__( 'Upload the recent property image.', 'rednews-image-widget' ),
			'rednews-image widefat',
			'image',
			$image
		);

		// Image link.
		$this->field_text(
			__( 'Image Link', 'rednews-image-widget' ),
			__( 'Input the PDF link for this property.', 'rednews-image-widget' ),
			'rednews-link widefat',
			'image_link',
			$image_link
		);

		// Image link target.
		$this->field_select(
			__( 'Image Link Target', 'rednews-image-widget' ),
			'',
			'image-link-target widefat',
			'image_link_target',
			$image_link_target,
			array(
				'_self'  => 'Default',
				'_blank' => 'New Window',
			)
		);
	}

	/**
	 * Update the widget settings.
	 *
	 * @since   1.0.0
	 *
	 * @param   array  $new_instance  The new settings for the widget instance.
	 * @param   array  $old_instance  The old settings for the widget instance.
	 *
	 * @return  array                 The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                      = $old_instance;
		$instance['title']             = sanitize_text_field( $new_instance['title'] );
		$instance['image']             = sanitize_text_field( $new_instance['image'] );
		$instance['image_link']        = sanitize_text_field( $new_instance['image_link'] );
		$instance['image_link_target'] = sanitize_text_field( $new_instance['image_link_target'] );

		return $instance;
	}

	/**
	 * Output a text input.
	 *
	 * @since  1.0.0
	 */
	public function field_text( $label = '', $description = '', $classes = '', $key = '', $value = '' ) {

		echo '<p class="rednews-text-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			printf(
				'<input type="text" class="%s" name="%s" value="%s" />',
				esc_attr( $classes ),
				$this->get_field_name( $key ),
				esc_attr( $value )
			);

			if ( '' !== $description) {
				printf(
					'<small class="rednews-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a single media item upload field.
	 *
	 * @since  1.0.0
	 */
	public function field_single_media( $label = '', $description = '', $classes = '', $key = '', $value = '' ) {

		if ( is_numeric( $value ) ) {
			$image = wp_get_attachment_image_src( (int)$value, 'large' )[0];
		} else {
			$image = '';
		}

		echo '<p class="rednews-single-media-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			?>
			<span class="rednews-single-media-wrap">
				<span class="rednews-single-media-image-preview-wrap <?php echo ( empty( $value ) ) ? 'no-image' : ''; ?>">

					<span class="rednews-single-media-no-image"><?php _e( 'No File Selected', 'rednews-image-widget' ); ?></span>
					<img class="rednews-single-media-image-preview" src="<?php echo esc_url( $image ); ?>" title="<?php _e( 'Media Item', 'rednews-image-widget' ); ?>" alt="<?php _e( 'Media Item', 'rednews-image-widget' ); ?>" />
				</span>
				<input type="hidden" name="<?php echo $this->get_field_name( $key ); ?>" class="rednews-single-media-image" class="regular-text" value="<?php echo esc_attr( $value ); ?>" />
				<input type="button" name="upload-btn" class="upload-btn button-secondary" value="<?php _e( 'Select Image', 'rednews-image-widget' ); ?>" />
				<input type="button" name="clear-btn" class="clear-btn button-secondary" value="<?php _e( 'Clear', 'rednews-image-widget' ); ?>" />
			</span>
			<?php

			if ( '' !== $description) {
				printf(
					'<small class="rednews-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}

	/**
	 * Output a select dropdown.
	 *
	 * @since  1.0.0
	 */
	public function field_select( $label = '', $description = '', $classes = '', $key = '', $value = '', $options = array() ) {

		echo '<p class="bssn-select-field-wrap">';

			echo '<label>' . esc_html( $label ) . '</label><br />';

			printf(
				'<select class="%s" name="%s">',
				esc_attr( $classes ),
				$this->get_field_name( $key )
			);

			// Test whether we have an associative or indexed array.
			if ( array_values( $options ) === $options ) {

				// We have an indexed array.
				foreach ( $options as $option ) {

					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option ),
						selected( $value, $option, false ),
						esc_html( $option )
					);
				}

			} else {

				// We have an associative array.
				foreach ( $options as $option_value => $option_display_name ) {

					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $option_value ),
						selected( $value, $option_value, false ),
						esc_html( $option_display_name )
					);
				}
			}

			echo '</select>';

			if ( '' !== $description) {
				printf(
					'<small class="bssn-description-text">%s</small>',
					esc_html( $description )
				);
			}

		echo '</p>';
	}
}