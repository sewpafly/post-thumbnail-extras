<?php
/*
Plugin name: Post Thumbnail Extras
Author: sewpafly
Version: 1.0
Description: Little things that make using post thumbnails easier
 */

/* 
 * Useful constants  
 */
//define( 'PTX_PLUGINURL', plugins_url(basename( dirname(__FILE__))) . "/");
//define( 'PTX_PLUGINPATH', dirname(__FILE__) . "/");
//define( 'PTX_VERSION', "1.0");

class PostThumbnailExtras {
	private $requires = array( 'php/shortcode.php' );
	private function loadRequires(){
		$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
		foreach ( $this->requires as $require ){
			require( $path . $require );
		}
	}

	public function __construct(){
		add_action( 'init', array( $this, 'i18n' ) );

		/*
		 * Load sub-objects
		 */
		$this->loadRequires();
		$s = new PTXShortcode();
	}

	/**
	 * Internationalization and Localization
	 */
	public function i18n(){
		$domain = 'post-thumbnail-extras';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain
			, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
		load_plugin_textdomain( $domain
			, FALSE
			, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
}

$ptx = new PostThumbnailExtras();
?>
