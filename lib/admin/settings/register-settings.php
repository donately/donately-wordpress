<?php
/**
 * Register Settings
 *
 * @package     Donately
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 1.8.4
 * @return mixed
 */
function dntly_get_option( $key = '', $default = false ) {
    global $dntly_settings;
    return isset( $dntly_settings[ $key ] ) ? $dntly_settings[ $key ] : $default;
}

/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 1.0
 * @return array DNTLY settings
 */
function dntly_get_settings() {

    $settings = get_option( 'dntly_settings' );
    if( empty( $settings ) ) {

        // Update old settings with new single option

        $general_settings = is_array( get_option( 'dntly_settings_general' ) )    ? get_option( 'dntly_settings_general' )      : array();
        $email_settings   = is_array( get_option( 'dntly_settings_email' ) )       ? get_option( 'dntly_settings_email' )         : array();
        $sync_settings    = is_array( get_option( 'dntly_settings_sync' ) )       ? get_option( 'dntly_settings_sync' )         : array();
        $misc_settings    = is_array( get_option( 'dntly_settings_misc' ) )       ? get_option( 'dntly_settings_misc' )         : array();

        $settings = array_merge( $general_settings, $email_settings, $sync_settings, $misc_settings );

        update_option( 'dntly_settings', $settings );
    }
    return apply_filters( 'dntly_get_settings', $settings );
}

/**
 * Add all settings sections and fields
 *
 * @since 1.0
 * @return void
*/
function dntly_register_settings() {

    if ( false == get_option( 'dntly_settings' ) ) {
        add_option( 'dntly_settings' );
    }

    foreach( dntly_get_registered_settings() as $tab => $settings ) {

        add_settings_section(
            'dntly_settings_' . $tab,
            __return_null(),
            '__return_false',
            'dntly_settings_' . $tab
        );

        foreach ( $settings as $option ) {
            add_settings_field(
                'dntly_settings[' . $option['id'] . ']',
                $option['name'],
                function_exists( 'dntly_' . $option['type'] . '_callback' ) ? 'dntly_' . $option['type'] . '_callback' : 'dntly_missing_callback',
                'dntly_settings_' . $tab,
                'dntly_settings_' . $tab,
                array(
                    'id'      => $option['id'],
                    'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
                    'name'    => $option['name'],
                    'section' => $tab,
                    'size'    => isset( $option['size'] ) ? $option['size'] : null,
                    'options' => isset( $option['options'] ) ? $option['options'] : '',
                    'std'     => isset( $option['std'] ) ? $option['std'] : ''
                )
            );
        }

    }

    // Creates our settings in the options table
    register_setting( 'dntly_settings', 'dntly_settings', 'dntly_settings_sanitize' );

}
add_action('admin_init', 'dntly_register_settings');

/**
 * Retrieve the array of plugin settings
 *
 * @since 1.8
 * @return array
*/
function dntly_get_registered_settings() {

    $pages = get_pages();
    $pages_options = array( 0 => '' ); // Blank option
    if ( $pages ) {
        foreach ( $pages as $page ) {
            $pages_options[ $page->ID ] = $page->post_title;
        }
    }

    /**
     * 'Whitelisted' DNTLY settings, filters are provided for each settings
     * section to allow extensions and other plugins to add their own settings
     */
    $dntly_settings = array(
        /** General Settings */
        'general' => apply_filters( 'dntly_settings_general',
            array(
                'api_settings' => array(
                    'id' => 'api_settings',
                    'name' => '<strong>' . __( 'API Settings', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                
                'donately_subdomain' => array(
                    'id' => 'donately_subdomain',
                    'name' => __( 'Donately Subdomain', 'dntly' ),
                    'desc' => __( 'Enter your dntly.com subdomain name here (http://example.dntly.com - example is the subdomain.', 'dntly' ),
                    'type' => 'text',
                    'size' => 'regular',
                    'std' => ''
                ),
                'donately_email' => array(
                    'id' => 'donately_email',
                    'name' => __( 'Donately Email', 'dntly' ),
                    'desc' => __( 'This is the email used to create your <a href="http://dntly.com">Donately</a> account.', 'dntly' ),
                    'type' => 'text',
                    'size' => 'regular',
                    'std' => ''
                ),
                'donately_token' => array(
                    'id' => 'donately_token',
                    'name' => __( 'Donately Token', 'dntly' ),
                    'desc' => __( 'Enter your Donately API Token. You can find it <a href="https://dntly.com/settings#/profile">here.</a>. (Click "Profile Details")', 'dntly' ),
                    'type' => 'password',
                    'size' => 'regular',
                    'std' => ''
                ),
                'donation_page' => array(
                    'id' => 'donation_page',
                    'name' => __( 'Donation Page', 'dntly' ),
                    'desc' => __( 'This is the page where all of donations will be processed', 'dntly' ),
                    'type' => 'select',
                    'options' => $pages_options
                ),
                'donation_button_text' => array(
                    'id' => 'donation_button_text',
                    'name' => __( 'Donately Button Text', 'dntly' ),
                    'desc' => __( 'Use custom text for your donation button', 'dntly' ),
                    'type' => 'text',
                    'size' => 'medium',
                    'std' => ''
                ),
            )
        ),
        /** Email Settings */
        'email' => apply_filters( 'dntly_settings_email',
            array(
                'email_settings' => array(
                    'id' => 'email_settings',
                    'name' => '<strong>' . __( 'Email Settings', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                'disable_dntly_email' => array(
                    'id' => 'disable_dntly_email',
                    'name' => __( 'Disable Donately Emails', 'dntly' ),
                    'desc' => __( 'Check this box to disable the Donately emails.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'custom_donately_email' => array(
                    'id' => 'custom_donately_email',
                    'name' => __( 'Custom Donately Email', 'dntly' ),
                    'desc' => __( 'If <em>disable Donately emails</em> is checked, then enter your custom email information here.', 'dntly' ),
                    'type' => 'rich_editor'
                ),
            )
        ),
        /** Sync Settings */
        'sync' => apply_filters( 'dntly_settings_sync',
            array(
                'sync_settings' => array(
                    'id' => 'sync_settings',
                    'name' => '<strong>' . __( 'Sync campaigns with Donately', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                'sync_controls' => array(
                    'id' => 'sync_controls',
                    'name' => __( 'Sync Controls', 'dntly' ),
                    'desc' => __( 'Select how you want your campaigns synched', 'dntly' ),
                    'type' => 'select',
                    'options' => array(
                        'manual'  => 'Manual Syncing',
                        'cron60'  => 'Auto Sync (every 60 mins)',
                        'cron30'  => 'Auto Sync (every 30 mins)'
                        )
                ),
                'sync_campaigns' => array(
                    'id' => 'sync_campaigns',
                    'name' => 'Sync Campaigns',
                    'desc' => '',
                    'type' => 'hook'
                ),
                'sync_account_stats' => array(
                    'id' => 'sync_account_stats',
                    'name' => 'Sync Accounts',
                    'desc' => '',
                    'type' => 'hook'
                ),
            )
        ),
        /** Misc Settings */
        'misc' => apply_filters('dntly_settings_misc',
            array(
                'disable_ajax_cart' => array(
                    'id' => 'disable_ajax_cart',
                    'name' => __( 'Disable Ajax', 'dntly' ),
                    'desc' => __( 'Check this to disable AJAX for the shopping cart.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'redirect_on_add' => array(
                    'id' => 'redirect_on_add',
                    'name' => __( 'Redirect to Checkout', 'dntly' ),
                    'desc' => __( 'Immediately redirect to checkout after adding an item to the cart?', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'live_cc_validation' => array(
                    'id' => 'live_cc_validation',
                    'name' => __( 'Disable Live Credit Card Validation', 'dntly' ),
                    'desc' => __( 'Live credit card validation means that that card type and number will be validated as the customer enters the number.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'logged_in_only' => array(
                    'id' => 'logged_in_only',
                    'name' => __( 'Disable Guest Checkout', 'dntly' ),
                    'desc' => __( 'Require that users be logged-in to purchase files.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'show_register_form' => array(
                    'id' => 'show_register_form',
                    'name' => __( 'Show Register / Login Form?', 'dntly' ),
                    'desc' => __( 'Display the registration and login forms on the checkout page for non-logged-in users.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'item_quantities' => array(
                    'id' => 'item_quantities',
                    'name' => __('Item Quantities', 'dntly' ),
                    'desc' => __('Allow item quantities to be changed at checkout.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'allow_multiple_discounts' => array(
                    'id' => 'allow_multiple_discounts',
                    'name' => __('Multiple Discounts', 'dntly' ),
                    'desc' => __('Allow customers to use multiple discounts on the same purchase?', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'disable_cart_saving' => array(
                    'id' => 'disable_cart_saving',
                    'name' => __( 'Disable Cart Saving', 'dntly' ),
                    'desc' => __( 'Check this to disable cart saving on the checkout', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'field_downloads' => array(
                    'id' => 'field_downloads',
                    'name' => '<strong>' . __( 'File Downloads', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                'download_method' => array(
                    'id' => 'download_method',
                    'name' => __( 'Download Method', 'dntly' ),
                    'desc' => sprintf( __( 'Select the file download method. Note, not all methods work on all servers.', 'dntly' ), dntly_campaigns_get_label_singular() ),
                    'type' => 'select',
                    'options' => array(
                        'direct' => __( 'Forced', 'dntly' ),
                        'redirect' => __( 'Redirect', 'dntly' )
                    )
                ),
                'symlink_file_downloads' => array(
                    'id' => 'symlink_file_downloads',
                    'name' => __( 'Symlink File Downloads?', 'dntly' ),
                    'desc' => __( 'Check this if you are delivering really large files or having problems with file downloads completing.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'file_download_limit' => array(
                    'id' => 'file_download_limit',
                    'name' => __( 'File Download Limit', 'dntly' ),
                    'desc' => sprintf( __( 'The maximum number of times files can be downloaded for purchases. Can be overwritten for each %s.', 'dntly' ), dntly_campaigns_get_label_singular() ),
                    'type' => 'text',
                    'size' => 'small'
                ),
                'download_link_expiration' => array(
                    'id' => 'download_link_expiration',
                    'name' => __( 'Download Link Expiration', 'dntly' ),
                    'desc' => __( 'How long should download links be valid for? Default is 24 hours from the time they are generated. Enter a time in hours.', 'dntly' ),
                    'type' => 'text',
                    'size' => 'small',
                    'std'  => '24'
                ),
                'disable_redownload' => array(
                    'id' => 'disable_redownload',
                    'name' => __( 'Disable Redownload?', 'dntly' ),
                    'desc' => __( 'Check this if you do not want to allow users to redownload items from their purchase history.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'accounting_settings' => array(
                    'id' => 'accounting_settings',
                    'name' => '<strong>' . __( 'Accounting Settings', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                'enable_skus' => array(
                    'id' => 'enable_skus',
                    'name' => __( 'Enable SKU Entry', 'dntly' ),
                    'desc' => __( 'Check this box to allow entry of product SKUs. SKUs will be shown on purchase receipt and exported purchase histories.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'terms' => array(
                    'id' => 'terms',
                    'name' => '<strong>' . __( 'Terms of Agreement', 'dntly' ) . '</strong>',
                    'desc' => '',
                    'type' => 'header'
                ),
                'show_agree_to_terms' => array(
                    'id' => 'show_agree_to_terms',
                    'name' => __( 'Agree to Terms', 'dntly' ),
                    'desc' => __( 'Check this to show an agree to terms on the checkout that users must agree to before purchasing.', 'dntly' ),
                    'type' => 'checkbox'
                ),
                'agree_label' => array(
                    'id' => 'agree_label',
                    'name' => __( 'Agree to Terms Label', 'dntly' ),
                    'desc' => __( 'Label shown next to the agree to terms check box.', 'dntly' ),
                    'type' => 'text',
                    'size' => 'regular'
                ),
                'agree_text' => array(
                    'id' => 'agree_text',
                    'name' => __( 'Agreement Text', 'dntly' ),
                    'desc' => __( 'If Agree to Terms is checked, enter the agreement terms here.', 'dntly' ),
                    'type' => 'rich_editor'
                ),
                'checkout_label' => array(
                    'id' => 'checkout_label',
                    'name' => __( 'Complete Purchase Text', 'dntly' ),
                    'desc' => __( 'The button label for completing a purchase.', 'dntly' ),
                    'type' => 'text'
                ),
                'add_to_cart_text' => array(
                    'id' => 'add_to_cart_text',
                    'name' => __( 'Add to Cart Text', 'dntly' ),
                    'desc' => __( 'Text shown on the Add to Cart Buttons', 'dntly' ),
                    'type' => 'text'
                )
            )
        )
    );

    return $dntly_settings;
}

/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function dntly_header_callback( $args ) {
    echo '';
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_checkbox_callback( $args ) {
    global $dntly_settings;

    $checked = isset($dntly_settings[$args['id']]) ? checked(1, $dntly_settings[$args['id']], false) : '';
    $html = '<input type="checkbox" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_multicheck_callback( $args ) {
    global $dntly_settings;

    foreach( $args['options'] as $key => $option ):
        if( isset( $dntly_settings[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
        echo '<input name="dntly_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
        echo '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
    endforeach;
    echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @since 1.3.3
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_radio_callback( $args ) {
    global $dntly_settings;

    foreach ( $args['options'] as $key => $option ) :
        $checked = false;

        if ( isset( $dntly_settings[ $args['id'] ] ) && $dntly_settings[ $args['id'] ] == $key )
            $checked = true;
        elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $dntly_settings[ $args['id'] ] ) )
            $checked = true;

        echo '<input name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
        echo '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
    endforeach;

    echo '<p class="description">' . $args['desc'] . '</p>';
}



/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_text_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
    $html = '<input type="text" class="' . $size . '-text" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_textarea_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
    $html = '<textarea class="large-text" cols="50" rows="5" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 1.3
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_password_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
    $html = '<input type="password" class="' . $size . '-text" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 1.3.1
 * @param array $args Arguments passed by the setting
 * @return void
 */
function dntly_missing_callback($args) {
    printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'dntly' ), $args['id'] );
}

/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_select_callback($args) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $html = '<select id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

    foreach ( $args['options'] as $option => $name ) :
        $selected = selected( $option, $value, false );
        $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
    endforeach;

    $html .= '</select>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Color select Callback
 *
 * Renders color select fields.
 *
 * @since 1.8
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_color_select_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $html = '<select id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

    foreach ( $args['options'] as $option => $color ) :
        $selected = selected( $option, $value, false );
        $html .= '<option value="' . $option . '" ' . $selected . '>' . $color['label'] . '</option>';
    endforeach;

    $html .= '</select>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @global $wp_version WordPress Version
 */
function dntly_rich_editor_callback( $args ) {
    global $dntly_settings, $wp_version;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    if ( $wp_version >= 3.3 && function_exists( 'wp_editor' ) ) {
        $html = wp_editor( stripslashes( $value ), 'dntly_settings_' . $args['section'] . '[' . $args['id'] . ']', array( 'textarea_name' => 'dntly_settings_' . $args['section'] . '[' . $args['id'] . ']' ) );
    } else {
        $html = '<textarea class="large-text" rows="10" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
    }

    $html .= '<br/><label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}

/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_upload_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[$args['id']];
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
    $html = '<input type="text" class="' . $size . '-text dntly_upload_field" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
    $html .= '<span>&nbsp;<input type="button" class="dntly_settings_upload_button button-secondary" value="' . __( 'Upload File', 'dntly' ) . '"/></span>';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Color picker Callback
 *
 * Renders color picker fields.
 *
 * @since 1.6
 * @param array $args Arguments passed by the setting
 * @global $dntly_settings Array of all the DNTLY Options
 * @return void
 */
function dntly_color_callback( $args ) {
    global $dntly_settings;

    if ( isset( $dntly_settings[ $args['id'] ] ) )
        $value = $dntly_settings[ $args['id'] ];
    else
        $value = isset( $args['std'] ) ? $args['std'] : '';

    $default = isset( $args['std'] ) ? $args['std'] : '';

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
    $html = '<input type="text" class="dntly-color-picker" id="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" name="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
    $html .= '<label for="dntly_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;
}



/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 1.0.8.2
 * @param array $args Arguments passed by the setting
 * @return void
 */
function dntly_hook_callback( $args ) {
    do_action( 'dntly_' . $args['id'] );
}

/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
 * @since 1.0.8.2
 * @param array $input The value inputted in the field
 * @return string $input Sanitizied value
 */
function dntly_settings_sanitize( $input = array() ) {

    global $dntly_settings;

    parse_str( $_POST['_wp_http_referer'], $referrer );

    $output    = array();
    $settings  = dntly_get_registered_settings();
    $tab       = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';
    $post_data = isset( $_POST[ 'dntly_settings_' . $tab ] ) ? $_POST[ 'dntly_settings_' . $tab ] : array();

    $input = apply_filters( 'dntly_settings_' . $tab . '_sanitize', $post_data );

    // Loop through each setting being saved and pass it through a sanitization filter
    foreach( $input as $key => $value ) {

        // Get the setting type (checkbox, select, etc)
        $type = isset( $settings[ $key ][ 'type' ] ) ? $settings[ $key ][ 'type' ] : false;

        if( $type ) {
            // Field type specific filter
            $output[ $key ] = apply_filters( 'dntly_settings_sanitize_' . $type, $value, $key );
        }

        // General filter
        $output[ $key ] = apply_filters( 'dntly_settings_sanitize', $value, $key );
    }


    // Loop through the whitelist and unset any that are empty for the tab being saved
    if( ! empty( $settings[ $tab ] ) ) {
        foreach( $settings[ $tab ] as $key => $value ) {

            // settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
            if( is_numeric( $key ) ) {
                $key = $value['id'];
            }

            if( empty( $_POST[ 'dntly_settings_' . $tab ][ $key ] ) ) {
                unset( $dntly_settings[ $key ] );
            }

        }
    }

    // Merge our new settings with the existing
    $output = array_merge( $dntly_settings, $output );

    // @TODO: Get Notices Working in the backend.
    add_settings_error( 'dntly-notices', '', __( 'Settings Updated', 'dntly' ), 'updated' );

    return $output;

}

/**
 * Sanitize text fields
 *
 * @since 1.8
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function dntly_sanitize_text_field( $input ) {
    return trim( $input );
}
add_filter( 'dntly_settings_sanitize_text', 'dntly_sanitize_text_field' );

/**
 * Retrieve settings tabs
 *
 * @since 1.8
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function dntly_get_settings_tabs() {

    $settings = dntly_get_registered_settings();

    $tabs            = array();
    $tabs['general'] = __( 'General', 'dntly' );
    $tabs['email']   = __( 'Email', 'dntly' );
    $tabs['sync']    = __( 'Sync', 'dntly' );
    $tabs['misc']    = __( 'Misc', 'dntly' );

    return apply_filters( 'dntly_settings_tabs', $tabs );
}

/**
 * Test Buttons
 *
 * @access private
 * @global $dntly_settings Array of all the DNTLY Options
 * @since 1.0.8.2
 */
function dntly_sync_campaigns_buttons() {
    global $dntly_settings;

    ob_start();
    ?>
    <a href="javascript:;" id="dntly-sync-campaigns" class="button-secondary" title="<?php _e( 'Sync Campaigns', 'dntly' ); ?> "><?php _e( 'Sync Campaigns', 'dntly' ); ?></a>
    <?php
    echo ob_get_clean();
}
add_action( 'dntly_sync_campaigns', 'dntly_sync_campaigns_buttons' );


/**
 * Test Buttons
 *
 * @access private
 * @global $dntly_settings Array of all the DNTLY Options
 * @since 1.0.8.2
 */
function dntly_sync_stats_buttons() {
    global $dntly_settings;

    ob_start();
    ?>
    <a href="javscript:;" class="button-secondary" title="<?php _e( 'Sync Account Stats', 'dntly' ); ?> "><?php _e( 'Sync Account Stats', 'dntly' ); ?></a>
    <?php
    echo ob_get_clean();
}
add_action( 'dntly_sync_account_stats', 'dntly_sync_stats_buttons' );
