<?php 
/**
 * Plugin Name: Donately for WordPress
 * Plugin URI: http://fiftyandfifty.org
 * Description: Utilize the processing power of Donately with the flexibility of WordPress for your organizations donations.
 * Version: 0.1
 * Author: Fifty and Fifty
 * Author URI: http://fiftyandfifty.org
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'DNTLY' ) ) :


/**
 * Main DNTLY Class
 *
 * @since 0.1 */
final class DNTLY {

  /**
   * @var DNTLY Instance
   * @since 0.1
   */
  private static $instance;


  /**
   * DNTLY Instance / Constructor
   *
   * Insures only one instance of DNTLY exists in memory at any one
   * time & prevents needing to define globals all over the place. 
   * Inspired by and credit to EDD.
   *
   * @since 0.1
   * @static
   * @uses DNTLY::setup_globals() Setup the globals needed
   * @uses DNTLY::includes() Include the required files
   * @uses DNTLY::setup_actions() Setup the hooks and actions
   * @see DNTLY()
   * @return void
   */
  public static function instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof DNTLY ) ) {
      self::$instance = new DNTLY;
      self::$instance->setup_constants();
      self::$instance->includes();
      // self::$instance->load_textdomain();
      // use @examples from public vars defined above upon implementation
    }
    return self::$instance;
  }



  /**
   * Setup plugin constants
   * @access private
   * @since 0.1 
   * @return void
   */
  private function setup_constants() {
    // Plugin version
    if ( ! defined( 'DNTLY_VERSION' ) )
      define( 'DNTLY_VERSION', '0.1' );

    // Plugin Folder Path
    if ( ! defined( 'DNTLY_PLUGIN_DIR' ) )
      define( 'DNTLY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    // Plugin Folder URL
    if ( ! defined( 'DNTLY_PLUGIN_URL' ) )
      define( 'DNTLY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    // Plugin Root File
    if ( ! defined( 'DNTLY_PLUGIN_FILE' ) )
      define( 'DNTLY_PLUGIN_FILE', __FILE__ );

    if ( ! defined( 'DNTLY_DEBUG' ) )
      define ( 'DNTLY_DEBUG', true );
  }



  /**
   * Include required files
   * @access private
   * @since 0.1
   * @return void
   */
  private function includes() {
    global $dntly_settings, $wp_version;

    require_once DNTLY_PLUGIN_DIR . 'lib/admin/settings/register-settings.php';
    $dntly_settings = dntly_get_settings();


    // CLASSES
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-api.class.php';

    // SCRIPTS
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-scripts.php';

    // PAGE TEMPLATES @TODO find solution to include basic page templates
    
    // PLUGIN REQUIRED INCLUDES
    require_once DNTLY_PLUGIN_DIR . '/lib/dntly-display-functions.php';
    require_once DNTLY_PLUGIN_DIR . '/lib/dntly-donatey-button.php';
    require_once DNTLY_PLUGIN_DIR . '/lib/donations/functions.php';

    // AJAX
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-ajax.php';

    // ADDITIONAL INCLUDES
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-formjs.php';
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-fields.php';
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-meta.php';
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-posttypes.php';
    require_once DNTLY_PLUGIN_DIR . 'lib/dntly-shortcodes.php';

    // admin-only includes
    if( is_admin() ) {

      require_once DNTLY_PLUGIN_DIR . 'lib/dntly-dashboard.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/admin-actions.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/dntly-admin-notices.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/dntly-admin-pages.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/dntly-dashboard-widgets.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/form-builder.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/settings/display-settings.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/plugins.php';
      require_once DNTLY_PLUGIN_DIR . 'lib/admin/dntly-welcome.php';
      
      if( version_compare( $wp_version, '3.6', '>=' ) ) {
        // require_once DNTLY_PLUGIN_DIR . 'lib/admin/dntly-heartbeat.php';
      }

    }else{

    }

    require_once DNTLY_PLUGIN_DIR . 'lib/install.php';

  }

} /* end DNTLY class */
endif; // End if class_exists check


/**
 * Main function for returning DNTLY Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $edd = EDD(); ?>
 *
 * @since 0.1
 * @return object The one true DNTLY Instance
 */
function DNTLY() {
  return DNTLY::instance();
}


/**
 * Initiate
 * Run the DNTLY() function, which runs the instance of the DNTLY class.
 */
DNTLY();



/**
 * Debugging
 * @since 0.1
 */
if ( DNTLY_DEBUG ) {
  ini_set('display_errors','On');
  error_reporting(E_ALL);
}



