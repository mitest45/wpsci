<?php

$post_id=$_GET['post'];
  $meta_value = get_post_meta($post_id, "wp-self-check-in-key", true);
  if (!$meta_value)  
  {
    $key=wp_generate_password(12,false);
    add_post_meta($post_id, "wp-self-check-in-key", $key, true);
  }

  
	?>
<div>
    <p class="mphbrp-request-link"><?php _e( 'Guest information link', 'wp-self-check-in' );?><br>
        <a id="_url" href="<?php echo $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$post_id.'&key='.get_post_meta($post_id, "wp-self-check-in-key", true);?>" target="_blank"><?php echo $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$post_id.'&key='.get_post_meta($post_id, "wp-self-check-in-key", true);?></a>
    </p>
    <button onclick="copyToClipboard('#_url')" id="url_btn1" type="button" class="button button-primary button-large"><?php _e( 'Copy URL', 'wp-self-check-in' );?></button>
    <button onclick="copyToClipboard('#_url')" id="url_btn2" type="button" class="button button-primary button-large" style="display:none;"><?php _e( 'Copied', 'wp-self-check-in' );?></button>
    <form method="POST" id="download_form_side" class="dis-in-block">
        <input type="hidden" name="booking_id" value="<?php echo $_GET['post'];?>">
        <?php
        if(check_field($_GET['post'])){
        ?>
        <button type="submit" name="_download" id="_download" class="button button-primary button-large"><?php _e( 'Download', 'wp-self-check-in' );?></button>
        <?php
        }?>
    </form>
</div>