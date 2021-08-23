<?php

/**
 * Development helper functions.
 *
 * @category Plugin
 * @package  mattrad-primary-category
 * @author   Matt Radford <matt@mattrad.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @since    1.0.0
 */

/**
 * Dump and die.
 *
 * Thanks Laravel :)
 */
if ( ! function_exists( 'dd' ) ) {
    function dd( $variable ) {
        var_dump( $variable );
        die();
    }
}