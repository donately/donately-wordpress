<?php
/**
 * Admin Actions
 *
 * @package     Donately
 * @subpackage  Admin/Actions
 * @copyright   Copyright (c) 2013, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Processes all Donately actions sent via POST and GET by looking for the 'dntly-action'
 * request and running do_action() to call the function
 *
 * @since 1.0
 * @return void
 */
function donately_process_actions() {
    if ( isset( $_POST['dntly-action'] ) ) {
        do_action( 'dntly_' . $_POST['dntly-action'], $_POST );
    }

    if ( isset( $_GET['dntly-action'] ) ) {
        do_action( 'dntly_' . $_GET['dntly-action'], $_GET );
    }
}
add_action( 'admin_init', 'donately_process_actions' );