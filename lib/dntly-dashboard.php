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
            <div class="about-text"><?php printf( __( 'Thank you for updating to the latest version!', 'dntly' ), $display_version ); ?></div>
            <div class="edd-badge"><?php printf( __( 'Version %s', 'dntly' ), $display_version ); ?></div>

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-dashboard' ), 'admin.php' ) ) ); ?>">
                    <?php _e( "What's New", 'dntly' ); ?>
                </a><a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-credits' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Credits', 'dntly' ); ?>
                </a>
            </h2>

            <div class="changelog">
                <h3><?php _e( 'A Great Donation Experience', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/17checkout.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Simple, Donations', 'dntly' );?></h4>
                    <p><?php _e( 'We have worked tirelessly to continually improve the checkout experience of Donately for WordPress, and with just a few subtle tweaks, we have made the experience in Donately for WordPress version 1.8 even better than before.', 'dntly' );?></p>

                    <h4><?php _e( 'Better Checkout Layout', 'dntly' );?></h4>
                    <p><?php _e( 'The position of each field on the checkout has been carefully reconsidered to ensure it is in the proper location so as to best create high conversion rates.', 'dntly' );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Cart Saving', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18cart-saving.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Allow Customers to Save Their Carts for Later','dntly' );?></h4>
                    <p><?php _e( 'With Cart Saving, customers can save their shopping carts and then come back and restore them at a later point.', 'dntly' );?></p>

                    <h4><?php _e( 'Encourage Customers to Come Back', 'dntly' );?></h4>
                    <p><?php _e( 'By making it easier for customers to save their cart and return later, you can increase the conversion rate of the customers that need time to think about their purchase.', 'dntly' );?></p>


                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Better Purchase Button Colors', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18-button-colors.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Eight Button Colors', 'dntly' );?></h4>
                    <p><?php _e( 'With eight beautifully button colors to choose from, you will almost certainly find the color to match your site.', 'dntly' );?></p>

                    <h4><?php _e( 'Simpler; Cleaner', 'dntly' );?></h4>
                    <p><?php _e( 'Purchase buttons are cleaner, simpler, and just all around better with Donately for WordPress 1.8.', 'dntly' );?></p>
                    <p><?php _e( 'By simplifying one of the most important aspects of your digital store, we ensure better compatibility with more themes and easier customization for advanced users and developers.', 'dntly' );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Better APIs for Developers', 'dntly' );?></h3>

                <div class="feature-section">

                    <h4><?php _e( 'EDD_Payment_Stats','dntly' );?></h4>
                    <p><?php _e( 'The new EDD_Payment_Stats class provides a simple way to retrieve earnings and sales stats for the store, or any specific Download product, for any date range. Get sales or earnings for this month, last month, this year, or even any custom date range.', 'dntly' );?></p>

                    <h4><?php _e( 'EDD_Payments_Query', 'dntly' ); ?></h4>
                    <p><?php _e( 'Easily retrieve payment data for any Download product or the entire store. EDD_Payments_Query even allows you to pass in any date range to retrieve payments for a specific period. EDD_Payments_Query works nearly identical to WP_Query, so it is simple and familiar.', 'dntly' ); ?></p>

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
