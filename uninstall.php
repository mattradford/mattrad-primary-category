<?php
/**
 * Mattrad Primary Category Uninstall.
 *
 * These functions will remove database entries for Mattrad Primary Category.
 *
 * @category Plugin
 * @package  mattrad-primary-category
 * @author   Matt Radford <matt@mattrad.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @since    1.0.0
 */

// If uninstall.php is not called by WordPress, abort.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_post_meta_by_key( 'mattrad_primary_category' );