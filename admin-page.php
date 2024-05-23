<?php 
if(get_option('wpsci_license_key_token') && wpsci_validate_license()){
  if(get_option( 'wpsci_plugin' )==1){
    if(isset($_GET['post'])){
      $get_post_type = $GLOBALS['wpdb']->get_row("select * from ".$wpdb->base_prefix."posts where ID = '".$_GET['post']."'");
      $get_post_type = json_decode(json_encode($get_post_type), true);
      if($get_post_type['post_type']=='mphb_booking')
      add_action( 'add_meta_boxes', 'admin_meta_boxes' );
    }
  }
}

/**
 * initialize meta boxes
 */
function admin_meta_boxes() {
  _admin_meta_box();
  _admin_side_meta_box();
  _admin_receipt_meta_box();
}

/**
 * guest information meta box
 */
function _admin_meta_box() {
  $title = __( 'Guest Information', 'wp-self-check-in' );
  add_meta_box('guest_info', $title, 'wpsci_admin', $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null );
}

/**
 * side meta box
 */
function _admin_side_meta_box() {
  $title = __( 'WP Self Check-In Pro', 'wp-self-check-in' );
  add_meta_box( 'guest_side_bar', $title, 'wpsci_side_bar', $screen = null, $context = 'side', $priority = 'default', $callback_args = null );
}

/**
 * receipt meta box
 */
function _admin_receipt_meta_box() {
  $title = __( 'WP Self Check-In Receipt', 'wp-self-check-in' );
  add_meta_box( 'side_bar_upload', $title, 'wpsci_receipt', $screen = null, $context = 'side', $priority = 'default', $callback_args = null );
}


/**
 *  _admin_meta_box() callback fn
 */
function wpsci_admin()
{
  global $base_url;
  global $base_path;
  global $web_url;
  global $wpdb;

  include_once(plugin_dir_path( __FILE__ ) . 'admin/meta-boxes/guests-info.php');
} 

/**
 *  _admin_side_meta_box() callback fn
 */
function wpsci_side_bar()
{
  global $base_url;
  global $base_path;
  global $web_url;

  include_once(plugin_dir_path( __FILE__ ) . 'admin/meta-boxes/side-box.php');
}

/**
 *  _admin_receipt_meta_box() callback fn
 */
function wpsci_receipt()
{
  global $base_url;
  global $base_path;

  include_once(plugin_dir_path( __FILE__ ) . 'admin/meta-boxes/receipt-box.php');
}


/**
 * admin menu callback function
 */
function wpsci_plugin_setting_page() { 
  global $wpdb;
  global $wp;
  global $web_url;
  global $base_url;
  global $base_path;

  /**
   * Save data for overview page
   */
  if(isset($_POST['update_overview'])){
    
    $count = count($_POST['first_name']);

    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM ".$wpdb->base_prefix."wpsci_guests
          WHERE booking_id = %d", $_POST['booking_id'])
    );
    
    
    for($i=0;$i<$count;$i++){

      $destinationPath = WPSCI_UPLOAD_PATH . "/" . date('Y') . "/" . date('m') . "/";
      if (!file_exists($destinationPath)) {
        if (!mkdir($destinationPath, 0777, true)) {
          die("Failed to create directory: $destinationPath");
        }
      }

      $doc_images = [];
      $doc_image = null;
      if(!empty($_FILES['doc_img']['name'][$i][0])){
        for ($j=0; $j < count($_FILES['doc_img']['name'][$i]) ; $j++) { 
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
        if(isset($_POST['doc_img_real'][$i]) && $_POST['doc_img_real'][$i])
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
      
      $_booking_id = $_POST['booking_id'];
      if($_POST['updated_booking_id'] && check_booking_type($_booking_id)=='wpsci'){
        $_booking_id = $_POST['updated_booking_id'];
      }

      $wpdb->query($wpdb->prepare(
        "INSERT INTO ".$wpdb->base_prefix."wpsci_guests " .
          '(booking_id, first_name, last_name, email, phone, sex, dob, country, country_code, house, provinces, municipalities, citizenship, doc_type, doc_number, doc_issue_place, doc_issue_province, doc_issue_municipality, doc_image) ' .
          'VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
          $_booking_id,
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
        $doc_image
      ));

      if($post_email && $post_phone){
        $array = array(
          'name' => $_POST['first_name'][$i],
          'surname' => $_POST['last_name'][$i],
          'email' => $post_email,
          'phone' => $post_phone,
          'country' => $_POST['country'][$i],
          'booking_id' => $_booking_id,
        );
        collect_guest_data($array);
      }
    }


    //updating data in checks-in
    if(check_booking_type($_POST['booking_id'])=='wpsci'){
      $sql = "Update `".$wpdb->base_prefix."wpsci_check_in` set 
      `first_name` = '".$_POST['first_name'][0]."',
      `last_name` = '".$_POST['last_name'][0]."',
      `arrival_date` = '".$_POST['arrival_date']."',
      `departure_date` = '".$_POST['departure_date']."',
      `booking_id` = '".$_booking_id."',
      `number_of_guests` = '".$count."'
      where id='".$_POST['check_in_id']."' AND
      booking_id='".$_POST['booking_id']."'
      ";
      $wpdb->query($wpdb->prepare($sql));
    }else{
      update_post_meta($_POST['booking_id'], "mphb_check_in_date", $_POST['arrival_date']);
      update_post_meta($_POST['booking_id'], "mphb_check_out_date", $_POST['departure_date']);

      $data = get_post_meta($_POST['booking_id'], '_mphb_booking_price_breakdown', true);
      $data = json_decode($data);
      if(get_total_guests($_POST['booking_id'] != $count)){
        $data->rooms[0]->room->adults = $count;
        update_post_meta($_POST['booking_id'], "_mphb_booking_price_breakdown", json_encode($data));
      }

    }

    wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&saved=1'));
  }

  /**
   * delete the records
   */
  if(isset($_POST['delete_record'])){

    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM ".$wpdb->base_prefix."wpsci_guests
          WHERE booking_id = %d", $_POST['booking_id'])
    );

    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM ".$wpdb->base_prefix."wpsci_check_in
          WHERE booking_id = %d", $_POST['booking_id'])
    );

    wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&deleted=1'));
  }


  /**
   * Created check in / save data
   */
  if(isset($_POST['save_check_in'])){

    $random_id = 10000;
    $check_in_key=wp_generate_password(12,false);

    $wpdb->query($wpdb->prepare(
      "INSERT INTO ".$wpdb->base_prefix."wpsci_check_in " .
        '(first_name, last_name, booking_id, arrival_date, departure_date, number_of_guests, check_in_key) ' .
        'VALUES (%s, %s, %s, %s, %s, %s, %s)',
      $_POST['first_name'],
      $_POST['last_name'],
      $random_id,
      $_POST['arrival_date'],
      $_POST['departure_date'],
      $_POST['number_of_guests'],
      $check_in_key
    ));

    $last_insert_id = $wpdb->insert_id;
    $new_booking_id = $wpdb->insert_id;

    if(isset($_POST['custom_booking_id']) && $_POST['custom_booking_id']!='')
    $new_booking_id = $_POST['custom_booking_id'];

    if ($last_insert_id) {
      $wpdb->update(
        $wpdb->base_prefix."wpsci_check_in",
        array(
          'booking_id' => $new_booking_id,
        ),
        array(
          'id' => $last_insert_id,
        )
      );
    }
    
    for($i=0;$i<$_POST['number_of_guests'];$i++){
      if($i==0){
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $post_email = isset($_POST['email']) ? $_POST['email'] : null;
        $post_phone = isset($_POST['phone']) ? $_POST['phone'] : null;
      }else{
        $post_email = null;
        $post_phone = null;
        $first_name = null;
        $last_name = null;
      }
      $wpdb->query($wpdb->prepare(
              "INSERT INTO ".$wpdb->base_prefix."wpsci_guests " .
                '(booking_id, first_name, last_name, email, phone) ' .
                'VALUES (%s, %s, %s, %s, %s)',
              $new_booking_id,
              $first_name,
              $last_name,
              $post_email,
              $post_phone
            ));
    }
    
    wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&saved=1'));
  }
  
  /**
   * save receipt
   */
  if(isset($_POST['save_receipt'])){
    
    $destinationPath = WPSCI_UPLOAD_PATH . "/" . date('Y') . "/" . date('m') . "/";

    if (!file_exists($destinationPath)) {
      if (!mkdir($destinationPath, 0777, true)) {
        die("Failed to create directory: $destinationPath");
      }
    }

    $id = $_POST['booking_id'];
    $image=$_FILES['book_receipt']['name'];
    $imageArr=explode('.',$image);
    if($image && $image!=''){
      $rand=rand(10000,99999);
      $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
      $uploadPath=$destinationPath . $newImageName;
      $isUploaded=move_uploaded_file($_FILES["book_receipt"]["tmp_name"],$uploadPath);
      if($isUploaded){
        $newImageName = date('Y') . "/" . date('m') . "/" .$newImageName;
        save_receipt($id,$newImageName); 
      }
    }

    wpsci_redirect(basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING']);
  }

  /**
   * delete receipt
   */
  if(isset($_POST['delete_receipt'])){
    if (get_receipt($_REQUEST['booking_id']))  
    {
      delete_receipt($_REQUEST['booking_id']); 
    }
    wp_redirect(basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING']);
    echo '<meta http-equiv="refresh" content="0;url='.basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING'].'">';
    exit;
  }

  /**
   * send email notification
   */
  if(isset($_POST['send_notification'])){
    if ($_REQUEST['booking_id'])
    {
      wpsci_send_mail($_REQUEST['booking_id']);
    }
  }


  $wp->parse_request();
  $page_url = home_url( $wp->request );
  $main_page = $_GET['page'];
  ?>

  <?php if(isset($_GET['tab']) && $_GET['tab']=='setting'){

    /**
     * setttings tab
     */
    include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/settings/settings.php');
    
  }elseif(isset($_GET['tab']) && $_GET['tab']=='notification'){
    
    /**
     * notification tab
     */
    include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/notification/notification.php');
    
  }elseif(isset($_GET['tab']) && $_GET['tab']=='guests-data'){
    
    /**
     * notification tab
     */
    if(isset($_GET['edit']) && $_GET['edit']!=''){

      include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/guests-data/edit-guests-data.php');
    }else{
      
      include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/guests-data/guests-data.php');
    }

  }else{

    /**
     * overview tab
     */
    if(isset($_GET['edit']) && $_GET['edit']!=''){

      include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/overview/edit-overview.php');
    }else{
      
      include_once(plugin_dir_path( __FILE__ ) . 'admin/tabs/overview/overview.php');
    }
  }?>


<?php } 


/**
 * admin menu hook function
 */
function wpsci_plugin_setting_function() {
  $title = __( 'WP Self Check-In Pro', 'wp-self-check-in' );
  $menu_url = menu_page_url( 'mphb_booking_menu', false );

  if ( $menu_url ) {
    add_submenu_page( 'mphb_booking_menu', $title,  $title, 'manage_options', 'wp-self-check-in', 'wpsci_plugin_setting_page');
  } else {
    add_menu_page( $title, $title,'manage_options', 'wp-self-check-in', 'wpsci_plugin_setting_page', $icon_url='', 30 );
  }
  
}

//admin menu hook
add_action('admin_menu', 'wpsci_plugin_setting_function',20);