=== Post Thumbnail Extras ===
Contributors: sewpafly
Tags: post-thumbnail, post thumbnail, featured image, awesome, media library, shortcode, shortcodes
Requires at least: 2.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2

Post Thumbnail Extras: little things that make using them easier

== Description ==

Provides a shortcode as a wrapper for the `wp_get_attachment_image` call. Allows your posts to quickly change the picture in your posts without wading through HTML (nice if your post-thumbnails change width or height due to a theme change or you have cropped them with another tool -- this will always pull the latest image from wordpress).

In the future, there will be support for changing the default option, a hook into the media gallery to embed the shortcode via the GUI, and any other neat ideas I can come up with.

== Usage ==

Use the shortcode `[pt]` in your posts to quickly embed the featured image as a thumbnail. Use the attribute 'size' to change to a different post-thumbnail size (e.g. `[pt size='medium']`). You can also use any image in your media gallery if you know the id, by using the `id` attribute (`[pt id='100']`).

== Installation ==

1. Download the zip file from <http://downloads.wordpress.org/plugin/post-thumbnail-extras.zip>
2. Unzip to your wp-content/plugins directory under the wordpress installation.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Rock On

= or =

1. Install from within your wordpress admin area by searching for "post thumbnail extras"

== Frequently Asked Questions ==

= Did you even test this? =

Yes. No. Sort of. Thanks for asking. But [let me know if you're having problems](https://github.com/sewpafly/post-thumbnail-editor/issues) and I'll see what I can do.

== Changelog ==

= 1.0 =
* Initial cut

== Upgrade Notice ==
