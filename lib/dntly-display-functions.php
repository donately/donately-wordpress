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