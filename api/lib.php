<?php

$GLOBALS['lvs_ui_error'] = array();

function validate_ip($ip)
{
  // if you care about ipv6 make this validator work with it
  if ( !preg_match('#^([0-9]{1,3}\.){3}[0-9]{1,3}$#', $ip, $r) ) {
    rq_error("[ ip=$ip ] is not an ip");
    return false;
  }
  
  return true;
}


function rq_error($msg=false)
{
  if ( !$msg ) {
    if ( count($GLOBALS['lvs_ui_error']) ) return true;
    return false;
  }

  if ( is_array($msg) ) {
    if ( count($msg) ) {
      $GLOBALS['lvs_ui_error'] = array_merge($GLOBALS['lvs_ui_error'], $msg);
    }
  }
  else {
    $GLOBALS['lvs_ui_error'][] = $msg;
  }
  
  return true;
}

function rq_get_errors()
{
  return $GLOBALS['lvs_ui_error'];
}

function rq_error_count()
{
  return count($GLOBALS['lvs_ui_error']);
}

function do_shell_cmd($cmd)
{
  if ( !rq_error() ) {
    exec($cmd, $out, $status);
    rq_error($out);
  }
  
  return json_encode( array('cmd'=>$cmd, 'status'=>rq_error_count(), 'msg'=>rq_get_errors()) );
    
}
