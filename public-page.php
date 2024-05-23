<?php

/**
 * frontend page shortcode
 */
function wpsci_form_function() {
  global $wpdb;
  global $base_url;
  global $web_url;
  $verified = false; //to check if the client verified his/her mail id
  ob_start();
  //verify guests with email
  if(isset($_POST['verify_email'])){
    
    if(check_booking_type($_REQUEST['id'])=='wpsci'){
      
        $sql = "SELECT gt.booking_id, ch.check_in_key 
        FROM ".$wpdb->prefix."wpsci_guests gt JOIN ".$wpdb->prefix."wpsci_check_in ch 
        ON ch.booking_id = gt.booking_id 
        WHERE gt.booking_id='".$_REQUEST['id']."' 
        AND gt.email = '".$_REQUEST['email']."' LIMIT 1";

        $result = $wpdb->get_row($sql, ARRAY_A);
        if ($result) {
          $url = $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$_REQUEST['id'].'&key='.$result['check_in_key'];
          $verified=true;
          wp_redirect($url);
          exit;
        }
    }else{

      $table_name = $wpdb->prefix ."postmeta";
      $sql = "SELECT * FROM `".$table_name."` where
        `post_id`='".$_REQUEST['id']."' AND
        `meta_key` = 'mphb_email' AND
        `meta_value` = '".$_REQUEST['email']."'";
        if ($wpdb->get_results($sql)) {
          $_key = get_post_meta($_REQUEST['id'], "wp-self-check-in-key", true);
          $url = $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$_REQUEST['id'].'&key='.$_key;
          $verified=true;
          wp_redirect($url);
          exit;
        }
    }
  }

 
  if(isset($_GET['id']) && $_GET['id']!=''){

    $post_id = $_GET['id'];
    
    $booking_id = get_booking_id($post_id);
    $number_of_guest = get_total_guests($booking_id);
    $first_member = get_first_member($booking_id);
    
    $customer_id = get_post_meta($_GET['id'], "mphb_customer_id", true);


    
    //insert/update guest data
    if(isset($_POST['submit_guests'])){
      
      $count = count($_POST['first_name']);
    
      $wpdb->query(
        $wpdb->prepare(
          "DELETE FROM ".$wpdb->base_prefix."wpsci_guests
            WHERE booking_id = %d", $booking_id)
      );
    
      
      for($i=0;$i<$count;$i++){

        //save document
        $destinationPath = WPSCI_UPLOAD_PATH . "/" . date('Y') . "/" . date('m') . "/";

        if (!file_exists($destinationPath)) {
          if (!mkdir($destinationPath, 0777, true)) {
            die("Failed to create directory: $destinationPath");
          }
        }

        $doc_images = [];
        $doc_image = null;
        if(!empty($_FILES['doc_img']['name'][$i][0])){
          
          for ($j=0; $j < count($_FILES['doc_img']['name'][$i]); $j++) {
            $image = $_FILES['doc_img']['name'][$i][$j];
            $imageArr = explode('.',$image);
            if($image){
              $rand = rand(10000,99999);
              $newImageFront = $imageArr[0].$rand.'.'.$imageArr[1];
              $uploadPath = $destinationPath . $newImageFront;
              $isUploaded = move_uploaded_file($_FILES['doc_img']["tmp_name"][$i][$j],$uploadPath);
              $doc_images[] = date('Y') . "/" . date('m') . "/" .$newImageFront;
            }else{
              $doc_images[] = '';
            }
          }

          $reversed_doc_image = str_replace("'", '"', stripslashes($_POST['doc_img_real'][$i]));
          if($reversed_doc_image==""){
            $unserialized_data=array();
          }else{
            $unserialized_data = unserialize($reversed_doc_image);
          }
  
          $array = array_merge($unserialized_data, $doc_images);
    
          $doc_image = serialize($array);
    
        }else{
          if($_POST['doc_img_real'][$i])
          $doc_image = stripslashes(str_replace("'", '"', $_POST['doc_img_real'][$i]));
        }

        // finalise the images
        if(isset($_POST['removed_images'][$i]) && $_POST['removed_images'][$i]!=""){
          $unserialized_data = unserialize($doc_image);
          $removed_images = explode(',', $_POST['removed_images'][$i]);
          foreach($removed_images as $removed_image){
            if (($key = array_search($removed_image, $unserialized_data)) !== false) {
              unset($unserialized_data[$key]);
            }
          }

          $doc_image = serialize($unserialized_data);
        }
        
        $post_email = isset($_POST['email'][$i]) ? $_POST['email'][$i] : null;
        $post_phone = isset($_POST['phone'][$i]) ? $_POST['phone'][$i] : null;

        $wpdb->query($wpdb->prepare(
          "INSERT INTO ".$wpdb->base_prefix."wpsci_guests " .
            '(booking_id, first_name, last_name, email, phone, sex, dob, country, country_code, house, provinces, municipalities, citizenship, doc_type, doc_number, doc_issue_place, doc_issue_province, doc_issue_municipality, doc_image) ' .
            'VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
            $booking_id,
          $_POST['first_name'][$i],
          $_POST['last_name'][$i],
          $post_email,
          $post_phone,
          $_POST['sex'][$i],
          $_POST['dob'][$i],
          $_POST['country'][$i],
          $_POST['country_code'][$i],
          $_POST['house'][$i],
          $_POST['provinces'][$i],
          $_POST['municipalities'][$i],
          $_POST['citizenship'][$i],
          $_POST['doc_type'][$i],
          $_POST['doc_number'][$i],
          $_POST['doc_issue_place'][$i],
          $_POST['doc_issue_province'][$i],
          $_POST['doc_issue_municipality'][$i],
          $doc_image,
        ));

        if($post_email && $post_phone){
          $array = array(
            'name' => $_POST['first_name'][$i],
            'surname' => $_POST['last_name'][$i],
            'email' => $post_email,
            'phone' => $post_phone,
            'country' => $_POST['country'][$i],
            'booking_id' => $booking_id,
          );
          collect_guest_data($array);
        }
      }
    }

 

    //validation
    if($verified===true){
      //if verified
      include_once(plugin_dir_path( __FILE__ ) . 'frontend/index.php');

    }else if(get_current_user_id()==$customer_id){
      
      include_once(plugin_dir_path( __FILE__ ) . 'frontend/index.php');

    }else if(isset($_GET['key']) && $_GET['key']!=''){

      //validate with key
      if(get_post_meta($_GET['id'], "wp-self-check-in-key", true)==$_GET['key'] || get_check_in_key($_GET['id'])==$_GET['key']){

        include_once(plugin_dir_path( __FILE__ ) . 'frontend/index.php');

      }else{

        echo "<h4 align='center'>".__( 'Page Not Found', 'wp-self-check-in' )."!</h4>";
      }
    }elseif($_GET['id']!=''){

      //validate with email
      include_once(plugin_dir_path( __FILE__ ) . 'frontend/templates/validate-by-email.php');
    }else{

      echo "<h4 align='center'>".__( 'Page Not Found', 'wp-self-check-in' )."!</h4>";
    }

  }else{
    
    if(isset($_GET['id']) && $_GET['id']!=''){
      echo "<h4 align='center'>".__( 'Page Not Found', 'wp-self-check-in' )."!</h4>";
    }
  }
  return ob_get_clean();
}

if(get_option('wpsci_license_key_token') && wpsci_validate_license()){
  if(get_option( 'wpsci_plugin' )==1){


    function shortcodes_init(){
      add_shortcode( 'wpsci_form', 'wpsci_form_function' );
    }

    //shortcode hook
    add_action('init', 'shortcodes_init');

  }
}