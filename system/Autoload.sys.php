<?php

// Autoload Files
foreach (config('autoload.files') as $file) {
	require dirname(__DIR__)."/".$file;
}

function AutoLoadClass($class){
	foreach (config('autoload.classmap') as $folder) {
		$filename = dirname(__DIR__)."/".trim($folder,"/")."/" . $class . ".php";
	    if (is_readable($filename)) {
	        require $filename;
	    }
	}	
}

spl_autoload_register('AutoLoadClass');