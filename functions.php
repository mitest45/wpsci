<?php


$web_url = get_site_url();
$base_url = plugin_dir_url( __FILE__ );
$base_path = plugin_dir_path( __FILE__ );
$upload_dir = wp_upload_dir();

define('WPSCI_UPLOAD_PATH', $upload_dir['basedir']);
define('WPSCI_UPLOAD_URL', $upload_dir['baseurl']);
define('WPSCI_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('WPSCI_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('WPSCI_PLUGIN_SLUG', 'wp-self-check-in');
define('WPSCI_LICENSE_SERVER_URL', 'http://localhost/hotel-booking/wp-json/dlm/v1/licenses/');
define('WPSCI_CONSUMER_KEY', 'ck_0e303ff51244b8eb9eb229d750bb11dbe2efd782');
define('WPSCI_CONSUMER_SECRET', 'cs_0917ad82d9bd00501547562dce9996c4c57ddec5');


require_once WPSCI_PLUGIN_PATH . 'init.php';

/**
 * 
 * update checker
 */
require plugin_dir_path( __FILE__ ).'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/iamsajidjaved/Auto-update-WordPress-plugin-or-Theme-from-Github',
	__FILE__,
	'FunPlugin'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');


// Define custom email tags
function wpsci_custom_email_tags( $message ) {
  global $web_url;
  global $post;

  if ( isset( $post ) && is_a( $post, 'WP_Post' ) ) {
    $post_id = $post->ID;
    $_key = get_post_meta($post_id, "wp-self-check-in-key", true);
    
    $_url = $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$post_id.'&key='.$_key;
    $custom_tags = array(
      '%wpsci_form_url%' => $_url,
    );
  
    foreach ( $custom_tags as $tag => $value ) {
      $message['message'] = str_replace( $tag, $value, $message['message'] );
    }
  }

  return $message;
}
add_filter( 'wp_mail', 'wpsci_custom_email_tags' );


/**
 * 
 */
function get_document_list(){
  global $base_url;

  $file_to_read = fopen($base_url."assets/documents/documents.db", 'r');
  $str ='';
  $lines = [];
  while (!feof($file_to_read) ) {
    $lines[] = fgetcsv($file_to_read, 1000, ',');
  }
  fclose($file_to_read);
  return $lines;
}

/**
 * 
 */
function get_country_list(){
  global $base_url;

  $file_to_read = fopen($base_url."assets/documents/states.db", 'r');
  $str ='';
  $lines = [];
  while (!feof($file_to_read) ) {
    $lines[] = fgetcsv($file_to_read, 1000, ',');
  }
  fclose($file_to_read);
  return $lines;
}


/**
 * 
 */
function get_house_list(){
  global $base_url;

  $file_to_read = fopen($base_url."assets/documents/house.db", 'r');
  $str ='';
  $lines = [];
  while (!feof($file_to_read) ) {
    $lines[] = fgetcsv($file_to_read, 1000, ',');
  }
  fclose($file_to_read);
  return $lines;
}


/**
 * 
 */
function get_municipal_list(){
  global $base_url;
  $file_to_read = fopen($base_url."assets/documents/municipalities.db", 'r');
  $str ='';
  $lines = [];
  while (!feof($file_to_read) ) {
    $lines[] = fgetcsv($file_to_read, 1000, ',');
  }
  fclose($file_to_read);
  return $lines;
}

/**
 * 
 */
function check_field($id){
  global $wpdb;

  $query = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."wpsci_guests WHERE booking_id = '".$id."'", OBJECT);
  if($query){
    $result = json_decode(json_encode($query), true);
    $c = 0;
    for ($i=0; $i < sizeof($result); $i++) { 
      if($result[$i]['first_name'] && $result[$i]['last_name'] && $result[$i]['sex'] && $result[$i]['dob'] && $result[$i]['country'] && $result[$i]['citizenship'] && $result[$i]['house']){
        $c++;
      }
    }
    if($c==sizeof($result)){
      if($result[0]['doc_type'] && $result[0]['doc_number'] && $result[0]['doc_issue_place']){
        return 1;
      }
    }else{
      return 0;
    }
  }else{
    return 0;
  }
}


/**
 * 
 */
function get_check_in($id){
  global $wpdb;

  $query=$wpdb->get_row("select meta_value from ".$wpdb->base_prefix."postmeta where post_id='$id' and meta_key ='mphb_check_in_date'");
  $check_in_date = json_decode(json_encode($query), true);
  if($check_in_date){
    return date("d-m-Y", strtotime($check_in_date['meta_value']));
  }else{
    $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
    $check_in_date = json_decode(json_encode($query), true);
    return date("d-m-Y", strtotime($check_in_date['arrival_date']));
  }
}


/**
 * 
 */
function get_check_out($id){
  global $wpdb;

  $query=$wpdb->get_row("select meta_value from ".$wpdb->base_prefix."postmeta where post_id='$id' and meta_key ='mphb_check_out_date'");
  $check_out_date = json_decode(json_encode($query), true);
  if($check_out_date){
    return date("d-m-Y", strtotime($check_out_date['meta_value']));
  }else{
    $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
    $check_out_date = json_decode(json_encode($query), true);
    return date("d-m-Y", strtotime($check_out_date['departure_date']));
  }
}


/**
 * 
 */
function get_check_in_key($id){
  global $wpdb;

  $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
  $data = json_decode(json_encode($query), true);
  if($data)
  return $data['check_in_key'];
}


/**
 * 
 */
function get_receipt($id){
  global $wpdb;

  $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
  $data = json_decode(json_encode($query), true);
  if($data){
    return $data['receipt'];
  }
  $meta_receipt = get_post_meta($id, "wp-self-check-in-receipt", true);
  return $meta_receipt;
}

/**
 * 
 */
function check_booking_type($id){
  global $wpdb;

  $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
  $data = json_decode(json_encode($query), true);
  if($data){
    return 'wpsci';
  }
  return 'mphb';
  // return 'internal';
}


/**
 * 
 */
function save_receipt($id,$img){
  global $wpdb;

  if(check_booking_type($id) == 'wpsci'){
    $wpdb->update($wpdb->base_prefix.'wpsci_check_in', array('receipt' => $img), array('booking_id' => $id), array('%s'),
    array('%d'));
    return true;
  }else{
    add_post_meta($id, "wp-self-check-in-receipt", $img, true);
    return true;
  }
}


/**
 * 
 */
function delete_receipt($id){
  global $wpdb;
  
  if(check_booking_type($id) == 'wpsci'){
    $wpdb->update($wpdb->base_prefix.'wpsci_check_in', array('receipt' => ''), array('booking_id' => $id), array('%s'),
    array('%d'));
    return true;
  }else{
    delete_post_meta($id, "wp-self-check-in-receipt");
    return true;
  }
}


/**
 * 
 */
function get_total_guests($id, $listing = false){
  global $wpdb;

  if($listing){
    $query=$wpdb->get_row("select count(id) as count from ".$wpdb->base_prefix."wpsci_guests where booking_id='$id'");
    $data = json_decode(json_encode($query), true);
    return $data['count'];
  }

  $query=$wpdb->get_row("select meta_value from ".$wpdb->base_prefix."postmeta where post_id='$id' and meta_key ='_mphb_booking_price_breakdown'");
  $result = json_decode(json_encode($query), true);
  if($result){
    $data = json_decode($result['meta_value'], true);
    $sum = 0;
    foreach ($data['rooms'] as $key => $value) {
      $sum += $value['room']['adults'];
    }
    return $sum;

  }else{

    $query=$wpdb->get_row("select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id='$id'");
    $data = json_decode(json_encode($query), true);
    return $data['number_of_guests'];
  }
}

/**
 * 
 */
function get_booking_id($id){
  global $wpdb;

  $query = $wpdb->get_row("select * from ".$wpdb->base_prefix."postmeta where post_id = '$id'");
  $result = json_decode(json_encode($query), true);
  if($result)
  {
    return $result['post_id'];
  }
  else{
    return $id;
  }
}

/**
 * 
 */
function get_first_member($id){
  global $wpdb;

  $query= $wpdb->get_results("select meta_value from ".$wpdb->base_prefix."postmeta where post_id='$id' and meta_key IN('mphb_first_name', 'mphb_last_name')");
  $result = json_decode(json_encode($query), true);
  if($result){
    return $result;
  }
}

/**
 * check if MPHB is active
 */
function is_mphb_active(){
  if(menu_page_url( 'mphb_booking_menu', false )){
    return true;
  }else{
    return false;
  }
}

/**
 * Send mail
 */
function wpsci_send_mail($booking_id, $to = null, $subject = null, $header= null, $message= null, $footer= null){
  global $wpdb;

  if(!$subject) $subject = get_option( 'wpsci_email_subject');

  if(!$message) $message = get_option( 'wpsci_email_message');

  if(!$header) $header = get_option( 'wpsci_email_header');

  if(!$footer) $footer = get_option( 'wpsci_email_footer');

  $headers = array(
    'Content-Type: text/html; charset=UTF-8',
  );

  if(check_booking_type($booking_id)=='wpsci'){

    $sql = "SELECT ci.*, gt.first_name as name, gt.last_name as surname, gt.phone, gt.email FROM ".$wpdb->base_prefix."wpsci_guests gt JOIN ".$wpdb->base_prefix."wpsci_check_in ci ON gt.booking_id=ci.booking_id WHERE ci.booking_id = '$booking_id' LIMIT 1";
    $results = $wpdb->get_row($sql, ARRAY_A);
  
    if(!$to) $to = $results['email'];

    $_url = site_url().'/'.get_option( 'wpsci_public_page' ).'?id='.$results['booking_id'].'&key='.$results['check_in_key'];
    
    $custom_tags = array(
      '%wpsci_form_url%' => $_url,
      '%booking_id%' => $results['booking_id'],
      '%site_title%' => get_bloginfo('name'),
      '%check_in_date%' => date('d-M-Y', strtotime($results['arrival_date'])),
      '%check_out_date%' => date('d-M-Y', strtotime($results['departure_date'])),
      '%guest_first_name%' => $results['name'],
      '%guest_last_name%' => $results['surname'],
      '%guest_email%' => $results['email'],
      '%guest_phone%' => $results['phone'],
      '%number_of_guests%' => $results['number_of_guests'],
    );
  
  }else{

    if(!$to) $to = get_post_meta($booking_id, "mphb_email", true);

    $_key = get_post_meta($booking_id, "wp-self-check-in-key", true);
    $_url = site_url().'/'.get_option( 'wpsci_public_page' ).'?id='.$booking_id.'&key='.$_key;
    $custom_tags = array(
      '%wpsci_form_url%' => $_url,
      '%booking_id%' => $booking_id,
      '%site_title%' => get_bloginfo('name'),
      '%check_in_date%' => date('d-M-Y', strtotime(get_check_in($id))),
      '%check_out_date%' => date('d-M-Y', strtotime(get_check_out($id))),
      '%guest_first_name%' => get_post_meta($booking_id, "mphb_first_name", true),
      '%guest_last_name%' => get_post_meta($booking_id, "mphb_last_name", true),
      '%guest_email%' => get_post_meta($booking_id, "mphb_email", true),
      '%guest_phone%' => get_post_meta($booking_id, "mphb_phone", true),
      '%number_of_guests%' => get_total_guests($booking_id),
    );
  }

  foreach ( $custom_tags as $tag => $value ) {
    $subject = str_replace($tag, $value, $subject);
    $header = str_replace($tag, $value, $header);
    $message = str_replace($tag, $value, $message);
    $footer = str_replace($tag, $value, $footer);
  }
  
  include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/notification/email-template.php');
  $message = wpsciEmailTemplate::getTemplate($header, $message, $footer);
  
  $result = wp_mail($to, $subject, $message, $headers);

  if($result) return true;
  else return false;
}

/**
 * 
 * get selected municipal
 */
function get_selected_municipal($str){

  $list = get_municipal_list();

  $items = array_filter($list, function($item) use ($str) {
    return isset($item[2]) && $item[2] === $str;
  });
  
  return $items;
}

/**
 * 
 * validate user in guest data
 */
function validate_guest_data($email, $phone){
  global $wpdb;

  $table_name = $wpdb->prefix . 'wpsci_guests_data';

  $query = "SELECT id FROM $table_name WHERE email= '$email' AND phone= '$phone'";
  return $wpdb->get_row($query, ARRAY_A);
}


/**
 * 
 * Collect guest data
 */
function collect_guest_data($array= array()){
  global $wpdb;

  $table_name = $wpdb->prefix . 'wpsci_guests_data';

  if(!validate_guest_data($array['email'], $array['phone'])){

    $data = array(
      'name'        => $array['name'],
      'surname'     => $array['surname'],
      'email'       => $array['email'],
      'phone'       => $array['phone'],
      'country'     => $array['country'],
      'booking_id'  => $array['booking_id'],
      'stay_period' => date('d-m-Y', strtotime(get_check_in($array['booking_id']))) .' / '. date('d-m-Y', strtotime(get_check_out($array['booking_id']))),
    );
  
    // Insert data
    $wpdb->insert($table_name, $data);  
  }
}

/**
 * 
 * Handle form submission and insert into guest data
 */
add_action('admin_post_edit_guests_data', 'edit_guests_data_handler');
add_action('admin_post_nopriv_edit_guests_data', 'edit_guests_data_handler');

function edit_guests_data_handler() {

  // Check if user has permission
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  // Verify nonce
  check_admin_referer('edit_guests_data-options');

  // Sanitize and store form data
  $name = sanitize_text_field($_POST['wpsci_name']);
  $surname = sanitize_text_field($_POST['wpsci_surname']);
  $email = sanitize_email($_POST['wpsci_email']);
  $phone = sanitize_text_field($_POST['wpsci_phone']);
  $country = sanitize_text_field($_POST['wpsci_country']);

  global $wpdb;
  $table_name = $wpdb->prefix . 'wpsci_guests_data';

  // update data
  $wpdb->update(
    $table_name,
    array(
      'name' => $name,
      'surname' => $surname,
      'email' => $email,
      'phone' => $phone,
      'country' => $country,
    ),
    array(
      'id' => $_POST['guest_id']
    )
  );

  wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=guests-data&saved=1'));
}

/**
 * 
 * delete guest data
 */
add_action('admin_post_delete_guest_data', 'delete_guest_data_handler');
add_action('admin_post_nopriv_delete_guest_data', 'delete_guest_data_handler');

function delete_guest_data_handler(){
  global $wpdb;
  $table_name = $wpdb->prefix . 'wpsci_guests_data';

  $wpdb->delete(
    $table_name,
    array(
      'id' => $_POST['guest_id']
    )
  );

  wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=guests-data&deleted=1'));
}

/**
 * 
 * plugin settings
 */
add_action('admin_post_wpsci_settings', 'wpsci_settings_handler');
add_action('admin_post_nopriv_wpsci_settings', 'wpsci_settings_handler');

function wpsci_settings_handler(){
  
  update_option( 'wpsci_plugin', $_REQUEST['digital_plugin'] );
  update_option( 'wpsci_document_field', $_REQUEST['_document_field'] );
  update_option( 'wpsci_guests_email', $_REQUEST['_wpsci_guests_email'] );
  update_option( 'wpsci_guests_phone', $_REQUEST['_wpsci_guests_phone'] );
  update_option( 'wpsci_public_page',$_REQUEST['digital_public_page'] );
  
  wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=setting&saved=1'));
}

/**
 * 
 * notification settings
 */
add_action('admin_post_wpsci_notification', 'wpsci_notification_handler');
add_action('admin_post_nopriv_wpsci_notification', 'wpsci_notification_handler');

function wpsci_notification_handler(){
  
  update_option( 'wpsci_email_subject', $_REQUEST['wpsci_email_subject'] );
  update_option( 'wpsci_email_header',  $_REQUEST['wpsci_email_header'] );
  update_option( 'wpsci_email_message', $_REQUEST['wpsci_email_message'] );
  update_option( 'wpsci_email_footer', $_REQUEST['wpsci_email_footer'] );

  wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=notification&saved=1'));
}

/**
 * 
 * get guest data
 */
function wpsci_get_guest_data($id){
  global $wpdb;

  $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}wpsci_guests_data WHERE id = %d", $id);
  
  if($sql)
  return $wpdb->get_row($sql, ARRAY_A);
  else
  return array();
}

/**
 * 
 * get check-in data
 */
function get_wpsci_checkin_data($id){
  global $wpdb;

  $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}wpsci_check_in WHERE booking_id = %d", $id);

  if($sql)
  return $wpdb->get_row($sql, ARRAY_A);
  else
  return array();
}

/**
 * 
 * export guest data
 */
add_action('admin_post_export_guest_data_csv', 'export_guest_data_csv');

function export_guest_data_csv() {

  global $wpdb;
  $table_name = $wpdb->prefix . 'wpsci_guests_data';

  $results = $wpdb->get_results("SELECT name, surname, email, phone, country, booking_id, stay_period FROM $table_name", ARRAY_A);

  if (empty($results)) {
    wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=guests-data'));
  }

  // Set CSV headers
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=guests-data.csv');

  // Open output stream
  $output = fopen('php://output', 'w');

  // Output column headers
  fputcsv($output, array('Name', 'Surname', 'Email', 'Phone', 'Country', 'Booking ID', 'Stay Period'));

  // Output rows
  foreach ($results as $row) {
    fputcsv($output, $row);
  }

  // Close output stream
  fclose($output);
  exit;
}

/**
 * 
 * redirect function
 */
function wpsci_redirect($url = null){
  $_url = menu_page_url('wp-self-check-in', false);
  if($url) $_url = $url;

  ob_end_clean();
  if (headers_sent()) {
    echo '<meta http-equiv="refresh" content="0;url='.$_url.'">';
    exit;
  } else {
    wp_redirect($_url);
    exit;
  }
}

//Including Admin and View Page//
if(is_admin()){
  
  require_once WPSCI_PLUGIN_PATH . 'admin-page.php';
}else{
  
  require_once WPSCI_PLUGIN_PATH . 'public-page.php'; 
}