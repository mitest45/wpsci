<?php
/**
 * 
 * DOWNLOAD TEXT FILE
 */
if(isset($_REQUEST['_download'])){
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename=guest_info.txt");

  $id = $_REQUEST['booking_id'];
  $result = $GLOBALS['wpdb']->get_results("SELECT * FROM ".$wpdb->base_prefix."wpsci_guests WHERE booking_id = '$id'", OBJECT);
  $res = json_decode(json_encode($result), true);

  $check_in_date = get_check_in($id);
  $check_out_date = get_check_out($id);

  $date1 = new DateTime($check_in_date);
  $date2 = new DateTime($check_out_date);
  $diff = $date1->diff($date2);
  $days_of_stay = $diff->days;

  if($days_of_stay>30){
    $days_of_stay = 30;
  }

  if($days_of_stay<10){
    $days_of_stay = '0'.$days_of_stay;
  }

  foreach ($res as $key => $val) {
    echo str_pad($val['house'],2," ");
    echo str_pad(date("d/m/Y", strtotime($check_in_date)),10," ");
    echo str_pad($days_of_stay,2," ");
    echo str_pad($val['last_name'],50," ");
    echo str_pad($val['first_name'],30," ");
    if($val['sex']=='male') echo str_pad(1,1," ");
    if($val['sex']=='female') echo str_pad(2,1," ");
    echo str_pad(date("d/m/Y", strtotime($val['dob'])),10," ");
    echo str_pad($val['municipalities'],9," ");
    echo str_pad($val['provinces'],2," ");
    echo str_pad($val['country_code'],9," ");
    echo str_pad($val['citizenship'],9," ");
    echo str_pad($val['doc_type'],5," ");
    echo str_pad($val['doc_number'],20," ");
    if($val['doc_issue_place']==100000100) echo str_pad($val['doc_issue_municipality'],9," ");
    else echo str_pad($val['doc_issue_place'],9," ");
    if ($key === array_key_last($res)) {

    }else{
      echo "\r\n";
    }
  }
  exit();
}