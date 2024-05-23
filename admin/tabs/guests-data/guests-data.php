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
  
          $sql = "Delete from ".$wpdb->base_prefix."wpsci_guests_data where id='".$record_id."'";
          
          $results = $wpdb->query($sql);
          
        }
        wpsci_redirect(admin_url('admin.php?page=wp-self-check-in&tab=guests-data&deleted=1'));
      }
    }

    function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="record[]" value="%s" />',
          $item['id']
      );
    }

    function column_default($item, $column_name) {
      return $item[$column_name];
    }

    function column_name($item) {
      return $item['name'];
    }

    function column_email($item) {
      return $item['email'];
    }

    function column_phone($item) {
      return $item['phone'];
    }

    function column_country($item) {
      return $item['country'];
    }

    function column_booking_id($item) {
      return $item['booking_id'];
    }

    function column_stay_period($item) {
      return $item['stay_period'];
    }

    function column_action($item) {
      return $item['action'];
    }

    function get_total_items() {
      global $wpdb;

      $sql = "SELECT COUNT(*) FROM {$wpdb->base_prefix}wpsci_guests_data";
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

      $sql = $wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}wpsci_guests_data ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset);
      $results = $wpdb->get_results($sql, ARRAY_A);
  
      $data = array();
      $i = 0;
      foreach($results as $result) {
        $i++;
        $_key = get_post_meta($result['booking_id'], "wp-self-check-in-key", true);
        if(!$_key) $_key = get_check_in_key($result['booking_id']);

        $action = '
          <form method="POST" id="edit_form" class="dis-in-block mg-2x" enctype="multipart/form-data">
            <button type="button" name="edit_overview" class="icon-button" title="'. __( 'Edit', 'wp-self-check-in' ) .'"><a href="'. menu_page_url('wp-self-check-in', false).'&tab=guests-data&edit='.$result['id'].'"><span class="dashicons dashicons-edit-page"></span></a></button>
          </form>

          <form method="POST" id="delete_record_form" action="'.admin_url('admin-post.php').'" class="dis-in-block mg-2x" enctype="multipart/form-data">
            <input type="hidden" name="guest_id" value="'. $result['id'] .'">
            <input type="hidden" name="action" value="delete_guest_data">
            <button type="submit" name="wpsci_delete_guest_record" class="icon-button" title="'. __( 'Delete', 'wp-self-check-in' ) .'"><span class="dashicons dashicons-trash"></span></button>
          </form>
        ';


        $data[]=array(
          'id'=> $result['id'],
          'name'=> $result['name'] . ' ' . $result['surname'],
          'email'=> $result['email'],
          'phone'=> $result['phone'],
          'country'=> $result['country'],
          'booking_id'=> $result['booking_id'],
          'stay_period'=> $result['stay_period'],
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
        'name'   => __('Name', 'wp-self-check-in' ),
        'email'   => __('Email', 'wp-self-check-in' ),
        'phone'   => __('Phone', 'wp-self-check-in' ),
        'country'   => __('Country', 'wp-self-check-in' ),
        'booking_id' => __('Booking ID', 'wp-self-check-in' ),
        'stay_period' => __('Stay Period', 'wp-self-check-in' ),
        'action'   => __('Action', 'wp-self-check-in' ),
      );
    }

    function get_sortable_columns() {
      return array();
    }
}

?>


<div class="wrap">
  <?php 
  /**
   * include tabs template
   */
  include_once WPSCI_PLUGIN_PATH . 'admin/tabs/tabs.php';?>
      
  <div class="metabox-holder">
    <div class="inside">
    
      <p>
        <form action="<?= admin_url('admin-post.php')?>" method="post" align="right">
          <input type="hidden" name="action" value="export_guest_data_csv">
          <button class="create-check-in button button-secondary"><?php _e( 'Export CSV', 'wp-self-check-in' );?></button>
        </form>
      </p>

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
