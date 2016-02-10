<?php

function rednews_image_widget( $args ) {

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'             => '',
		'image'             => '',
		'pdf_link'          => '',
		'image_link_target' => '_self',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$title             = $args['title'];
	$image             = $args['image'];
	$pdf_link          = $args['pdf_link'];
	$image_link_target = $args['image_link_target'];

	// Support the image being an ID or a URL.
	if ( is_numeric( $image ) ) {
		$image_array = wp_get_attachment_image_src( $image, 'full' );
		$image_url   = $image_array[0];
	} else {
		$image_url = esc_url( $image );
	}

	if ( is_numeric( $pdf_link ) ) {
		$pdf_link = wp_get_attachment_url( $pdf_link );
	} else {
		$pdf_link = esc_url( $pdf_link );
	}

	ob_start(); ?>

	<div class="rednews-image-widget-wrap">

		<?php
		if ( ! empty( $title ) ) {
			printf(
				'<h2>%s</h2>',
				esc_html( $title )
			);
		}

		printf(
			'<a class="%s" href="%s" target="%s"><img src="%s"></a>',
			'rednews-image-widget-image',
			esc_url( $pdf_link ),
			esc_attr( $image_link_target ),
			esc_attr( $image_url )
		);

		printf(
			'<a class="%s" href="%s" target="%s">%s</a>',
			'rednews-image-widget-button',
			esc_url( $pdf_link ),
			esc_attr( $image_link_target ),
			__( 'Click to View Digital Issue', 'rednews-image-widget')
		 );

		?>

	</div>

	<?php

	return ob_get_clean();
}