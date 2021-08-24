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
 * Check term is valid.
 *
 * @param string    $term Category term
 * @return object   $primary_category_term
 */
function mattrad_get_valid_primary_category( $term ) {
    // Check a term has been supplied.
    if ( null === $term ) {
        return new WP_Error( 'no_term_specified', __( 'No primary category specified.' ) );
    }

    // Get the term object
    $term = esc_html( $term );
    $primary_category_term = get_term_by( 'name', $term , 'category' );

    // Check a term is valid.
    if ( ! $primary_category_term || is_wp_error( $primary_category_term ) ) {
        return new WP_Error( 'invalid_term_specified', __( 'Invalid primary category specified.' ) );
    }

    return $primary_category_term;
}

/**
 * Get all public post types.
 *
 * @return array   $post_types
 */
function mattrad_get_public_post_types() {
    // Get all public post types as default.
    $post_type_args = [
        'public' => true,
        '_builtin' => false,
    ];
    $post_types = get_post_types( $post_type_args );
    $post_types = array_values( $post_types );
    // Include "post" post type.
    $post_types[] = 'post';

    return $post_types;
}

/**
 * Get all posts with a Primary Category, by term.
 *
 * @param string    $term Category term
 * @param array     $args WP_Query arguments
 * @return object
 */
function mattrad_get_posts_in_primary_category( $term = 'null', $args = [] ) {
    $primary_category_term = mattrad_get_valid_primary_category( $term );
    $post_types = mattrad_get_public_post_types();

    // Monitor query run time, if that's your thing.
    // $start_time = microtime(true);

    $default_args = [
        'post_type'        => $post_types,
        'post_status'      => 'publish',
        'cat'              => $primary_category_term->term_id,
        'posts_per_page'   => 10,
        'meta_query' => [
            [
                'key'     => 'mattrad_primary_category',
                'value'   => $primary_category_term->name,
                'compare' => '=',
            ],
        ],
    ];

    // Get user-supplied query arguments.
    $query_args = wp_parse_args( $args, $default_args );

    // Build a new query.
    $primary_posts = new WP_Query( $query_args );

    // Query monitoring end.
    // $end_time = microtime(true);
    // $execution_time = ($end_time - $start_time);
    // dd('mattrad_get_posts_in_primary_category() ran in ' . round($execution_time, 3) . ' sec');

    return $primary_posts;
}

/**
 * Get latest 10 posts with a Primary Category, by term.
 *
 * Parameters are more tightly controlled for this function.
 * So let's apply some caching :)
 *
 * @param string    $term Category term
 * @return object
 */
function mattrad_get_latest_posts_in_primary_category( $term = 'null' ) {
    $primary_category_term = mattrad_get_valid_primary_category( $term );
    $post_types = mattrad_get_public_post_types();

    $cache_key = 'mattrad_primary_posts_in_'. strtolower($primary_category_term->name);
    $latest_primary_posts = wp_cache_get( $cache_key );

    if ( false === $latest_primary_posts ) {
        $default_args = [
            'post_type'        => $post_types,
            'post_status'      => 'publish',
            'cat'              => $primary_category_term->term_id,
            'posts_per_page'   => 10,
            'no_found_rows'     => true,
            'meta_query' => [
                [
                    'key'     => 'mattrad_primary_category',
                    'value'   => $primary_category_term->name,
                    'compare' => '=',
                ],
            ],
        ];

        // Build a new query.
        $latest_primary_posts = new WP_Query( $default_args );
        wp_cache_set( $cache_key, $latest_primary_posts );
    }

    return $latest_primary_posts;
}

 /**
 * Get Primary Category for the post.
 *
 * Same premise as get_the_category(), except for a single term.
 * This function may be used outside The Loop by passing a post ID as the parameter.
 *
 * @param int           $post_id
 * @return object|null  $primary_category_term
 * @link https://developer.wordpress.org/reference/functions/get_the_category/
 */
function mattrad_get_the_primary_category( $post_id = null ) {
    if ( null !== $post_id ) {
        $post_id = (int) $post_id;
    } else {
        $post_id = $post->ID;
    }

    // Get the primary category.
    $primary_category_assigned = get_post_meta( $post_id, 'mattrad_primary_category', true );
    $primary_category_term = mattrad_get_valid_primary_category( $primary_category_assigned  );

    return $primary_category_term;
}

 /**
 * Register all shortcodes.
 *
 * @return void
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
 * @param string        $output
 * @param object|null   $primary_category_term
 * @param string        $primary_category_text
 * @return string|null  Primary Category name|null.
 */
function mr_primary_category( $atts = [] ) {
    $output = '';
    global $post;

    // Default parameters
    extract( shortcode_atts( [
        'link' => 'true',
        'text' => __( 'Category:&nbsp;' ),
    ], $atts ) );

    $primary_category_term = mattrad_get_the_primary_category($post->ID);

    if ( 'false' === $link ) {
        $primary_category_text = '<span>' . __( $primary_category_term->name ) . '</span>';
    } else {
        $primary_category_text = '<a href="' . get_term_link( $primary_category_term->slug, 'category' ) . '">' . __( $primary_category_term->name ) . '</a>';
    }

    if ( null !== $primary_category_term && ! empty( $primary_category_text ) ) {
        $output = '<span class="mr-primary-category-assigned">';
        $output .= esc_html( $text );
        $output .= $primary_category_text;
        $output .= '</span>';
        return $output;
    } else {
        return null;
    }
}
