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

    /**
     * Register Styles & Scripts
     * @since 0.1
     */
    // Styles
    wp_register_style( 'dntly-admin', $css_dir . 'dntly-admin' . $suffix . '.css', DNTLY_VERSION );
    // Scripts
    wp_register_script( 'dntly-back', $js_dir . 'dntly-back.js', array('jquery'), DNTLY_VERSION, true );
    wp_register_script( 'dntly-media', $js_dir . 'admin-upload.js', array('jquery'), DNTLY_VERSION, true );



    /**
     * Enqueue Styles & Scripts
     * @since 0.1
     */
    // Styles
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_style( 'dntly-admin' );
    // Scripts
    wp_enqueue_script( 'dntly-back' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'dntly-media' );
}
add_action( 'admin_enqueue_scripts', 'dntly_load_admin_scripts', 100 );


function dntly_load_front_end_styles()
{
    $css_dir = DNTLY_PLUGIN_URL . 'assets/css/';

    wp_register_style( 'dntly-styles', $css_dir . 'dntly.css', DNTLY_VERSION );

    wp_enqueue_style('dntly-styles' );

}
add_action( 'wp_enqueue_scripts', 'dntly_load_front_end_styles' );