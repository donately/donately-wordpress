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
 * Output donation form.
 *
 * Checks for global form setttings and then checks the args set in the function
 * and then outputs the result.
 * @since  1.0
 * @author  Bryan Monzon
 * @param  array  $args accepts a series of arguments
 * @return [type]       [description]
 * @todo  Add some actions & filter hooks to allow extensibility.
 */
function donately_form( $args=array() )
{
    global $post, $dntly_settings;

    $dntly = new DNTLY_API;
    $form_js_url = 'http://'. $dntly->api_subdomain . '.dntly.com' . '/assets/js/v1/form.js';  //Needs to be refactored to included environment variables.

    //Debugging
    // print '<pre>';
    // print_r( $dntly_settings );
    // print '</pre>';
    

    /**
     * Condtionals checking $dntly_settings first, then $args. We'll need
     * this in the shortcode as well.
     * @since  1.0
     * @author  Bryan Monzon <[email]>
     * @todo  We should refactor these conditionals
     */
    //Set $show_address
    if( isset( $dntly_settings['donately_address'] ) && !isset( $args['show_address'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $show_address = $dntly_settings['donately_address'];
    
    }elseif( isset( $args['show_address'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $show_address = $args['show_address'];
    }else{
       //If neither are set, set it to false
        $show_address = false;
    }


    //Set $show_phone
    if( isset( $dntly_settings['donately_phone'] ) && !isset( $args['show_phone'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $show_phone = $dntly_settings['donately_phone'];
    
    }elseif( isset( $args['show_phone'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $show_phone = $args['show_phone'];

    }else{
        //If neither are set, set it to false
        $show_phone = false;
    }


    //Set $show_comments
    if( isset( $dntly_settings['donately_comments'] ) && !isset( $args['show_comments'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $show_comments = $dntly_settings['donately_comments'];
    
    }elseif( isset( $args['show_comments'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $show_comments = $args['show_comments'];
    }else{
        //If neither are set, set it to false
        $show_comments = false;
    }


    //Set $show_onbehalf
    if( isset( $dntly_settings['donately_onbehalf'] ) && !isset( $args['show_onbehalf'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $show_onbehalf = $dntly_settings['donately_onbehalf'];
    
    }elseif( isset( $args['show_onbehalf'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $show_onbehalf = $args['show_onbehalf'];

    }else{
        //If neither are set, set it to false
        $show_onbehalf = false;
    }


    //Set $show_anonymous
    if( isset( $dntly_settings['donately_anonymous'] ) && !isset( $args['show_anonymous'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $show_anonymous = $dntly_settings['donately_anonymous'];
    
    }elseif( isset( $args['show_anonymous'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $show_anonymous = $args['show_anonymous'];

    }else{
        //If neither are set, set it to false
        $show_anonymous = false;
    }


    //Set $amount
    if( isset( $dntly_settings['donately_amount'] ) && !isset( $args['amount'] ) ) {
        //If $dntly_settings is set but $args is not, use $dntly_settings
        $amount = $dntly_settings['donately_amount'];
    
    }elseif( isset( $args['amount'] ) ) {
        // If $args is set, this overrides $dntly_settings
        $amount = $args['amount'];

    }else{
        //If neither are set, set it to false
        $amount = 0;
    }



    //Set Donately Variables
    //$show_address   = isset( $args['show_address'] ) ? $args['show_address'] : false;
    //$show_phone     = isset( $args['show_phone'] ) ? $args['show_phone'] : false;
    //$show_comments  = isset( $args['show_comments'] ) ? $args['show_comments'] : false;
    //$show_onbehalf  = isset( $args['show_onbehalf'] ) ? $args['show_onbehalf'] : false;
    //$show_anonymous = isset( $args['show_anonymous'] ) ? $args['show_anonymous'] : false;
    //$amount         = isset( $args['amount'] ) ? $args['amount'] : 0;

    $cid = ( isset( $_GET['cid'] ) ) ? $_GET['cid'] : dntly_get_donately_campaign_id();

    //Check to see if the campaign ID is set. this is temporary
    if( $cid ) {

        //Start the Output Buffer
        ob_start();
        ?>
        <ul>
            <li>Title: <strong><?php echo get_the_title( $cid ); ?></strong></li>
            <li>Dntly Form JS: <strong><?php echo $form_js_url; ?></strong></li>
            <li>Account ID: <strong><?php echo dntly_get_account_id( $cid ); ?></strong></li>
            <li>Campaign ID: <strong><?php echo dntly_get_donately_campaign_id( $cid ); ?></strong></li>
            <li>Campagin Goal: <strong><?php echo dntly_get_campaign_goal( $cid ); ?></strong></li>
            <li>Campaign Raised: <strong><?php echo dntly_get_amount_raised( $cid ); ?></strong></li>
            <li>Percent Funded: <strong><?php echo dntly_get_percent_funded( $cid ); ?></strong></li>
        </ul>
        
       <div class="campaign alert">
        You're donating to <a href="<?php echo get_permalink( $cid ); ?>"><?php echo get_the_title( $cid ); ?></a>
       </div> 

       <script class="donately-formjs" src='<?php echo $form_js_url; ?>' type='text/javascript' async='async'
          data-donately-id='<?php //echo dntly_get_account_id(); ?>198'
          data-donately-campaign-id='<?php echo $cid; ?>' 
          data-donately-address="<?php echo $show_address; ?>" 
          data-donately-phone="<?php echo $show_phone; ?>" 
          data-donately-comments="<?php echo $show_comments; ?>" 
          data-donately-onbehalf="<?php echo $show_onbehalf; ?>" 
          data-donately-anonymous="<?php echo $show_anonymous; ?>" 
          data-donately-amount="<?php echo $amount; ?>"
         ></script>
        <?php
        //Set the ob_get_clean to a variable
        $dntly_form = ob_get_clean();

    }else{
        //If campaign ID isn't set
         ob_start();
         ?>
         
        <script class="donately-formjs" src='<?php echo $form_js_url; ?>' type='text/javascript' async='async'
           data-donately-id='<?php //echo dntly_get_account_id(); ?>198' 
           data-donately-address="<?php echo $show_address; ?>" 
           data-donately-phone="<?php echo $show_phone; ?>" 
           data-donately-comments="<?php echo $show_comments; ?>" 
           data-donately-onbehalf="<?php echo $show_onbehalf; ?>" 
           data-donately-anonymous="<?php echo $show_anonymous; ?>" 
           data-donately-amount="<?php echo $amount; ?>"
          ></script>

         <?php

         $dntly_form = ob_get_clean();

    }

    echo $dntly_form;

}



/**
 * [dntly_get_donately_campaign_id description]
 *
 * Get the campaign ID.
 * @return [string] Returns the ID of the campaign y
 */
function dntly_get_donately_campaign_id( $campaign_id=null)
{
    global $post;

    $dntly_campaign_meta = get_post_meta( $campaign_id, 'dntly_campaign_id', true );

   return $dntly_campaign_meta;
}

function dntly_get_account_id()
{
    global $post;

    $dntly_account_id = get_post_meta( $post->ID, 'dntly_account_id', true );

    return $dntly_account_id;
}

function dntly_get_campaign_goal( $campaign_id = '' )
{
    global $post;

    $dntly_campaign_goal = get_post_meta( $campaign_id, 'dntly_campaign_goal', true );
    
    return '$' . number_format( $dntly_campaign_goal );
}

function dntly_get_amount_raised( $campaign_id= '' )
{
    global $post;

    $dntly_amount_raised = get_post_meta( $campaign_id, 'dntly_amount_raised', true );
    $dntly_amount_raised = isset( $dntly_amount_raised ) ?  $dntly_amount_raised : 0;

    return '$' . number_format( $dntly_amount_raised );
}

function dntly_get_percent_funded( $campaign_id = '')
{
    global $post;

    $dntly_percent_funded = get_post_meta( $campaign_id, 'dntly_percent_funded', true );
    $dntly_percent_funded = round((float)$dntly_percent_funded * 100 ) . '%';

    return $dntly_percent_funded;
}






