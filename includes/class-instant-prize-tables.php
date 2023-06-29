<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rightglobalgroup.com/
 * @since      4.1.0
 *
 * @package    Instant_Prize_Tables
 * @subpackage Instant_Prize_Tables/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      4.1.0
 * @package    Instant_Prize_Tables
 * @subpackage Instant_Prize_Tables/includes
 * @author     Right Global Group <info@rightglobalgroup.com>
 */
class Instant_Prize_Tables {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    4.1.0
	 * @access   protected
	 * @var      Instant_Prize_Tables_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    4.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    4.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    4.1.0
	 */
	public function __construct() {
		if ( defined( 'INSTANT_PRIZE_TABLES_VERSION' ) ) {
			$this->version = INSTANT_PRIZE_TABLES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'instant-prize';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Instant_Prize_Tables_Loader. Orchestrates the hooks of the plugin.
	 * - Instant_Prize_Tables_i18n. Defines internationalization functionality.
	 * - Instant_Prize_Tables_Admin. Defines all hooks for the admin area.
	 * - Instant_Prize_Tables_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    4.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-instant-prize-tables-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-instant-prize-tables-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-instant-prize-tables-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-instant-prize-tables-public.php';

		$this->loader = new Instant_Prize_Tables_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Instant_Prize_Tables_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    4.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Instant_Prize_Tables_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    4.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Instant_Prize_Tables_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    4.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Instant_Prize_Tables_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_shortcode('instant_prize_league_table', $plugin_public, 'instant_prize_league_table');
		$this->loader->add_shortcode('instant_prize_medals', $plugin_public, 'instant_prize_medals');
		$this->loader->add_shortcode("instant_prize_user_medal", $plugin_public, 'instant_prize_user_medal');
		$this->loader->add_action("wp_ajax_instant_medals", $plugin_public, "instant_prize_medals_ajax");
		$this->loader->add_action("wp_ajax_nopriv_instant_medals", $plugin_public, "instant_prize_medals_ajax");
		$this->loader->add_action('woocommerce_order_status_completed', $plugin_public, 'auto_add_site_credit', 15); // add_action( 'woocommerce_order_status_completed', 'auto_add_site_credit', 10, 1 );
		$this->loader->add_shortcode('instant_winners', $plugin_public, 'instant_winners');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    4.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     4.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     4.1.0
	 * @return    Instant_Prize_Tables_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     4.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
