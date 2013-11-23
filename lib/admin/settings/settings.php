<?php
/**
 * Admin Options Page
 *
 * @package     Donately for Wordpress
 * @subpackage  Admin Options Page
 * @copyright   Copyright (c) 2012, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @access      private
 * @since       1.0
 * @return      void
*/

function dntly_options_page() {
    global $dntly_options;

    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

    ob_start(); ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo add_query_arg('tab', 'general', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'edd'); ?></a>
            <a href="<?php echo add_query_arg('tab', 'misc', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'misc' ? 'nav-tab-active' : ''; ?>"><?php _e('Misc', 'edd'); ?></a>
        </h2>

        <div id="tab_container">
            <?php //settings_errors( 'edd-notices' ); ?>

            <form method="post" action="options.php">
                <?php
                if ( $active_tab == 'general' ) {
                    settings_fields( 'dntly_settings_general' );
                    do_settings_sections( 'dntly_settings_general' );
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