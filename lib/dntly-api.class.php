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
  function build_api_methods()
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
  function make_api_request( $api_method, $auth=true, $post_variables=null )
  {
    // Set $url variable after running the $api_method through the build_url() function
    $url = $this->build_url( $api_method );

    // If console_calls is not empty in $dntly_options AND logging is NOT suppressed
    if( !empty($this->dntly_options['console_calls']) && !$this->do_not_log() ) {
      // Log the transaction with API url, API post args, then print the debug results
      dntly_transaction_logging("\n" . "api url: " . $url . "\n" . "api post args: " . (sizeof($post_variables) ? print_r($post_variables, true) : '') . "\n", 'print_debug');
    }

    // If $auth is true, set $session_token to 'token' value in $dntly_options, set $authorization to base64 encoded token, and prep them as $header var to later send
    if( $auth ){
      $session_token = $this->dntly_options['token'];
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




}
