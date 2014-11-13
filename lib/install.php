<?php
/**
 * Install Function
 *
 * @package     Donately for WordPress
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2013, Fifty & Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new 'campaigns' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successful install, the user is redirected to the DNTLY Welcome
 * screen.
 *
 * @since 1.0
 * @global $wpdb
 * @global $dntly_settings
 * @global $wp_version
 * @return void
 */
function dntly_install() {
    global $wpdb, $dntly_settings, $wp_version;

    // Setup the Downloads Custom Post Type
    setup_dntly_campaign_post_types();

    // Setup the Download Taxonomies
    dntly_campaigns_setup_taxonomies();

    // Clear the permalinks
    flush_rewrite_rules();

    // Add Upgraded From Option
    $current_version = get_option( 'dntly_version' );
    if ( $current_version )
        update_option( 'dntly_version_upgraded_from', $current_version );

    // Checks if the donation page option exists
    if ( ! isset( $dntly_settings['donation_page'] ) ) {
        // Checkout Page
        $donation = wp_insert_post(
            array(
                'post_title'     => __( 'Donation', 'dntly' ),
                'post_content'   => '[donately_form]',
                'post_status'    => 'publish',
                'post_author'    => 1,
                'post_type'      => 'page',
                'comment_status' => 'closed'
            )
        );

    // Store our page IDs
        $options = array(
            'donation_page' => $donation
        );

        update_option( 'dntly_settings', $options );
        update_option( 'dntly_version', DNTLY_VERSION );

        $activation_pages = $options;
        set_transient( '_dntly_activation_pages', $activation_pages, 30 );

    }



    // Bail if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
        return;

    // Add the transient to redirect
    set_transient( '_dntly_activation_redirect', true, 30 );
}
register_activation_hook( DNTLY_PLUGIN_FILE, 'dntly_install' );

/**
 * Post-installation
 *
 * Runs just after plugin installation and exposes the
 * dntly_after_install hook.
 *
 * @since 0.1
 * @return void
 */
function dntly_after_install() {

    if( ! is_admin() )
        return;

    $activation_pages = get_transient( '_dntly_activation_pages' );

    // Exit if not in admin or the transient doesn't exist
    if ( false === $activation_pages )
        return;

    // Delete the transient
    delete_transient( '_dntly_activation_pages' );

    do_action( 'dntly_after_install', $activation_pages );
}
add_action( 'admin_init', 'dntly_after_install' );