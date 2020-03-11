<?php

/**
 * @link              www.codeoz.com
 * @since             1.0.0
 * @package           WC_Order_Admin_Search_And_Filter
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Order Admin Search & Filters
 * Plugin URI:        www.codeoz.com/woocommerce_order_admin_search_and_filter
 * Description:       Adds additional functionality to WooCommerce Order Admin, like custom filters & search, additional columns, and more..
 * Version:           1.0.0
 * Author:            codeOz
 * Author URI:        www.codeoz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       coz-oasf
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_ORDER_ADMIN_SEARCH_AND_FILTER_VERSION', '1.0.0-Alpha1' );

if ( ! class_exists( 'Codeoz_OASF' ) ) :

	/**
	 * Main "WooCommerce Order Admin Search & Filters" Class
	 *
	 * "Word hard. Stay bumble."
	 */
	final class Codeoz_OASF {

		/** @var array Topic views */
		public $views = array();

		/** @var array Overloads get_option() */
		public $options = array();

		/** @var array Storage of options not in the database */
		public $not_options = array();

		/** @var ZOASF_Admin Main Admin object and data	 */
		public $adminObj = null;

		/** @var ZOASF_Public Main Public object and data */
		public $publicObj = null;

		/** @var string */
		public $version;

		/** @var string */
		public $db_version;

		/** @var string The full file path for the main pluging file (plugin-folder-name.php) */
		public $file;

		/** @var string The plugin base name, is "pluging-folder-name/pluging-folder-name.php" */
		public $basename;

		/** @var string The plugin base path(with trailing forward slash), is "pluging-folder-name/" */
		public $basepath;

		/**
		 * @var string Path to the "WooCommerce Order Admin Search & Filters" plugin directory, with trailing forward slash
		 * For example: C:\xampp\htdocs\u99tech\wp-content\plugins\wc-order-admin-search-and-filter/
		 */
		public $plugin_dir = '';

		/**
		 * @var string URL to the "WooCommerce Order Admin Search & Filters" plugin directory, with trailing forward slash
		 * For example: https://localhost/u99tech/wp-content/plugins/wc-order-admin-search-and-filter/
		 */
		public $plugin_url = '';

		/**
		 * @var string Path to the "WooCommerce Order Admin Search & Filters" includes directory, with trailing forward slash
		 * For example: C:\xampp\htdocs\u99tech\wp-content\plugins\wc-order-admin-search-and-filter/src/
		 */
		public $src_dir = '';

		/**
		 * @var string URL to the "WooCommerce Order Admin Search & Filters" includes directory, with trailing forward slash
		 * For example: https://localhost/u99tech/wp-content/plugins/wc-order-admin-search-and-filter/src/
		 */
		public $src_url = '';

		/**
		 * @var string Path to the "WooCommerce Order Admin Search & Filters" language base
		 * For example: wc-order-admin-search-and-filter/languages/
		 */
		public $lang_base = '';

		/**
		 * @var string Path to the "WooCommerce Order Admin Search & Filters" language directory
		 * For example: C:\xampp\htdocs\u99tech\wp-content\plugins\wc-order-admin-search-and-filter/languages/
		 */
		public $lang_dir = '';


		/** Magic *****************************************************************/

		/**
		 * "WooCommerce Order Admin Search & Filters" uses many variables, several of which
		 * can be filtered to customize the way it operates. Most of these variables are
		 * stored in a private array that gets updated with the help of PHP magic methods.
		 *
		 * This is a precautionary measure, to avoid potential errors produced by
		 * unanticipated direct manipulation of run-time data.
		 *
		 * @see Codeoz_OASF::setup_globals()
		 * @var array
		 */
		private $dataArray;

		/** Singleton *************************************************************/

		/**
		 * Main "WooCommerce Order Admin Search & Filters" Instance
		 *
		 * Insures that only one instance of Codeoz_OASF exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @staticvar object $instance
		 * @return Codeoz_OASF The one true Codeoz_OASF
		 * @see codeoz_oasf()
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new Codeoz_OASF;
				$instance->setup_environment();
				$instance->includes();
				$instance->setup_variables();
				$instance->setup_actions();
			}

			// Always return the instance
			return $instance;
		}


		/** Magic Methods *********************************************************/

		/**
		 * A dummy constructor to prevent Codeoz_OASF from being loaded more than once.
		 *
		 * @see Codeoz_OASF::instance()
		 * @see codeoz_oasf();
		 */
		private function __construct() { /* Do nothing here */
		}

		/**
		 * A dummy magic method to prevent Codeoz_OASF from being cloned
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'coz_oasf' ), '2.1' );
		}

		/**
		 * A dummy magic method to prevent Codeoz_OASF from being unserialized
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'coz_oasf' ), '2.1' );
		}

		/**
		 * Magic method for checking the existence of a certain custom field
		 */
		public function __isset( $key ) {
			return isset( $this->dataArray[ $key ] );
		}

		/**
		 * Magic method for getting Codeoz_OASF variables
		 */
		public function __get( $key ) {
			return isset( $this->dataArray[ $key ] ) ? $this->dataArray[ $key ] : null;
		}

		/**
		 * Magic method for setting Codeoz_OASF variables
		 */
		public function __set( $key, $value ) {
			$this->dataArray[ $key ] = $value;
		}

		/**
		 * Magic method for unsetting Codeoz_OASF variables
		 */
		public function __unset( $key ) {
			if ( isset( $this->dataArray[ $key ] ) ) {
				unset( $this->dataArray[ $key ] );
			}
		}

		/**
		 * Magic method to prevent notices and errors from invalid method calls
		 */
		public function __call( $name = '', $args = array() ) {
			unset( $name, $args );

			return null;
		}


		/** Private Methods *******************************************************/

		/**
		 * Setup the environment variables to allow the rest of "WooCommerce Order Admin Search & Filters" to function
		 * more easily.
		 *
		 * @access private
		 */
		private function setup_environment() {

			/** Versions **********************************************************/

			$this->version = '1.0.0';
			$this->db_version = '1';

			/** Paths *************************************************************/

			// File & base
			$this->file     = __FILE__;
			$this->basename = apply_filters( 'zoasf_plugin_basename', str_replace( array(
				'build/',
				'src/'
			), '', plugin_basename( $this->file ) ) );
			$this->basepath = apply_filters( 'zoasf_plugin_basepath', trailingslashit( dirname( $this->basename ) ) );

			// Path and URL
			$this->plugin_dir = apply_filters( 'zoasf_plugin_dir_path', plugin_dir_path( $this->file ) );
			$this->plugin_url = apply_filters( 'zoasf_plugin_dir_url', plugin_dir_url( $this->file ) );

			// Source
			$this->src_dir = apply_filters( 'zoasf_includes_dir', trailingslashit( $this->plugin_dir . 'src' ) );
			$this->src_url = apply_filters( 'zoasf_includes_url', trailingslashit( $this->plugin_url . 'src' ) );

			// Languages
			$this->lang_base = apply_filters( 'zoasf_lang_base', trailingslashit( $this->basepath . 'languages' ) );
			$this->lang_dir  = apply_filters( 'zoasf_lang_dir', trailingslashit( $this->plugin_dir . 'languages' ) );


			/** Admin or Public Object ********************************************/

			if (is_admin()) {
				require plugin_dir_path( __FILE__ ) . 'src/admin/class-zoasf-admin.php';
				$this->adminObj = new ZOASF_Admin();
			}
			else {
				require plugin_dir_path( __FILE__ ) . 'src/public/class-zoasf-public.php';
				$this->publicObj = new ZOASF_Public();
			}
		}

		/**
		 * Smart defaults to many "WooCommerce Order Admin Search & Filters" specific class variables.
		 */
		private function setup_variables() {

//			/** Identifiers *******************************************************/
//
//			// Post type identifiers
//			//$this->reply_post_type   = apply_filters( 'zoasf_reply_post_type',  'reply'     );
//
//			/** Queries ***********************************************************/
//
//			$this->current_view_id = 0; // Current view id
//
//			/** Misc **************************************************************/
//
//			$this->domain = 'coz_oasf';      // Unique identifier for retrieving translated strings
		}

		/**
		 * Include required files
		 *
		 * @access private
		 */
		private function includes() {

			/** Core **************************************************************/

			require $this->src_dir . 'includes/core/abstraction.php';
			require $this->src_dir . 'includes/core/update.php';


//			/** Components ********************************************************/
//
//			// Common
//			require $this->src_dir . 'core/functions.php';
//			require $this->src_dir . 'core/shortcodes.php';


			/** Internationalization **********************************************/
			//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zoasf-i18n.php';



//			/** Hooks *************************************************************/
//
//			require $this->src_dir . 'core/actions.php';
//			require $this->src_dir . 'core/filters.php';
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @access private
		 */
		private function setup_actions() {

			// Add actions to plugin activation and deactivation hooks
			//add_action( 'activate_' . $this->basename, 'zoasf_activation' );
			//add_action( 'deactivate_' . $this->basename, 'zoasf_deactivation' );
			register_activation_hook( __FILE__, 'zoasf_activation' );
			register_deactivation_hook( __FILE__, 'zoasf_deactivation' );


			// If "WooCommerce Order Admin Search & Filters" is being deactivated, do not add any actions
			if ( zoasf_is_deactivation( $this->basename ) ) {
				return;
			}

			// Array of "WooCommerce Order Admin Search & Filters" core actions
			$actions = array(
				'register_meta',            // Register meta
				'register_shortcodes',      // Register shortcodes
				'register_views',           // Register the views
				'load_textdomain'           // Load textdomain
			);

			// Add the actions
			foreach ( $actions as $class_action ) {
				add_action( 'zoasf_' . $class_action, array( $this, $class_action ), 5 );
			}

			// Internationalization
			//$plugin_i18n = new WC_Order_Admin_Search_And_Filter_i18n();
			//add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');


			// All "WooCommerce Order Admin Search & Filters" actions are setup (includes bbp-core-hooks.php)
			do_action_ref_array( 'zoasf_after_setup_actions', array( &$this ) );
		}
	}


	/**
	 * The main function responsible for returning the one true Codeoz_OASF Instance
	 * to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $coz_oasf = codeoz_oasf(); ?>
	 *
	 * @return Codeoz_OASF The one true Codeoz_OASF Instance
	 */
	function codeoz_oasf() {
		return Codeoz_OASF::instance();
	}

	/**
	 * Hook Codeoz_OASF early onto the 'plugins_loaded' action.
	 *
	 * This gives all other plugins the chance to load before "WooCommerce Order Admin Search & Filters", to get their
	 * actions, filters, and overrides setup without "WooCommerce Order Admin Search & Filters" being in the way.
	 */
	if ( defined( 'COZ_OASF_LATE_LOAD' ) ) {
		add_action( 'plugins_loaded', 'coz_oasf', (int) COZ_OASF_LATE_LOAD );
	} else {
		codeoz_oasf();
	}

endif; // class_exists check