/**
 * RedNews Image Widget Admin JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	$( document ).ready( function() {

		// Set up any single media fields.
		$( '.rednews-single-media-wrap' ).rednewsSingleMediaField();
		$( '.rednews-single-file-media-wrap' ).rednewsSingleFileMediaField();
	});

	// Reset or initialize certain fields when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {

		$( data[0] ).find( '.rednews-single-media-wrap' ).rednewsSingleMediaField();
		$( data[0] ).find( '.rednews-single-file-media-wrap' ).rednewsSingleFileMediaField();
	});

	/**
	 * Set up one or many single media fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.rednewsSingleMediaField = function() {

		return this.each( function() {

			var $field        = $( this );
			var $elements     = $();
			var $uploadButton = $field.find( '.upload-btn' );
			var $imagePreview = $field.find( '.rednews-single-media-image-preview' );
			var $noImage      = $field.find( '.rednews-single-media-no-image' );
			var $clearButton  = $field.find( '.clear-btn' );

			$elements = $uploadButton.add( $imagePreview ).add( $noImage );

			// Set up the interaction with wp.media.
			$elements.on( 'click', function( e ) {
				e.preventDefault();

				$field.rednewsSingleMediaUpload();
			});

			// Set up the clear button.
			$clearButton.on( 'click', function( e ) {
				e.preventDefault();

				$field.find( '.rednews-single-media-image' ).val( '' );
				$field.find( '.rednews-single-media-image-preview-wrap' ).addClass( 'no-image' );
			});
		});
	};

	/**
	 * Handle the interaction with wp.media for one or many single media upload fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.rednewsSingleMediaUpload = function() {

		return this.each( function() {

			var $field = $( this );

			var rednewsSingleMedia = wp.media( {
				title    : 'Upload Image or File',
				multiple : false
			}).open().on( 'select', function( e ) {

				var uploadedMedia    = rednewsSingleMedia.state().get( 'selection' ).first();
				var rednewsSingleMediaId  = uploadedMedia.id;
				var rednewsSingleMediaUrl = uploadedMedia.attributes.url;

				$field.find( '.rednews-single-media-image' ).val( rednewsSingleMediaId );
				$field.find( '.rednews-single-media-image-preview-wrap' ).removeClass( 'no-image' );
				$field.find( '.rednews-single-media-image-preview' ).attr( 'src', rednewsSingleMediaUrl );
			});
		});
	};

	/**
	 * Set up one or many single file upload fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.rednewsSingleFileMediaField = function() {

		return this.each( function() {

			var $field        = $( this );
			var $elements     = $();
			var $uploadButton = $field.find( '.file-upload-btn' );
			var $imagePreview = $field.find( '.rednews-single-file-media-image-preview' );
			var $noImage      = $field.find( '.rednews-single-file-media-no-image' );
			var $clearButton  = $field.find( '.file-clear-btn' );

			$elements = $uploadButton.add( $imagePreview ).add( $noImage );

			// Set up the interaction with wp.media.
			$elements.on( 'click', function( e ) {
				e.preventDefault();


				$field.rednewsSingleFileMediaUpload();
			});

			// Set up the clear button.
			$clearButton.on( 'click', function( e ) {
				e.preventDefault();

				$field.find( '.rednews-single-file-media-image' ).val( '' );
				$field.find( '.rednews-single-file-media-image-preview-wrap' ).addClass( 'no-image' );
			});
		});
	};

	/**
	 * Handle the interaction with wp.media for one or many single file upload fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.rednewsSingleFileMediaUpload = function() {

		return this.each( function() {

			var $field = $( this );

			var rednewsSingleFileMedia = wp.media( {
				title    : 'Upload File',
				multiple : false
			}).open().on( 'select', function( e ) {

				var uploadedMedia    = rednewsSingleFileMedia.state().get( 'selection' ).first();
				var rednewsSingleFileMediaId  = uploadedMedia.id;
				var rednewsSingleFileMediaIcon = uploadedMedia.attributes.icon;

				$field.find( '.rednews-single-file-media-image' ).val( rednewsSingleFileMediaId );
				$field.find( '.rednews-single-file-media-image-preview-wrap' ).removeClass( 'no-image' );
				$field.find( '.rednews-single-file-media-image-preview' ).attr( 'src', rednewsSingleFileMediaIcon );
			});

		});
	};

}( jQuery ));