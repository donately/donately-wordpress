<?php
/**
 * System Info
 *
 * These are functions are used for exporting data from Easy Digital Downloads.
 *
 * @package     dntly
 * @subpackage  Admin/System
 * @copyright   Copyright (c) 2013, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * System info
 *
 * Shows the system info panel which contains version data and debug info.
 * The data for the system info is generated by the Browser class.
 *
 * @since 1.4
 * @global $wpdb
 * @global object $wpdb Used to query the database using the WordPress
 *   Database API
 * @global $dntly_settings Array of all the dntly Options
 * @author Chris Christoff
 * @return void
 */
function dntly_system_info() {
    global $wpdb, $dntly_settings;

    if ( ! class_exists( 'Browser' ) )
        require_once DNTLY_PLUGIN_DIR . 'lib/libraries/browser.php';

    $browser = new Browser();
    if ( get_bloginfo( 'version' ) < '3.4' ) {
        $theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
        $theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
    } else {
        $theme_data = wp_get_theme();
        $theme      = $theme_data->Name . ' ' . $theme_data->Version;
    }

    // Try to identifty the hosting provider
    $host = false;
    if( defined( 'WPE_APIKEY' ) ) {
        $host = 'WP Engine';
    } elseif( defined( 'PAGELYBIN' ) ) {
        $host = 'Pagely';
    }
?>
<style>
    #system-info-textarea {
        width: 800px;
        height: 400px;
        font-family: Menlo,Monaco,monospace;
        background: 0;
        white-space: pre;
        overflow: auto;
        display: block;
        }
</style>
    <div class="wrap">
        <h2><?php _e( 'System Information', 'dntly' ) ?></h2><br/>
        <p>This is the information we're receiving about your server. Copy and paste into a text editor when submitting a support request.</p>
        <form action="<?php echo esc_url( admin_url( 'admin.php?page=dntly-system-info' ) ); ?>" method="post" dir="ltr">
            <textarea readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="dntly-sysinfo" title="<?php _e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'dntly' ); ?>">
### Begin System Info ###

## Please include this information when posting support requests ##

<?php do_action( 'dntly_system_info_before' ); ?>

Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

SITE_URL:                 <?php echo site_url() . "\n"; ?>
HOME_URL:                 <?php echo home_url() . "\n"; ?>

Dontely Version:           <?php echo DNTLY_VERSION . "\n"; ?>
Upgraded From:            <?php echo get_option( 'dntly_version_upgraded_from', 'None' ) . "\n"; ?>
WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Active Theme:             <?php echo $theme . "\n"; ?>
<?php if( $host ) : ?>
Host:                     <?php echo $host . "\n"; ?>
<?php endif; ?>


Symlinks Enabled:         <?php echo apply_filters( 'dntly_symlink_file_downloads', isset( $dntly_settings['symlink_file_downloads'] ) ) && function_exists( 'symlink' ) ? "Yes\n" : "No\n"; ?>


Campaigns slug:           <?php echo defined( 'DNTLY_SLUG' ) ? '/' . DNTLY_SLUG . "\n" : "/campaigns\n"; ?>


Registered Post Stati:    <?php echo implode( ', ', get_post_stati() ) . "\n\n"; ?>

<?php echo $browser ; ?>

PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
MySQL Version:            <?php echo mysql_get_server_info() . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

PHP Safe Mode:            <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
PHP Upload Max Size:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
PHP Upload Max Filesize:  <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Time Limit:           <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
PHP Max Input Vars:       <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>

WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

WP Table Prefix:          <?php echo "Length: ". strlen( $wpdb->prefix ); echo " Status:"; if ( strlen( $wpdb->prefix )>16 ) {echo " ERROR: Too Long";} else {echo " Acceptable";} echo "\n"; ?>

Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n" ?>
Page On Front:            <?php $id = get_option( 'page_on_front' ); echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>
Page For Posts:           <?php $id = get_option( 'page_for_posts' ); echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>

<?php
$request['cmd'] = '_notify-validate';

$params = array(
    'sslverify'     => false,
    'timeout'       => 60,
    'user-agent'    => 'dntly/' . DNTLY_VERSION,
    'body'          => $request
);

$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
    $WP_REMOTE_POST =  __( 'wp_remote_post() works', 'dntly' ) . "\n";
} else {
    $WP_REMOTE_POST =  __( 'wp_remote_post() does not work', 'dntly' ) . "\n";
}
?>
WP Remote Post:           <?php echo $WP_REMOTE_POST; ?>

Session:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; ?><?php echo "\n"; ?>
Session Name:             <?php echo esc_html( ini_get( 'session.name' ) ); ?><?php echo "\n"; ?>
Cookie Path:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); ?><?php echo "\n"; ?>
Save Path:                <?php echo esc_html( ini_get( 'session.save_path' ) ); ?><?php echo "\n"; ?>
Use Cookies:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>
Use Only Cookies:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>

WordPress Memory Limit:   <?php echo "Unknown"; //( dntly_let_to_num( WP_MEMORY_LIMIT )/( 1024 ) )."MB"; ?><?php echo "\n"; ?>
DISPLAY ERRORS:           <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? __( 'Your server supports fsockopen.', 'dntly' ) : __( 'Your server does not support fsockopen.', 'dntly' ); ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? __( 'Your server supports cURL.', 'dntly' ) : __( 'Your server does not support cURL.', 'dntly' ); ?><?php echo "\n"; ?>
SOAP Client:              <?php echo ( class_exists( 'SoapClient' ) ) ? __( 'Your server has the SOAP Client enabled.', 'dntly' ) : __( 'Your server does not have the SOAP Client enabled.', 'dntly' ); ?><?php echo "\n"; ?>
SUHOSIN:                  <?php echo ( extension_loaded( 'suhosin' ) ) ? __( 'Your server has SUHOSIN installed.', 'dntly' ) : __( 'Your server does not have SUHOSIN installed.', 'dntly' ); ?><?php echo "\n"; ?>

ACTIVE PLUGINS:

<?php
$plugins = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ) {
    // If the plugin isn't active, don't show it.
    if ( ! in_array( $plugin_path, $active_plugins ) )
        continue;

    echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
}

if ( is_multisite() ) :
?>

NETWORK ACTIVE PLUGINS:

<?php
$plugins = wp_get_active_network_plugins();
$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

foreach ( $plugins as $plugin_path ) {
    $plugin_base = plugin_basename( $plugin_path );

    // If the plugin isn't active, don't show it.
    if ( ! array_key_exists( $plugin_base, $active_plugins ) )
        continue;

    $plugin = get_plugin_data( $plugin_path );

    echo $plugin['Name'] . ' :' . $plugin['Version'] ."\n";
}

endif;

do_action( 'dntly_system_info_after' );
?>
### End System Info ###</textarea>
            <p class="submit">
                <input type="hidden" name="dntly-action" value="download_sysinfo" />
                <?php submit_button( __( 'Download System Info File', 'dntly' ), 'primary', 'dntly-download-sysinfo', false ); ?>
            </p>
        </form>
        </div>
    </div>
<?php
}

/**
 * Generates the System Info Download File
 *
 * @since 1.4
 * @return void
 */
function dntly_generate_sysinfo_download() {
    nocache_headers();

    header( "Content-type: text/plain" );
    header( 'Content-Disposition: attachment; filename="dntly-system-info.txt"' );

    echo wp_strip_all_tags( $_POST['dntly-sysinfo'] );
    //wp_die();
}
add_action( 'dntly_download_sysinfo', 'dntly_generate_sysinfo_download' );
