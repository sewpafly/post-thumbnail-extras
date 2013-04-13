=== Post Thumbnail Extras ===
Contributors: sewpafly
Donate link: https://www.wepay.com/donate/34543
Tags: post-thumbnail, post thumbnail, featured image, awesome, media library, shortcode, shortcodes
Requires at least: 2.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2

Make using post thumbnails easier for everyday wordpressing.

== Description ==

= Features =

* Provides a shortcode as a wrapper for the `wp_get_attachment_image` call. Allows your posts to quickly change the picture in your posts without wading through HTML (nice if your post-thumbnails change width or height due to a theme change or you have cropped them with another tool -- this will always pull the latest image from wordpress).
* Add and manage post thumbnails via the media screen.

= Future Plans =

In the future, there will be support for changing the default option, a hook into the media gallery to embed the shortcode via the GUI, and any other neat ideas I can come up with. Submit any other ideas at https://github.com/sewpafly/post-thumbnail-extras/issues.

== Installation ==

= Shortcode =

Use the shortcode `[pt]` in your posts to quickly embed the featured image as a thumbnail. Use the attribute 'size' to change to a different post-thumbnail size (e.g. `[pt size='medium']`). You can also use any image in your media gallery if you know the id, by using the `id` attribute (`[pt id='100']`).

= Add/Delete Post Thumbnails =

1. In Settings &rarr; Media, click the plus sign to add a new post thumbnail. 
2. Update the width/height and whether or not the post-thumbnail should be cropped to an exact size. (If the width or height is set to 0, that boundary constraint is not enforced). Make sure you click the "save" button so that the changes are stored in the database.

== Frequently Asked Questions ==

= Did you even test this? =

Yes. No. Sort of. Thanks for asking. But [let me know if you're having problems](https://github.com/sewpafly/post-thumbnail-extra/issues) and I'll see what I can do.

== Screenshots ==
1. In Settings &rarr; Media, click the plus sign to add a new post thumbnail. 
2. Update the width/height and whether or not the post-thumbnail should be cropped to an exact size. (If the width or height is set to 0, that boundary constraint is not enforced). Make sure you click the "save" button so that the changes are stored in the database.

== Changelog ==

= 2.0 =
* CRUD (Create/Read/Update/Delete) operations on post-thumbnails

= 1.0 =
* Initial cut

== Upgrade Notice ==
= 2.0 =
New CRUD operations on post-thumbnails
