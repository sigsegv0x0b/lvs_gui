<?php
  require('lib.php');

  #echo "<PRE>";
  #print_r($_GET);

  $c = $_GET['ref']['cluster'];
  $i = $_GET['input'];
  
  //going to sudo here, so validate stuff
  validate_ip($i['addr']);
  ctype_digit($i['port']) OR trigger_error("[ {$i['port']} ] invalid port", E_USER_ERROR);
  
  validate_ip($c['addr']);
  ctype_digit($c['port']) OR trigger_error("[ {$c['port']} ] invalid port", E_USER_ERROR);
  
  if ( $i['weight'] ) {
    ctype_digit($i['weight']) OR trigger_error("[ {$i['weight']} ] invalid weight", E_USER_ERROR);
    $weight = ' -w '.escapeshellarg($i['weight']);
  } else {
    $weight = '';
  }

  $forward = '';
  switch ( $i['forward'] ) {
    case 'Masq':
      $forward = '-m';
      break;
    case 'Route':
      $forward = '-g';
      break;
    case 'Tunnel':
      $forward = '-i';
      break;
    default:
      trigger_error("[ {$i['forward']} ] invalid forwarding mode", E_USER_ERROR);
  }
  
  $cmd = 'sudo /sbin/ipvsadm -a -t '.escapeshellarg("{$c['addr']}:{$c['port']}").' -r '.escapeshellarg("{$i['addr']}:{$i['port']}")." $forward $weight 2>&1";
  exec($cmd, $out, $status);
    
  echo json_encode( array('cmd'=>$cmd, 'status'=>$status, 'msg'=>$out) );
