<?php
/**
 * Form Builder
 *
 * @package     Donately
 * @subpackage  Admin/Forms
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.4
 */

function dntly_form_builder() {
    global $dntly_settings;

    ob_start();
    ?>
    <div class="wrap">
        <h2>Form Shortcode Builder</h2>
        <p>Use this page to build your form.  </p>
    </div>
    <?php
    echo ob_get_clean();
}