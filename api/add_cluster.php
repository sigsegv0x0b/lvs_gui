<?php
  require('lib.php');

  $i = $_GET['input'];
  
  //going to sudo here, so validate stuff
  validate_ip($i['addr']);
  ctype_digit($i['port']) OR rq_error("[ port={$i['port']} ] invalid port");
  
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
      rq_error("[ scheduler={$i['scheduler']} ] invalid scheduler");
  }
  
  $cmd = 'sudo /sbin/ipvsadm -A '.get_proto_switch($i['proto']).' '.escapeshellarg("{$i['addr']}:{$i['port']}")." -s ".escapeshellarg($i['scheduler']).' 2>&1';
  echo do_shell_cmd($cmd);  
  