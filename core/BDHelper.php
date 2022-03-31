<?php

namespace core;

class BDHelper{
	public function __construct(){
	$this::carrega_bdhelpers();	
	}
	/**
	 * Responsavel por carregar as classes de bancos de dados
	 */
	public static function carrega_bdhelpers(){
	$bdhelper = scandir('database');
	sort($bdhelper);
	foreach($bdhelper as $bdhelper){
		if($bdhelper != '.' && $bdhelper != '..'){
		require('database/'.$bdhelper);
		}
	}
	}
}