<?php 
/**
 * Plugin Name: Donately for WordPress
 * Plugin URI: http://fiftyandfifty.org
 * Description: Utilize the processing power of Donately with the flexibility of WordPress for your organizations donations.
 * Version: 0.1
 * Author: Fifty and Fifty
 * Author URI: http://fiftyandfifty.org
 */


/* =============================================================================
   D E F I N I T I O N S  &   G L O B A L  V A R S
================================================================================ */
/**
 * Global Variables
 * @since 0.1
 */
$PREFIX = 'DNTLY_';
$DEBUG  = true;

/**
 * Definitions
 * @since 0.1
 */
define( $PREFIX . 'JS_DIR', get_template_directory_uri().'/assets/js' );          // DNTLY_JS_DIR
define( $PREFIX . 'IMG_DIR', get_template_directory_uri().'/assets/images' );     // DNTLY_IMG_DIR
define( $PREFIX . 'CSS_DIR', get_template_directory_uri().'/assets/css' );        // DNTLY_CSS_DIR
define( $PREFIX . 'VENDOR_DIR', get_template_directory_uri().'/assets/vendor' );  // DNTLY_VENDOR_DIR



/* =============================================================================
   S E T U P  & T O G G L E S
================================================================================ */

/**
 * Load Options Framework
 * @since 0.1
 */
if ( !function_exists( 'optionsframework_init' ) ) 
{
  define( 'OPTIONSFRAME_DIR', DNTLY_VENDOR_DIR . '/options-framework/' );
  require_once ( DNTLY_VENDOR_DIR . '/options-framework/options-framework.php' );
}

/**
 * Debugging
 * @since 0.1
 */
if ( $DEBUG ) {
  ini_set('display_errors','On');
  error_reporting(E_ALL);
  define('WP_USE_THEMES', false);
}