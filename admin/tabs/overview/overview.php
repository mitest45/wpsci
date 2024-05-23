<?php
/**
 * Overview.
 *
 * @version 1.0.0
 *
 * @category Template
 * @author Custom
 */

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Custom_Table extends WP_List_Table {

    function __construct() {
        parent::__construct(array(
            'singular' => 'record',
            'plural'   => 'records',
            'ajax'     => false,
        ));
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'   => 'Delete',
        );
        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;

        if ('delete' === $this->current_action()) {
            
            // Handle delete action
            $record_ids = isset($_REQUEST['record']) ? $_REQUEST['record'] : array();

            foreach ($record_ids as $record_id) { 
    
                $sql = "Delete from ".$wpdb->base_prefix."wpsci_guests where booking_id='".$record_id."'";
                
                $results = $wpdb->query($sql);

                $sql = "Delete from ".$wpdb->base_prefix."wpsci_check_in where booking_id='".$record_id."'";
                
                $results = $wpdb->query($sql);
            
            }
            
        }

    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="record[]" value="%s" />',
            $item['booking_id']
        );
    }

    function column_default($item, $column_name) {
        return $item[$column_name];
    }

    function column_booking_id($item) {
        return $item['booking_id'];
    }

    function column_first_guest_name($item) {
        return $item['first_guest_name'];
    }

    function column_arrival_date($item) {
        return $item['arrival_date'];
    }

    function column_departure_date($item) {
        return $item['departure_date'];
    }

    function column_total_guests($item) {
        return $item['total_guests'];
    }

    function column_action($item) {
      if(get_option('wpsci_license_key_token') && wpsci_validate_license())
      return $item['action'];
    }

    function get_total_items() {
        global $wpdb;

        $sql = "SELECT COUNT(DISTINCT booking_id) FROM {$wpdb->base_prefix}wpsci_guests";
        return $wpdb->get_var($sql);
    }

    function prepare_items() {
        $this->process_bulk_action();

        global $wpdb;
        global $web_url;
        global $base_url;

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = $this->get_total_items();
        
        $offset = ($current_page - 1) * $per_page;

        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}wpsci_guests GROUP BY booking_id ORDER BY booking_id DESC LIMIT %d OFFSET %d", $per_page, $offset);
        $results = $wpdb->get_results($sql, ARRAY_A);
   
        $data = array();
        $i = 0;
        foreach($results as $result) {
            $i++;
            $_key = get_post_meta($result['booking_id'], "wp-self-check-in-key", true);
            if(!$_key) $_key = get_check_in_key($result['booking_id']);

            $action = '
                <a id="url_'. $i .'" class="dis-none" href="'. $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$result['booking_id'].'&key='.$_key .'" target="_blank">
                '. $web_url.'/'.get_option( 'wpsci_public_page' ).'?id='.$result['booking_id'].'&key='.$_key .'
                </a>

                <button onclick="copyToClipboard(\'#url_'. $i.'\')" id="url_btn_copy'. $i .'" type="button" class="icon-button mg-2x" title="'. __( 'Copy URL', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-paperclip"></span></button>
                <button onclick="copyToClipboard(\'#url_'. $i.'\')" id="url_btn_copied'. $i .'" type="button" class="icon-button mg-2x" style="display:none;" title="'. __( 'Copied', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-paperclip"></span></button>

                <form method="POST" id="edit_form" class="dis-in-block mg-2x" enctype="multipart/form-data">
                    <input type="hidden" name="booking_id" value="'. $result['booking_id'] .'">
                    <button type="button" name="edit_overview" class="icon-button" title="'. __( 'Edit', 'wp-self-check-in' ) .'"><a href="'. menu_page_url('wp-self-check-in', false).'&edit='.$result['booking_id'].'"><span class="dashicons dashicons-edit-page"></span></a></button>
                </form>

                <form method="POST" id="delete_record_form" class="dis-in-block mg-2x" enctype="multipart/form-data">
                    <input type="hidden" name="booking_id" value="'. $result['booking_id'] .'">
                    <button type="submit" name="delete_record" class="icon-button" title="'. __( 'Delete', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-trash"></span></button>
                </form>
            ';

            $action .= '
            <form method="POST" id="send_notification" class="dis-in-block mg-2x" enctype="multipart/form-data">
                <input type="hidden" name="booking_id" value="'. $result['booking_id'] .'">
                <button type="submit" name="send_notification" class="icon-button" title="'. __( 'Notify', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-email"></span></button>
            </form>
            ';

            if(check_field($result['booking_id'])){
                $action .= '
                    <form method="POST" id="download_form" class="dis-in-block mg-2x">
                        <input type="hidden" name="booking_id" value="'. $result['booking_id'] .'">
                        <button type="submit" name="_download" id="_download" class="icon-button" title="'. __( 'Download', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-download"></span></button>
                    </form>
                ';
            }

            $action .= '
                <form method="POST" id="book_receipt_form" enctype="multipart/form-data">
                    <input type="hidden" name="booking_id" value="'. $result['booking_id'] .'">
            ';
            
            if(get_receipt($result['booking_id'])){ $meta_receipt = get_receipt($result['booking_id']);
                $action .= '
                    <p>
                        <a download="'. $meta_receipt .'" href="'. WPSCI_UPLOAD_URL .'/'.$meta_receipt .'">
                        <button type="button" name="download_receipt" id="download_receipt" class="button button-secondary mg-2x">'. __( 'Download Receipt', 'wp-self-check-in' ) .'</button>
                        </a>
                        <button type="submit" name="delete_receipt" id="delete_receipt" class="button button-secondary mg-2x">'. __( 'Remove', 'wp-self-check-in' ) .'</button>
                    </p>
                ';
            }else{
                $action .= '
                    <p>
                        <input type="file" name="book_receipt" id="book_receipt" accept=".png,.jpg,application/pdf">
                        <button type="submit" name="save_receipt" id="save_receipt" class="button button-secondary">'. __( 'Upload Receipt', 'wp-self-check-in' ) .'</button>
                    </p>
                ';
            }
            $action .= '
                </form>
            ';

            $data[]=array(
                'id'=> $result['booking_id'],
                'booking_id'=> $result['booking_id'],
                'first_guest_name'=> $result['first_name'] . ' ' . $result['last_name'],
                'arrival_date'=> get_check_in($result['booking_id']),
                'departure_date'=> get_check_out($result['booking_id']),
                'total_guests'=> get_total_guests($result['booking_id'], true),
                'action'=> $action,
            );
        }

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ));
    }

    function get_columns() {
        return array(
            'cb'          => '<input type="checkbox" />',
            'booking_id' => __('Booking ID', 'wp-self-check-in' ),
            'first_guest_name'   => __('First Guest Name', 'wp-self-check-in' ),
            'arrival_date'   => __('Arrival Date', 'wp-self-check-in' ),
            'departure_date'   => __('Departure Date', 'wp-self-check-in' ),
            'total_guests'   => __('Total Guests', 'wp-self-check-in' ),
            'action'   => __('Action', 'wp-self-check-in' ),
        );
    }

    function get_sortable_columns() {
        return array(
            'booking_id' => array('Booking ID', false),
        );
    }
}

?>

<div class="wrap">
    
  <?php 
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>

  <div class="metabox-holder" id="overview_wrap">
    <div class="inside">
      <?php if(get_option('wpsci_license_key_token') && wpsci_validate_license()){?>
      <p align="right">
        <button class="create-check-in button button-secondary" onclick="open_modal('#modalCreateCheckIn')"><?php _e( 'Create Check-In', 'wp-self-check-in' );?></button>
      </p>
      <?php }?>

      <form method="post">
        <?php
          $custom_table = new Custom_Table();
          $custom_table->prepare_items();
          $custom_table->display();
        ?>
      </form>
    </div>
  </div>
</div>


<!-- The Modal -->
<div id="modalCreateCheckIn" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3><?php _e( 'Create Check-In', 'wp-self-check-in' );?>
      <span onclick="close_modal('#modalCreateCheckIn')" class="close">&times;</span>
      </h3>
    </div>
    <div class="modal-body">
      <form method="POST" id="check_in_form">
        <table class="form-table">
          <tbody>
            <tr>
              <th><label for=""><?php _e( 'Booking ID', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="custom_booking_id" id="custom_booking_id" class=" regular-text" type="number" placeholder="Optional">
                  <small class="field-alert hide"><?= __('Booking ID not available', 'wp-self-check-in' );?></small>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'First Guest Name', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="first_name" id="" class=" regular-text" type="text" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'First Guest Surname', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="last_name" id="" class=" regular-text" type="text" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'First Guest Email', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="email" id="" class=" regular-text" type="email" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'First Guest Phone', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="phone" id="" class=" regular-text" type="tel" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'Arrival Date', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="arrival_date" id="" class=" regular-text" type="date" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'Departure Date', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="departure_date" id="" class=" regular-text" type="date" required>
                </div>
              </td>
            </tr>
            <tr>
              <th><label for=""><?php _e( 'Number of Guests', 'wp-self-check-in' );?></label></th>
              <td colspan="1">
                <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                  <input name="number_of_guests" id="" class=" regular-text" type="number" required>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <hr>
        <div class="col-12 mt-3 mb-3">
          <input type="hidden" name="save_check_in" value="1">
          <button type="submit" name="save_check_in" id="save_check_in" class="button button-primary"><?php _e( 'Save', 'wp-self-check-in' );?></button>
        </div>
      </form>
    </div>
  </div>
</div>
