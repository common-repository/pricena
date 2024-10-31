<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ae.pricena.com
 * @since      1.0.0
 *
 * @package    Pricena
 * @subpackage Pricena/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pricena
 * @subpackage Pricena/admin
 * @author     Pricena Development Team
 */
class Pricena_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pricena_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pricena_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();

		if ($screen->base === 'settings_page_pna-settings') {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pricena-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name.'_bs', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Pricena_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pricena_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pricena-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_bs', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

	}

	public function show_admin_notices() {

		if (!get_option('storesetup_is_done') || get_option('storesetup_is_done') == 'no') {
			echo '<div class="notice notice-success">
            <p><strong>Congratulations!</strong> Pricena plugin activated. Please <a href="'.menu_page_url('pna-settings', false).'">provide us some store details</a>, so that we could place your store in our listing.</p></div>';
		}

		if ( !class_exists( 'WooCommerce' ) ) {
			echo '<div class="notice notice-warning"><p>'.__('Hmmm... Seems WooCommerce not installed or inactive. Please install and/or activate WooCommerce plugin for correct work of Pricena plugin.', 'pricena').'</p></div>';
		}

	}

	/**
	 * Add pages for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_pages() {
		add_options_page( __('Pricena Settings', 'pricena'), __('Pricena', 'pricena'), 'manage_options', 'pna-settings', array( $this, 'settings_page' ), null );
	}

	/**
	 * Render settings page of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function settings_page() {

		if ( !class_exists( 'WooCommerce' ) ) {
			return;
		}

		// tabs router
		if ( array_key_exists('tab', $_GET) ) {
			switch ( $_GET['tab']) {
				case 'storesetup':
					$this->tab_storesetup();
					break;
			}
			return;
		}

		// require passing storesetup
		if (!get_option('storesetup_is_done') || get_option('storesetup_is_done') == 'no') {
			$storesetup_url = menu_page_url('pna-settings', false).'&tab=storesetup&section=request';
			wp_redirect( $storesetup_url );
			exit;
		}
		
		//render dashboard by default
		$success_url = 'options-general.php?page=pna-settings&tab=storesetup&section=request-done';
		wp_redirect( $success_url );
		exit;
		// $page_path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/pricena-admin-display.php';

		// if ( !file_exists( $page_path ) ) {
		// 	echo 'Page not found';
		// 	return;
		// }

		// require_once $page_path;
	}

	/**
	 * Add 'settings' link to plugin list-item on the plugins page
	 */
	public function add_plugin_settings_link ( $actions ) {
	   $links = [
	      '<a href="'. menu_page_url('pna-settings', false).'">'.__('Settings', 'pricena').'</a>',
	   ];

	   $actions = array_reverse(array_merge( $actions, $links ));
	   return $actions;
	}

	/**
	 * Hook the pricena store setup request sent by post from the admin panel
	 */
	public function process_storesetup_request () {
		if ( ! isset( $_POST['csrf'] ) || ! wp_verify_nonce( $_POST['csrf'], 'pna_process_storesetup_request' ) ) {
			$fail_url = 'options-general.php?page=pna-settings&tab=storesetup&section=request&error=1';
			wp_redirect( $fail_url );
			exit;
		}

		if ( ! $this->send_storesetup_request( $_POST ) ) {
			$fail_url = 'options-general.php?page=pna-settings&tab=storesetup&section=request&error=2';
			wp_redirect( $fail_url );
			exit;
		}

		$success_url = 'options-general.php?page=pna-settings&tab=storesetup&section=request-done';
		wp_redirect( $success_url );
		exit;
	}

	/**
	 * Send setup request with store data to Pricena
	 *
	 * @access   private
	 * @param 	 array 		$data 		store data
	 * @since    1.0.0
	 */
	private function send_storesetup_request ( $data ) {

		$store_data = array(
			'url' => get_option('siteurl'),
			'name' => sanitize_text_field($data['contact_name']),
			'email' => get_option('admin_email'),
			'phone' => sanitize_text_field($data['contact_phone']),
			'platform' => 'woocommerce',
			'comments' => sanitize_text_field($data['client_comments']),
			'address' => get_option('woocommerce_store_address').'; '
				.get_option('woocommerce_store_address_2').'; '
				.get_option('woocommerce_store_city').'; '
				.get_option('woocommerce_default_country').'; '
				.get_option('woocommerce_store_postcode'),
			'identifier' => md5(strrev(get_option('siteurl'))),
			'extra' => array(
				'token' => strrev(sanitize_text_field($data['api_key']).':'.sanitize_text_field($data['api_secret'])),
				'categories' => array(),
			),
		);

		$categories = [];

		foreach ($_POST['categories'] as $category) {
			$term = get_term( $category, 'product_cat' );
			$categories[$term->name] = get_term_link( $term );
		}

		$store_data['extra']['categories'] = $categories;
		$store_data['extra'] = json_encode($store_data['extra']);

		$args = array(
			'body' => $store_data,
			'user-agent' => 'Pricena WP App',
		);

		$response = wp_remote_post('https://api-ae.pricena.com/en/plugins/sendStoreListingRequest', $args);
		$output = json_decode( $response['body'] );

		if ( $output->Status == 200 ) {
			update_option('storesetup_is_done', 'yes');
			$store_data['extra'] = json_decode($store_data['extra'], true);

			$saved_store_data = get_option(Pricena::OPTION_STORE_REQUEST);

			if (!$saved_store_data) {
				add_option(Pricena::OPTION_STORE_REQUEST, $store_data);
			}
			else {
				update_option(Pricena::OPTION_STORE_REQUEST, $store_data);
			}

			return true;
		}

		return false;
	}

	/**
	 * Show page from storesetup flow according to section passed in get request
	 */
	private function tab_storesetup () {
		switch ($_GET['section']) {
			case 'request':
				$page_path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/pricena-admin-storesetup-form.php';
				break;
			case 'request-done':
				$page_path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/pricena-admin-storesetup-done.php';
				break;
		}
		
		if ( !file_exists( $page_path ) ) {
			echo 'Page not found';
			return;
		}

		require_once $page_path;
	}

}
