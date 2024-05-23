<?php

$id = $_REQUEST['edit'];
        
$res_check_in = get_wpsci_checkin_data($id);

//for guests
$result = $GLOBALS['wpdb']->get_results("SELECT * FROM ".$wpdb->base_prefix."wpsci_guests WHERE booking_id='$id'", OBJECT);
$res = json_decode(json_encode($result), true);
?>

<div class="wrap">

  <?php 
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>
  
  <div class="metabox-holder" id="overview_wrap">
 
    <div class="inside postbox" id="edit_wrap">
      <form method="POST" id="update_form" enctype="multipart/form-data">
        <div class="master-details-div">
          <div class="dates-div w-50">
            <div><h3></span> <?php _e( 'Check-In', 'wp-self-check-in' );?></h3></div>     
            <div class="master-dates">
              <table class="form-table">
                <tbody>
                  <?php if(check_booking_type($id)=='wpsci'){?>
                  <tr>
                    <th><label for="updated_booking_id"><?php _e('Booking ID', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <input type="number" name="updated_booking_id" id="updated_booking_id" class=" regular-text" value="<?= $id?>" data-id="<?= $id?>">
                      <small class="field-alert hide"><?= __('Booking ID not available', 'wp-self-check-in' );?></small>
                    </td>
                  </tr>
                  <?php }?>
                  <tr>
                    <th><label for="arrival_date"><?php _e('Arrival Date', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <input type="date" name="arrival_date" id="arrival_date" class=" regular-text" value="<?= date("Y-m-d", strtotime(get_check_in($id)))?>">
                    </td>
                  </tr>
                  <tr>
                    <th><label for="departure_date"><?php _e('Departure Date', 'wp-self-check-in' );?></label></th>
                    <td colspan="1">
                      <input type="date" name="departure_date" id="departure_date" class=" regular-text" value="<?= date("Y-m-d", strtotime(get_check_out($id)))?>">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
            <div class="add-row">
              <span class="addrow-btn">
                <button class="button button-success add-guest" data-path = "<?= WPSCI_PLUGIN_URL . 'assets/documents/' ?>" type="button"><?= __('Add Row', 'wp-self-check-in' );?></button>
              </span>
            </div>
        </div>
        <hr>
        <input type="hidden" name="check_in_id" value="<?= $res_check_in ? $res_check_in['id'] : '';?>" readonly>
        <input type="hidden" name="booking_id" value="<?php echo $_REQUEST['edit'];?>" readonly>
        <?php
            $i = 0;
            foreach ($res as $key => $value) {
              $i++;
              ?>
          <div class="table-count" id="table_<?= $i?>" data-count ="<?= $i ?>">
          <table class="form-table" >
            <div class="guest-information-heading guest-info-d-flex align-items-center w-50">
                <div><h3><span class="guest_count"><?php echo $i; ?>.</span> <?php _e( 'Guest Information', 'wp-self-check-in' );?></h3></div>
                <?php 
                  if($i>1){
                    echo"<div class='remove-sign' data-id='table_$i'>X</div>";
                  }
                ?>                
            </div>
            <tbody>
              <tr>
                <th><label for=""><?php _e( 'Name', 'wp-self-check-in' );?></label></th>
                <td colspan="1">
                  <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                    <input name="first_name[]" id="first_name<?php echo $i; ?>" value="<?= (check_booking_type($_REQUEST['edit']) == 'mphb' && $i==1) ? get_post_meta($_REQUEST['edit'], 'mphb_first_name', true) : $value['first_name']; ?>" class="regular-text <?= (check_booking_type($_REQUEST['edit']) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text">
                  </div>
                </td>
              </tr>
              <tr>
                <th><label for=""><?php _e( 'Surname', 'wp-self-check-in' );?></label></th>
                <td colspan="1">
                  <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                    <input name="last_name[]" id="last_name<?php echo $i; ?>" value="<?= (check_booking_type($_REQUEST['edit']) == 'mphb' && $i==1) ? get_post_meta($_REQUEST['edit'], 'mphb_last_name', true) : $value['last_name']; ?>" class=" regular-text <?= (check_booking_type($_REQUEST['edit']) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text">
                  </div>
                </td>
              </tr>

              <?php if($i == 1){
                ?>
                <tr>
                  <th><label for="email"><?php _e( 'Email', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="email[]" id="email<?php echo $i; ?>" value="<?= check_booking_type($_REQUEST['edit']) == 'mphb' ? get_post_meta($_REQUEST['edit'], 'mphb_email', true) : $value['email']; ?>" class="regular-text" type="text" <?= check_booking_type($_REQUEST['edit']) == 'mphb' ? 'readonly' : ''; ?>>
                    </div>
                  </td>
                </tr>
                <?php
              }elseif(get_option('wpsci_guests_email') =='1'){?>
                <tr>
                  <th><label for="email"><?php _e( 'Email', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                    <input name="email[]" id="email<?php echo $i; ?>" value="<?= $value['email']; ?>" class="regular-text" type="text">
                    </div>
                  </td>
                </tr>
              <?php }?>

              <?php if($i == 1){
                ?>
                <tr>
                  <th><label for=""><?php _e( 'Phone', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="phone[]" id="phone<?php echo $i; ?>" value="<?= check_booking_type($_REQUEST['edit']) == 'mphb' ? get_post_meta($_REQUEST['edit'], 'mphb_phone', true) : $value['phone']; ?>" class=" regular-text" type="text" <?= check_booking_type($_REQUEST['edit']) == 'mphb' ? 'readonly' : ''; ?>>
                    </div>
                  </td>
                </tr>
                <?php
              }elseif(get_option('wpsci_guests_phone') =='1'){?>
                <tr>
                  <th><label for=""><?php _e( 'Phone', 'wp-self-check-in' );?></label></th>
                  <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                      <input name="phone[]" id="phone<?php echo $i; ?>" value="<?= $value['phone']; ?>" class=" regular-text" type="text">
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
                      <option class="<?php if($i>1){if($item[0]==16 || $item[0]==17 ||$item[0]==18) echo "dis-none";}?>" value="<?php echo  $item[0];?>" <?php if($item[0]==$value["house"]) echo "selected";?>><?php echo  $item[1];?></option>
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
                      foreach($municipal_list as $key => $item){
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
              <?php }
                
              if(get_option( 'wpsci_document_field' )){
              ?>
              <tr>
                <th><label for=""><?php _e( 'Upload document image', 'wp-self-check-in' );?></label></th>
                <td colspan="1">
                  <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text doc-image-wrapper" data-type="text" data-inited="true">
                    <?php
                    if( $value['doc_image']){
                    $files = unserialize($value['doc_image']);
                    if(pathinfo($files[key($files)], PATHINFO_EXTENSION)=='pdf'){
                      ?>
                      <span><?php echo $files[key($files)];?></span>
                      <?php }
                      }?>
                    <div class="image-preview-container">
                      <input type="hidden" name="removed_images[]" class="removed_images" value="">
                      <input name="doc_img[<?= $i-1 ?>][]" id="doc_img<?php echo $i; ?>" class="doc_img regular-text" type="file" accept=".png,.jpg,application/pdf"  multiple>
                      <input name="doc_img_real[]" value="<?= $value['doc_image'] ? stripslashes(str_replace('"', "'", $value['doc_image'])) : '';?>" type="hidden" id="doc_img_real<?= $i?>">
                      <div class="image-preview-div">
                        <div class="image-preview">
                          <?php if( $value['doc_image']){?>
                          <?php if(pathinfo($files[key($files)], PATHINFO_EXTENSION)!='pdf'){?>
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
          </div>
            <?php } ?>
          
        <!-- to display the additional rows -->
        <div class="additional-table-rows" id="additional-table-rows">

        </div>

          <div class="add-row">
            <center><button class="button add-guest" data-path = "<?= WPSCI_PLUGIN_URL . 'assets/documents/' ?>" type="button"><?= __('Add Row', 'wp-self-check-in' );?></button></center>
          </div>

        <hr>

        <div class="col-12 mt-3 mb-3">
          <button type="submit" name="update_overview" class="button button-primary"><?php _e( 'Update', 'wp-self-check-in' );?></button>
          <a href="<?= menu_page_url('wp-self-check-in', false)?>" class="button button-secondary"><?php _e( 'Back', 'wp-self-check-in' );?></a>
          </a>
        </div>
      </form>
    </div>
  </div>
</div>