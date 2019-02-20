=== Post Author IP ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: post, author, IP, IP address, audit, auditing, tracking, users, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 5.1
Stable tag: 1.0

Records the IP address of the original post author when a post first gets created.

== Description ==

This plugin records the IP address of the original post author when a post first gets created.

The admin listing of posts is amended with a new "Author IP" column that shows the IP address of the author who first saved the post.

The plugin is unable to provide IP address information for posts that were created prior to the use of this plugin.


Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/post-author-ip/) | [Plugin Directory Page](https://wordpress.org/plugins/post-author-ip/) | [GitHub](https://github.com/coffe2code/post-author-ip/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `post-author-ip.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress


== Screenshots ==

1. A screenshot of the admin post listing showing the added "Author IP" column. It demonstrates the mix of a post where the post author IP address was recorded, and posts where it wasn't (due to the plugin not being activated at the time).
2. A screenshot of the Publish metabox for a post showing the post author's IP address.


== Frequently Asked Questions ==

= If a post is originally drafted at one IP address, then later worked on at another IP address, which IP address gets recorded? =

The IP address in use at the time that the post is first saved (regardless of whether the post was saved as a draft, immediately published, or some other status) will be recorded.

= Are other IP addresses in use during the post's handling (such as when it is edited, published, etc) also tracked? =

No, this plugin only records the IP address in use when the post was first saved.

= How do I see (or hide) the "Author IP" column in an admin listing of posts? =

In the upper-right of the page is a "Screen Options" link that reveals a panel of options. In the "Columns" section, check (to show) or uncheck (to hide) the "Author IP" option.

= Does this plugin include unit tests? =

Yes.


== Hooks ==

The plugin is further customizable via three filters. Typically, code making use of filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_show_post_author_ip_column (filter)**

The 'c2c_show_post_author_ip_column' filter allows you to determine if the post author IP column should appear in the admin post listing table. Your hooking function can be sent 1 argument:

Argument :

* $show_column (bool) Should the column be shown? Default true.

Example:

`
/**
 * Don't show the post author IP column except to admins.
 *
 * @param bool $show_column Should the column be shown? Default true.
 * @return bool
 */
function post_author_ip_column_admin_only( $show ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		$show = false;
	}
	return $show;
}
add_filter( 'c2c_show_post_author_ip_column', 'post_author_ip_column_admin_only' );
`

**c2c_get_post_author_ip (filter)**

The 'c2c_get_post_author_ip' filter allows you to customize the value stored as the post author IP address. Your hooking function can be sent 2 arguments:

Arguments :

* $ip (string)   The post author IP address.
* $post_id (int) The post ID.

Example:

`
/**
 * Store all IP addresses from local subnet IP addresses as the same IP address.
 *
 * @param string $ip      The post author IP address.
 * @param int    $post_id The post ID.
 * @return string
 */
function customize_post_author_ip( $ip, $post_id ) {
	if ( 0 === strpos( $ip, '192.168.' ) ) {
		$ip = '192.168.1.1';
	}
	return $ip;
}
add_filter( 'c2c_get_post_author_ip', 'customize_post_author_ip', 10, 2 );
`

**c2c_get_current_user_ip (filter)**

The 'c2c_get_current_user_ip' filter allows you to customize the current user's IP address, as used by the plugin. Your hooking function can be sent 1 argument:

Argument :

* $ip (string)   The post author IP address.

Example:

`
/**
 * Overrides localhost IP address.
 *
 * @param string $ip      The post author IP address.
 * @param int    $post_id The post ID.
 * @return string
 */
function customize_post_author_ip( $ip, $post_id ) {
	if ( 0 === strpos( $ip, '192.168.' ) ) {
		$ip = '192.168.1.1';
	}
	return $ip;
}
add_filter( 'c2c_get_post_author_ip', 'customize_post_author_ip', 10, 2 );
`


== Changelog ==

= () =
* New: Add 'Hooks' section to readme with full documentation and examples for hooks
* New: Add inline documentation for hooks
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

= 1.0 (2018-01-24) =
* Initial public release


== Upgrade Notice ==

= 1.0 =
Initial public release.
