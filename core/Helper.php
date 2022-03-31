<?php

namespace core;

class Helper{
	public function __construct(){
		$this::carrega_helpers();	
	}
	
	public static function carrega_helpers(){
	$helper = scandir('helpers');
	sort($helper);
	foreach($helper as $helper){
		if($helper != '.' && $helper != '..' && !is_dir(sisdir.'/helpers/'.$helper)){
		require('helpers/'.$helper);
		}
	}
	}
}
