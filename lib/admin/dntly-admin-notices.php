<?php
/**
 * Admin Notices
 *
 * @package     EDD
 * @subpackage  Admin/Notices
 * @copyright   Copyright (c) 2013, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Messages
 *
 * @since 1.0
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function dntly_admin_messages() {
    global $dntly_settings;

    settings_errors( 'dntly-notices' );
}
add_action( 'admin_notices', 'dntly_admin_messages' );


/**
 * Dismisses admin notices when Dismiss links are clicked
 *
 * @since 1.8
 * @return void
*/
function dntly_dismiss_notices() {

    $notice = isset( $_GET['dntly_notice'] ) ? $_GET['dntly_notice'] : false;

    if( ! $notice )
        return; // No notice, so get out of here

    update_user_meta( get_current_user_id(), '_dntly_' . $notice . '_dismissed', 1 );

    wp_redirect( remove_query_arg( array( 'dntly_action', 'dntly_notice' ) ) ); exit;

}
add_action( 'dntly_dismiss_notices', 'dntly_dismiss_notices' );
