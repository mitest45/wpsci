<?php

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', plugin_dir_path(__FILE__) . 'error_log.txt');

/**
 * 
 * add license page
 */
add_action('admin_menu', 'wpsci_license_menu');

function wpsci_license_menu() {

  $title = __( 'WP Self Check-In Pro Licensing', 'wp-self-check-in' );
  add_options_page($title, $title, 'manage_options', 'wp-self-check-in-license', 'wpsci_license_page');
}

function wpsci_license_page() {
  include WPSCI_PLUGIN_PATH . 'includes/licensing.php';
}

/**
 * 
 * validate license
 */
function wpsci_validate_license(){

  if(!get_option('wpsci_license_key_token')){
    return true;
  }
  
  if(!get_option('wpsci_license_key_validate')){
    return false;
  }

  $last_date        = new DateTime(date('Y-m-d H:i:s', get_option('wpsci_license_key_validate')));
  $till_date       = $last_date->diff(new DateTime(date('Y-m-d H:i:s')));
  if($till_date->d >= 1){
 
    $action = 'validate';
    $api_url = WPSCI_LICENSE_SERVER_URL . $action . '/' . get_option('wpsci_license_key_token');
    $api_url = add_query_arg(array(
      'consumer_key'    => WPSCI_CONSUMER_KEY,
      'consumer_secret' => WPSCI_CONSUMER_SECRET,
    ), $api_url);
  
    $curl = curl_init();
  
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $response = json_decode($response);
  
    if($response->data->license->is_expired == '1'){
  
      delete_option('wpsci_license_key_validate');
      return false;
    }else{
      
      update_option('wpsci_license_key_validate', time());
      return true;
    }
  }else{
    return true;
  }

}

/**
 * Translation hook
 */
function myplugin_init() {
    $pluginDir = plugin_dir_path( __FILE__ );
    $pluginDir = plugin_basename( $pluginDir ); 
    load_plugin_textdomain( 'wp-self-check-in', false, $pluginDir . '/languages' );
}

add_action('init', 'myplugin_init');


/**
 * 
 * Load assets
 */
function enqueue_tinymce() {
  // Enqueue TinyMCE JavaScript file
  wp_enqueue_script('tinymce', includes_url() . 'js/tinymce/tinymce.min.js', array(), false, true);

  // Enqueue TinyMCE CSS file
  wp_enqueue_style('editor-style', includes_url() . 'css/editor.min.css');
}
add_action('admin_enqueue_scripts', 'enqueue_tinymce');

//Including assets//

add_action('init', '_digital_register_scripts');

add_action('wp_enqueue_scripts', '_digital_enqueue_public_scripts');
add_action('admin_enqueue_scripts', '_digital_enqueue_admin_scripts', 10);

function _digital_register_scripts()
{ 
  $version   = '1.0.0';

  wp_register_style('digital-styles', WPSCI_PLUGIN_URL . 'assets/css/digital-style.css', [], $version);
  wp_register_script('digital-script', WPSCI_PLUGIN_URL . 'assets/js/digital-script.js', ['jquery'], $version, 10);
  wp_localize_script('digital-script', 'wpsci', array(
    'ajaxurl' => admin_url('admin-ajax.php')
  ));
}

function _digital_enqueue_public_scripts()
{
  wp_enqueue_style('digital-styles');
  wp_enqueue_script('digital-script');
}

function _digital_enqueue_admin_scripts()
{
  wp_enqueue_style('digital-styles');
  wp_enqueue_script('digital-script');
}

//
require_once WPSCI_PLUGIN_PATH . 'includes/ajax.php';
require_once WPSCI_PLUGIN_PATH . 'includes/download_report.php';