<?php
/*
Description:  Donately API Class
Author:       5ifty&5ifty - A humanitarian focused creative agency
Author URI:   http://www.fiftyandfifty.org/
Contributors: Bryan Shanaver, Bryan Monzon, Alexander Zizzo

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Alex Moss or pleer nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


class DNTLY_API {

  /**
   * Variables & Conditionals
   *  
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   */
  public $api_scheme           = array('production' => 'https');    
  public $api_domain           = array('production' => 'dntly.com');
  public $api_subdomain        = "www";
  public $api_endpoint         = "/api/v1/";
  public $api_methods          = array();
  public $api_runtime_id       = 0;
  public $dntly_account_id     = 0;
  public $dntly_options        = array();
  public $wordpress_upload_dir = null;
  public $suppress_logging     = false;
  public $remote_results       = null;
  public $tokens_b64_arr       = array();     // @TODO temporary, remove


  /**
   * DNTLY_API Constructor
   *
   * What to automatically call when new instance of class is initiated
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   */
  function __construct()
  {
    // If debugging is enabled, change $api_scheme from production to staging/dev
    if( DNTLY_DEBUG )
    { // Staging
      $this->api_scheme['staging'] = 'https';
      $this->api_domain['staging'] = 'dntly-staging.com';
      // Dev
      $this->api_scheme['dev']     = 'http';
      $this->api_domain['dev']     = 'dntly.local:3000';
    }
    // Get the dntly_options array
    $this->dntly_options = get_option('dntly_options');

    // If the account is set in the dntly_options array,
    if( isset($this->dntly_options['account']) )
    {
      // If the account value is not empty in dntly_options, set it as the subdomain, else set subomdian to default to 'www'
      if ( $this->dntly_options['account'] != '' ) {
        $this->api_subdomain = $this->dntly_options['account'];
      } else {
        $this->api_subdomain = 'www';
      }

      // If the account_id is set in dntly_options, set it as the account_id, else set it to 0 (no accounts created yet)
      if ( isset( $this->dntly_options['account_id'] ) ) {
        $this->dntly_account_id = $this->dntly_options['account_id'];
      } else {
        $this->dntly_account_id = 0;
      }
    }
    // Run the API method building function
    $this->build_api_methods();
    // Set the WP upload directory by running the wp core function
    $this->wordpress_upload_dir = wp_upload_dir();

    // Token Vars
    $token_raw    = $this->dntly_options['donately_token'];
    $token_base64 = base64_encode($token_raw);
    $this->tokens_b64_arr = array(
      'BM' => base64_encode('d06449cd77c32744857218c834556668'),
      'AZ' => base64_encode('b384c98ff1bf9273c6b4929346d87b90')
    );
  }
  


  /**
   * Authorization Test
   *
   * Test/Debug Authorization by using some of the set options (token, etc)
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo
   * @param void
   * @return void
   */
  function debug_request_test( $url = NULL, $post_vars = array(), $method = NULL )
  {

    $token_b64_temp = base64_encode($this->dntly_options['donately_token']);

    // If 'POST'
    if ( $method == 'POST' ) {

      $example_post_args = array(
        'headers' => array (
          'Authorization' => 'Basic ' . $token_b64_temp,
          'sslverify'     => false
        ),
        'body'    => $post_vars
      );

      $response = wp_remote_post( $url, $example_post_args );

      return $response;
    } 
    // If 'GET'
    if ( $method == 'GET' ) {

      $example_get_args = array(
          'headers' => array (
            'Authorization' => 'Basic ' . $token_b64_temp,
            'sslverify'     => false
          ),
          'timeout' => 30,
          'body'    => $post_vars
      );

      $response = wp_remote_get( $url, $example_get_args );

      return $response;
    }

  }




  /**
   * Build API Methods
   *
   * Build various methods (api calls) as an array for accessibility ease.
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param void
   * @return [array]
   * @todo Create more / remove unneeded
   */
  function build_api_methods( $return = false )
  {
    /**
     * api_methods
     * @param [array] : Request Method (GET/POST), Request Path
     */
    $this->api_methods = array(
      "root"                =>  array("get",  ""),
      "get_session_token"   =>  array("post", "sessions"),
      "donate_without_auth" =>  array("post", "accounts/" . $this->dntly_account_id . "/donate_without_auth"),
      "create_fundraiser"   =>  array("post", "fundraisers"),
      "create_person"       =>  array("post", "people"),
      "person_exists"       =>  array("get",  "public/people/exists"),
      "get_my_accounts"     =>  array("get",  "accounts"),
      "get_account_stats"   =>  array("get",  "admin/account/stats"),
      "get_person"          =>  array("get",  "admin/people" . ( $this->api_runtime_id ? '/' . $this->api_runtime_id : '' )),
      "get_all_accounts"    =>  array("get",  "public/accounts"),
      "get_campaigns"       =>  array("get",  "admin/campaigns"),
      "get_fundraisers"     =>  array("get",  "admin/fundraisers"),
      "get_donations"       =>  array("get",  "admin/donations"),
      "get_events"          =>  array("get",  "admin/events"),
    );

    if ( $return ) {
      return $this->api_methods;
    } else {
      return NULL;
    }
  }



  /**
   * Return JSON SUCCESS message
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param $data
   * @return [array] : success (true/false), data ($data array)
   */
  function return_json_success( $data = '' )
  {
    print json_encode( array("success" => 'true', "data" => $data) );
  }



  /**
   * Return JSON ERROR message
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param $error
   * @return [array] : success (true/false), message ($error string)
   */
  function return_json_error( $error = '' )
  {
    print json_encode( array("success" => 'false', 'error' => array("message" => $error)) );
  }


  /**
   * Convert Array to Object
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param [array]
   * @return {object}
   */
  function array_to_object( $array ) 
  {
    // If the array parameter is not an array, exit and return the parameter stripped
    if( !is_array($array) ) { 
      return stripslashes($array); 
    }
    // Instantiate an object to be populated later
    $object = new stdClass();

    // If the array parameter is an array, and has contents (is not empty)
    if ( is_array($array) && count($array) > 0 ) {
      // For each array item as a $value, using $name as key
      foreach ( $array as $name => $value ) {
          // Set the $name (array key) to lowercase and trim any whitespace
          $name = strtolower(trim($name));
          // If the $name (array key) is not empty
          if ( !empty($name) ) {
            // Set each object item (using $name as key). Rerun the function on the $value to sanitize.
            $object->$name = $this->array_to_object($value);
          }
      }
      // Return the object.
      return $object;
    }
    // Else, return false.
    else { 
      return FALSE; 
    }
  }



  /**
   * Build URL
   *
   * Build API Call URLs based on environment -> protocols etc.
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param [array] $api_method
   * @return (string) $url
   */
  function build_url( $api_method )
  {
    // If the environment value in $dntly_options is not empty, $url is the set environment (protocol : https), else default to production environment
    if ( !empty($this->dntly_options['environment']) ) {
      $url = $this->api_scheme[$this->dntly_options['environment']];
    } else {
      $url = $this->api_scheme['production'] . '://';
    }
    // Append the subdomain and a period (Ex: __SUBDOMAIN__.dntly.com/ ... )
    $url .= $this->api_subdomain . '.';

    // If the environment value in $dntly_options is not empty, append $url with the api_domain (dntly.com), else default to api_domain (production)
    if ( !empty($this->dntly_options['environment']) ) {
      $url .= $this->api_domain[$this->dntly_options['environment']];
    } else {
      $url .= $this->api_domain['production'];
    }
    // If the $api_method is NOT 'root', append $url with the $api_endpoint (API path)
    if ( $api_method != 'root' ) {
      $url .= $this->api_endpoint . $this->api_methods[$api_method][1];
    }
    // Return the built URL
    return $url;
  } 




  /**
   * Do Not Log
   *
   * Check for logging suppression and setting of $_REQUESTION['action']
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @return (boolean) true/false
   */
  function do_not_log()
  {
    // If $surpress_logging is set to true, return true
    if( $this->suppress_logging ) {
      return true;
    }
    // If the action request is not set, return true
    if( !isset($_REQUEST['action']) ) {
      return true;
    }
    // If the action request substring contains dntly, return false 
    if( substr($_REQUEST['action'], 0 ,5) == 'dntly' ){
      return false;
    }
  }




  /**
   * Make API Request
   *
   * Make an API request to Donately using select methods, header auth and post vars
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param [array] $api_method, (boolean) $auth, [array] $post_variables
   * @return [array] JSON data array || NULL
   */
  function make_api_request( $api_method, $auth = true, $post_variables = null )
  {
    // Set $url variable after running the $api_method through the build_url() function
    $url = $this->build_url( $api_method );

    // If console_calls is not empty in $dntly_options AND logging is NOT suppressed
    if( !empty($this->dntly_options['console_calls']) && !$this->do_not_log() ) {
      // Log the transaction with API url, API post args, then print the debug results
      // dntly_transaction_logging("\n" . "api url: " . $url . "\n" . "api post args: " . (sizeof($post_variables) ? print_r($post_variables, true) : '') . "\n", 'print_debug');
    }

    // If $auth is true, set $session_token to 'token' value in $dntly_options, set $authorization to base64 encoded token, and prep them as $header var to later send
    if( $auth ){
      $session_token = $this->dntly_options['donately_token'];
      $authorization = 'Basic ' . base64_encode("{$session_token}:");
      $headers       = array( 'Authorization' => $authorization, 'sslverify' => false );
    // Else, do not send authorization (token), only set sslverify as false in header (used only for non-auth API calls)
    } else {
      $headers = array( 'sslverify' => false );
    }

    // If the first value (method) of the $api_method is "POST", use wp_remote_post with the above set auth headers and post vars
    if( $this->api_methods[$api_method][0] == "post" ){
      $this->remote_results = wp_remote_post($url, array('headers' => $headers, 'body' => $post_variables));
    }
    // Else if the $post_variables parameters are set/true...  (break down array in foreach)
    else{
      if( $post_variables ) {
        // Append the URL with a '?' in preparation to send post variables
        $url .= '?';
        // Loop through the $post_variables array and append them to the $url
        foreach( $post_variables as $var => $val ) { 
          $url .= $var . '=' . $val . '&';
        }
      }
      // Otherwise (not $post_variables passed), perform "GET" using wordpress' function, only sending headers and no post variables
      $this->remote_results = wp_remote_get($url, array('headers' => $headers));
    }
    // If the results from the request is an object ...
    if( is_object($this->remote_results) ){
      // And if the class name (string) of the result object is 'WP_Error' ...
      if( get_class($this->remote_results) == 'WP_Error' ){
        // Then something went wrong, so just return NULL.
        return null;
      }
    }
    // If the result response array's 'code' value is NOT 200 (unsuccessfull) ...
    if( $this->remote_results['response']['code'] != '200' ) {
      // Then run the display function to display the JSON error, passing the message value, and return null.
      $this->return_json_error($this->remote_results['response']['message']);
      return null;
    }
    // Otherwise, we succeeded, and return the JSON body decoded to PHP
    return json_decode($this->remote_results['body']);
  }





  /**
   * Convert Amount in Cents to Amount
   *
   * Converts an integer to an amount in cents of dollars
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param (int) $amount_in_cents
   * @return (string) $dollar . $cents
   * @todo Review compatibility for various PHP versions
   */
  function convert_amount_in_cents_to_amount( $amount_in_cents )
  {
    $dollars = substr($amount_in_cents, 0, (strlen($amount_in_cents)-2));
    $cents   = substr($amount_in_cents, -2);

    return $dollars . '.' . $cents;
  }





  /**
   * Get Accounts
   *
   * Make an API request to get all Donately accounts
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @return [array] $accounts
   * @todo Use alert notifications (pending Monzon implementation)
   */
  function get_accounts()
  {
    // Use make_api_request function with method defined above
    $accounts = $this->make_api_request("get_my_accounts");

    // If we do NOT have accounts, print error message
    if( !$accounts ){
      print '<div class="updated" id="message"><p><strong>Error retrieving Donately accounts!</strong> - ' . print_r($this->remote_results, true) . '</p></div>';
    }
    // If all is good, return $accounts array 
    return $accounts;
  }




  /**
   * Update Account Stats
   *
   * Update various Donately statistics in option 'dntly_stats'
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param $stats
   * @return [array] $dntly_stats
   * @todo Add/remove fundraisers and additional as necessary
   */
  function update_account_stats( $stats )
  {
    $existing_stats = get_option('dntly_stats');
    $dntly_stats = array(
        'total_raised'              => ( !empty($stats['total_raised'])           ? $stats['total_raised']          : $existing_stats['total_raised'] ),
        'total_raised_onetime'      => ( !empty($stats['total_raised_onetime'])   ? $stats['total_raised_onetime']  : $existing_stats['total_raised_onetime'] ),
        'total_raised_recurring'    => ( !empty($stats['total_raised_recurring']) ? $stats['total_raised_recurring']: $existing_stats['total_raised_recurring'] ),
        'total_donations'           => ( !empty($stats['total_donations'])        ? $stats['total_donations']       : $existing_stats['total_donations'] ),
        'total_campaigns_count'     => ( !empty($stats['total_campaigns_count'])  ? $stats['total_campaigns_count'] : $existing_stats['total_campaigns_count'] ),
    );
    update_option('dntly_stats', $dntly_stats);
    return $dntly_stats;
  }




  /**
   * Get Account Stats
   *
   * Get the account statistics by making an API call and storing them in an array,
   * then run the update_account_stats to store them in an option
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @return [array] $all_stats
   * @todo Revisit transaction logging
   */
  function get_account_stats(){
    // Make API call with authorization
    $stats = $this->make_api_request("get_account_stats", true);
    // Create array with the returned object
    $dntly_stats = array(
        'total_raised'           => $stats->amount_raised,
        'total_raised_onetime'   => $stats->one_time_amount_raised,
        'total_raised_recurring' => $stats->recurring_amount_raised,
        'total_donations'        => $stats->donations_count
    );
    // Update the returned array into the options
    $all_stats = $this->update_account_stats( $dntly_stats );
    // dntly_transaction_logging("Synced Account Stats - total_raised: $" . $stats->amount_raised . ", total_donations: " . $stats->donations_count);
    return $all_stats;
  }




  /**
   * Get Campaigns
   *
   * Get the campaigns associated with the users account
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param (boolean) $referrer, (int), $campaign_count
   * @return void
   * @todo Revisit using die() at end of function
   */
  function get_campaigns( $referrer = NULL, $campaign_count = 100 )
  {
    // Set the account count at 0
    $count_accounts  = 0;
    // Set campaign counts at 0
    $count_campaigns = array('add' => 0, 'update' => 0, 'skip' => 0);

    // If referrer is passed as true, make the API request and pass the referrer
    if( $referrer ) {
      $get_accounts = $this->make_api_request("get_my_accounts", true, array('referrer' => $referrer));
      // Foreach account, count++ on the accounts, and get campaigns with an API call passing account_ids as post variables
      foreach( $get_accounts->accounts as $account ) {
        // Count up so we can reach a total
        $count_accounts++;
        // Make API call with each account's ID (as post var)
        $get_campaigns = $this->make_api_request("get_campaigns", true, array('account_ids' => $account->id));

        // Foreach campaign (within account), add/update the campaign to be stored in $_dntly_data
        foreach($get_campaigns->campaigns as $campaign){
          $count_campaigns = $this->add_update_campaign($campaign, $account->id, $count_campaigns);
        }
      }
    }
    // Else (no referrer), do not perform API request for accounts with referrer post var
    else{
      // Count up accounts so we can reach a total
      $count_accounts++;
      // Make API call for campaigns with each account_id in post vars, and also set a limit (can be overriden as second parameter)
      $get_campaigns = $this->make_api_request("get_campaigns", true, array('account_ids' => $this->dntly_account_id, 'count' => $campaign_count));

      // Foreach campaign, update their data as an option using add_update_campaign() function
      foreach($get_campaigns->campaigns as $campaign){
        $count_campaigns = $this->add_update_campaign($campaign, $this->dntly_account_id, $count_campaigns);
      }
    }

    // If $count_campaigns array key/vals exist, update account stats with the accounts tallied in above loops
    if( $count_campaigns['add'] || $count_campaigns['update'] || $count_campaigns['skip'] ){
      $this->update_account_stats( array('total_campaigns_count' => ($count_campaigns['add'] + $count_campaigns['update'] + $count_campaigns['skip']) ) );
      // dntly_transaction_logging("Synced Campaigns - ".$count_campaigns['add']." added, ".$count_campaigns['update']." updated ".$count_campaigns['skip']." skipped " . ($count_accounts>1?"from {$count_accounts} accounts":""));
    }
    else{
      // dntly_transaction_logging('Synced Campaigns Error, no campaigns found', 'error');
      ?> <script>console.log('Campaign sync error, no campaigns found. Refer to get_campaigns function in dntly-api.class.php');</script> <?php
    }
  }





  /**
   * Add / Update Campaign
   *
   * Add or update $dntly_data with campaign information
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @param {object} $campaign, (string) $account_id, (int) $count_campaigns
   * @return [array] $campaign_count (?) 'add', 'update', 'skip'
   * @todo Revisit the nature of the returned information
   */
  function add_update_campaign( $campaign, $account_id, $count_campaigns )
  {
    // If the campaign state is archived, return [array] ('add', 'update', 'skip')
    if( $campaign->state == 'archived' ){
      return array( 'add' => $count_campaigns['add'], 'update' => $count_campaigns['update'], 'skip' => ($count_campaigns['skip']+1) );
    }
    // Set the transaction type to NULL
    $trans_type = null;

    // Create $_dntly_data array from the $campaign object
    $_dntly_data = array(
      'dntly_id'               => $campaign->id,
      'account_title'          => $this->dntly_options['account_title'],
      'account_id'             => $account_id,
      'campaign_goal'          => $campaign->campaign_goal,
      'donations_count'        => $campaign->donations_count,
      'donors_count'           => $campaign->donors_count,
      'amount_raised'          => $campaign->amount_raised,
      'amount_raised_in_cents' => $campaign->amount_raised_in_cents,
      'percent_funded'         => $campaign->percent_funded,
      'photo_original'         => (stristr($campaign->photo->original, 'http') ? $campaign->photo->original : ''),
    );

    // Does this exist in the DB already? If it does, update it.
    $post_exists = new WP_Query(
      array(
      'posts_per_page' => 1,
      'post_type'      => $this->dntly_options['dntly_campaign_posttype'],
      'post_status'    => array( 'publish', 'private', 'draft', 'pending', 'future', 'pending'), // essentially match any not in the trash
      'meta_query'     => array(
        array(
          'key'   => '_dntly_id',
          'value' => $campaign->id
        ),
        array(
          'key'   => '_dntly_environment',
          'value' => $this->dntly_options['environment'],
        )
      ))
    );

    // If the post exists (isset), get first in array by ID
    if( isset($post_exists->posts[0]->ID) ){
      // Set the post ID
      $post_id    = $post_exists->posts[0]->ID;
      // Set transaction type to 'update'
      $trans_type = "update";
    }
    // Else, set post variables parameters as an array
    else{
      $post_params = array(
        'post_type'     => $this->dntly_options['dntly_campaign_posttype'],
        'post_title'    => $campaign->title,
        'post_content'  => $campaign->description,
        'post_status'   => ($this->dntly_options['sync_to_private']?'private':'publish'),
      );
      // Set post ID and run wp_insert_post (inserts post in the database and sanitize variables)
      $post_id = wp_insert_post($post_params);

      // If campaign photo has http (stristr finds first occurence of substring in a string), set the $image var 
      if ( (stristr($campaign->photo->original, 'http')) ) {
        $image = $campaign->photo->original;
      // Otherwise we don't have an image and set the $image var to NULL
      } else {
        $image = null;
      }

      // If we DO have an image, perform various checks and operations
      if( $image ){
        // Check the image filetype
        $img_filetype = wp_check_filetype( $image, null );
        // Format the image filename to lowercase and use regex to prefix it
        $img_name     = strtolower( preg_replace('/[\s\W]+/','-', $campaign->title . '-dntly-img') );
        // Set the image path 
        $img_path     = $this->wordpress_upload_dir['path'] . "/" . $img_name . "." . $img_filetype['ext'];
        // Set the image subpath
        $img_sub_path = $this->wordpress_upload_dir['subdir'] . "/" . $img_name . "." . $img_filetype['ext'];
        // Get the image file
        $image_file   = file_get_contents( $image );
        // Write the image path and file data
        file_put_contents($img_path, $image_file);

          // Set $attachment array keys/values
          $attachment = array(
           'post_type'      => 'attachment',
           'post_title'     => 'Donately Campaign - ' . $campaign->title,
           'post_parent'    => $post_id,
           'post_status'    => 'inherit',
           'post_mime_type' => $img_filetype['type'],
          );
          // Write the $attachment_id to the database, passing true for WP_Error object on failure
          $attachment_id = wp_insert_post( $attachment, true );
          // Add post meta for the thumbnail of the attachment on the post
          add_post_meta($post_id, '_thumbnail_id', $attachment_id);
          // Add post meta for the attachment_id and the image subpath
          add_post_meta($attachment_id, '_wp_attached_file', $img_sub_path, true );
      }
      // Set transaction type to 'Add'
      $trans_type = "add";
    }

    // Update various _dntly_* meta
    update_post_meta($post_id, '_dntly_data', $_dntly_data);
    update_post_meta($post_id, '_dntly_id', $campaign->id );
    update_post_meta($post_id, '_dntly_account_id', $account_id);
    update_post_meta($post_id, '_dntly_environment', $this->dntly_options['environment']);
    update_post_meta($post_id, '_dntly_amount_raised', $campaign->amount_raised);
    update_post_meta($post_id, '_dntly_goal', $campaign->campaign_goal);

    // Return campaign array
    return array('add' => ($count_campaigns['add']+($trans_type=='add'?1:0)), 'update' => ($count_campaigns['update']+($trans_type=='update'?1:0)), 'skip' => $count_campaigns['skip']);
  }





  /**
   * Look up person
   *
   * Look up a person by making an API request using users email on $_POST
   *
   * @since 0.1
   * @package Donately Wordpress
   * @author Alexander Zizzo, Bryan Shanaver, Bryan Monzon (Fifty and Fifty, LLC)
   * @return [array] JSON data with (boolean) 'success' and 'url' or error
   * @todo Revisit need for die() at end of function
   */
  function lookup_person()
  {
    // Set email from the $_POST variable (assumes used in form on submit)
    $email = $_POST['email'];
    // Suppress logging 
    $this->suppress_logging = true;
    // Set $lookup_person variable by making API request (method : 'person_exists'), authed with token and $email as post variable
    $lookup_person = $this->make_api_request("person_exists", true, array('email' => $email));

    // If API success
    if( isset($lookup_person->success) ){
      // And if there is data set for the person
      if( isset($lookup_person->people[0]) ){
        // Print a JSON array of the success boolean and the person URL
        print json_encode(array('success' => true, 'url' => $lookup_person->people[0]));
      }
      // Else, print JSON array with failure message/details
      else{
        print json_encode(array('success' => false, 'message' => 'No Match Found'));
      }
    }
    // If API failure, print JSON array with failure message/details (in this instance it should be a connection error)
    else{
      print json_encode(array('success' => false, 'message' => 'Connection Error'));
    }
    die();
  }



}
