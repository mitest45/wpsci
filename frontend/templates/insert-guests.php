<div class="modal-body">    
    <div id="info_main" class="postbox">
    <div class="postbox-header">
        <h4 class="hndle ui-sortable-handle"><?php _e( 'Add Guest Information for Booking', 'wp-self-check-in' );?> #<?php echo $booking_id;?></h4>
    </div>
    <div class="inside">
        <form method="POST" id="member_form" enctype="multipart/form-data">
        <div class="table-res" id="guest_form_add">
            <?php for($i=0;$i<$number_of_guest;$i++){?>
            <section id="mphb-customer-details" class="mphb-checkout-section mphb-customer-details">
                <h3 class="mphb-customer-details-title">
                <?php echo $i+1;?>. <?php _e( 'Guest Information', 'wp-self-check-in' );?>
                </h3>

                <p class="mphb-required-fields-tip">
                <small>
                <?php _e( 'Required fields are followed by', 'wp-self-check-in' );?> <abbr title="required">*</abbr>			
                </small>
                </p>
                <p class="mphb-customer-first-name mphb-customer-name mphb-text-control">
                <label for="mphb_first_name">
                    <?php _e( 'Name', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <input name="first_name[]" id="first_name<?php echo $i+1?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_first_name', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                </p>
                <p class="mphb-customer-last-name mphb-text-control">
                <label for="">
                    <?php _e( 'Surname', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <input name="last_name[]" id="last_name<?php echo $i+1?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_last_name', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                </p>
                <?php if($i+1==1){?>

                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Email', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="email[]" id="email<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_email', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                    </p>
                <?php }elseif(get_option('wpsci_guests_email') =='1'){?>

                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Email', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="email[]" id="email<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_email', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                    </p>
                <?php }?>

                <?php if($i+1==1){?>
                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Phone', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="phone[]" id="phone<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_phone', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                    </p>
                <?php }elseif(get_option('wpsci_guests_phone') =='1'){?>

                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Phone', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="phone[]" id="phone<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? get_post_meta($booking_id, 'mphb_phone', true) : '';?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i+1==1) ? 'readonly':'';?>>
                    </p>

                <?php }?>
                <p class="mphb-customer-last-name mphb-text-control">
                <label for="">
                    <?php _e( 'Sex', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="sex[]" id="sex<?php echo $i+1; ?>">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <option value="male"><?php _e( 'Male', 'wp-self-check-in' );?></option>
                    <option value="female"><?php _e( 'Female', 'wp-self-check-in' );?></option>
                </select>
                </p>
                <p class="mphb-customer-last-name mphb-text-control">
                <label for="">
                    <?php _e( 'Date of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <input name="dob[]" id="dob<?php echo $i+1?>" class=" regular-text" type="date">
                </p>
                <p class="mphb-customer-last-name mphb-text-control">
                <label for="">
                    <?php _e( 'Country of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="country_code[]" class="ccode" id="country_code<?php echo $i+1; ?>" onchange="get_country_name(<?php echo $i+1;?>)">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php 
                    $line = get_country_list();
                    for ($j=0; $j < sizeof($line); $j++) { 
                    $item = $line[$j];
                    ?>
                    <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>"><?php echo $item[1];?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="hidden" name="country[]" id="country<?php echo $i+1; ?>">
                </p>

                <p class="mphb-customer-last-name mphb-text-control dis-none" id="provinces<?php echo $i+1;?>">
                <label for="">
                    <?php _e( 'Province of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="provinces[]" id="provinces<?php echo $i+1; ?>" onchange="get_municipal(<?php echo $i+1;?>)">
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
                    <option value="<?php echo $key?>"><?php echo $val; ?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>


                <p class="mphb-customer-last-name mphb-text-control dis-none" id="municipal<?php echo $i+1;?>">
                <label for="">
                    <?php _e( 'Municipality of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="municipalities[]" id="municipalities<?php echo $i+1; ?>">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
 
                </select>
                </p>
                
                <p class="mphb-customer-last-name mphb-text-control guest_type" id="guest_type<?php echo $i+1; ?>">
                <label for="">
                    <?php _e( 'Guest Type', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="house[]" id="house<?php echo $i+1; ?>" <?= $i+1==1 ? 'readonly':'';?> required>
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php 
                    $line = get_house_list();
                    for ($j=0; $j < sizeof($line); $j++) { 
                    $item = $line[$j];
                    ?>
                    <option value="<?php echo  $item[0];?>" <?php if( $i+1>1){ if($item[0]==20) echo "selected"; }?>><?php echo  $item[1];?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>
                <p class="mphb-customer-last-name mphb-text-control">
                <label for="">
                    <?php _e( 'Citizenship', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="citizenship[]" id="citizenship<?php echo $i+1; ?>">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php 
                    $line = get_country_list();
                    for ($j=0; $j < sizeof($line); $j++) { 
                    $item = $line[$j];
                    ?>
                    <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>"><?php echo $item[1];?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>
                <p class="mphb-customer-last-name mphb-text-control <?php if($i+1>1) echo "dis-none";?>">
                <label for="">
                    <?php _e( 'Document Type', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="doc_type[]" id="doc_type<?php echo $i+1; ?>">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php 
                    $line = get_document_list();
                    for ($j=0; $j < sizeof($line); $j++) { 
                    $item = $line[$j];
                    ?>
                    <option value="<?php echo $item[0];?>"><?php echo $item[1];?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>
                <p class="mphb-customer-last-name mphb-text-control <?php if($i+1>1) echo "dis-none";?>">
                <label for="">
                    <?php _e( 'Document Number', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <input name="doc_number[]" id="doc_number<?php echo $i+1?>" class=" regular-text" type="text">
                </p>
                <p class="mphb-customer-last-name mphb-text-control <?php if($i+1>1) echo "dis-none";?>" id="poid_country<?php echo $i+1?>">
                <label for="">
                    <?php _e( 'Place of Issue of Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="doc_issue_place[]" id="doc_issue_place<?php echo $i+1; ?>" onchange="get_doc_place(<?php echo $i+1; ?>)">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php 
                    $line = get_country_list();
                    for ($j=0; $j < sizeof($line); $j++) { 
                    $item = $line[$j];
                    ?>
                    <option value="<?php echo $item[0];?>" data-val="<?php echo $item[1];?>"><?php echo $item[1];?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>
                <p class="mphb-customer-last-name mphb-text-control dis-none" id="doc_provinces<?php echo $i+1;?>">
                <label for="">
                    <?php _e( 'Province of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="doc_issue_province[]" id="doc_issue_province<?php echo $i+1; ?>" onchange="get_doc_municipal(<?php echo $i+1;?>)">
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
                    <option value="<?php echo $key?>"><?php echo $val; ?></option>
                    <?php
                    }
                    ?>
                </select>
                </p>


                <p class="mphb-customer-last-name mphb-text-control dis-none" id="doc_municipal<?php echo $i+1;?>">
                <label for="">
                    <?php _e( 'Municipality of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                </label><br>
                <select name="doc_issue_municipality[]" id="doc_issue_municipality<?php echo $i+1; ?>">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>

                </select>
                </p>
                <?php if(get_option( 'wpsci_document_field' )){?>
                <div class="public-img">
                    <div class="image-preview-container">
                        <input name="doc_img[<?= $i?>][]" id="doc_img<?php echo $i?>" class="doc_img regular-text" type="file" accept=".png,.jpg,application/pdf" multiple>
                        <div class="image-preview-div">
                        <div class="image-preview"></div>
                        </div>
                    </div>
                    <input type="hidden" name="doc_img_real[]">
                </div>
                <?php }?>
            </section>
            <?php }?>
            <div class="col-12 mb-3">
            <button type="submit" name="submit_guests" class="button button-primary"><?php _e( 'Save', 'wp-self-check-in' );?></button>
            </div>
        </div>
        </form>
    </div>
    </div>
</div>