=== Post Thumbnail Extras ===
Contributors: sewpafly
Donate link: http://sewpafly.github.io/post-thumbnail-editor/extras/#toc_donations
Tags: post-thumbnail, post thumbnail, featured image, awesome, media library, shortcode, shortcodes
Requires at least: 2.5
Tested up to: 4.0.x
Stable tag: trunk
License: GPLv2

Make using post thumbnails easier for everyday wordpressing.

== Description ==

= Features =

* Provides a shortcode for embedding post-thumbnails. Authors can quickly change pictures in posts without wading through HTML.  This can be awesome in several ways: if your post-thumbnails change the width or height due to a theme change, or if you have cropped them with another tool -- this will always pull wordpress' latest image).
* Includes a hook into the media library to create the above shortcode.
* Add new post thumbnails via Wordpress Settings &rarr; Media.

= Future Plans =

Submit any other ideas at https://github.com/sewpafly/post-thumbnail-extras/issues.

== Installation ==

= Shortcode =

Use the shortcode `[pt]` in your posts to quickly embed the featured image as a thumbnail. Use the attribute 'size' to change to a different post-thumbnail size (e.g. `[pt size='medium']`). You can also use any image in your media gallery if you know the id, by using the `id` attribute (`[pt id='100']`).

Use the `link` attribute to wrap the image in a link. 

* `link='file'` will create a link to the full size image.
* `link='post'` will create a link to the attachment page.
* `link='http...'` creates a link to any URL.

Use the media library to quickly add this shortcode for the selected image by pressing the "Insert shortcode" button.

= Add/Delete Post Thumbnails =

1. In Settings &rarr; Media, click the plus sign to add a new post thumbnail. 
2. Update the width/height and whether or not the post-thumbnail should be cropped to an exact size. (If the width or height is set to 0, that boundary constraint is not enforced -- effectively, it's infinite). Make sure you click the "save" button so that the changes are stored in the database.

== Frequently Asked Questions ==

= Did you even test this? =

Yes. No. Sort of. Thanks for asking. But [let me know if you're having problems](https://github.com/sewpafly/post-thumbnail-extras/issues) and I'll see what I can do.

== Screenshots ==

1. In Settings &rarr; Media, click the plus sign to add a new post thumbnail. 
2. Update the width/height and whether or not the post-thumbnail should be cropped to an exact size. (If the width or height is set to 0, that boundary constraint is not enforced). Make sure you click the "Save Changes" button so that the changes are stored in the database.
3. In the media editor, you can choose to insert a shortcode of a single picture with the "Insert shortcode" button.

== Changelog ==

= 6.0 =

* Added `ptx_html_attrs` filter for modifying the html output
* Fixed the `shortcode_atts` so that the `shortcode_atts_ptx` filter will fire

= 5.0 = 

* Updated for wordpress 3.8
* Added code to check for pending operations on the Options &rarr; Media screen.

= 4.0 =

* Added alignment and link options to the shortcode
* Wordpress 3.6-beta2 fix

= 3.0 =

* Shortcode Creation with the Media Library
* Display post thumbnail information for thumbnails created by other plugins and themes

= 2.0 =

* CRUD (Create/Read/Update/Delete) operations on post-thumbnails

= 1.0 =

* Initial cut

== Upgrade Notice ==

= 6.0 =

Added some filters
