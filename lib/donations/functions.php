<?php
/**
 * Donation Button Functions
 *
 * @package     Donately
 * @subpackage  Cart
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


/**
 * Get the URL of the Checkout page
 *
 * @since 1.0.8
 * @global $dntly_settings Array of all the EDD Options
 * @param array $args Extra query args to add to the URI
 * @return mixed Full URL to the checkout page, if present | null if it doesn't exist
 */
function dntly_get_donation_page_uri( $args = array() ) {
    global $dntly_settings;

    $uri = isset( $dntly_settings['donation_page'] ) ? get_permalink( $dntly_settings['donation_page'] ) : NULL;

    if ( ! empty( $args ) ) {
        // Check for backward compatibility
        if ( is_string( $args ) )
            $args = str_replace( '?', '', $args );

        $args = wp_parse_args( $args );

        $uri = add_query_arg( $args, $uri );
    }

    $scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

    $ajax_url = admin_url( 'admin-ajax.php', $scheme );

    if ( ! preg_match( '/^https/', $uri ) && preg_match( '/^https/', $ajax_url ) ) {
        $uri = preg_replace( '/^http/', 'https', $uri );
    }


    return apply_filters( 'dntly_get_donation_page_uri', $uri );
}
