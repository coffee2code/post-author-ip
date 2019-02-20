<?php

defined( 'ABSPATH' ) or die();

class Post_Author_IP_Test extends WP_UnitTestCase {

	protected static $meta_key = 'c2c-post-author-ip';

	protected static $default_ip = '192.168.2.30';
	protected static $filter_ip  = '192.168.1.112';

	/**
	 * Test REST Server
	 *
	 * @var WP_REST_Server
	 */
	protected $server;

	public function setUp() {
		parent::setUp();

		$_SERVER['REMOTE_ADDR'] = self::$default_ip;

		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$this->server = $wp_rest_server = new \WP_REST_Server;
		do_action( 'rest_api_init' );
	}

	public function tearDown() {
		parent::tearDown();
		$this->unset_current_user();

		remove_filter( 'c2c_show_post_author_ip_column', '__return_false' );
		remove_filter( 'c2c_post_author_ip', array( $this, 'c2c_post_author_ip' ) );
		remove_filter( 'c2c_get_current_user_ip', array( $this, 'c2c_post_author_ip' ) );
		remove_filter( 'c2c_post_author_ip_allowed', array( $this, 'c2c_post_author_ip_allowed' ) );
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function create_user( $set_as_current = true ) {
		$user_id = $this->factory->user->create();
		if ( $set_as_current ) {
			wp_set_current_user( $user_id );
		}
		return $user_id;
	}

	// helper function, unsets current user globally. Taken from post.php test.
	private function unset_current_user() {
		global $current_user, $user_ID;

		$current_user = $user_ID = null;
	}


	//
	//
	// FUNCTIONS FOR HOOKING ACTIONS/FILTERS
	//
	//


	public function query_for_posts( $text ) {
		$q = new WP_Query( array( 'post_type' => 'post' ) );
		$GLOBALS['custom_query'] = $q;
		return $text;
	}

	public function filter_on_special_meta( $wpquery ) {
		$wpquery->query_vars['meta_query'][] = array(
			'key'     => 'special',
			'value'   => '1',
			'compare' => '='
		);
	}

	public function check_default_c2c_published_by_post_status( $post_statuses ) {
		return self::$default_c2c_published_by_post_status = $post_statuses;
	}

	public function c2c_post_author_ip( $ip ) {
		return self::$filter_ip;
	}

	public function c2c_post_author_ip_allowed( $allowed, $post_id, $ip ) {
		if ( $_SERVER['REMOTE_ADDR'] === self::$default_ip ) {
			$allowed = false;
		}

		return $allowed;
	}


	//
	//
	// TESTS
	//
	//


	public function test_plugin_version() {
		$this->assertEquals( '1.0', c2c_PostAuthorIP::version() );
	}

	public function test_class_is_available() {
		$this->assertTrue( class_exists( 'c2c_PostAuthorIP' ) );
	}

	public function test_meta_key_created_for_post_saved_as_draft() {
		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$this->assertEquals( self::$default_ip, get_post_meta( $post_id, self::$meta_key, true ) );
	}

	public function test_meta_key_created_for_post_immediately_published() {
		$post_id = $this->factory->post->create( array( 'post_status' => 'publish' ) );

		$this->assertEquals( self::$default_ip, get_post_meta( $post_id, self::$meta_key, true ) );
	}

	public function test_meta_key_created_for_post_future_published() {
		$post_id = $this->factory->post->create( array( 'post_status' => 'future', 'post_date' => '2080-01-01 12:00:00' ) );

		$this->assertEquals( self::$default_ip, get_post_meta( $post_id, self::$meta_key, true ) );
	}

	public function test_meta_key_not_updated_for_draft_upon_publish() {
		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$post = get_post( $post_id );

		// Simulate user moved to new IP address.
		$_SERVER['REMOTE_ADDR'] = '192.168.13.13';

		$post->post_status = 'publish';
		wp_update_post( $post );

		$this->assertEquals( self::$default_ip, get_post_meta( $post_id, self::$meta_key, true ) );
	}


	/*
	 * REST API
	 */


	public function test_meta_is_registered() {
		$this->assertTrue( registered_meta_key_exists( 'post', self::$meta_key ) );
	}

	public function test_rest_post_request_includes_meta() {
		$author_id = $this->create_user( false );
		$post_id = $this->factory->post->create( array( 'post_status' => 'publish', 'post_author' => $author_id ) );
		c2c_PostAuthorIP::set_post_author_ip( $post_id, self::$default_ip );

		$request = new WP_REST_Request( 'GET', sprintf( '/wp/v2/posts/%d', $post_id ) );
		$response = $this->server->dispatch( $request );

		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();
		$this->assertArrayHasKey( 'meta', $data );

		$meta = (array) $data['meta'];

		$this->assertArrayHasKey( self::$meta_key, $meta );
		$this->assertEquals( self::$default_ip, $meta[ self::$meta_key ] );
	}


	/*
	 * set_post_author_ip()
	 */

	public function test_set_post_author_ip() {
		$ip = '192.168.1.225';

		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$this->assertEquals( self::$default_ip, c2c_PostAuthorIP::get_post_author_ip( $post_id ) );

		c2c_PostAuthorIP::set_post_author_ip( $post_id, $ip );

		$this->assertEquals( $ip , c2c_PostAuthorIP::get_post_author_ip( $post_id ) );
	}

	/*
	 * get_post_author_ip()
	 */

	public function test_get_post_author_ip() {
		$ip = '192.168.1.222';

		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );
		c2c_PostAuthorIP::set_post_author_ip( $post_id, $ip );

		$this->assertEquals( $ip , c2c_PostAuthorIP::get_post_author_ip( $post_id ) );
	}

	public function test_get_post_author_ip_on_post_without_the_meta() {
		$_SERVER['REMOTE_ADDR'] = '';

		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$this->assertEmpty( c2c_PostAuthorIP::get_post_author_ip( $post_id ) );
	}

	/*
	 * get_current_user_ip()
	 */

	public function test_get_current_user_ip() {
		$_SERVER['REMOTE_ADDR'] = '';

		$this->assertEmpty( c2c_PostAuthorIP::get_current_user_ip() );

		$ip = '192.168.1.111';
		$_SERVER['REMOTE_ADDR'] = $ip;

		$this->assertEquals( $ip , c2c_PostAuthorIP::get_current_user_ip() );
	}

	/*
	 * Filter: c2c_show_post_author_ip_column
	 */

	public function test_filter_c2c_show_post_author_ip_column() {
		$this->assertTrue( c2c_PostAuthorIP::include_column() );

		add_filter( 'c2c_show_post_author_ip_column', '__return_false' );

		$this->assertFalse( c2c_PostAuthorIP::include_column() );
	}

	/*
	 * Filter: c2c_get_post_author_ip
	 */

	public function test_filter_c2c_get_post_author_ip() {
		add_filter( 'c2c_get_post_author_ip', array( $this, 'c2c_post_author_ip' ) );

		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$this->assertEquals( self::$filter_ip, c2c_PostAuthorIP::get_post_author_ip( $post_id ) );
	}

	/*
	 * Filter: c2c_get_current_user_ip
	 */

	public function test_filter_c2c_get_current_user_ip() {
		add_filter( 'c2c_get_current_user_ip', array( $this, 'c2c_post_author_ip' ) );

		$this->assertEquals( self::$filter_ip , c2c_PostAuthorIP::get_current_user_ip() );
	}

	/*
	 * Filter: c2c_post_author_ip_allowed
	 */

	public function test_filter_c2c_post_author_ip_allowed() {
		add_filter( 'c2c_post_author_ip_allowed', array( $this, 'c2c_post_author_ip_allowed' ), 10, 3 );

		$post_id = $this->factory->post->create( array( 'post_status' => 'draft' ) );

		$this->assertEmpty( c2c_PostAuthorIP::get_post_author_ip( $post_id ) );
	}

}
