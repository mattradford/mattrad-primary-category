<?php

/**
 * Mattrad Primary Category Functions.
 *
 * User-facing functions to show primary category.
 *
 * @category Plugin
 * @package  mattrad-primary-category
 * @author   Matt Radford <matt@mattrad.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @since    1.0.0
 */

 /**
 * Register all shortcodes.
 */
function mattrad_shortcodes_init() {
    add_shortcode( 'mr_primary_category', 'mr_primary_category' );
}
add_action( 'init', 'mattrad_shortcodes_init' );

 /**
 * [mr_primary_category] shortcode.
 *
 * Displays the Primary Category, if assigned.
 *
 * @return string|null Primary Category name|null.
 */
function mr_primary_category( $atts = [] ) {
    $output = '';
    global $post;

    // Default parameter
    extract( shortcode_atts( [
        'link' => 'true',
        'text' => __( 'Category:&nbsp;' ),
    ], $atts ) );

    // Get the existing primary category.
    $primary_category_assigned = get_post_meta( $post->ID, 'mattrad_primary_category', true );

    // Get term object and build link.
    if ( ! is_wp_error( $primary_category_assigned ) && ! empty( $primary_category_assigned ) ) {
        $primary_category_assigned = esc_html( $primary_category_assigned );
        $primary_category_term = get_term_by( 'name', $primary_category_assigned, 'category' );
        if ( 'false' === $link ) {
            $primary_category_text = '<span>' . __( $primary_category_term->name ) . '</span>';
        } else {
            $primary_category_text = '<a href="' . get_term_link( $primary_category_term->slug, 'category' ) . '">' . __( $primary_category_term->name ) . '</a>';
        }
    }

    if ( null != $primary_category_assigned && ! empty( $primary_category_text ) ) {
        $output = '<span class="mr-primary-category-assigned">';
        $output .= esc_html( $text );
        $output .= $primary_category_text;
        $output .= '</span>';
        return $output;
    } else {
        return null;
    }
}
