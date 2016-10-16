<?php

function validate_ip($ip, $fatal=true)
{
  // if you care about ipv6 make this validator work with it
  if ( !preg_match('#^([0-9]{1,3}\.){3}[0-9]{1,3}$#', $ip, $r) ) {
    $fatal && trigger_error("[ $ip ] is not an ip", E_USER_ERROR);
    return false;
  }
  
  return true;
}