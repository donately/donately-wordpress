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
 * @todo use get_template_directory_uri() instead of plugin_dir_url() ?
 */
define( 'DNTLY_VERSION', '0.1');

define( $PREFIX . 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );                   // DNTLY_PLUGIN_URL
define( $PREFIX . 'PLUGIN_PATH', plugin_dir_url( __FILE__ ) );                  // DNTLY_PLUGIN_PATH
define( $PREFIX . 'PLUGIN_BASENAME', plugin_dir_url( __FILE__ ) );              // DNTLY_PLUGIN_BASENAME
define( $PREFIX . 'JS_DIR', plugin_dir_url( __FILE__ ).'/assets/js' );          // DNTLY_JS_DIR
define( $PREFIX . 'IMG_DIR', plugin_dir_url( __FILE__ ).'/assets/images' );     // DNTLY_IMG_DIR
define( $PREFIX . 'CSS_DIR', plugin_dir_url( __FILE__ ).'/assets/css' );        // DNTLY_CSS_DIR
define( $PREFIX . 'VENDOR_DIR', plugin_dir_url( __FILE__ ).'/assets/vendor' );  // DNTLY_VENDOR_DIR


/**
 * Load Options Framework
 * @since 0.1
 */
if ( !function_exists( 'optionsframework_init' ) ) 
{
  define( 'OPTIONSFRAME_DIR', DNTLY_VENDOR_DIR . '/options-framework/' );
  require_once ( DNTLY_VENDOR_DIR . '/options-framework/options-framework.php' );
}


/* =============================================================================
   D E B U G G I N G             ( php error reporting and dntly debug logging )
================================================================================ */
/**
 * Debugging
 * @since 0.1
 */
if( !defined('DNTLY_DEBUG') ) {
  define('DNTLY_DEBUG', false);
  /* set to true for testing/debugging in development & staging environments */
}
if ( $DEBUG ) {
  ini_set('display_errors','On');
  error_reporting(E_ALL);
  define('WP_USE_THEMES', false);
}


/* =============================================================================
   S E T U P                                               ( requires/includes )
================================================================================ */
require_once ( DNTLY_VENDOR_DIR . '/options-framework/options-framework.php' );







