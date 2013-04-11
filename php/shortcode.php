<?php

class PTXShortcode {
	public function __construct() {
		add_shortcode( 'pt', array( $this, 'parse_shortcode' ) );
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
}

