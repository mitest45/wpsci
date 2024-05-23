<?php

$post_id=$_GET['post'];
$meta_receipt = get_post_meta($post_id, "wp-self-check-in-receipt", true);
if(isset($_REQUEST['_receipt'])){

    $destinationPath = WPSCI_UPLOAD_PATH . "/" . date('Y') . "/" . date('m') . "/";

    if (!file_exists($destinationPath)) {
      if (!mkdir($destinationPath, 0777, true)) {
        die("Failed to create directory: $destinationPath");
      }
    }

    $image=$_FILES['booking_receipt']['name'];
    $imageArr=explode('.',$image);
    if($image){
        $rand=rand(10000,99999);
        $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
        $uploadPath= $destinationPath . $newImageName;
        $isUploaded=move_uploaded_file($_FILES["booking_receipt"]["tmp_name"],$uploadPath);
        $newImageName = date('Y') . "/" . date('m') . "/" .$newImageName;
        if (!$meta_receipt)  
        {
            add_post_meta($post_id, "wp-self-check-in-receipt", $newImageName, true);
        }else{
            update_post_meta($post_id, "wp-self-check-in-receipt", $newImageName, true);
        }
        wp_redirect(basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING']);
    }else{
        $newImageName = $_POST['booking_receipt_prev'];
    }
    
}

if(isset($_REQUEST['delete_receipt'])){
    if ($meta_receipt)  
    {
        delete_post_meta($post_id, "wp-self-check-in-receipt", $meta_receipt);
        wp_redirect(basename($_SERVER['PHP_SELF']) . "?" . $_SERVER['QUERY_STRING']); 
    }
}
?>
<p>
    <form method="POST" id="receipt_form" enctype="multipart/form-data">
        <p>
        <?php
        if($meta_receipt)
        { if(pathinfo($meta_receipt, PATHINFO_EXTENSION)=='pdf'){
            ?>
            <span><?php echo $meta_receipt;?></span>
            <?php }else{?>
            <img src="<?php echo WPSCI_UPLOAD_URL."/".$meta_receipt; ?>" alt="image" class="receipt-prev">
            <?php }?>
            </p>
            <a download="<?php echo $meta_receipt;?>" href="<?php echo WPSCI_UPLOAD_URL."/".$meta_receipt; ?>">
            <button type="button" name="download_receipt" id="download_receipt" class="button button-primary"><?php _e( 'Download Receipt', 'wp-self-check-in' );?></button>
            </a>
            <button type="submit" name="delete_receipt" id="delete_receipt" class="button button-primary"><?php _e( 'Remove', 'wp-self-check-in' );?></button>
            <?php
        }else{
            ?>
            <p>
                <input type="file" name="booking_receipt" id="booking_receipt" accept=".png,.jpg,application/pdf">
            </p>
            <input type="hidden" name="booking_receipt_prev" id="booking_receipt_prev">
            <button type="submit" name="_receipt" id="_receipt" class="button button-primary"><?php _e( 'Upload Receipt', 'wp-self-check-in' );?></button>
            <?php
        }
        ?>
    </form>
</p>