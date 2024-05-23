<div class="table-res container" id="guest_table">
    <h4><?php _e( 'Guest Information for Booking', 'wp-self-check-in' );?> #<?php echo $booking_id;?></h4>
    <table class="form-table">
    <thead>
        <th width="10%"><?php _e( 'Sr No.', 'wp-self-check-in' );?></th>
        <th width="15%"><?php _e( 'Name', 'wp-self-check-in' );?></th>
        <th width="15%"><?php _e( 'Date of Birth', 'wp-self-check-in' );?></th>
        <th width="15%"><?php _e( 'Country of Birth', 'wp-self-check-in' );?></th>
        <th width="15%"><?php _e( 'Document Type', 'wp-self-check-in' );?></th>
        <th width="15%"><?php _e( 'Document Number', 'wp-self-check-in' );?></th>
        <?php if(get_option( 'wpsci_document_field' )){?>
            <th width="15%"><?php _e( 'Document Image', 'wp-self-check-in' );?></th>
        <?php }?>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($result as $key => $value) {
            $i++;
        ?>

        <tr class="mphb-customer-field-wrap">
            <td>
                <?php echo $i;?>
            </td>
            <td>
                <?php echo $value['first_name'].' '.$value['last_name'];?>
            </td>
            <td>
                <?php if($value['dob'] && $value['dob']!='0000-00-00')
                echo date("d-m-Y", strtotime($value['dob']));?>
            </td>
            <td>
                <?php echo $value['country'];?>
            </td>
            <td>
                <?php echo $value['doc_type'];?>
            </td>
            <td>
                <?php echo $value['doc_number'];?>
            </td>
            <?php if(get_option( 'wpsci_document_field' )){?>
            <td>
                <?php 
                if($value['doc_image']){
                $files = unserialize($value['doc_image']);
                if(pathinfo($files[key($files)], PATHINFO_EXTENSION)=='pdf'){
                ?>
                <span><?php echo 'PDF document';?></span>
                <?php }else{?>
                <span class="image-preview">
                    <?php
                    
                    foreach($files as $file){
                        if($file){
                        echo "<img src='".WPSCI_UPLOAD_URL."/".$file."' class='preview-image'>";
                        }
                    }
                    ?>
                </span>
                <?php }}?>
            </td>
            <?php }?>
        </tr>
        <?php }?>
    </tbody>
    </table>
    <div class="col-12 mt-3 mb-3">
    <form method="POST" id="edit_form" enctype="multipart/form-data">
        <input type="hidden" name="booking_id">
        <input type="hidden" name="guest">
        <button type="button" name="edit" onclick="edit_form()" class="button button-primary"><?php _e( 'Edit', 'wp-self-check-in' );?></button>
    </form>
    </div>
</div>