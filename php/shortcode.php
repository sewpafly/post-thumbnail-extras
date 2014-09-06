<?php

class PTXShortcode {
	public function __construct() {
		add_shortcode( 'pt', array( $this, 'parse_shortcode' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'add_meta_boxes_post', array( $this, 'enqueue_scripts' ) );
		add_filter( 'media_view_strings', array( $this, 'media_strings' ), 10, 2 );
		add_filter( 'image_size_names_choose', array( $this, 'image_sizes' ) );
		add_filter( 'wp_prepare_attachment_for_js', array( $this, 'fix_attachment' ), 10, 3 );
	}

	/**
	 * parse and output the link for a post-thumbnail
	 *
	 * @param $attrs provided by the call to add_shortcode this should give an array
	 *				with the following variables set:
	 *			[id] the id of the image to use from the media library - defaults to the featured-image
	 *			[size] the post-thumbnail size to use
	 *			[link] where should the link point to? 'file', 'post', 'none', or an arbitrary URL.
	 *	@return string HTML content to display post-thumbnail.
	 */
	public function parse_shortcode( $attrs ) {
		$post = get_post();
		extract( shortcode_atts( array(
			'id' => get_post_thumbnail_id( $post->ID ),
			'size' => 'thumbnail',
			'class' => 'pt-post-thumbnail',
			'link' => 'none'
		), $attrs ) );

		if ( ! wp_attachment_is_image( $id ) ) return;

		$html = wp_get_attachment_image( $id, $size, false, array( 'class' => $class ) );

		switch( $link ) {
		case 'none':
			return $html;
			break;
		case 'file':
			$link_url = wp_get_attachment_url( $id );
			break;
		case 'post':
			$link_url = get_attachment_link( $id );
			break;
		default:
			$link_url = $link;
		}

		return "<a href='$link_url'>$html</a>";
	}

	/**
	 * Enqueue scripts for the Edit Post page
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'ptx-shortcode'
			, PTX_PLUGINURL . 'js/media-shortcode.js'
			, array('media-views', 'media-editor')
			, false, true
		);
	}

	/**
	 * The i18n strings for the Edit Post page
	 */
	public function media_strings( $strings, $post ) {
		$strings['PTXInsertShortcode'] = __( 'Insert Shortcode', PTX_DOMAIN );
		return $strings;
	}

	/**
	 * Get the list of image sizes
	 */
	public function image_sizes ( $sizes ) {
		if ( false !== $ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' ) ) {
			foreach ( $ptx_post_thumbnails as $thumbnail ){
				$sizes[$thumbnail['name']] = $thumbnail['name'];
			}
		}
		return $sizes;
	}

	/**
	 * In the prepare_attachment_for_js ajax call, it marshalls a list of
	 * attachment metadata used for adding to the media-library sidebar. If we want
	 * the other post thumbnails to show up, we have to add them here.
	 *
	 * _Sidenotes_:
	 *
	 * 1. If the post-thumbnail has not yet been generated, and the user selects
	 *    a post-thumbnail and does an "Insert into Post", it will link to the fullsize
	 *    image and the browser will resize it inefficiently.
	 *
	 * 2. I tried using the image_downsize filter, but that caused an infinite loop
	 *    with the wp_get_attachment_* methods.
	 *
	 * See <wp-includes/media.php> for more information.
	 *
	 * @param $response - the metadata being returned to the client
	 * @param $attachment = wp_get_attachment_metadata for the post
	 * @param $meta - Other metadata
	 */
	public function fix_attachment( $response, $attachment, $meta ) {
		$predefined = array( 'thumbnail', 'medium', 'large', 'full' );
		$possible_sizes = apply_filters( 'image_size_names_choose', array() );
		foreach ( $predefined as $size ) {
			if ( isset( $possible_sizes[$size] ) ) {
				unset( $possible_sizes[$size] );
			}
		}


		foreach ( $possible_sizes as $size => $label ) {
			if ( isset( $response['sizes'][$size] ) )
				continue;
			$img = wp_get_attachment_image_src( $response['id'], $size );
			$response['sizes'][$size] = array(
				'height' => $img[2],
				'width' => $img[1],
				'url' => $img[0],
			  	'orientation' => ( $img[2] > $img[1] ) ? 'portrait' : 'landscape'
			);
		}
		return $response;
	}
}

$PTX_SHORTCODE = new PTXShortcode();
