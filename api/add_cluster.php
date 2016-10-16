<?php
  require('lib.php');

  echo "<PRE>";
  print_r($_GET);

  $i = $_GET['input'];
  
  //going to sudo here, so validate stuff
  validate_ip($i['addr']);
  ctype_digit($i['port']) OR trigger_error("[ {$i['port']} ] invalid port", E_USER_ERROR);
  
  switch ( $i['scheduler'] ) {
    case 'rr':
    case 'wrr':
    case 'lc':
    case 'wlc':
    case 'lblc':
    case 'dh':
    case 'sh':
    case 'sed':
    case 'nq':
      break;
    default:
      trigger_error("[ {$i['scheduler']} ] invalid scheduler", E_USER_ERROR);
  }
  
  $cmd = "sudo /sbin/ipvsadm -A -t ".escapeshellarg("{$i['addr']}:{$i['port']}")." -s ".escapeshellarg($i['scheduler']).' 2>&1';
  
  exec($cmd, $out, $status);
  print_r($out);
  
  echo json_encode( array('cmd'=>$cmd, 'status'=>$status, 'msg'=>$out) );
