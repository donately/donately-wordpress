<?php
/**
 * Admin Pages
 *
 * @package     Donately for WordPress
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates the admin submenu pages under the Downloads menu and assigns their
 * links to global variables
 *
 * @since 1.0
 * @global $dntly_settings_page
 * @return void
 */
function dntly_add_options_link() {

    global $dntly_settings_page;


   // $dntly_settings_page = add_submenu_page( 'edit.php?post_type=dntly_campaigns', __( 'Donately for WordPress Settings', 'dntly' ), __( 'Settings', 'dntly' ), 'inall_plugins', 'dntly-settings', 'dntly_settings_page' );

}
add_action( 'admin_menu', 'dntly_add_options_link', 10 );





/**
 * Creates the admin menu pages under Donately and assigns them their global variables
 *
 * @since  0.1
 * @global  $dntly_dashboard_page
 * @global  $dntly_settings_page
 * @global  $dntly_system_info_page
 * @return voic
 */
function dntly_add_menu_page() {
    global $dntly_dashboard_page, $dntly_settings_page, $dntly_system_info_page;

    require_once 'dntly-system-info.php';
     
    $dntly_top_level_page   = add_menu_page( __( 'Donately', 'dntly'), __( 'Donately', 'dntly' ), 'edit_pages', 'dntly', '', DNTLY_PLUGIN_URL . '/assets/images/campaigns.png', 27 ); 
    $dntly_dashboard_page   = add_submenu_page( 'dntly', __( 'Dashboard', 'dntly' ), __( 'Dashboard', 'dtly'), 'edit_pages', 'dntly-dashboard', 'dntly_dashboard_page' );
    $dntly_settings_page    = add_submenu_page( 'dntly', __( 'Donately Settings', 'dntly' ), __( 'Settings', 'dtly'), 'edit_pages', 'dntly-settings', 'dntly_settings_page' );
    $dntly_system_info_page = add_submenu_page( 'dntly', __( 'Donately System Info', 'dntly' ), __( 'System Info', 'dntly' ), 'install_plugins', 'dntly-system-info', 'dntly_system_info' );
    
}
add_action( 'admin_menu', 'dntly_add_menu_page', 10 );

