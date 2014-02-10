<?php
/**
 * Dashboard Widgets
 *
 * @package     Donately for WordPress
 * @subpackage  Admin/Dashboard
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers the dashboard widgets
 *
 * @author Sunny Ratilal
 * @since 1.2.2
 * @return void
 */
function dntly_register_dashboard_widgets() {
    if ( current_user_can( apply_filters( 'dntly_dashboard_stats_cap', 'edit_pages' ) ) ) {
        wp_add_dashboard_widget( 'dntly_dashboard_stats', __('Donately for WordPress stats Summary', 'dntly'), 'dntly_dashboard_stats_widget' );
    }
}
//add_action('wp_dashboard_setup', 'dntly_register_dashboard_widgets', 10 );

/**
 * Sales Summary Dashboard Widget
 *
 * Builds and renders the Sales Summary dashboard widget. This widget displays
 * the current month's sales and earnings, total sales and earnings best selling
 * downloads as well as recent purchases made on your dntly Store.
 *
 * @author Sunny Ratilal
 * @since 1.2.2
 * @return void
 */
function dntly_dashboard_stats_widget() { ?>

    <div class="dntly_dashboard_widget">
        <div class="table table_left table_current_month">
            <p class="sub"><?php _e( 'Current Month', 'dnlty' ) ?></p>
            <table>
                <tbody>
                    <tr class="first">
                        <td class="first b b-earnings">$1,523.55</td>
                        <td class="t monthly_earnings"><?php _e( 'Earnings', 'dntly' ); ?></td>
                    </tr>
                    <tr>
                        <td class="first b b-sales">23</td>
                        <td class="t monthly_sales"><?php _e( 'Donations', 'dntly' ); ?></td>
                    </tr>
                </tbody>
            </table>
            <p class="label_heading"><?php _e( 'Last Month', 'dntly' ) ?></p>
            <div>
                <?php echo __( 'Donations', 'dntly' ) . ':&nbsp;<span class="price_label">$597.75</span>'; ?>
            </div>
            <div>
                <?php echo __( 'Donors', 'dntly' ) . ':&nbsp;<span class="price_label">45</span>'; ?>
            </div>
        </div>
        <div class="table table_right table_totals">
            <p class="sub"><?php _e( 'Totals', 'dntly' ) ?></p>
            <table>
                <tbody>
                    <tr class="first">
                        <td class="b b-earnings">$3,034.52</td>
                        <td class="last t earnings"><?php _e( 'Total Donations', 'dntly' ); ?></td>
                    </tr>
                    <tr>
                        <td class="b b-sales">156</td>
                        <td class="last t sales"><?php _e( 'Total Donors', 'dntly' ); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php
            
            /* BEST CAMPAIGN STATS HERE? @TODO
            $best_selling = $stats->get_best_selling( 1 );
            if ( ! empty( $best_selling ) ) {
                foreach( $best_selling as $top_seller ) { ?>
                    <p class="lifetime_best_selling label_heading"><?php _e('Lifetime Best Selling', 'dntly') ?></p>
                    <p><span class="lifetime_best_selling_label"><?php echo $top_seller->sales; ?></span> <a href="<?php echo get_permalink( $top_seller->download_id ); ?>"><?php echo get_the_title( $top_seller->download_id ); ?></a></p>
            <?php } } */ ?>
        </div>
        <div style="clear: both"></div>
    </div>
    <?php
}