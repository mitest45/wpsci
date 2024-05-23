<div class="wrap">
  
  <?php 
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>
  
  <div class="metabox-holder">        
    <div class="postbox">
      <h3><?php _e( 'Email Template', 'wp-self-check-in' );?></h3>
      <div class="inside">
        <div class="digital-setting wrap">
          <div class="default-template-wrap">
            <button class="default-template-btn button button-secondary"><?= __( 'Default Template', 'wp-self-check-in' );?></button>
          </div>
          <form method="post" action="<?= admin_url('admin-post.php')?>">
            <input type="hidden" name="action" value="wpsci_notification">
            <?php wp_nonce_field('wpsci_notification-options'); ?>

            <table class="form-table">
              <tr>
                <th><label for=""><?php _e( 'Subject', 'wp-self-check-in' );?></label></th>
                <td>
                  <input type="text" name="wpsci_email_subject" id="wpsci_email_subject" class="large-text" value="<?= get_option( 'wpsci_email_subject' )?>">
                </td>
              </tr>
              <tr>
                <th><label for=""><?php _e( 'Header', 'wp-self-check-in' );?></label></th>
                <td>
                  <input type="text" name="wpsci_email_header" id="wpsci_email_header" class="large-text"  value="<?= get_option( 'wpsci_email_header' )?>">
                </td>
              </tr>
              <tr>
                <th><label for=""><?php _e( 'Message', 'wp-self-check-in' );?></label></th>
                <td>
                  <?php
                  $content = get_option( 'wpsci_email_message' );
                  wp_editor(  get_option( 'wpsci_email_message' ) , 'wpsci_email_message', array(
                    'wpautop'       => true,
                    'media_buttons' => false,
                    'teeny' => true,
                    'textarea_name' => 'wpsci_email_message',
                    'editor_class'  => 'wpsci-email-message',
                    'textarea_rows' => 20
                  ));
                  ?>
                </td>
              </tr>
              <tr>
                <th><label for=""><?php _e( 'Footer', 'wp-self-check-in' );?></label></th>
                <td>
                  <input type="text" name="wpsci_email_footer" id="wpsci_email_footer" class="large-text"  value="<?= get_option( 'wpsci_email_footer' )?>">
                </td>
              </tr>
              <tr>
                <th><label for=""><?php _e( 'Tags', 'wp-self-check-in' );?></label></th>
                <td>
                  <div class="wpsci-email-tags">
                    <div class="heading-collasp">
                      <details>
                        <summary><b><?= __( 'Email tags', 'wp-self-check-in' );?></b></summary>
                        <div class="wrapper">
                          <table class="form-table wpsci-email-tags-table">
                            <tbody>
                              <tr>
                                <td>Booking ID</td>
                                <td><span>%booking_id%</span></td>
                              </tr>
                              <tr>
                                <td>Site Title</td>
                                <td><span>%site_title%</span></td>
                              </tr>
                              <tr>
                                <td>Check-In Form Link</td>
                                <td><span>%wpsci_form_url%</span></td>
                              </tr>
                              <tr>
                                <td>Check-In Date	</td>
                                <td><span>%check_in_date%</span></td>
                              </tr>
                              <tr>
                                <td>Check-Out Date	</td>
                                <td><span>%check_out_date%</span></td>
                              </tr>
                              <tr>
                                <td>Guest First Name</td>
                                <td><span>%guest_first_name%</span></td>
                              </tr>
                              <tr>
                                <td>Guest Last Name</td>
                                <td><span>%guest_last_name%</span></td>
                              </tr>
                              <tr>
                                <td>Guest Email</td>
                                <td><span>%guest_email%</span></td>
                              </tr>
                              <tr>
                                <td>Guest Phone</td>
                                <td><span>%guest_phone%</span></td>
                              </tr>
                              <tr>
                                <td>Number of Guests</td>
                                <td><span>%number_of_guests%</span></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </details>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
            <?php submit_button(); ?>
          </form>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  jQuery(document).ready(function($){

    //load email template editor
    tinymce.init({
      selector: '#wpsci_email_message',
      menubar: false,
      paste_as_text: true,
      plugins: 'paste',
      toolbar: 'undo redo | formatselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | removeformat ',
    });

  });
</script>