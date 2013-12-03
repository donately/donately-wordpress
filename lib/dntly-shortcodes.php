<?php
/**
 * Shortcodes
 *
 * @package     Donately
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function dntly_donation_form( $atts, $content = null ) 
{
    global $post, $dntly_settings;

    extract( shortcode_atts( array( 
            'id'    =>  $post->ID
        ),
        $atts, 'donation_form' )
    );
    $campaign_id = $_GET['cid'];
    $campaign_title = get_the_title( $campaign_id );
    
    $dntly_form = "You're donating to " . $campaign_title . "<br />";
    $dntly_form .= '<script src="https://www.dntly.com/assets/js/v1/form.js"
      data-donately-id="1" 
      data-donately-campaign-id="1" 
      data-donately-address="true">
    </script>';

    return $dntly_form;
}
add_shortcode( 'donation_form', 'dntly_donation_form' );


 /**
  * DNTLY Fields Shortcodes
  *
  * Accompanying shortcodes for various display functions in display-fields.php
  *
  * @since 0.1
  * @package Donately Wordpress
  * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
  */
 function xxx( $atts, $content = null ) 
 {
     global $post, $dntly_settings;

     extract( shortcode_atts( array( 
         'id'    =>  $post->ID
     ),
     $atts, 'donation_form' )
     );
     $campaign_id = $_GET['cid'];
     $campaign_title = get_the_title( $campaign_id );
     
     $dntly_form = "You're donating to " . $campaign_title . "<br />";
     $dntly_form .= '<script src="https://www.dntly.com/assets/js/v1/form.js"
       data-donately-id="1" 
       data-donately-campaign-id="1" 
       data-donately-address="true">
     </script>';

     return $dntly_form;
 }
 add_shortcode( 'donation_form', 'xxx' );