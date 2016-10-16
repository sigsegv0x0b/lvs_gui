<?php
  require('lib.php');

  $s = $_GET['ref']['server'];
  $c = $_GET['ref']['cluster'];
  
  //going to sudo here, so validate stuff
  validate_ip($c['addr']);
  validate_ip($s['addr']);
  ctype_digit($s['port']) OR trigger_error("[ {$s['port']} ] invalid port", E_USER_ERROR);
  ctype_digit($c['port']) OR trigger_error("[ {$c['port']} ] invalid port", E_USER_ERROR);
  
  $cmd = "sudo /sbin/ipvsadm -d -t {$c['addr']}:{$c['port']} -r {$s['addr']}:{$s['port']}";
  
  exec($cmd, $out, $status);
  
  echo json_encode( array('cmd'=>$cmd, 'status'=>0, 'msg'=>$out) );
