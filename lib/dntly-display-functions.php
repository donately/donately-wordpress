<?php
/**
 * Display Buttons
 *
 * @package     Donately
 * @subpackage  Display
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Display Donate Button
 *
 * Checks for single-dntly_campaigns and outputs a button after the_content()
 * 
 * @return [string]
 */
function dntly_display_donate_button( $content ) 
{
    global $post;

    if( is_singular( 'dntly_campaigns' ) ) {

        ob_start();

        dntly_donate_link();

        $content = $content . ob_get_clean();
    }

    return $content;
}
add_filter( 'the_content', 'dntly_display_donate_button' );     


/**
 * Render a Stats Bar
 *
 * This function check $dntly_settings to see if the user wants to show the stats bar.
 * @param  [type] $cid [description]
 * @return [type]      [description]
 */
function dntly_render_stats_bar( $cid )
{
  global $dntly_settings;

  $dntly_show_stats = isset( $dntly_settings['show_stats'] ) ? $dntly_settings['show_stats'] : false;
  if( $dntly_show_stats ) {

    $cid = ( isset( $_GET['cid'] ) ) ? $_GET['cid'] : dntly_get_donately_campaign_id();
    ob_start();
    ?>
    <div class="stats">
        <div class="dntly-box amount-raised">
            <strong>Raised:</strong> <?php echo dntly_get_amount_raised( $cid ); ?>
        </div>
        <div class="dntly-box campaign-goal">
            <strong>Goal:</strong> <?php echo dntly_get_campaign_goal( $cid ); ?>
        </div>
        <div class="dntly-box percent-funded">
            <strong>Percent Funded</strong> <?php echo dntly_get_percent_funded( $cid ); ?>
        </div>
    </div>
    <?php

    echo ob_get_clean();
  }
}
add_action('dntly_stats_bar', 'dntly_render_stats_bar');