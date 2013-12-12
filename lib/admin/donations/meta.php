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
        add_meta_box( 'campaigninfo', sprintf( __( '%1$s Configuration', 'edd' ), dntly_campaigns_get_label_singular(), dntly_campaigns_get_label_plural() ),  'dntly_render_campaigns_meta_box', $post_type, 'normal', 'default' );

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
            'dntly_account_id',
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
            delete_post_meta( $post_id, $field );
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

    $dntly_account_id      = get_post_meta( $post->ID, 'dntly_account_id', true );
    $dntly_campaign_id     = get_post_meta( $post->ID, 'dntly_campaign_id', true );
    $dntly_campaign_goal   = get_post_meta( $post->ID, 'dntly_campaign_goal', true );
    $dntly_amount_raised   = get_post_meta( $post->ID, 'dntly_amount_raised', true );
    $dntly_percent_funded  = get_post_meta( $post->ID, 'dntly_percent_funded', true );
    $dntly_donations_count = get_post_meta( $post->ID, 'dntly_donations_count', true );
    $dntly_donors_count    = get_post_meta( $post->ID, 'dntly_donors_count', true );


    ?>
    
    <p><strong><?php _e( 'Campaign Information', 'dntly' ); ?></strong></p>
    <p>
        <label for="dntly_account_id">
            <input type="text" name="dntly_account_id" id="dntly_account_id" value="<?php echo $dntly_account_id; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php if( $dntly_account_id == 0 ) : ?><strong style="color:red;">  Link your account</strong>.  <a href="<?php echo admin_url( add_query_arg( array( 'post_type' => 'download', 'page' => 'dntly-settings', 'tab' => 'general' ), 'admin.php' ) ); ?>">Settings</a>
            <?php else : ?>
            <?php _e( 'Donately Account ID', 'dntly' );  ?>
        <?php endif; ?>
        </label>
    </p>

    <p>
        <label for="dntly_campaign_id">
            <input type="text" name="dntly_campaign_id" id="dntly_campaign_id" value="<?php echo $dntly_campaign_id; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( 'Donately Campaign ID', 'dntly' );  ?>
        </label>
    </p>

    <p>
        <label for="dntly_campaign_goal">
            $<input type="text" name="dntly_campaign_goal" id="dntly_campaign_goal" value="<?php echo $dntly_campaign_goal; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( 'Campaign goal', 'dntly' );  ?>
        </label>
    </p>

    <p>
        <label for="dntly_amount_raised">
            $<input type="text" name="dntly_amount_raised" id="dntly_amount_raised" value="<?php echo $dntly_amount_raised; ?>"  style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( 'Amount raised', 'dntly' );  ?>
        </label>
    </p>

    <p>
        <label for="dntly_percent_funded">
            <input type="text" name="dntly_percent_funded" id="dntly_percent_funded" value="<?php echo $dntly_percent_funded; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( '% Percent funded', 'dntly' );  ?>
        </label>
    </p>

    <p>
        <label for="dntly_donations_count">
            <input type="text" name="dntly_donations_count" id="dntly_donations_count" value="<?php echo $dntly_donations_count; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( 'Donations count (the number of donations received).', 'dntly' );  ?>
        </label>
    </p>

    <p>
        <label for="dntly_donors_count">
            <input type="text" name="dntly_donors_count" id="dntly_donors_count" value="<?php echo $dntly_donors_count; ?>" style="width:80px; cursor:not-allowed;" readonly />
            <?php _e( 'Donors Count (the number of people who have donated to this campaign).', 'dntly' );  ?>
        </label>
    </p>

    <?php

}
add_action( 'dntly_meta_box_fields', 'dntly_render_campaign_fields', 10 );


function dntly_render_primer_fields()
{
    global $post, $dntly_settings;
    $dntly_campaign_primer = get_post_meta( $post->ID, 'dntly_campaign_primer', true );
    ?>
    <hr>
    <p><strong>Campaign Primer</strong></p>
    <textarea rows="3" cols="40" class="large-texarea" name="dntly_campaign_primer" id="dntly_campaign_primer"><?php echo esc_textarea( $dntly_campaign_primer ); ?></textarea>
    <p><?php _e( 'Special notes or instructions for this product. These notes will be added to the purchase receipt.', 'edd' ); ?></p>
    <?php
}
add_action( 'dntly_meta_box_fields', 'dntly_render_primer_fields', 15);
