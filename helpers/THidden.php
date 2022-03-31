<?php

namespace helpers;

/**
 * Cria um campo de formulario do tipo Hidden
 */
class THidden{
	private $id;	
	private $name;
	private $value;
	
	
	public function __construct($name){
		$this->name=$name;
		$this->id=$name;
	}
		
	public function set_value($value){
		$this->value=$value;
		return $this->value;
	}
	
	public function exibir(){
		echo "<input type=\"hidden\" name=\"{$this->name}\" id=\"{$this->id}\" value=\"{$this->value}\" />";
	}
}
