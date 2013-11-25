<?php
/**
 * Admin Options Page
 *
 * @package     EDD
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2013, Pippin Williamson
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
 * @since 1.0
 * @global $dntly_options Array of all the EDD Options
 * @return void
 */
function dntly_dashboard_page() {
    global $dntly_options;

    ob_start();
    ?>
    <div class="wrap">
        <div style="margin-top:30px;">
        <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/dntly-logo.png'; ?>" alt="" />
        </div>
        <p>Some options here, high level stats with links to Donately Dashboard. Let's talk about it yo. </p>
    </div><!-- .wrap -->
    <?php
    echo ob_get_clean();
}
