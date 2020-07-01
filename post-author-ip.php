<?php
/**
 * Plugin Name: Post Author IP
 * Version:     1.2.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/post-author-ip/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: post-author-ip
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Records the IP address of the original post author when a post first gets created.
 *
 * Compatible with WordPress 4.9 through 5.4+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/post-author-ip/
 *
 * @package Post_Author_IP
 * @author  Scott Reilly
 * @version 1.2.1
 */

/*
 * TODO:
 */

/*
	Copyright (c) 2017-2020 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_PostAuthorIP' ) ) :

class c2c_PostAuthorIP {

	/**
	 * Name for meta key used to store IP address of original post author.
	 *
	 * @access private
	 * @var string
	 */
	private static $meta_key = 'c2c-post-author-ip';

	/**
	 * Field name for the post listing column.
	 *
	 * @access private
	 * @var string
	 */
	private static $field = 'post_author_ip';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.0
	 */
	public static function version() {
		return '1.2.1';
	}

	/**
	 * Hooks actions and filters.
	 *
	 * @since 1.0
	 */
	public static function init() {
		// Load textdomain.
		load_plugin_textdomain( 'post-author-ip' );

		// Register hooks.
		add_filter( 'manage_posts_columns',        array( __CLASS__, 'add_post_column' )               );
		add_action( 'manage_posts_custom_column',  array( __CLASS__, 'handle_column_data' ),     10, 2 );

		add_action( 'load-edit.php',               array( __CLASS__, 'add_admin_css' )                 );
		add_action( 'load-post.php',               array( __CLASS__, 'add_admin_css' )                 );
		add_action( 'transition_post_status',      array( __CLASS__, 'transition_post_status' ), 10, 3 );
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'show_post_author_ip' )           );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_block_editor_assets' )   );
		add_action( 'init',                        array( __CLASS__, 'register_meta' ) );
	}

	/**
	 * Registers the post meta field.
	 *
	 * @since 1.0
	 */
	public static function register_meta() {
		$config = array(
			'type'              => 'string',
			'description'       => __( 'The IP address of the original post author', 'post-author-ip' ),
			'single'            => true,
			'sanitize_callback' => array( __CLASS__, 'sanitize_ip_address' ),
			'auth_callback'     => function() {
				return current_user_can( 'edit_posts' );
			},
			'show_in_rest'      => true,
		);

		if ( function_exists( 'register_post_meta' ) ) {
			// @todo Support non-"post" post types.
			register_post_meta( 'post', self::$meta_key, $config );
		}
		// Pre WP 4.9.8 support
		else {
			register_meta( 'post', self::$meta_key, $config );
		}
	}

	/**
	 * Determines if the Author IP column should be shown.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function include_column() {
		/**
		 * Filters to determine if post author IP column show appear in the admin
		 * post listing table.
		 *
		 * @since 1.0
		 *
		 * @param bool $show_column Should the column be shown? Default true.
		 */
		return (bool) apply_filters( 'c2c_show_post_author_ip_column', true );
	}

	/**
	 * Adds hook to outputs CSS for the display of the Author IP column if
	 * on the appropriate admin page.
	 *
	 * @since 1.0
	 */
	public static function add_admin_css() {
		if ( ! self::include_column() ) {
			return;
		}

		add_action( 'admin_head', array( __CLASS__, 'admin_css' ) );
	}

	/**
	 * Outputs CSS for the display of the Author IP column.
	 *
	 * @since 1.0
	 */
	public static function admin_css() {
		$type_attr = current_theme_supports( 'html5' ) ? '' : ' type="text/css"';
		$field = self::$field;

echo <<<HTML
<style{$type_attr}>
	.fixed .column-{$field} { width: 14%; }
	#c2c-post-author-ip { font-weight: 600; }
</style>

HTML;
	}

	/**
	 * Displays the IP address of the original post author in the publish metabox.
	 *
	 * @since 1.0
	 */
	public static function show_post_author_ip() {
		global $post;

		$post_author_ip = self::get_post_author_ip( $post->ID );

		if ( ! $post_author_ip ) {
			return;
		}

		$author_ip = sprintf( '<span id="c2c-post-author-ip">%s</span>', sanitize_text_field( $post_author_ip ) );

		echo '<div class="misc-pub-section curtime misc-pub-curtime">';
		printf( __( 'Author IP address: <strong>%s</strong>', 'post-author-ip' ), $author_ip );
		echo '</div>';
	}

	/**
	 * Enqueues JavaScript and CSS for the block editor.
	 *
	 * @since 1.2.0
	 */
	public static function enqueue_block_editor_assets() {
		global $post;

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		if ( ! $post ) {
			return;
		}

		$post_author_ip = self::get_post_author_ip( $post->ID );

		if ( ! $post_author_ip ) {
			return;
		}

		wp_enqueue_script(
			'post-author-ip-js',
			plugins_url( 'assets/js/editor.js', __FILE__ ),
			array(
				'wp-components',
				'wp-data',
				'wp-edit-post',
				'wp-editor',
				'wp-element',
				'wp-i18n',
				'wp-plugins',
				'wp-api-fetch',
			),
			self::version(),
			true
		);

		wp_enqueue_style(
			'post-author-ip',
			plugins_url( 'assets/css/editor.css', __FILE__ ),
			array(),
			self::version()
		);

		//if ( function_exists( 'wp_set_script_translations' ) ) {
		//	wp_set_script_translations( 'post-author-ip-js', 'post-author-ip-js', \dirname( __DIR__ ) . '/languages' );
		//}
	}

	/**
	 * Adds a column to show the IP address of the original post author.
	 *
	 * @since 1.0
	 *
	 * @param  array $posts_columns Array of post column titles.
	 *
	 * @return array The $posts_columns array with the 'post-author-ip' column's title added.
	 */
	public static function add_post_column( $posts_columns ) {
		if ( self::include_column() ) {
			$posts_columns[ self::$field ] = __( 'Author IP', 'post-author-ip' );
		}

		return $posts_columns;
	}

	/**
	 * Outputs the IP address of the original post author for each post listed in the post
	 * listing table in the admin.
	 *
	 * @since 1.0
	 *
	 * @param string $column_name The name of the column.
	 * @param int    $post_id     The id of the post being displayed.
	 */
	public static function handle_column_data( $column_name, $post_id ) {
		if ( ! self::include_column() ) {
			return;
		}

		if ( self::$field === $column_name ) {
			$post_author_ip = self::get_post_author_ip( $post_id );

			if ( $post_author_ip ) {
				echo '<span>' . sanitize_text_field( $post_author_ip ) . '</span>';
			}
		}
	}

	/**
	 * Records the IP address of the original author of a post.
	 *
	 * @since 1.0
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public static function transition_post_status( $new_status, $old_status, $post ) {
		// Only concerned with posts on creation.
		if ( 'new' !== $old_status || 'revision' === get_post_type( $post ) ) {
			return;
		}

		if ( $post_author_ip = self::get_current_user_ip() ) {
			self::set_post_author_ip( $post->ID, $post_author_ip );
		}
	}

	/**
	 * Returns the IP address of the current user.
	 *
	 * @since 1.0
	 *
	 * @return string The IP address of the current user.
	 */
	public static function get_current_user_ip() {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ) : '';

		/**
		 * Filters the current user's IP address as used by the plugin.
		 *
		 * @since 1.0
		 *
		 * @param string $ip The current user's IP address.
		 */
		return apply_filters(
			'c2c_get_current_user_ip',
			$ip
		);
	}

	/**
	 * Returns the IP address of the original post author.
	 *
	 * @since 1.0
	 *
	 * @param  int|WP_Post $post_id Post object or post id.
	 * @return string      The IP address of the original post author.
	 */
	public static function get_post_author_ip( $post_id ) {
		$post_author_ip = '';
		$post           = get_post( $post_id );

		if ( $post ) {
			/**
			 * Filters the post author IP address.
			 *
			 * @since 1.0
			 *
			 * @param string $ip      The post author IP address.
			 * @param int    $post_id The post ID.
			 */
			$post_author_ip = apply_filters(
				'c2c_get_post_author_ip',
				get_post_meta( $post->ID, self::$meta_key, true ),
				$post->ID
			);
		}

		return $post_author_ip;
	}

	/**
	 * Explicitly sets the post author IP address for a post.
	 *
	 * @since 1.0
	 *
	 * @param  int|WP_Post $post_id Post object or post id.
	 * @param  string      $ip      IP address.
	 */
	public static function set_post_author_ip( $post_id, $ip ) {
		$post = get_post( $post_id );

		if ( $post && $ip ) {
			/**
			 * Filters whether the post author IP can be stored for the post.
			 *
			 * @since 1.1
			 *
			 * @param bool   $allowed Can post author IP be saved for post? Default true.
			 * @param int    $post_id The post ID.
			 * @param string $ip      The post author IP address.
			 */
			$can_store_post_author_id = (bool) apply_filters( 'c2c_post_author_ip_allowed', true, $post_id, $ip );

			if ( $can_store_post_author_id ) {
				update_post_meta( $post->ID, self::$meta_key, filter_var( $ip, FILTER_VALIDATE_IP ) );
			}
		}
	}

} // end c2c_PostAuthorIP

add_action( 'plugins_loaded', array( 'c2c_PostAuthorIP', 'init' ) );

endif; // end if !class_exists()
