<?php
  require('lib.php');

  #echo "<PRE>";
  #print_r($_GET);

  $c = $_GET['ref']['cluster'];
  $i = $_GET['input'];
  
  //going to sudo here, so validate stuff
  validate_ip($i['addr']);
  ctype_digit($i['port']) OR rq_error("[ server_port={$i['port']} ] invalid port");
  
  validate_ip($c['addr']);
  ctype_digit($c['port']) OR rq_error("[ cluster_port={$c['port']} ] invalid port");
  
  if ( $i['weight'] ) {
    ctype_digit($i['weight']) OR rq_error("[ weight={$i['weight']} ] invalid weight");
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
      rq_error("[ forward={$i['forward']} ] invalid forwarding mode");
  }
  
  $cmd = 'sudo /sbin/ipvsadm -a -t '.escapeshellarg("{$c['addr']}:{$c['port']}").' -r '.escapeshellarg("{$i['addr']}:{$i['port']}")." $forward $weight 2>&1";
  exec($cmd, $out, $status);
  
  do_shell_cmd($cmd);    
  echo json_encode( array('cmd'=>$cmd, 'status'=>rq_error_count(), 'msg'=>rq_get_errors()) );
