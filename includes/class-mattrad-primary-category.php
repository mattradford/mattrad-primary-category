<?php
/**
 * Mattrad Primary Category.
 *
 * This is the main plugin class file.
 * We ensure only one instance of the plugin can be instantiated.
 * Constants are defined and files are required.
 *
 * @category Plugin
 * @package  mattrad-primary-category
 * @author   Matt Radford <matt@mattrad.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @since    1.0.0
 */

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The main class for the plugin.
 */
class Mattrad_Primary_Category {

     /**
     * The plugin instance.
     *
     * @var self
     */
    protected static $instance = null;

    /**
     * Plugin version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Get the globally available instance of the plugin.
     *
     * This is called in in mattrad-primary-category.php.
     *
     * @return static
     */
    public static function getInstance() {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->require_files();
    }

    /**
     * Define the plugin constants.
     *
     * MPC_PLUGIN_DIR is already defined in mattrad-primary-category.php.
     */
    public function define_constants() {
        if ( ! defined( 'MPC_PLUGIN_VERSION' ) ) {
            define( 'MPC_PLUGIN_VERSION', $this->version );
        }
        if ( ! defined( 'MPC_PLUGIN_URL' ) ) {
            define( 'MPC_PLUGIN_URL', trailingslashit( plugin_dir_url( __DIR__ ) ) );
        }
        if ( ! defined( 'MPC_BASENAME' ) ) {
            define( 'MPC_BASENAME', plugin_basename( MPC_PLUGIN_DIR ) );
        }
    }

    /**
     * Include the plugin files.
     */
    public function require_files() {
        // Helper functions.
        require_once( MPC_PLUGIN_DIR . '/includes/helpers.php' );

        // Public facing functions.
        require_once( MPC_PLUGIN_DIR . '/public/mattrad-primary-category-functions.php' );

        // WP Admin only functions.
		if ( is_admin() ) {
            require_once( MPC_PLUGIN_DIR . '/admin/class-mattrad-primary-category-set.php' );
		}
    }
}
