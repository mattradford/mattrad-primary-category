<?php

/**
 * Mattrad Primary Category Set.
 *
 * This is the class that allows users to set the primary term.
 *
 * We create a metabox on the post edit screen, check through existing terms,
 * and allow the user to choose a term as the primary.
 *
 * @category Plugin
 * @package  mattrad-primary-category
 * @author   Matt Radford <matt@mattrad.uk>
 * @license  https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GPL-2.0+
 * @since    1.0.0
 */
class Mattrad_Primary_Category_Set {

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Run class functions.
     */
    public function init() {
        add_action( 'add_meta_boxes', [ $this, 'add_mattrad_primary_category_metabox' ] );
        add_action( 'save_post', [ $this, 'save_mattrad_primary_category_metabox' ] );
        add_action( 'admin_head', [ $this, 'fix_block_editor_metabox_heading'] );
        add_action( 'admin_init', [ $this, 'add_admin_columns'] );
    }

    /**
     * Get all public post types.
     *
     * Has to be called during a late enough request,
     * in order to return registered CPTs i.e. after WP init().
     */
    public function get_public_post_types() {

        // Get only Custom Post Types
        $args = [
            'public' => true,
            '_builtin' => false,
        ];
        $post_types = get_post_types( $args );

        // Include "post" post type
        $post_types['post'] = 'post';

        return $post_types;
    }

    /**
     * Add meta boxes for each public post type.
     */
    public function add_mattrad_primary_category_metabox() {
        $public_post_types = $this->get_public_post_types();

        add_meta_box(
            'mattrad_primary_category',
            __( 'Primary Category' ),
            [$this, 'render_mattrad_primary_category_metabox'],
            $public_post_types,
            'side',
            'default'
        );
    }

    /**
     * Render the metabox
     *
     * @param $post
     */
    public function render_mattrad_primary_category_metabox( $post ) {
        // Get categories assigned to the post.
        $categories = get_the_category();
        // Get the existing primary category.
        $primary_category_assigned = get_post_meta( $post->ID, 'mattrad_primary_category', true );

        // Build the select, or show a message.
        $select = '';
        if (! empty( $categories ) ) {
            $select .= '<select style="width:auto;" name="mattrad_primary_category" id="mattrad_primary_category">';
            $select .= '<option value="-1">';
            $select .= __( 'Choose Primary Category' );
            $select .= '</option>';
            foreach( $categories as $category ) {
                $select .= '<option value="' . $category->name . '" ' . selected( $primary_category_assigned, $category->name, false ) . '>' . ucwords( $category->name ) . '</option>';
            }
            $select .= '</select>';
        } else {
            $select = '<span>';
            $select .= __( 'Please choose categories first, then save the post. Refresh the page and assign a primary.' );
            $select .= '</span>';
        }

        // Define the nonce.
        wp_nonce_field( MPC_BASENAME, 'mattrad_primary_category_nonce' );

        // Render the HTML.
        echo '<div class="mattrad-primary-category">' . $select . '</div>';
    }

    /**
     * Save the Primary Category
     *
     * Runs when saving or updating a post.
     *
     * @param $post_id
     */
    public function save_mattrad_primary_category_metabox( $post_id ) {

        // Check and verify the nonce.
        if ( ! isset( $_POST['mattrad_primary_category_nonce'] ) || ! wp_verify_nonce( $_POST['mattrad_primary_category_nonce'], MPC_BASENAME ) ) {
           return;
        }

        // Check if a primary category is being set and save to post meta.
        if ( isset( $_POST[ 'mattrad_primary_category' ] ) ) {
    		$primary_category = sanitize_text_field( $_POST[ 'mattrad_primary_category' ] );
            // If the choice is "Choose Primary Category", remove the previous choice.
            // Otherwise, save the new value.
            if ( '-1' === $primary_category) {
                update_post_meta( $post_id, 'mattrad_primary_category', '' );
            } else {
                update_post_meta( $post_id, 'mattrad_primary_category', $primary_category );
            }
    	}
    }

    /**
     * Fix the Block Editor metabox heading style.
     *
     * Only added if we're in block editor AND it's a post edit screen.
     * Because Gutenberg.
     */
    public function fix_block_editor_metabox_heading() {
        $screen = get_current_screen();
        if ( true === $screen->is_block_editor && 'post' === $screen->base ) {
            ?>
            <style>
            #mattrad_primary_category h2 {
                font-size: 13px;
                font-weight: 500 !important;
            }
            </style>
            <?php
        }
    }

    /**
     * Add admin columns.
     *
     * Added to each public post type.
     */
    public function add_admin_columns() {
        $public_post_types = $this->get_public_post_types();

        foreach ( $public_post_types as $post_type ) {
            // Add the Primary Category columns
            add_filter( 'manage_'. $post_type .'_posts_columns', function( $columns ) {
                return array_merge( $columns, [ 'mattrad-primary-category' => __( 'Primary Category' ) ] );
            });

            // Populate the Primary Category columns
            add_action( 'manage_'. $post_type .'_posts_custom_column', function( $column_key, $post_id ) {
                if ( $column_key == 'mattrad-primary-category' ) {
                    $primary_category_assigned = get_post_meta( $post_id, 'mattrad_primary_category', true );
                    if ( $primary_category_assigned ) {
                        esc_html_e( ucwords( $primary_category_assigned ) );
                    } else {
                        echo '<span style="color:red;">';
                        _e( 'No' );
                        echo '</span>';
                    }
                }
            }, 10, 2);
        }
    }

}
new Mattrad_Primary_Category_Set();
