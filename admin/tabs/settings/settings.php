<?php
  $query = $GLOBALS['wpdb']->get_results("SELECT post_title,post_name FROM ".$wpdb->base_prefix."posts WHERE post_type='page' AND post_status='publish'");
  $pages = json_decode(json_encode($query), true);

  $wpsci_plugin = get_option( 'wpsci_plugin' );
  $_document_field = get_option( 'wpsci_document_field' );
  $_wpsci_guests_email = get_option( 'wpsci_guests_email' );
  $_wpsci_guests_phone = get_option( 'wpsci_guests_phone' );

?>

<div class="wrap">

  <?php 
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>

  <div class="metabox-holder">        
    <div class="postbox" id="setting_wrap">
      <h3><?php _e( 'Plugin Settings', 'wp-self-check-in' );?></h3>
      <div class="inside">
        <div class="digital-setting wrap">
          <form method="post" action="<?= admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="wpsci_settings">
            <?php wp_nonce_field('wpsci_settings-options'); ?>

            <table class="form-table">
              <tr>
                <th><label for="digital_plugin"><?php _e( 'Plugin Enable/Disable', 'wp-self-check-in' );?></label></th>
                <td>
                  <select name="digital_plugin" id="digital_plugin" class="digital_plugin">
                    <option value="1" <?php if($wpsci_plugin==1) echo "selected";?>><?php _e( 'Enable', 'wp-self-check-in' );?></option>
                    <option value="0" <?php if($wpsci_plugin==0) echo "selected";?>><?php _e( 'Disable', 'wp-self-check-in' );?></option>
                  </select> 
                </td>
              </tr>
              <tr>
                <th><label for="_document_field"><?php _e( 'Document Upload Field', 'wp-self-check-in' );?></label></th>
                <td>
                  <select name="_document_field" id="_document_field" class="digital_plugin">
                    <option value="1" <?php if($_document_field==1) echo "selected";?>><?php _e( 'Enable', 'wp-self-check-in' );?></option>
                    <option value="0" <?php if($_document_field==0) echo "selected";?>><?php _e( 'Disable', 'wp-self-check-in' );?></option>
                  </select> 
                </td>
              </tr>
              <tr>
                <th><label for="_wpsci_guests_email"><?php _e( 'Guests Email', 'wp-self-check-in' );?></label></th>
                <td>
                  <select name="_wpsci_guests_email" id="_wpsci_guests_email" class="digital_plugin">
                    <option value="1" <?php if($_wpsci_guests_email==1) echo "selected";?>><?php _e( 'Enable', 'wp-self-check-in' );?></option>
                    <option value="0" <?php if($_wpsci_guests_email==0) echo "selected";?>><?php _e( 'Disable', 'wp-self-check-in' );?></option>
                  </select> 
                </td>
              </tr>
              <tr>
                <th><label for="_wpsci_guests_phone"><?php _e( 'Guests Phone', 'wp-self-check-in' );?></label></th>
                <td>
                  <select name="_wpsci_guests_phone" id="_wpsci_guests_phone" class="digital_plugin">
                    <option value="1" <?php if($_wpsci_guests_phone==1) echo "selected";?>><?php _e( 'Enable', 'wp-self-check-in' );?></option>
                    <option value="0" <?php if($_wpsci_guests_phone==0) echo "selected";?>><?php _e( 'Disable', 'wp-self-check-in' );?></option>
                  </select> 
                </td>
              </tr>
              <tr>
                <th><label for="digital_public_page"><?php _e( 'Select Public Page', 'wp-self-check-in' );?></label></th>
                <td>
                  <select name="digital_public_page" id="digital_public_page">
                    <option value=""><?php _e( 'Select', 'wp-self-check-in' );?></option>
                    <?php
                    foreach ($pages as $key => $page) {
                    ?><option value="<?php echo $page['post_name'];?>" <?php if(get_option( 'wpsci_public_page' )==$page['post_name']) echo "selected";?>><?php echo $page['post_title'];?></option>
                    <?php 
                    }
                    ?>
                  </select>
                </td>
              </tr>
            </table>
            <?php submit_button(); ?>
          </form>
          <br>
          <p>
            <ul>
              <li><?php _e( 'Shortcode for Public Page', 'wp-self-check-in' );?>:</li>
              <li><b>[wpsci_form]</b></li>
            </ul>
          </p>
          <?php if(is_mphb_active()){?>
          <p>
            <ul>
              <li>MPHB <?php _e( 'Add-on', 'wp-self-check-in' );?>:</li>
              <li><?php _e( 'Get form link with following email tag', 'wp-self-check-in' );?></li>
              <li><b>%wpsci_form_url%</b></li>
            </ul>
          </p>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
