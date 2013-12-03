<?php
/**
 * Admin Options Page
 *
 * @package     Donately for Wordpress
 * @subpackage  Admin Options Page
 * @copyright   Copyright (c) 2012, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @access private
 * @since 0.1
 * @return void
*/

function dntly_settings_page() {
    global $dntly_settings;

    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

    ob_start(); ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo add_query_arg('tab', 'general', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'dntly'); ?></a>
            <a href="<?php echo add_query_arg('tab', 'email', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : ''; ?>"><?php _e('Email', 'dntly'); ?></a>
            <a href="<?php echo add_query_arg('tab', 'sync', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>"><?php _e('Sync', 'dntly'); ?></a>
            <a href="<?php echo add_query_arg('tab', 'misc', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'misc' ? 'nav-tab-active' : ''; ?>"><?php _e('Misc', 'dntly'); ?></a>
        </h2>

        <div id="tab_container">
            <?php //settings_errors( 'edd-notices' ); ?>

            <form method="post" action="options.php">
                <?php
                if ( $active_tab == 'general' ) {
                    settings_fields( 'dntly_settings_general' );
                    do_settings_sections( 'dntly_settings_general' );
                } elseif ( $active_tab == 'email') {
                    settings_fields( 'dntly_settings_email' );
                    do_settings_sections( 'dntly_settings_email' );
                } elseif ( $active_tab == 'sync') {
                    settings_fields( 'dntly_settings_sync' );
                    do_settings_sections( 'dntly_settings_sync' );
                } else {
                    settings_fields( 'dntly_settings_misc' );
                    do_settings_sections( 'dntly_settings_misc' );
                }

                submit_button();
                ?>

            </form>
        </div><!-- #tab_container-->
    </div><!-- .wrap -->
    <?php
    echo ob_get_clean();
}