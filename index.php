<?php

// Kickstart the framework
$f3=require('lib/base.php');
  $f3->route('GET /',
 	    function() {
     echo "hello world";        
 		}
);

$f3->run();

 

