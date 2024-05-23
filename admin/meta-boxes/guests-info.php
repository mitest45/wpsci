<?php
/**
 * 
 * UPDATE RECORDS
 * 
 */
if(isset($_POST['update'])){
  $count = count($_POST['first_name']);
  $booking_id = $_GET['post'];
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

    if(!empty($_FILES['doc_img']['name'][$i][0])){

      $doc_images = [];
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


/**
 * FETCH RECORDS
 */
$id = $_GET['post'];
$result = $GLOBALS['wpdb']->get_results("SELECT * FROM ".$wpdb->base_prefix."wpsci_guests WHERE booking_id = '$id'", OBJECT);
$res = json_decode(json_encode($result), true);
if($result){

/**
 * 
 * ADMIN PAGE TABLE START
 */
?>


      <div class="table-res container" id="guest_table">
        <h3>
          <?php _e( 'Guest Information for Booking', 'wp-self-check-in' );?> #<?php echo $res[0]['booking_id'];?>
        </h3>
        <table class="form-table">
          <thead>
            <th width="10%"><?php _e( 'Sr No.', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Name', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Date of Birth', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Country of Birth', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Document Type', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Document Number', 'wp-self-check-in' );?></th>
            <th width="15%"><?php _e( 'Document Image', 'wp-self-check-in' );?></th>
          </thead>
          <tbody>
            <?php
            $i = 0;
            foreach ($res as $key => $value) {
              $i++;
            ?>

              <tr class="mphb-customer-field-wrap">
                <td>
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $value['first_name'] . ' ' . $value['last_name']; ?>
                </td>
                <td>
                  <?php if($value['dob']!='0000-00-00')
                  echo date("d-m-Y", strtotime($value['dob']));?>
                </td>
                <td>
                  <?php echo $value['country']; ?>
                </td>
                <td>
                  <?php echo $value['doc_type']; ?>
                </td>
                <td>
                  <?php echo $value['doc_number']; ?>
                </td>
                <td>
                  <?php if( $value['doc_image']){
                    $files = unserialize($value['doc_image']);
                    if(pathinfo($files[0], PATHINFO_EXTENSION)=='pdf'){
                    ?>
                    <span><?php echo $files[0];?></span>
                    <?php }else{?>
                      <span class="image-preview">
                        <?php 
                        foreach($files as $file){
                            if($file!=""){
                            echo "<img src='".WPSCI_UPLOAD_URL."/$file' class='preview-image'>";
                            }
                        }
                        ?>
                      </span>
                  <?php }}?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
          <input type="hidden" value="<?php echo $i;?>" id="guest_count">
        </table>
        <div class="col-12 mt-3 mb-3">
          <form method="POST" id="edit_form" class="dis-in-block" enctype="multipart/form-data">
            <input type="hidden" name="booking_id">
            <button type="button" name="edit" onclick="edit_form()" class="button button-primary"><?php _e( 'Edit', 'wp-self-check-in' );?></button>
          </form>
          <form method="POST" id="download_form" class="dis-in-block">
            <input type="hidden" name="booking_id" value="<?php echo $_GET['post'];?>">
            <?php
            if(check_field($_GET['post'])){
              ?>
              <button type="submit" name="_download" id="_download" class="button button-primary"><?php _e( 'Download', 'wp-self-check-in' );?></button>
              <?php
            }?>
          </form>
        </div>
      </div>
<!-- ------------------------------------------- -->

<!-- ADMIN PAGE TABLE END -->

<!-- ------------------------------------------- -->


<!-- ------------------------------------------- -->

<!-- ADMIN PAGE UPDATE FORM START -->

<!-- ------------------------------------------- -->

      <div class="inside hide" id="edit_wrap">
        <form method="POST" id="update_form" enctype="multipart/form-data">
          <?php
              $i = 0;
              foreach ($res as $key => $value) {
                $i++;
                ?>

            <table class="form-table">
              <h3><?php echo $i; ?>. <?php _e( 'Guest Information', 'wp-self-check-in' );?></h3>
              <tbody>
                <tr>
                  <th><label for=""><?php _e( 'Name', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="first_name[]" id="first_name<?php echo $i; ?>" value="<?= ($i==1) ? get_post_meta($_GET['post'], 'mphb_first_name', true) : $value['first_name']; ?>" class=" regular-text" type="text" <?= ($i==1) ? 'readonly':'';?>>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><label for=""><?php _e( 'Surname', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="last_name[]" id="last_name<?php echo $i; ?>" value="<?= ($i==1) ? get_post_meta($_GET['post'], 'mphb_last_name', true) : $value['last_name']; ?>" class=" regular-text" type="text" <?= ($i==1) ? 'readonly':'';?>>
                    </div>
                  </td>
                </tr>
                <?php if($i==1){?>
                  <tr>
                    <th><label for=""><?php _e( 'Email', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="email[]" id="email<?php echo $i; ?>" value="<?= get_post_meta($_GET['post'], 'mphb_email', true) ?>" class=" regular-text" type="text" readonly>
                      </div>
                    </td>
                  </tr>
                <?php }elseif(get_option('wpsci_guests_email') =='1'){?>
                  <tr>
                    <th><label for=""><?php _e( 'Email', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="email[]" id="email<?php echo $i; ?>" value="<?= $value["email"] ?>" class=" regular-text" type="text">
                      </div>
                    </td>
                  </tr>
                <?php }?>
                <?php if($i==1){?>
                  <tr>
                    <th><label for=""><?php _e( 'Phone', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="phone[]" id="phone<?php echo $i; ?>" value="<?= get_post_meta($_GET['post'], 'mphb_phone', true) ?>" class=" regular-text" type="text" readonly>
                      </div>
                    </td>
                  </tr>
                <?php }elseif(get_option('wpsci_guests_phone') =='1'){?>
                  <tr>
                    <th><label for=""><?php _e( 'Phone', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="phone[]" id="phone<?php echo $i; ?>" value="<?= $value["phone"] ?>" class=" regular-text" type="text">
                      </div>
                    </td>
                  </tr>
                <?php }?>
                <tr>
                  <th><label for=""><?php _e( 'Sex', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="sex[]" id="sex<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <option value="male" <?php if("male"==$value["sex"]) echo "selected";?>><?php _e( 'Male', 'wp-self-check-in' );?></option>
                        <option value="female" <?php if("female"==$value["sex"]) echo "selected";?>><?php _e( 'Female', 'wp-self-check-in' );?></option>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><label for=""><?php _e( 'Date of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="dob[]" id="dob<?php echo $i; ?>" value="<?php echo $value['dob']; ?>" class=" regular-text" type="date">
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><label for=""><?php _e( 'Country of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="country_code[]" class="ccode" id="country_code<?php echo $i; ?>" onchange="get_country_name(<?php echo $i;?>)">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $line = get_country_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
                          ?>
                          <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>" <?php if($item[0]==$value["country_code"]) echo "selected";?>><?php echo $item[1];?></option>
                          <?php
                        }
                        ?>
                      </select>
                      <input type="hidden" name="country[]" id="country<?php echo $i; ?>" value="<?php echo $value['country'];?>">
                    </div>
                  </td>
                </tr>
                <?php if($value["country_code"]==100000100){?>
                <tr id="provinces<?php echo $i; ?>">
                  <th><label for=""><?php _e( 'Province of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="provinces[]" id="provinces<?php echo $i; ?>" onchange="get_municipal(<?php echo $i;?>)">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                         $province=array();
                        $line = get_municipal_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
              
                          $province[$item[2]]=$item[3];
                         
                        }
                        asort($province);
                        foreach ($province as $key=>$val) { 
                          
                          ?>
                         <option value="<?php echo $key?>" <?php if($key==$value["provinces"]) echo "selected";?>><?php echo $val; ?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr id="municipal<?php echo $i; ?>">
                  <th><label for=""><?php _e( 'Municipality of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="municipalities[]" id="municipalities<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $municipal_list = get_selected_municipal($value["provinces"]);
                        foreach($municipal_list as $key => $item){
                          ?>
                         <option value="<?php echo $item[0]?>" data-provice="<?php echo $item[2];?>" <?php if($item[0]==$value["municipalities"]) echo "selected";?>><?php echo $item[1];?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <?php }else {?>
                  <tr id="provinces<?php echo $i; ?>" class="dis-none">
                  <th><label for=""><?php _e( 'Province of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="provinces[]" id="provinces<?php echo $i; ?>" onchange="get_municipal(<?php echo $i;?>)">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                         $province=array();
                        $line = get_municipal_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
              
                          $province[$item[2]]=$item[3];
                         
                        }
                        asort($province);
                        foreach ($province as $key=>$val) { 
                          
                          ?>
                         <option value="<?php echo $key?>" <?php if($key==$value["provinces"]) echo "selected";?>><?php echo $val; ?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr id="municipal<?php echo $i; ?>" class="dis-none">
                  <th><label for=""><?php _e( 'Municipality of Birth', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="municipalities[]" id="municipalities<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        
                      </select>
                    </div>
                  </td>
                </tr>
                  <?php }?>
                <tr>
                  <th><label for=""><?php _e( 'Guest Type', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="house[]" id="house<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $line = get_house_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
                          ?>
                         <option value="<?php echo  $item[0];?>" <?php if($item[0]==$value["house"]) echo "selected";?>><?php echo  $item[1];?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th><label for=""><?php _e( 'Citizenship', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="citizenship[]" id="citizenship<?php echo $i+1; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $line = get_country_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
                          ?>
                          <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>" <?php if($item[0]==$value["citizenship"]) echo "selected";?>><?php echo $item[1];?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr class="<?php if($i>1) echo "dis-none";?>">
                  <th><label for=""><?php _e( 'Document Type', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="doc_type[]" id="doc_type<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $line = get_document_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
                          ?>
                          <option value="<?php echo $item[0];?>" <?php if($item[0]==$value["doc_type"]) echo "selected";?>><?php echo $item[1];?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr class="<?php if($i>1) echo "dis-none";?>">
                  <th><label for=""><?php _e( 'Document Number', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="doc_number[]" id="doc_number<?php echo $i; ?>" value="<?php echo $value['doc_number']; ?>" class=" regular-text" type="text">
                    </div>
                  </td>
                </tr>
                <tr id="poid_country<?php echo $i?>" class="<?php if($i>1) echo "dis-none";?>">
                  <th><label for=""><?php _e( 'Place of Issue of Document', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                    <select name="doc_issue_place[]" id="doc_issue_place<?php echo $i; ?>" onchange="get_doc_place(<?php echo $i;?>)">
                      <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                      <?php 
                      $line = get_country_list();
                      for ($j=0; $j < sizeof($line); $j++) { 
                        $item = $line[$j];
                        ?>
                        <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>" <?php if($item[0]==$value["doc_issue_place"]) echo "selected";?>><?php echo $item[1];?></option>
                        <?php
                      }
                      ?>
                    </select>
                    </div>
                  </td>
                </tr>
                <?php if($value["doc_issue_place"]==100000100){?>
                <tr id="doc_provinces<?php echo $i; ?>">
                  <th><label for=""><?php _e( 'Province of Issue Document', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="doc_issue_province[]" id="doc_issue_province<?php echo $i; ?>" onchange="get_doc_municipal(<?php echo $i;?>)">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                         $province=array();
                        $line = get_municipal_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
              
                          $province[$item[2]]=$item[3];
                         
                        }
                        asort($province);
                        foreach ($province as $key=>$val) { 
                          
                          ?>
                         <option value="<?php echo $key?>" <?php if($key==$value["doc_issue_province"]) echo "selected";?>><?php echo $val; ?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr id="doc_municipal<?php echo $i; ?>">
                  <th><label for=""><?php _e( 'Municipality of Issue Document', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="doc_issue_municipality[]" id="doc_issue_municipality<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $municipal_list = get_selected_municipal($value["doc_issue_province"]);
                        foreach($municipal_list as $key => $item) {
                          ?>
                         <option value="<?php echo $item[0]?>" data-provice="<?php echo $item[2];?>" <?php if($item[0]==$value["doc_issue_municipality"]) echo "selected";?>><?php echo $item[1];?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <?php }else {?>
                  <tr id="doc_provinces<?php echo $i; ?>" class="dis-none">
                  <th><label for=""><?php _e( 'Province of Issue Document', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="doc_issue_province[]" id="doc_issue_province<?php echo $i; ?>" onchange="get_doc_municipal(<?php echo $i;?>)">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                         $province=array();
                        $line = get_municipal_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                          $item = $line[$j];
              
                          $province[$item[2]]=$item[3];
                         
                        }
                        asort($province);
                        foreach ($province as $key=>$val) { 
                          
                          ?>
                         <option value="<?php echo $key?>" <?php if($key==$value["doc_issue_province"]) echo "selected";?>><?php echo $val; ?></option>
                         <?php
                        }
                        ?>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr id="doc_municipal<?php echo $i; ?>" class="dis-none">
                  <th><label for=""><?php _e( 'Municipality of Issue Document', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <select name="doc_issue_municipality[]" id="doc_issue_municipality<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        
                      </select>
                    </div>
                  </td>
                </tr>
                <?php }?>
                <?php if(get_option( 'wpsci_document_field' )){?>
                <tr>
                  <th><label for=""><?php _e( 'Upload document image', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text doc-image-wrapper" data-type="text" data-inited="true">
                      <?php
                      if( $value['doc_image']){
                      $files = unserialize($value['doc_image']);
                      if(pathinfo($files[0], PATHINFO_EXTENSION)=='pdf'){
                        ?>
                        <span><?php echo $files[0];?></span>
                        <?php } }?>
                      <div class="image-preview-container">
                        <input type="hidden" name="removed_images[]" class="removed_images" value="">
                        <input name="doc_img[<?= $i-1 ?>][]" id="doc_img<?php echo $i; ?>" class="doc_img regular-text" type="file" accept=".png,.jpg,application/pdf"  multiple>
                        <input name="doc_img_real[]" value="<?= $value['doc_image'] ? stripslashes(str_replace('"', "'", $value['doc_image'])) : '';?>" type="hidden" id="doc_img_real<?= $i?>">
                        <div class="image-preview-div">
                          <div class="image-preview">
                            <?php if(pathinfo($files[0], PATHINFO_EXTENSION)!='pdf'){?>
                              <div class="old-images">
                                <?php foreach($files as $file){
                                  if($file!=""){?>
                                <div class="image-preview-item">
                                  <img class="preview-image" src="<?= WPSCI_UPLOAD_URL."/".$file ?>">
                                  <span class="remove-btn old-img" data-val="<?= $file ?>">✖</span>
                                </div>
                                <?php }
                                } ?>
                              </div>
                            <?php }?>
                          </div>
                        </div>
                      </div>
                    </div>

                  </td>
                </tr>
                <?php }?>
              </tbody>
            </table>
            <hr>
              <?php } ?>

          <div class="col-12 mt-3 mb-3">
            <button type="submit" name="update" class="button button-primary"><?php _e( 'Update', 'wp-self-check-in' );?></button>
            <button type="button" onclick="back_form()" class="button button-secondary"><?php _e( 'Back', 'wp-self-check-in' );?></button>
          </div>
        </form>
      </div>


    </div>

  </div>

<!-- ------------------------------------------- -->
<!-- ADMIN PAGE UPDATE FORM END -->
<!-- ------------------------------------------- -->

<?php
} else{
  ?>
    <div class="inside" align="center">
      <p><?php _e( 'Guest Information has not added', 'wp-self-check-in' );?></p>
      <p><a href="<?php echo $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$_GET['post'].'&key='.get_post_meta($_GET['post'], "wp-self-check-in-key", true);?>" target="_blank" class="button button-primary"><?php _e( 'Click here to Add', 'wp-self-check-in' );?></a></p>
    </div>
<?php }