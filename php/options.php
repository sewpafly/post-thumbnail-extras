<?php

class PTXOptions {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		add_settings_field( 'ptx-post-thumbnails'
			, '<b>' . __('Post Thumbnail Extra Sizes', PTX_DOMAIN) . '</b>'
			, array( $this, 'create_post_thumbnails_html' )
			, 'media'
			, 'default'
		);
		register_setting( 'media'
			, 'ptx_post_thumbnails'
			, array( $this, 'sanitize_post_thumbnails' ) );
	}

	/**
	 * Display the HTML for ptx-post-thumbnails
	 */
	public function create_post_thumbnails_html() {
		$ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' );
		$output = "</td></tr>";
		if ( isset( $ptx_post_thumbnails ) and is_array( $ptx_post_thumbnails ) ){
			foreach ( $ptx_post_thumbnails as $thumbnail ){
				//print_r($thumbnail);
				$output .= self::thumbnail_html( $thumbnail );
			}
		}
		$output .= self::thumbnail_html();
		$output .= <<<EOT
		<script type="text/javascript" charset="utf-8">
			(function($){
				$(function(){
					$(".ptx-delete-thumb").click(function(){
						$(this).parents("tr").get(0).remove();
					});
				})
			})(jQuery);
		</script>

EOT;
		echo( $output );
	}

	private static function thumbnail_html( $thumbnail = NULL ) {
		$delete = sprintf( '<a href="#" tabindex="999" style="color:red;font-size:smaller;" class="ptx-delete-thumb">%s</a>'
			, __( 'Delete', PTX_DOMAIN )
		);
		$value = "value={$thumbnail['name']}";
		if ( is_null( $thumbnail ) ) {
			$thumbnail = array( 'name' => 'new-name'
				, 'width' => 150
				, 'height' => 150
				, 'crop' => true
			);
			$delete = "";
			$value = "";
		}
		$checked = checked( $thumbnail['crop'], true, false );
		$html = <<<EOT
		<tr valign="top">
			<th scope="row">
				<input type="text" class="ptx-thumb-name" $value
						name="ptx_post_thumbnails[{$thumbnail['name']}][name]" 
						id="ptx_post_thumbnails[{$thumbnail['name']}][name]" 
						placeholder="{$thumbnail['name']}" /><br/>
				$delete
			</th>
			<td>
				<label for="ptx_post_thumbnails[{$thumbnail['name']}][width]">%s</label>
				<input name="ptx_post_thumbnails[{$thumbnail['name']}][width]" 
						type="number" step="1" min="0" 
						id="ptx_post_thumbnails[{$thumbnail['name']}][width]" value="{$thumbnail['width']}" class="small-text" />

				<label for="ptx_post_thumbnails[{$thumbnail['name']}][height]">%s</label>
				<input name="ptx_post_thumbnails[{$thumbnail['name']}][height]"
						type="number" step="1" min="0"
						id="ptx_post_thumbnails[{$thumbnail['name']}][height]" value="{$thumbnail['height']}" class="small-text" /><br />

				<input name="ptx_post_thumbnails[{$thumbnail['name']}][crop]" type="checkbox"
						id="ptx_post_thumbnails[{$thumbnail['name']}][crop]" value="1" $checked/>
				<label for="ptx_post_thumbnails[{$thumbnail['name']}][crop]">%s</label>
			</td>
		</tr>

EOT;
		return sprintf( $html
			, __( 'Width', PTX_DOMAIN )
			, __( 'Height', PTX_DOMAIN )
			, __( 'Crop to exact dimensions', PTX_DOMAIN )
		);
	}

	/**
	 * Sanitize the data
	 *
	 * TODO: Leave an updated message if the size > 2000 letting them know that 
	 *       '0' is unlimited and might be a better choice
	 */
	public function sanitize_post_thumbnails( $input ) {
		//add_settings_error( 'ptx-post-thumbnails'
		//    , 'not-really-helpful'
		//    , sprintf( "INPUT: '%s'", print_r( $input, true ) )
		//    , 'updated'
		//);

		$new_input = array();
		foreach ( $input as $name => $thumbnail ) {
			if ( !isset( $thumbnail['name'] ) || $thumbnail['name'] == "" )
				if ( $name == "new-name" )
					continue;
				else
					$thumbnail['name'] = $name;

			//add_settings_error( 'ptx-post-thumbnails'
			//    , NULL, "'$thumbnail[name]'");
			// Validate the name
			if ( preg_match( "/[^[:alnum:]-]+/", $thumbnail['name'] ) ) {
				add_settings_error( 'ptx-post-thumbnails'
					, NULL, __( "Post Thumbnail Name must contain only alphanumeric characters and '-'.", PTX_DOMAIN ));
				continue;
			}

			$thumbnail['width'] = abs( intval( $thumbnail['width'] ) );
			$thumbnail['height'] = abs( intval( $thumbnail['height'] ) );
			$thumbnail['crop'] = ( isset( $thumbnail['crop'] ) && $thumbnail['crop'] );

			$new_input[] = $thumbnail;
		}

		//add_settings_error( 'ptx-post-thumbnails'
		//    , NULL, "Successfully updated post-thumbnails...", 'updated');
		//add_settings_error( 'ptx-post-thumbnails'
		//    , 'not-really-helpful'
		//    , sprintf( "SETTING: <pre>%s</pre>", print_r( $new_input, true ) )
		//    , 'updated'
		//);

		return $new_input;
	}
}

