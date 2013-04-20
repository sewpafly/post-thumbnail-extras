<?php

class PTXShortcode {
	public function __construct() {
		add_shortcode( 'pt', array( $this, 'parse_shortcode' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'add_meta_boxes_post', array( $this, 'enqueue_scripts' ) );
		add_filter( 'media_view_strings', array( $this, 'media_strings' ), 10, 2 );
	}

	/**
	 * parse and output the link for a post-thumbnail
	 *
	 * @param $attrs provided by the call to add_shortcode this should give an array
	 *				with the following variables set:
	 *			[id] the id of the image to use from the media library - defaults to the featured-image
	 *			[size] the post-thumbnail size to use
	 *	@return string HTML content to display post-thumbnail.
	 */
	public function parse_shortcode( $attrs ) {
		$post = get_post();
		extract( shortcode_atts( array(
			'id' => get_post_thumbnail_id( $post->ID ),
			'size' => 'thumbnail',
			'class' => 'pt-post-thumbnail'
		), $attrs ) );

		return wp_get_attachment_image( $id, $size, false, array( 'class' => $class ) );
	}

	/**
	 * Enqueue scripts for the admin media page
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'ptx-shortcode'
			, PTX_PLUGINURL . 'js/media-shortcode.js'
			, array('media-views', 'media-editor')
			, false, true
		);
	}

	/**
	 * The i18n strings for the GUI
	 */
	function media_strings( $strings, $post ) {
		$strings['PTXInsertShortcode'] = __( 'Insert Shortcode', PTX_DOMAIN );
		return $strings;
	}

}

$PTX_SHORTCODE = new PTXShortcode();
