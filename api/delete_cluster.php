<?php
  require('lib.php');
  
  $c = $_GET['ref']['cluster'];
  
  //going to sudo here, so validate stuff
  validate_ip($c['addr']);
  ctype_digit($c['port']) OR rq_error("[ cluster_port={$c['port']} ] invalid port");
  
  $cmd = 'sudo /sbin/ipvsadm -D '.get_proto_switch($c['proto']).' '.escapeshellarg("{$c['addr']}:{$c['port']}").' 2>&1';
  echo do_shell_cmd($cmd);
