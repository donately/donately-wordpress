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
    $campaign_id = isset ( $_GET['cid'] ) ? $_GET['cid'] : null;
    $campaign_title = get_the_title( $campaign_id );

    if( isset( $campaign_id ) ) {
        echo "You're donating to " . $campaign_title . "<br />";
    }

    ob_start();
    ?>
    <script src="https://www.dntly.com/assets/js/v1/form.js"
      data-donately-id="1" 
      data-donately-campaign-id="<?php echo $campaign_id; ?>" 
      data-donately-address="true"
    </script>';
    <?php 
    $dntly_form = ob_get_clean();
    return $dntly_form;
}
add_shortcode( 'donation_form', 'dntly_donation_form' );