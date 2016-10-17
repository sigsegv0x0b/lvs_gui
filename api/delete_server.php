<?php
  require('lib.php');

  $s = $_GET['ref']['server'];
  $c = $_GET['ref']['cluster'];
  
  //going to sudo here, so validate stuff
  validate_ip($c['addr']);
  validate_ip($s['addr']);
  ctype_digit($s['port']) OR rq_error("[ server_port={$s['port']} ] invalid port");
  ctype_digit($c['port']) OR rq_error("[ cluster_port={$c['port']} ] invalid port");
  
  $cmd = 'sudo /sbin/ipvsadm -d '.get_proto_switch($c['proto']).' '.escapeshellarg("{$c['addr']}:{$c['port']}").' -r '.escapeshellarg("{$s['addr']}:{$s['port']}").' 2>&1';
  echo do_shell_cmd($cmd);    
