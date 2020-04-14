<?php

/*
 * Copyright (C) 2019-present, Wedepohl Engineering
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 * this program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Genera Public License for more details.
 */

/**
 * @package BISKPlugin
 */

namespace BISKPlugin\Includes;

defined('ABSPATH') or die;

/**
 * This class is used to provide all the constants used by the plugin
 */
class BISKCosts {

    const POST_TYPE  = 'bisk-costs';
    const POST_NONCE = 'bisk-costs-post-class-nonce';

    /**
     * Constructor for the Costs class
     */
    public function __construct() {

        add_action('init', array($this, 'create_posttype'));
        add_action('add_meta_boxes_' . self::POST_TYPE, array($this, 'meta_box'));
        add_action('save_post', array($this, 'save_meta'), 10, 2);
        add_filter('manage_edit-' . self::POST_TYPE . '_columns', array($this, 'table_head'));
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', array($this, 'table_content'), 10, 2);

    }

    /**
     * Create Costs Custom Post Type
     */
    public function create_posttype() {

        $labels = array(
            'name'                     => __( 'Costs', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'singular_name'            => __( 'Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'add_new'                  => __( 'Add New Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'add_new_item'             => __( 'Add New Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'edit_item'                => __( 'Edit Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'new_item'                 => __( 'New Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'view_item'                => __( 'View Cost', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'view_items'               => __( 'View Costs', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'search_items'             => __( 'Search Costs', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'not_found'                => __( 'No Costs found', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'not_found_in_trash'       => __( 'No Costs found in trash', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'all_items'                => __( 'All Costs', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'attributes'               => __( 'Cost attributes', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'item_published'           => __( 'Cost published', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'item_published_privately' => __( 'Cost published privately', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'item_reverted_to_draft'   => __( 'Cost reverted to draft', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'item_scheduled'           => __( 'Cost scheduled', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            'item_updated'             => __( 'Cost updated', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
        );

        $supports = array(
            'title',
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'has_archive'         => false,
            'query_var'           => false,
            'capability_type'     => 'post',
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_in_nav_menus'   => false,
            'show_in_rest'        => false,
            'show_ui'             => true,
            'show_in_menu'        => BISKConfig::ADMIN_MENU_SLUG,
            'menu_position'       => 200,
            'menu_icon'           => 'dashicons-tickets-alt',
            'rewrite'             => array( 'slug' => 'costs' ),
            'supports'            => $supports,
        );

        register_post_type(
            self::POST_TYPE,
            $args
        );

    }

    /**
     * Add a meta box for the Costs custom post type
     */
    public function meta_box() {

        add_meta_box(
            'cost-meta-box',
            __( 'Cost Information', BISKConfig::TEXT_DOMAIN ), // phpcs:ignore
            array( $this, 'render_meta_box' ),
            self::POST_TYPE,
            'side',
            'default'
        );

    }

    /**
     * Render the meta box in the post.
     *
     * @param array $post The post.
     */
    public function render_meta_box( $post ) {

        $amount = esc_attr( get_post_meta( $post->ID, 'bisk-costs-amount', true ) );
        ?>
        <?php wp_nonce_field( basename( __FILE__ ), self::POST_NONCE ); ?>
        <label for="bisk-costs-amount"><?php esc_attr_e( 'Amount ($)', BISKConfig::TEXT_DOMAIN ); ?></label> <?php // phpcs:ignore ?>
        <input class="widefat" type="number" min="0" max="5000" step="1" name="bisk-costs-amount" id="bisk-costs-amount" value="<?php echo $amount; ?>" required/> <?php // phpcs:ignore ?>
        <?php

    }

    /**
     * Save the meta box if:
     *   - Not autosaving
     *   - Not a revision save
     *   - Has a valid NONCE
     *   - The user can edit the post
     *   - We are on the correct post
     *
     * @param int   $post_id The ID for the post.
     * @param array $post    The post.
     */
    public function save_meta( $post_id, $post ) {

        $is_autosave    = ( false === wp_is_post_autosave( $post_id ) ) ? false : true;
        $is_revision    = ( false === wp_is_post_revision( $post_id ) ) ? false : true;
        $is_valid_nonce = ( isset( $_POST[ self::POST_NONCE ] ) && wp_verify_nonce( $_POST[ self::POST_NONCE ], basename( __FILE__ ) ) ) ? true : false; // phpcs:ignore
        $can_edit       = current_user_can( 'edit_post', $post_id );
        $correct_post   = ( self::POST_TYPE === $post->post_type ) ? true : false;

        // Exit function if anything fails.
        if ( $is_autosave || $is_revision || ! $is_valid_nonce || ! $can_edit || ! $correct_post ) {
            return $post_id;
        }

        // Handle the amount.
        $meta_id    = 'bisk-costs-amount';
        $amount     = get_post_meta( $post_id, $meta_id, true );
        $new_amount = ( isset( $_POST[ $meta_id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $meta_id ] ) ) : '' );
        if ( '' !== $new_amount && '' === $amount ) {
            add_post_meta( $post_id, $meta_id, $new_amount, true );
        } elseif ( '' !== $new_amount && $new_amount !== $amount ) {
            update_post_meta( $post_id, $meta_id, $new_amount );
        } elseif ( '' === $new_amount && '' !== $amount ) {
            delete_post_meta( $post_id, $meta_id, $amount );
        }

    }

    /**
     * Display the table header for all the posts including all the added
     * ones for the custom post type.
     *
     * @param array $columns The array of column headers.
     * @return array The new array of column headers.
     */
    public function table_head( $columns ) {

        $newcols = array();

        // Want the selection box and title (name for our custom post type) first.
        $newcols['cb'] = $columns['cb'];
        unset( $columns['cb'] );
        $newcols['title'] = 'Name';
        unset( $columns['title'] );

        // Our custom meta data columns.
        $newcols['bisk-costs-amount']    = __( 'Amount', BISKConfig::TEXT_DOMAIN ); // phpcs:ignore
        $newcols['bisk-costs-shortcode'] = __( 'Shortcode', BISKConfig::TEXT_DOMAIN ); // phpcs:ignore

        // Want date last.
        unset( $columns['date'] );

        // Add all other selected columns.
        foreach ( $columns as $col => $title ) {
            $newcols[ $col ] = $title;
        }

        // Add the date back.
        $newcols['date'] = 'Date';

        return $newcols;
    }

    /**
     * Display the custom post data in the correct column
     *
     * @param string $column_name Name of the column.
     * @param int    $post_id     The ID of this post.
     */
    public function table_content( $column_name, $post_id ) {

        if ( 'bisk-costs-amount' === $column_name ) {
            $amount = esc_attr( get_post_meta( $post_id, 'bisk-costs-amount', true ) );
            $amount = '$' . $amount . '.00';
            echo $amount; // phpcs:ignore
        } else if ( 'bisk-costs-shortcode' === $column_name ) {
            $title     = strtolower( get_the_title( $post_id ) );
            $shortcode = str_replace( ' ', '_', $title );
            $shortcode = "[bisk_cost item='{$shortcode}']";
            echo $shortcode; // phpcs:ignore
        }
    }

}
