<?php //Scripts

/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 1.0
 * @global $post
 * @global $pagenow
 * @global $edd_discounts_page
 * @global $edd_payments_page
 * @global $edd_settings_page
 * @global $edd_reports_page
 * @global $edd_system_info_page
 * @global $edd_add_ons_page
 * @global $edd_options
 * @global $edd_upgrades_screen
 * @param string $hook Page hook
 * @return void
 */
function dntly_load_admin_scripts() 
{

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    $js_dir  = DNTLY_PLUGIN_URL . 'assets/js/';  
    $css_dir = DNTLY_PLUGIN_URL . 'assets/css/';

    

    wp_enqueue_style( 'dntly-admin', $css_dir . 'dntly-admin' . $suffix . '.css', DNTLY_VERSION );
}
add_action( 'admin_enqueue_scripts', 'dntly_load_admin_scripts', 100 );