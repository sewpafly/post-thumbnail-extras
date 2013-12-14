<?php

class PTXOptions {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		if ( false !== $ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' ) ) {
			foreach ( $ptx_post_thumbnails as $thumbnail ){
				add_image_size( $thumbnail['name']
					, $thumbnail['width']
					, $thumbnail['height']
					, $thumbnail['crop']
				);
			}
		}
	}

	public function admin_init() {
		add_settings_field( 'ptx-post-thumbnails'
			, '<b>' . __( 'Post Thumbnail Extras', PTX_DOMAIN ) . '</b>'
				. '&nbsp;<a class="ptx-add-thumb" href="#">+</a>'
			, array( $this, 'create_post_thumbnails_html' )
			, 'media'
			, 'default'
		);
		// Register the settings to be handled by wordpress and the callback function
		register_setting( 'media'
			, 'ptx_post_thumbnails'
			, array( $this, 'sanitize_post_thumbnails' ) );

		// Add a section for displaying other Post Thumbnails and their metadata
		// (e.g. width, height, crop)
		add_settings_section( 'ptx-other-post-thumbnails'
			, __( 'Post Thumbnail Extras - Display other post thumbnails', PTX_DOMAIN )
			, array( $this, 'other_post_thumbnails_html' )
			, 'media'
		);
	}

	/**
	 * Display the HTML for ptx-post-thumbnails
	 */
	public function create_post_thumbnails_html() {
		$ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' );
		$output = "%s</td></tr>";
		if ( isset( $ptx_post_thumbnails ) and is_array( $ptx_post_thumbnails ) ){
			foreach ( $ptx_post_thumbnails as $thumbnail ){
				//print_r($thumbnail);
				$output .= self::thumbnail_html( $thumbnail );
			}
		}
		$output .= '<script id="ptx-template" type="text/template">';
		$output .= self::thumbnail_html();
		$output .= '</script>';
		$output .= <<<EOT
		<script type="text/javascript" charset="utf-8">
			(function($){

				// Check for pending operations
				var pending = false;
				$(window).on("beforeunload", function(e) {
					if (e.target.activeElement.name == "submit")
						return;
					if (pending) {
						return "%s";
					}
				})

				$("body").on("change", "input", function(){
				   pending = true;
				});

				$(function(){
					var post_template = $('#ptx-template').html(), counter = 0;
					$('.ptx-add-thumb').click(function(e){
						pending = true;
						e.preventDefault();
						var html = post_template.replace(/new-name/g, 'new-name-' + counter++);
						$(this).parents('tr').siblings().last().after($(html));
					});
					$('body').delegate('.ptx-delete-thumb', 'click', function(e){
						pending = true;
						e.preventDefault();
						$(this).parents('tr').first().remove();
					});
				})

			})(jQuery);
		</script>
		<style type="text/css" media="all">
			.ptx-section {
				width: 300px;
			}
			.ptx-add-thumb {
				bottom: -2px;
				color: #44bb44 !important;
				font-size: 1.5em;
				font-weight: bold;
				position: relative;
				text-decoration: none;
			}
			.ptx-delete-thumb {
				color: red;
				font-size: smaller;
			}
			.ptx-thumb-name {
				position:relative;
				left: -3px;
			}
		</style>

EOT;
		echo( sprintf( $output
			, __( "Create additional post thumbnail sizes here:")
			, __( "There are pending changes, are you sure that you want to leave?" ) ) );
	}

	private static function thumbnail_html( $thumbnail = NULL ) {
		$value = "value={$thumbnail['name']}";
		if ( is_null( $thumbnail ) ) {
			$thumbnail = array( 'name' => 'new-name'
				, 'width' => 150
				, 'height' => 150
				, 'crop' => true
			);
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
				<a href="#" tabindex="999" class="ptx-delete-thumb">%s</a>
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
			, __( 'Delete', PTX_DOMAIN )
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
		$too_large = 2000;
		//add_settings_error( 'ptx-post-thumbnails'
		//    , 'not-really-helpful'
		//    , sprintf( "INPUT: '%s'", print_r( $input, true ) )
		//    , 'updated'
		//);
		$new_input = array();

		if ( ! is_array( $input ) )
			return $new_input;

		$counter = 0;
		$pattern = "/[^[:alnum:]-]+/";
		foreach ( $input as $name => $thumbnail ) {
			if ( !isset( $thumbnail['name'] ) || $thumbnail['name'] == "" ) {
				if ( preg_match( "/^new-name-[0-9]{1,2}/", $name ) ) {
					$thumbnail['name'] = 'new-name-' . preg_replace( $pattern, "", uniqid() );
				} else {
					$thumbnail['name'] = $name;
				}
			}

			//add_settings_error( 'ptx-post-thumbnails'
			//    , NULL, "'$thumbnail[name]'");
			// Validate the name
			if ( preg_match( $pattern, $thumbnail['name'] ) ) {
				add_settings_error( 'ptx-post-thumbnails'
					, NULL
					, sprintf("%s: %s"
						, __( "Post Thumbnail Name must contain only alphanumeric characters and '-'.", PTX_DOMAIN )
						, $thumbnail['name']));
				continue;
			}

			$thumbnail['width'] = abs( intval( $thumbnail['width'] ) );
			$thumbnail['height'] = abs( intval( $thumbnail['height'] ) );
			$thumbnail['crop'] = ( isset( $thumbnail['crop'] ) && $thumbnail['crop'] );

			if ( $too_large < $thumbnail['width'] || $too_large < $thumbnail['height'] ) {
				add_settings_error( 'ptx-post-thumbnails'
					, NULL
					, sprintf( __( "Consider using 0 for an unlimited size side (%s)", PTX_DOMAIN ), $thumbnail['name'] )
					, 'updated');
			}


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

	/**
	 * Display post thumbnail metadata for other post thumbnails defined elsewhere
	 */
	public function other_post_thumbnails_html() {
		$thumbnails = $this->get_other_intermediate_image_sizes();

		if ( ! isset( $thumbnails ) || 0 == count( $thumbnails ) ) {
			_e( "No additional image sizes defined", PTX_DOMAIN );
			return;
		}

		$name = __( 'Name', PTX_DOMAIN );
		$width = __( 'Width', PTX_DOMAIN );
		$height = __( 'Height', PTX_DOMAIN );
		$crop = __( 'Crop', PTX_DOMAIN );
		$output = <<<EOT
<style type="text/css" media="all">
	#ptx-other-post-thumbnails {
		width: 50%%;
	}
	.widefat thead th:first-of-type {
		padding-left: 8px;
	}
	.widefat.media .check-column {
		padding-bottom: 8px;
	}
</style>
<table id="ptx-other-post-thumbnails" class="wp-list-table widefat fixed media" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column column-name check-column" style="">
				$name
			</th>
			<th class="manage-column check-column" style="">
				$width
			</th>
			<th class="manage-column check-column" style="">
				$height
			</th>
			<th class="manage-column check-column" style="">
				$crop
			</th>
		</tr>
	</thead>
	<tbody>
		%s
	</tbody>
</table>
EOT;
		$body = "";
		$row = "<tr><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>";
		foreach ( $thumbnails as $name => $thumbnail ) {
			$body .= sprintf( $row
				, $name
				, $thumbnail['width']
				, $thumbnail['height']
				, ( true == $thumbnail['crop'] ) ? __( 'True' ) : __( 'False' )
			);
		}

		print( sprintf( $output, $body ) );
	}

	/**
	 * Ignore 'large', 'medium', 'thumbnail' and any ptx_thumbs
	 */
	public function get_other_intermediate_image_sizes() {
		global $_wp_additional_image_sizes;
		$filter = array( 'large', 'medium', 'thumbnail' );

		$ptx_post_thumbnails = get_option( 'ptx_post_thumbnails' );
		if ( isset( $ptx_post_thumbnails ) and is_array( $ptx_post_thumbnails ) ){
			foreach ( get_option( 'ptx_post_thumbnails' ) as $thumb ) {
				$filter[] = $thumb['name'];
			}
		}

		foreach ( $_wp_additional_image_sizes as $name => $thumb ) {
			if ( ! in_array( $name, $filter ) ) {
				$return[$name] = $thumb;
			}
		}
		return $return;
	}
}

$PTX_OPTIONS = new PTXOptions();
