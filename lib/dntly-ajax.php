<?php 

/**
 * DNTLY Ajax Methods
 *
 * @since 0.1
 * @package Donately Wordpress
 * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
 * @todo General clean up, deprecate get_token (user will input directly in settings)
 */
add_action( 'init', 'dntly_ajax_methods' );

function dntly_ajax_methods()
{

  /**
   * Get Accounts
   * @since 0.1
   */
  function dntly_get_accounts()
  {
    $dntly      = new DNTLY_API;
    $dntly_data = $dntly->get_accounts();

    return $dntly_data->accounts;
  }
  add_action( 'wp_ajax_dntly_get_accounts', 'dntly_get_accounts' );

  /**
   * Get Campaigns
   * @since 0.1
   */
  function dntly_get_campaigns()
  {
    $dntly = new DNTLY_API;
    $dntly->get_campaigns();
  }
  add_action( 'wp_ajax_dntly_get_campaigns', 'dntly_get_campaigns' );


  /**
   * Get Account Statistics
   * @since 0.1
   */
  function dntly_get_account_stats()
  {
    $dntly = new DNTLY_API;
    $dntly->get_account_stats();
  }
  add_action( 'wp_ajax_dntly_get_account_stats', 'dntly_get_account_stats' );



  /**
   * Submit Button Hook - General Tab
   * @since 0.1
   */
  function dntly_settings_submit_button_hook_general()
  {
    $dntly = new DNTLY_API;
    // $dntly->get_account_stats();
    if(isset($_GET['tab']) && $_GET['tab']) {

      ?> <script>console.log('General Tab');</script> <?php
    
      if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
       {
          ?>
            <script>console.log('test');</script>
          <?php
       }

     }
  }
  add_action( 'wp_ajax_dntly_settings_submit_button_hook_general', 'dntly_settings_submit_button_hook_general' );



  /**
   * AJAX TEST
   * @since 0.1
   */
  function dntly_ajax_test()
  {
    ?>
      <script>console.log('Ajax Testing Success');</script>
    <?php
  }
  add_action( 'wp_ajax_dntly_ajax_test', 'dntly_ajax_test' );



  /**
   * Lookup a Person
   * @since 0.1
   */
  function dntly_lookup_person()
  {
    $dntly = new DNTLY_API;
    $dntly->lookup_person();
  }
  add_action('wp_ajax_dntly_lookup_person','dntly_lookup_person');
  add_action('wp_ajax_nopriv_dntly_lookup_person','dntly_lookup_person');

}