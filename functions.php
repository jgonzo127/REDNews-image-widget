<?php

/**
 * Return an array of registered image sizes.
 *
 * @since   1.0.0
 *
 * @param   string  $context  The context to pass to our filter.
 *
 * @return  array             The array of formatted image sizes.
 */
function rednews_get_image_sizes( $context = '' ) {

	$image_sizes = get_intermediate_image_sizes();
	$formatted_image_sizes = array();

	foreach ( $image_sizes as $image_size ) {

		$formatted_image_size = ucwords( str_replace( '_', ' ', str_replace( '-', ' ', $image_size ) ) );
		$formatted_image_sizes[ $image_size ] = $formatted_image_size;
	}

	// Manually add in the 'Full' size.
	$formatted_image_sizes['full'] = __( 'Full', 'mm-components' );

	return apply_filters( 'rednews_image_sizes', $formatted_image_sizes, $context );
}