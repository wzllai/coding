<?php
$max = 6;
$current = 0;
pcntl_signal(SIGCHLD, "reduce_current");

/*
* signal callback function 
*/
function reduce_current($signal)
{
	global $current;
	if ($signal === SIGCHLD) {
		$current--;
	}
}

while(1) {
	$current++;
	if (($pid = pcntl_fork()) === -1) {
		//log and  exit
	
	} elseif ($pid) {
		//father process
		if ($current >= $max ) {
			if(pcntl_wait($status) === -1) {
				//log or exit
			}
		}
		
	} else {
		//child process 
		//do something repalce sleep
		sleep(3);
		exit;
	}
}

