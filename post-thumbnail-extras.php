<?php
/*
Plugin name: Post Thumbnail Extras
Plugin URI: https://github.com/sewpafly/post-thumbnail-extras
Author: sewpafly
Author URI: http://sewpafly.github.io/post-thumbnail-editor/
Version: 2.0
Description: Little things that make using post thumbnails easier
*/

/* 
 * Useful constants  
 */
define( 'PTX_DOMAIN', 'post-thumbnail-extras' );

class PostThumbnailExtras {
	public function __construct(){
		// Wordpress hooks and settings
		add_action( 'init', array( $this, 'i18n' ) );

		if ( false !== $ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' ) ) {
			foreach ( get_option( 'ptx_post_thumbnails' ) as $thumbnail ){
				add_image_size( $thumbnail['name']
					, $thumbnail['width']
					, $thumbnail['height']
					, $thumbnail['crop']
				);
			}
		}

		/*
		 * Load sub-objects
		 */
		$this->load_requires();
		$s = new PTXShortcode();
		$o = new PTXOptions();
	}

	/**
	 * Internationalization and Localization
	 */
	public function i18n(){
		$locale = apply_filters( 'plugin_locale', get_locale(), PTX_DOMAIN );
		load_textdomain( PTX_DOMAIN
			, WP_LANG_DIR.'/'.PTX_DOMAIN.'/'.PTX_DOMAIN.'-'.$locale.'.mo' );
		load_plugin_textdomain( PTX_DOMAIN
			, FALSE
			, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
	}

	private $requires = array( 'php/shortcode.php'
		, 'php/options.php'
	);

	private function load_requires(){
		$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
		foreach ( $this->requires as $require ){
			require( $path . $require );
		}
	}

}

$ptx = new PostThumbnailExtras();
