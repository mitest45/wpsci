<?php
$data = wpsci_get_guest_data($_GET['edit']);
?>
<div class="wrap">
  <?php
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>
      
  <div class="metabox-holder">
    <div class="inside">
      <h1><?php _e('Edit Guest Data', 'wp-self-check-in'); ?></h1>

      <form method="post" action="<?= admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="edit_guests_data">
        <input type="hidden" name="guest_id" value="<?= $_GET['edit']?>">
        <?php wp_nonce_field('edit_guests_data-options'); ?>
        
        <table class="form-table">
          <tr valign="top">
            <th scope="row">
              <label for="wpsci_name"><?php _e('Name', 'wp-self-check-in'); ?></label>
            </th>
            <td>
              <input type="text" id="wpsci_name" name="wpsci_name" value="<?= $data['name']?>" class="regular-text" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="wpsci_surname"><?php _e('Surname', 'wp-self-check-in'); ?></label>
            </th>
            <td>
              <input type="text" id="wpsci_surname" name="wpsci_surname" value="<?= $data['surname']?>" class="regular-text" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="wpsci_email"><?php _e('Email', 'wp-self-check-in'); ?></label>
            </th>
            <td>
              <input type="text" id="wpsci_email" name="wpsci_email" value="<?= $data['email']?>" class="regular-text" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="wpsci_phone"><?php _e('Phone', 'wp-self-check-in'); ?></label>
            </th>
            <td>
              <input type="text" id="wpsci_phone" name="wpsci_phone" value="<?= $data['phone']?>" class="regular-text" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="wpsci_country"><?php _e('Country', 'wp-self-check-in'); ?></label>
            </th>
            <td>
              <input type="text" id="wpsci_country" name="wpsci_country" value="<?= $data['country']?>" class="regular-text" />
            </td>
          </tr>
        </table>

        <?php submit_button(); ?>
      </form>
    </div>
  </div>
</div>
