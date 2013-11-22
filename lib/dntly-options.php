<?php
/**
 * File used to setup your theme options.
 * Theme options are located in your dashboard at Appreance->Theme Options
 *
 * @package WordPress
 * @subpackage Donately Wordpress
 * @since 0.1
 */
 
function optionsframework_option_name()
{
    $optionsframework_settings = get_option( 'optionsframework' );
    $optionsframework_settings['id'] = 'options_'.$theme_prefix.'themes';
    update_option( 'optionsframework', $optionsframework_settings);
}


function register_dntly_menu_page()
{
  add_menu_page( 'Donately', 'Donately', 'manage_options', 'dntly_page', 'optionsframework_options', plugins_url( DNTLY_IMG_DIR . 'tooltip.png' ), 6 ); 
}
add_action( 'admin_menu', 'register_dntly_menu_page' );


// Begin options
function optionsframework_options()
{
  $options = array();

  /**
   * Settings
   * @since 0.1
   */
  $options[] = array(
    'name'  => __( 'Settings', 'DNTLY' ),
    'type'  => 'heading'
  );

    // TOKEN
    $options[] = array(
      'name' => __( 'Your Donately Token. You can find this by visiting https://www.dntly.com/settings#/profile and clicking "PROFILE DETAILS" in the sidebar.', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_token',
      'type' => 'text'
    );
    // SUBDOMAIN
    $options[] = array(
      'name' => __( 'Your Donately Subdomain. Ex: SUBDOMAIN.dntly.com', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_subdomain',
      'type' => 'text'
    );


  /**
   * Campaigns
   * @since 0.1
   */
  $options[] = array(
    'name'  => __( 'Campaigns', 'DNTLY' ),
    'type'  => 'heading'
  );

    // SYNCING EXAMPLE TEXT FIELD
    $options[] = array(
      'name' => __( 'Campaigns Example', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_campaigns_example',
      'type' => 'text'
    );



  /**
   * Syncing
   * @since 0.1
   */
  $options[] = array(
    'name'  => __( 'Syncing', 'DNTLY' ),
    'type'  => 'heading'
  );

    // SYNCING EXAMPLE TEXT FIELD
    $options[] = array(
      'name' => __( 'Syncing Example', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_syncing_example',
      'type' => 'text'
    );



  /**
   * Logs
   * @since 0.1
   */
  $options[] = array(
    'name'  => __( 'Logs', 'DNTLY' ),
    'type'  => 'heading'
  );

    // SYNCING EXAMPLE TEXT FIELD
    $options[] = array(
      'name' => __( 'Logs Example', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_logs_example',
      'type' => 'text'
    );



  /**
   * Help
   * @since 0.1
   */
  $options[] = array(
    'name'  => __( 'Help', 'DNTLY' ),
    'type'  => 'heading'
  );

    // SYNCING EXAMPLE TEXT FIELD
    $options[] = array(
      'name' => __( 'Help Example', 'DNTLY' ),
      'std'  => '',
      'id'   => 'dntly_help_example',
      'type' => 'text'
    );


  return $options;
}