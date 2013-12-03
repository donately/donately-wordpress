<?php
/**
 * Admin Dashboard Page
 *
 * @package     Donately for WordPress
 * @subpackage  Admin
 * @copyright   Copyright (c) 2013, Fifty & Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 1.0
 * @global $dntly_settings Array of all the EDD Options
 * @return void
 */
function dntly_dashboard_page() 
{

    global $dntly_settings;
    list( $display_version ) = explode( '-', DNTLY_VERSION );
    ob_start();
    ?>


    <div class="wrap about-wrap">
            <h1><?php printf( __( 'Donately for WordPress %s', 'dntly' ), $display_version ); ?></h1>
            <div class="about-text"><?php printf( __( 'An integrated donation platform.', 'dntly' ), $display_version ); ?></div>
            <div class="edd-badge"><?php printf( __( 'Version %s', 'dntly' ), $display_version ); ?></div>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'dntly-dashboard', 'admin.php' ) ) ); ?>" class="nav-tab nav-tab-active">
                    <?php _e( 'Dashboard', 'dntly') ?>
                </a>
                <a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-about' ), 'admin.php' ) ) ); ?>">
                    <?php _e( "About Donately", 'dntly' ); ?>
                </a>
                <a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-credits' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Credits', 'dntly' ); ?>
                </a>
            </h2>

            <div class="changelog">
                <h3><?php _e( 'The Latest with you account', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/17checkout.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( '$2,450.00 Donated in the last 30 days', 'dntly' );?></h4>
                    <p><?php _e( 'Your best campaign has been <strong><a href="#">Wells for Africa</a></strong>', 'dntly' );?></p>

                    <h4><?php _e( 'A graph of your last 30 days', 'dntly' );?></h4>
                    <p><?php _e( 'There\'s nothing here yet because there isn\'t any data to track right now.', 'dntly' );?></p>

                </div>
            </div>

        

            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to Donately for WordPress Settings', 'dntly' ); ?></a>
            </div>
        </div>


    <?php
    echo ob_get_clean();
}
