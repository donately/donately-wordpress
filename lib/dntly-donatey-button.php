<?php
/**
 * Donate Button Functions
 *
 * @package     Donately
 * @subpackage  Functions/Templates
 * @copyright   Copyright (c) 2013, Bryan Monzon
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Get Donate Link
 *
 * Builds a URL to create a dynamic donation link based on the campaign ID.
 * @param  [type] $campaign_id [description]
 * @return [type]              [description]
 */
function dntly_donate_link( $args = array() ) {
    global $post, $dntly_settings;


    $defaults = apply_filters( 'dntly_donation_link_defaults', array(
        'campaign_id' => $post->ID,
        'text'        => !empty( $dntly_settings[ 'donation_button_text' ] ) ? $dntly_settings[ 'donation_button_text' ] : __( 'Donation', 'dntly' ),
        'amount'      => isset( $args['amount'] ) ? $args['amount'] : 123,
        'class'       => 'button'
    ) );


    $args = wp_parse_args( $args, $defaults );


    if( isset( $args['campaign_id'] ) && isset( $args['amount'] ) ) {
        
        $append_url = '?cid=' . $args['campaign_id'] . '&amount=' . $args['amount'];

    }else{
        
        $append_url = '?cid=' . $args['campaign_id'];
    }
     
?>

    <div class="donation_button_wrapper">
        <?php 
        printf(
        '<a href="%2$s" class="%3$s">%4$s</a>',
        implode( ' ', array( trim( $args['class'] ), $args['text'] ) ),
        esc_url( dntly_get_donation_page_uri() . $append_url ),
        esc_attr( $args['class'] ),
        esc_attr( $args['text'] )
        )
        ?>
    </div>
<?php

    return apply_filters( 'dntly_donately_button', $args );
}

