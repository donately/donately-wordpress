<?php
/**
 * Donation Button Functions
 *
 * @package     Donately
 * @subpackage  Donation
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


/**
 * Get the URL of the Checkout page
 *
 * @since 1.0.8
 * @global $dntly_settings Array of all the Dntly Options
 * @param array $args Extra query args to add to the URI
 * @return mixed Full URL to the checkout page, if present | null if it doesn't exist
 */
function dntly_get_donation_page_uri( $args = array() ) {
    global $dntly_settings;

    $uri = isset( $dntly_settings['donation_page'] ) ? get_permalink( $dntly_settings['donation_page'] ) : NULL;

    if ( ! empty( $args ) ) {
        // Check for backward compatibility
        if ( is_string( $args ) )
            $args = str_replace( '?', '', $args );

        $args = wp_parse_args( $args );

        $uri = add_query_arg( $args, $uri );
    }

    $scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

    $ajax_url = admin_url( 'admin-ajax.php', $scheme );

    if ( ! preg_match( '/^https/', $uri ) && preg_match( '/^https/', $ajax_url ) ) {
        $uri = preg_replace( '/^http/', 'https', $uri );
    }


    return apply_filters( 'dntly_get_donation_page_uri', $uri );
}




/**
 * Output Donation Form
 *
 * @since  1.0
 * @param  [type] $post [description]
 * @return [type]       [description]
 */
function donately_form( $post_id)
{
    global $post, $dntly_settings;

    $dntly = new DNTLY_API;
    $form_js_url = 'http://'. $dntly->api_subdomain . '.dntly.com' . '/assets/js/v1/form.js';  //Needs to be refactored to included environment variables.

    $cid = ( isset( $_GET['cid'] ) ) ? $_GET['cid'] : dntly_get_campaign_id();
    ob_start();
    ?>
    <ul>
        <li>Dntly Form JS: <strong><?php echo $form_js_url; ?></strong></li>
        <li>Account ID: <strong><?php echo dntly_get_account_id( $cid ); ?></strong></li>
        <li>Campaign ID: <strong><?php echo $cid; ?></strong></li>
        <li>Campagin Goal: <strong><?php echo dntly_get_campaign_goal(); ?></strong></li>
        <li>Campaign Raised: <strong><?php echo dntly_get_amount_raised(); ?></strong></li>
        <li>Percent Funded: <strong><?php echo dntly_get_percent_funded(); ?></strong></li>
    </ul>
    
   <script class="donately-formjs" src='<?php echo $form_js_url; ?>' type='text/javascript' async='async'
      data-donately-id='<?php //echo dntly_get_account_id(); ?>198'
      data-donately-campaign-id='<?php echo $cid; ?>'
     ></script>
    <?php
    $dntly_form = ob_get_clean();
    echo $dntly_form;

}


function dntly_get_campaign_id()
{
    global $post;

    $dntly_campaign_meta = get_post_meta( $post->ID, 'dntly_campaign_id', true );

    return $dntly_campaign_meta;
}

function dntly_get_account_id()
{
    global $post;

    $dntly_account_id = get_post_meta( $post->ID, 'dntly_account_id', true );

    return $dntly_account_id;
}

function dntly_get_campaign_goal()
{
    global $post;

    $dntly_campaign_goal = get_post_meta( $post->ID, 'dntly_campaign_goal', true );

    return '$'.number_format( $dntly_campaign_goal );
}

function dntly_get_amount_raised()
{
    global $post;

    $dntly_amount_raised = get_post_meta( $post->ID, 'dntly_amount_raised', true );
    $dntly_amount_raised = isset( $dntly_amount_raised ) ? '$' . $dntly_amount_raised : 0;

    return $dntly_amount_raised;
}

function dntly_get_percent_funded()
{
    global $post;

    $dntly_percent_funded = get_post_meta( $post->ID, 'dntly_percent_funded', true );
    $dntly_percent_funded = round((float)$dntly_percent_funded * 100 ) . '%';

    return $dntly_percent_funded;
}



