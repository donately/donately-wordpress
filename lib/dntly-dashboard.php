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
                <a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-dashboard' ), 'admin.php' ) ) ); ?>">
                    <?php _e( "About Donately", 'dntly' ); ?>
                </a><a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-credits' ), 'index.php' ) ) ); ?>">
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
                <h3><?php _e( 'Additional Updates', 'dntly' );?></h3>

                <div class="feature-section col three-col">
                    <div>
                        <h4><?php _e( 'Retina Ready Checkout', 'dntly' );?></h4>
                        <p><?php _e( 'Every icon and image asset used by the Donately for WordPress checkout is now fully retina ready to ensure your most important page always looks great.', 'dntly' );?></p>

                        <h4><?php _e( 'Improved Settings API', 'dntly' );?></h4>
                        <p><?php _e( 'The EDD settings API has been dramatically simplified to be more performant, provide better filters, and even support custom settings tabs.', 'dntly' );?></p>
                    </div>

                    <div>
                        <h4><?php _e( 'Live Dashboard Updates', 'dntly' );?></h4>
                        <p><?php _e( 'The Dashboard summary widget now updates live with the WP Heartbeat API, meaning you can literally watch your stats update live as sales come in.', 'dntly' );?></p>

                        <h4><?php _e( 'Category Filters for Downloads Reports', 'dntly' );?></h4>
                        <p><?php _e( 'The Downloads Reports view now supports filtering Downloads by category, making it easier to see earnings and sales based on product categories.', 'dntly' );?></p>
                    </div>

                    <div class="last-feature">
                        <h4><?php _e( 'Tools Menu', 'dntly' );?></h4>
                        <p><?php _e( 'A new Tools submenu has been added to the main Downloads menu that houses settings import / export, as well as other utilities added by extensions.' ,'dntly' );?></p>

                        <h4><?php _e( 'Bulk Payment History Update','dntly' );?></h4>
                        <p><?php _e( 'The bulk update options for Payments have been updated to include all payment status options, making it easier to manage payment updates in bulk.', 'dntly' );?></p>
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
