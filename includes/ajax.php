<?php
/**
 * 
 * Handle ajax request
 */
class wpsci_ajax{
  
    private static $instance;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){

        add_action('wp_ajax_get_municipal_data', array($this, 'get_municipal_data_handler'));
        add_action('wp_ajax_nopriv_get_municipal_data', array($this, 'get_municipal_data_handler'));

        add_action('wp_ajax_check_booking_id', array($this, 'check_booking_id_handler'));
        add_action('wp_ajax_nopriv_check_booking_id', array($this, 'check_booking_id_handler'));
    }

    /**
     * 
     * get municipal data as per province
     */
    public function get_municipal_data_handler() {
        global $wpdb;
        
        $list = get_municipal_list();
    
        $items = array_filter($list, function($item) {
        return $item[2] === $_POST['province'];
        });
    
        $options = '';
        foreach ($items as $key => $item) {
    
        $options .= '<option value="'.$item[0].'" data-provice="'.$item[2].'">'. $item[1] .'</option>';
        }
    
        wp_send_json_success($options);
        wp_die();
    }

    /**
     * 
     * validate booking id
     */
    function check_booking_id_handler() {
        global $wpdb;
    
        if(isset($_POST['booking_id']) && $_POST['booking_id']!=''){
        $booking_id = $_POST['booking_id'];
        
        $sql = "select * from ".$wpdb->base_prefix."posts where ID = '$booking_id' AND post_type = 'mphb_booking'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        
        if($result){
            wp_send_json_success('unavailable');
            wp_die();
        }
        
        $sql = "select * from ".$wpdb->base_prefix."wpsci_check_in where booking_id = '$booking_id'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        
        if($result){
            wp_send_json_success('unavailable');
            wp_die();
        }
        }else{
        $result = $wpdb->get_row("SELECT * FROM ".$wpdb->base_prefix."wpsci_check_in ORDER BY id DESC", ARRAY_A);
        if($result){
    
            $check_id = $result['id']+1;
            $result = $wpdb->get_row("SELECT * FROM ".$wpdb->base_prefix."wpsci_check_in WHERE booking_id = $check_id", ARRAY_A);
            if($result){
            wp_send_json_success('unavailable');
            wp_die();
            }
    
            $mphb_result = $wpdb->get_row("SELECT * FROM ".$wpdb->base_prefix."posts WHERE ID = $check_id AND post_type = 'mphb_booking'", ARRAY_A);
            if($mphb_result){
            wp_send_json_success('unavailable');
            wp_die();
            }
        }
        }
    
        wp_send_json_success('available');
        wp_die();
    }

}

$wpsci_instance = wpsci_ajax::getInstance();