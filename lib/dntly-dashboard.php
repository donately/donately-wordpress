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
                <a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-dashboard' ), 'admin.php' ) ) ); ?>">
                    <?php _e( "About Donately", 'dntly' ); ?>
                </a>
                <a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-credits' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Credits', 'dntly' ); ?>
                </a>
            </h2>

            <div class="changelog">
                <h3><?php _e( 'A Great Donation Experience', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/17checkout.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Simple, Donations', 'dntly' );?></h4>
                    <p><?php _e( 'When it comes to accepting donations, you need options. You need campaigns and fundraisers and secure donation forms. Donately was created to solve the problems all non-profits have without creating a ton of overhead.', 'dntly' );?></p>

                    <h4><?php _e( 'It\'s free to sign up!', 'dntly' );?></h4>
                    <p><?php _e( 'There are some transaction fees but you won\'t find a cheaper solution out there. Check out <a href="http://donate.ly">Donate.ly</a> for more information.', 'dntly' );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'The Platform', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18cart-saving.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'About the Plugin','dntly' );?></h4>
                    <p><?php _e( 'Donately is built on a powerful and flexible API. We built this plugin using the API to create a better experience for your donors. They can donate directly to your organization and campaigns without ever leaving your site!', 'dntly' );?></p>

                    <h4><?php _e( 'Loads of Settings', 'dntly' );?></h4>
                    <p><?php _e( 'We\'e given you a host of options, shortcodes and widgets. If you\'re a developer, you can find a ton of actions and filters to shape your experience. If you are interested in extending the plugin you can checkout our docs at <a href="http://support.donate.ly">http://support.donate.ly</a>.', 'dntly' );?></p>


                </div>
            </div>

             <div class="changelog">
                <h3><?php _e( 'Sign Up for Donately', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18cart-saving.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Create an Account','dntly' );?></h4>
                    <p><?php _e( 'Need an account? No worries.', 'dntly' );?></p>
                    <p><a href="http://dntly.com" class="button button-primary">Create an Account</a></p>

                    <h4><?php _e( 'Have an account already?', 'dntly' );?></h4>
                    <p><?php _e( 'Head over to our settings page and enter the details.', 'dntly' );?></p>
                    <p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>" class="button">Donately for WordPress Settings</a></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Additional Features', 'dntly' );?></h3>

                <div class="feature-section col three-col">
                    <div>
                        <h4><?php _e( 'Widget Ready', 'dntly' );?></h4>
                        <p><?php _e( 'Need a simple form in your sidebar? No problem, just drag and drop our widget and you are good to go.', 'dntly' );?></p>

                        <h4><?php _e( 'Shortcodes', 'dntly' );?></h4>
                        <p><?php _e( 'We have a number of shortcodes that output forms and donation statistics wherever you need them.', 'dntly' );?></p>
                    </div>

                    <div>
                        <h4><?php _e( 'Custom Forms', 'dntly' );?></h4>
                        <p><?php _e( 'You can enter your a URL for a custom css file and override the default styles.', 'dntly' );?></p>

                        <h4><?php _e( 'Campaigns', 'dntly' );?></h4>
                        <p><?php _e( 'Create campaigns on Dntly and sync them to WordPress. Keep your followers in the loop with your goals and other statistics. You can change the name from campaigns to anything.', 'dntly' );?></p>
                    </div>

                    <div class="last-feature">
                        <h4><?php _e( 'Email', 'dntly' );?></h4>
                        <p><?php _e( 'You can disable the email Donately sends and send your own from the settings panel.' ,'dntly' );?></p>

                        <h4><?php _e( 'Form Builder','dntly' );?></h4>
                        <p><?php _e( 'Sometimes you don\'t want to use the global settings. Create your form shortcode and use it wherever you need it.', 'dntly' );?></p>
                    </div>
                </div>
            </div>

            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to Donately for WordPress Settings', 'dntly' ); ?></a>
            </div>
        </div>


    <?php
    echo ob_get_clean();
}
