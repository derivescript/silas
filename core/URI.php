<?php

namespace core;

class URI{
	
	private $segmentos;
	/**
	 * 
	 */
	public function __construct()
	{
		$this->set_segmentos();
		$uri = $this->checar();
		
	}
	/**
	 * 
	 */
	public function get_request()
	{
		return $_SERVER['REQUEST_URI'];
	}
	/**
	 * 
	 */
	public function set_segmentos(){
		$this->segmentos=explode("/",ltrim($_SERVER['REQUEST_URI'],'/'));		
		return $this->segmentos;
	}
	/**
	 * 
	 */
	public function getsegmento($segmento){
		if(isset($this->segmentos[$segmento])){
			return $this->segmentos[$segmento];
		}
	}
	/**
	 * 
	 */
	public function checar()
	{
		$uri = '';
		$get = filter_input(INPUT_GET, "url");
		if(isset($get)){
			$uri = explode('/',$get);
		}
		return $uri;
	}
}