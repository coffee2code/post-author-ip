=== Post Author IP ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: post, author, IP, IP address, audit, auditing, tracking, users, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 4.9
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


== Changelog ==

= () =
* Change: Update copyright date (2019)

= 1.0 (2018-01-24) =
* Initial public release


== Upgrade Notice ==

= 1.0 =
Initial public release.
