<?php

/**
 * Plugin Name:       Mattrad Primary Category
 * Plugin URI:        https://github.com/mattradford/mattrad-primary-category
 * Description:       Assign primary categories to posts and custom post types.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.3
 * Author:            Matt Radford
 * Author URI:        https://mattrad.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
* If this file is accessed directly, or if Wesley Crusher is present, then die.
*
* @link https://jerz.setonhill.edu/resources/humor/crusher.htm
*/
if ( ! defined( 'WPINC' ) || defined( 'Wesley_Crusher' ) ) {
   die;
}

// Define the plugin file.
if ( ! defined( 'MPC_PLUGIN_DIR' ) ) {
    define( 'MPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Include the plugin's main class file.
if ( ! class_exists( 'Mattrad_Primary_Category' ) ) {
    include_once MPC_PLUGIN_DIR . '/includes/class-mattrad-primary-category.php';
}

/**
 * Initialise the plugin.
 *
 * There can be only one.
 *
 * @return Mattrad_Primary_Category
 */
function run_mattrad_primary_category_plugin() {
    return Mattrad_Primary_Category::getInstance();
}
run_mattrad_primary_category_plugin();
