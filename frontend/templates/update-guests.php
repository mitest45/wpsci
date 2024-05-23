<div class="entry-content dis-none" id="edit_wrap">
    <div class="wpsci-main-wrapper ">
        <div class="table-res" id="guest_form_edit">
            <form method="POST" id="update_form" enctype="multipart/form-data">
                <div class="table-res" id="guest_form_add">
                    <h4><?php _e( 'Guest Information for Booking', 'wp-self-check-in' );?> #<?php echo $booking_id;?></h4>
                    <?php 
                    $i = 0;
                    foreach ($result as $key => $value) {
                    $i++;?>
                    <section id="mphb-customer-details" class="mphb-checkout-section mphb-customer-details">
                    <h3 class="mphb-customer-details-title">
                        <?php echo $i;?>. <?php _e( 'Guest Information', 'wp-self-check-in' );?>
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
                        <input name="first_name[]" id="first_name<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_first_name', true) : $value['first_name'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Surname', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="last_name[]" id="last_name<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_last_name', true) : $value['last_name'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                    </p>

                    <?php if($i==1){?>
                        <p class="mphb-customer-last-name mphb-text-control">
                            <label for="">
                            <?php _e( 'Email', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <input name="email[]" id="email<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_email', true) : $value['email'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                        </p>
                    <?php }elseif(get_option('wpsci_guests_email') =='1'){?>
                        <p class="mphb-customer-last-name mphb-text-control">
                            <label for="">
                            <?php _e( 'Email', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <input name="email[]" id="email<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_email', true) : $value['email'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                        </p>
                    <?php }?>

                    <?php if($i==1){?>
                        <p class="mphb-customer-last-name mphb-text-control">
                            <label for="">
                            <?php _e( 'Phone', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <input name="phone[]" id="phone<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_phone', true) : $value['phone'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                        </p>
                    <?php }elseif(get_option('wpsci_guests_phone') =='1'){?>
                        <p class="mphb-customer-last-name mphb-text-control">
                            <label for="">
                            <?php _e( 'Phone', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <input name="phone[]" id="phone<?php echo $i?>" value="<?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? get_post_meta($booking_id, 'mphb_phone', true) : $value['phone'];?>" class=" regular-text <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'disabled-field':'';?>" type="text" <?= (check_booking_type($booking_id) == 'mphb' && $i==1) ? 'readonly':'';?>>
                        </p>
                    <?php }?>

                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Sex', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="sex[]" id="sex<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <option value="male" <?php if("male"==$value["sex"]) echo "selected";?>><?php _e( 'Male', 'wp-self-check-in' );?></option>
                        <option value="female" <?php if("female"==$value["sex"]) echo "selected";?>><?php _e( 'Female', 'wp-self-check-in' );?></option>
                        </select>
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Date of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="dob[]" id="dob<?php echo $i?>" value="<?php echo $value['dob'];?>" class=" regular-text" type="date">
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Country of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <?php if($value["country_code"]==100000100){?>
                    <p class="mphb-customer-last-name mphb-text-control" id="provinces<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Province of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control" id="municipal<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Municipality of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="municipalities[]" id="municipalities<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $municipal_list = get_selected_municipal($value["provinces"]);
                        foreach($municipal_list as $key => $item) {
                            ?>
                            <option value="<?php echo $item[0];?>" data-provice="<?php echo $item[2];?>" <?php if($item[0]==$value["municipalities"]) echo "selected";?>><?php echo $item[1];?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </p>
                    <?php } else{?>
                    <p class="mphb-customer-last-name mphb-text-control dis-none" id="provinces<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Province of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control dis-none" id="municipal<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Municipality of Birth', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="municipalities[]" id="municipalities<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        
                        </select>
                    </p>
                    <?php }?>
                    <p class="mphb-customer-last-name mphb-text-control guest_type"  id="guest_type<?php echo $i; ?>">
                        <label for="">
                        <?php _e( 'Guest Type', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="house[]" id="house<?php echo $i; ?>" <?= $i==1 ? 'readonly':'';?> required>
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $line = get_house_list();
                        for ($j=0; $j < sizeof($line); $j++) { 
                            $item = $line[$j];
                            if($i!=1){
                                if($j<=2) continue;
                            }
                            ?>
                            <option value="<?php echo  $item[0];?>" <?php if($item[0]==$value["house"]) echo "selected";?>><?php echo  $item[1];?></option>
                            <?php
                            if($i==1){
                                if($j==2) break;
                            }
                        }
                        ?>
                        </select>
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control">
                        <label for="">
                        <?php _e( 'Citizenship', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="citizenship[]" id="citizenship<?php echo $i; ?>">
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control <?php if($i>1) echo "dis-none";?>">
                        <label for="">
                        <?php _e( 'Document Type', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control <?php if($i>1) echo "dis-none";?>">
                        <label for="">
                        <?php _e( 'Document Number', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <input name="doc_number[]" id="doc_number<?php echo $i?>" value="<?php echo $value['doc_number'];?>" class=" regular-text" type="text">
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control <?php if($i>1) echo "dis-none";?>" id="poid_country<?php echo $i?>">
                        <label for="">
                        <?php _e( 'Place of Issue of Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="doc_issue_place[]" id="doc_issue_place<?php echo $i; ?>" onchange="get_doc_place(<?php echo $i; ?>)">
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
                    </p>
                    <?php if($value["doc_issue_place"]==100000100){?>
                    <p class="mphb-customer-last-name mphb-text-control" id="doc_provinces<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Province of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control" id="doc_municipal<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Municipality of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="doc_issue_municipality[]" id="doc_issue_municipality<?php echo $i; ?>">
                        <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        <?php 
                        $municipal_list = get_selected_municipal($value["doc_issue_province"]);
                        foreach($municipal_list as $key => $item){
                            ?>
                            <option value="<?php echo $item[0];?>" data-provice="<?php echo $item[2];?>" <?php if($item[0]==$value["doc_issue_municipality"]) echo "selected";?>><?php echo $item[1];?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </p>
                    <?php } else{?>
                    <p class="mphb-customer-last-name mphb-text-control dis-none" id="doc_provinces<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Province of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
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
                    </p>
                    <p class="mphb-customer-last-name mphb-text-control dis-none" id="doc_municipal<?php echo $i;?>">
                        <label for="">
                        <?php _e( 'Municipality of Issue Document', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                        </label><br>
                        <select name="doc_issue_municipality[]" id="doc_issue_municipality<?php echo $i; ?>">
                            <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                        
                        </select>
                    </p>
                    <?php }?>
                    <?php if(get_option( 'wpsci_document_field' )){?>
                    <div class="public-img mb-3">
                        <div class="mphb-customer-last-name mphb-text-control doc-image-wrapper">
                            
                            <label for="">
                            <?php _e( 'Upload document image', 'wp-self-check-in' );?>&nbsp; <abbr title="required">*</abbr>
                            </label><br>
                            <?php 
                            if($value['doc_image']){
                                $files = unserialize($value['doc_image']);
                                if(pathinfo($files[key($files)], PATHINFO_EXTENSION)=='pdf'){
                                ?>
                                <span><?php echo $files[key($files)];?></span>
                                <?php }
                            }?>

                            <div class="image-preview-container">
                                <?php if( $value['doc_image']){?>
                                <?php }?>
                                <input type="hidden" name="removed_images[]" class="removed_images" value="">
                                <input name="doc_img[<?= $i-1?>][]" id="doc_img<?php echo $i?>" class="doc_img regular-text" type="file" accept=".png,.jpg,application/pdf" multiple>
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
                                                <span class="remove-btn old-img" data-val="<?= $file ?>"></span>
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
                    </div>
                    <?php }?>
                    </section>
                    <?php }?>
                    <div class="col-12 mt-3 mb-3">
                        <button type="submit" name="submit_guests" class="button button-primary"><?php _e( 'Update', 'wp-self-check-in' );?></button>
                        <button type="button" onclick="back_form()" class="button button-primary"><?php _e( 'Back', 'wp-self-check-in' );?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>