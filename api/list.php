<?php

/*
IP Virtual Server version 1.2.1 (size=4096)
Prot LocalAddress:Port Scheduler Flags
  -> RemoteAddress:Port           Forward Weight ActiveConn InActConn
TCP  192.168.10.116:25 rr
  -> 192.168.10.225:25            Route   1      0          0
  -> 192.168.10.227:25            Route   1      0          0
*/


$descriptorspec = array(
   0 => array("pipe", 'r'),  // stdin is a pipe that the child will read from
   1 => array("pipe", 'w'),  // stdout is a pipe that the child will write to
   2 => array("pipe", 'r') // stderr is a file to write to
);

$process = proc_open('sudo /sbin/ipvsadm -L -n', $descriptorspec, $pipes);

$lvs = array('clusters'=>array());
$server_ref = false;

while ( $i = trim(fgets($pipes[1])) ) {
   if ( preg_match('#^([A-Z]+)\s+([^\s:]+):([^\s]+)\s+([^\s]+)#', $i, $r) ) {
       $lvs['clusters'][] = array('proto'=>$r[1], 'addr'=>$r[2], 'port'=>$r[3], 'algo'=>$r[4], 'servers'=>array());
       $server_ref =  &$lvs['clusters'][count($lvs['clusters'])-1]['servers'];
       continue;
   }
      
   if ( preg_match('#^\s*->\s+([^\s:]+):([^\s]+)\s+([^\s]+)#', $i, $r) ) {
      $server_ref[] = array('addr'=>$r[1], 'port'=>$r[2], 'forward'=>$r[3]);
   }
}
proc_close($process);

#print_r($lvs);
echo json_encode($lvs);