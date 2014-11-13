<?php
/**
 * Metabox Functions
 *
 * @package     Donately for WordPress
 * @subpackage  Admin/Downloads
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** All Downloads *****************************************************************/

/**
 * Register all the meta boxes for the Download custom post type
 *
 * @since 1.0
 * @return void
 */
function dntly_add_campaigns_meta_box() {

    $post_types = apply_filters( 'dntly_campaigns_metabox_post_types' , array( 'dntly_campaigns' ) );

    foreach ( $post_types as $post_type ) {

        /** Download Configuration */
        add_meta_box( 'campaigninfo', sprintf( __( '%1$s Information', 'edd' ), dntly_campaigns_get_label_singular(), dntly_campaigns_get_label_plural() ),  'dntly_render_campaigns_meta_box', $post_type, 'normal', 'default' );

    }
}
add_action( 'add_meta_boxes', 'dntly_add_campaigns_meta_box' );


/**
 * Sabe post meta when the save_post action is called
 *
 * @since 1.0
 * @param int $post_id Download (Post) ID
 * @global array $post All the data of the the current post
 * @return void
 */
function dntly_campaigns_meta_box_save( $post_id) {
    global $post, $dntly_settings;

    if ( ! isset( $_POST['dntly_campaigns_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['dntly_campaigns_meta_box_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) )
        return $post_id;

    if ( isset( $post->post_type ) && $post->post_type == 'revision' )
        return $post_id;


    // The default fields that get saved
    $fields = apply_filters( 'dntly_metabox_fields_save', array(
            'dntly_campaign_id',
            'dntly_campaign_goal',
            'dntly_amount_raised',
            'dntly_percent_funded',
            'dntly_donations_count',
            'dntly_donors_count',
            'dntly_campaign_primer'
        )
    );


    foreach ( $fields as $field ) {
        if ( ! empty( $_POST[ $field ] ) ) {
            $new = apply_filters( 'dntly_metabox_save_' . $field, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $new );
        } else {
            //delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'dntly_campaigns_meta_box_save' );



/** Campaign Configuration *****************************************************************/

/**
 * Campaign Metabox
 *
 * Extensions (as well as the core plugin) can add items to the main download
 * configuration metabox via the `edd_meta_box_fields` action.
 *
 * @since 1.0
 * @return void
 */
function dntly_render_campaigns_meta_box() {
    global $post, $dntly_settings;

    do_action( 'dntly_meta_box_fields', $post->ID );
    wp_nonce_field( basename( __FILE__ ), 'dntly_campaigns_meta_box_nonce' );
}



function dntly_render_campaign_fields( $post )
{
    global $post, $dntly_settings;

    $dntly_campaign_id     = get_post_meta( $post->ID, 'dntly_campaign_id', true );
    $dntly_campaign_goal   = get_post_meta( $post->ID, 'dntly_campaign_goal', true );
    $dntly_amount_raised   = get_post_meta( $post->ID, 'dntly_amount_raised', true );
    $dntly_percent_funded  = get_post_meta( $post->ID, 'dntly_percent_funded', true );
    $dntly_donations_count = get_post_meta( $post->ID, 'dntly_donations_count', true );
    $dntly_donors_count    = get_post_meta( $post->ID, 'dntly_donors_count', true );


    ?>
    
    <div class="donately_information_metabox">
        <p>Your last update was December 10, 2013 4:32pm</p>
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2">Donately Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php dntly_the_subdomain(); ?>
                    <td><strong><?php _e( 'Campaign ID', 'dntly' );  ?></strong></td>
                    <td><?php echo $dntly_campaign_id; ?> - <em class="hint"><?php _e( 'This is the campaign ID from Donately', 'dntly' ); ?></em></td>
                </tr>

                <tr>
                    <td><strong><?php _e( 'Campaign Goal', 'dntly' );  ?></strong></td>
                    <td><?php echo '$' . number_format( $dntly_campaign_goal ); ?></td>
                </tr>

                <tr>
                    <td><strong><?php _e( 'Amount Raised', 'dntly' );  ?></strong></td>
                    <td><?php echo '$' . number_format( $dntly_amount_raised ); ?></td>
                </tr>

                <tr>
                    <td><strong><?php _e( 'Percent Funded', 'dntly' );  ?></strong></td>
                    <td><?php echo round((float)$dntly_percent_funded * 100 ) . '%'; ?></td>
                </tr>

                <tr>
                    <td><strong><?php _e( 'Donations Count', 'dntly' );  ?></strong></td>
                    <td><?php echo $dntly_donations_count; ?> <em class="hint"><?php _e( 'total donations', 'dntly'); ?></em></td>
                </tr>

                <tr>
                    <td><strong><?php _e( 'Donor Count', 'dntly' );  ?></strong></td>
                    <td><?php echo $dntly_donors_count; ?> <em class="hint">people have contributed to <?php echo get_the_title($post->ID); ?></em></td>
                </tr>
                <?php do_action('after_campaign_rows_metabox'); ?>
            </tbody>
        </table>
    </div>


    <?php

}
add_action( 'dntly_meta_box_fields', 'dntly_render_campaign_fields', 10 );


function dntly_meta_box_footer_fields()
{
    global $post, $dntly_settings;
    $dntly_campaign_primer = get_post_meta( $post->ID, 'dntly_campaign_primer', true );
    ?>
    
    <h4>Thanks for using Donately and Donately for WordPress</h4>
    <p>When using this plugin, you're taking advantage of WordPress' flexibility and the processing power of Donately. If you have any questions about this plugin and/or Donately please shoot us an email and we'll do our best to support your needs. </p>
    <p><a href="mailto:support@donate.ly&subject=Donately+for+WordPress" class="button button-secondary">Email Support</a></p>

    <?php

    do_action( 'dntly_meta_box_after_footer');
}
add_action( 'dntly_meta_box_fields', 'dntly_meta_box_footer_fields', 100);