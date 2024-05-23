<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

//creates wpsci_guests table
$table_name = $wpdb->base_prefix.'wpsci_guests';
$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

if ( ! $wpdb->get_var( $query ) == $table_name ) {

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `booking_id` int(11) DEFAULT NULL,
          `first_name` text DEFAULT NULL,
          `last_name` text DEFAULT NULL,
          `email` varchar(100) DEFAULT NULL,
          `phone` varchar(100) DEFAULT NULL,
          `sex` varchar(20) DEFAULT NULL,
          `dob` date DEFAULT NULL,
          `country` text DEFAULT NULL,
          `country_code` varchar(50) DEFAULT NULL,
          `house` varchar(20) DEFAULT NULL,
          `provinces` varchar(30) DEFAULT NULL,
          `municipalities` varchar(30) DEFAULT NULL,
          `citizenship` varchar(50) DEFAULT NULL,
          `doc_type` text DEFAULT NULL,
          `doc_number` text DEFAULT NULL,
          `doc_issue_place` varchar(50) DEFAULT NULL,
          `doc_issue_province` varchar(30) DEFAULT NULL,
          `doc_issue_municipality` varchar(30) DEFAULT NULL,
          `doc_image` text DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT current_timestamp()
        ) $charset_collate;";

  dbDelta( $sql );
}


//creates check in table
$table_name = $wpdb->base_prefix.'wpsci_check_in';
$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

if ( ! $wpdb->get_var( $query ) == $table_name ) {

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `first_name` text DEFAULT NULL,
          `last_name` text DEFAULT NULL,
          `booking_id` varchar(20) DEFAULT NULL,
          `arrival_date` date DEFAULT NULL,
          `departure_date` date DEFAULT NULL,
          `number_of_guests` int(11) DEFAULT NULL,
          `check_in_key` varchar(20) DEFAULT NULL,
          `receipt` text DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT current_timestamp()
        ) $charset_collate;";

  dbDelta( $sql );
}


//creates guests data table
$table_name = $wpdb->base_prefix.'wpsci_guests_data';
$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

if ( ! $wpdb->get_var( $query ) == $table_name ) {

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name` text DEFAULT NULL,
          `surname` text DEFAULT NULL,
          `email` varchar(100) DEFAULT NULL,
          `phone` varchar(50) DEFAULT NULL,
          `country` varchar(50) DEFAULT NULL,
          `booking_id` varchar(20) DEFAULT NULL,
          `stay_period` varchar(50) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT current_timestamp()
        ) $charset_collate;";

  dbDelta( $sql );
}