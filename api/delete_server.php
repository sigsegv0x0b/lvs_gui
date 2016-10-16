<?php
  require('lib.php');

  $s = $_GET['ref']['server'];
  $c = $_GET['ref']['cluster'];
  
  //going to sudo here, so validate stuff
  validate_ip($c['addr']);
  validate_ip($s['addr']);
  ctype_digit($s['port']) OR rq_error("[ server_port={$s['port']} ] invalid port");
  ctype_digit($c['port']) OR rq_error("[ cluster_port={$c['port']} ] invalid port");
  
  $cmd = 'sudo /sbin/ipvsadm -d -t '.escapeshellarg("{$c['addr']}:{$c['port']}").' -r '.escapeshellarg("{$s['addr']}:{$s['port']}").' 2>&1';
  
  exec($cmd, $out, $status);
    
  do_shell_cmd($cmd);    
  echo json_encode( array('cmd'=>$cmd, 'status'=>rq_error_count(), 'msg'=>rq_get_errors()) );
