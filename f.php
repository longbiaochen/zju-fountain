<?php
/* Author: Felix
   Date: 2011-12-26
   Usage: Flow control
*/
   
session_start();

include_once( 'global.php' );

date_default_timezone_set('Asia/Taipei');

$f_h_prev=$_SESSION['F_H'];
$f_flow_count=$_SESSION['F_C'];
$f_h_now=date("H");

$f_fc=false; // flow control

if($f_h_prev==$f_h_now){
  // same hour, start flow control
  $f_flow_count++;
  // echo $f_flow_count;
  $f_h_ub=$ARRAY_UPBOUND[$f_h_now];
  
  if($f_flow_count>=$f_h_ub){
    echo 'Flow Limit Exceeded, ';
    $f_fc=true;
  } else {
    echo 'Flow No.'.$f_flow_count.', ';
    $f_fc=false;
  }
  $_SESSION['F_C']=$f_flow_count;
  
} else {
  // new hour, reset
  $_SESSION['F_H']=date("H");

}
if($f_fc){
  echo 'Flow control is ON.';
} else {
  echo 'Flow control is OFF.';

}


?>
