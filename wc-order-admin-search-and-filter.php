<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.codeoz.com
 * @since             1.0.0
 * @package           WC_Order_Admin_Search_And_Filter
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Order Admin Search & Filters
 * Plugin URI:        www.codeoz.com/coz-aosf
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            codeOz
 * Author URI:        www.codeoz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       coz-aosf
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
define( 'WC_ORDER_ADMIN_SEARCH_AND_FILTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-coz-aosf-activator.php
 */
function activate_wc_order_admin_search_and_filter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coz-aosf-activator.php';
	Wc_Order_Admin_Search_And_Filter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-coz-aosf-deactivator.php
 */
function deactivate_wc_order_admin_search_and_filter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-coz-aosf-deactivator.php';
	Wc_Order_Admin_Search_And_Filter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_order_admin_search_and_filter' );
register_deactivation_hook( __FILE__, 'deactivate_wc_order_admin_search_and_filter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-coz-aosf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_order_admin_search_and_filter() {

	$plugin = new WC_Order_Admin_Search_And_Filter();
	$plugin->run();

}
run_wc_order_admin_search_and_filter();
