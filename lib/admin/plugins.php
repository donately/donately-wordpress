<?php
/**
 * Admin Plugins
 *
 * @package     Donately
 * @subpackage  Admin/Plugins
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add a URL to the Plugin Actions menu
 * 
 * @param array $links already defined action links
 * @param string $file plugin file path and name being processed
 * @return [array] a new settings link
 */
function dntly_plugin_action_links( $links, $file ) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=dntly-settings' ) . '">' . esc_html__( 'Settings', 'dntly' ) . '</a>';
    if ( $file == 'donately-wordpress/dntly.php' )
        array_unshift( $links, $settings_link );

    return $links;
}
add_filter( 'plugin_action_links', 'dntly_plugin_action_links', 10, 2 );


/**
 * Plugin row meta links
 *
 * @since 1.8
 * @param array $input already defined meta links
 * @param string $file plugin file path and name being processed
 * @return array $input
 */
function dntly_plugin_row_meta( $input, $file ) {
    if ( $file != 'donately-wordpress/dntly.php' )
        return $input;

    $links = array(
        '<a href="https://www.dntly.com/a#/npo/signup">' . esc_html__( 'Non-Profit Sign Up', 'dntly' ) . '</a>',
        '<a href="http://support.donate.ly">' . esc_html__( 'Support', 'dntly' ) . '</a>',
    );

    $input = array_merge( $input, $links );

    return $input;
}
add_filter( 'plugin_row_meta', 'dntly_plugin_row_meta', 10, 2 );