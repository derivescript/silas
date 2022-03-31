<?php

namespace helpers;

/**
 * Classe que gera campos tipo Password
 */
class TPassword{
	private $name;
	private $class;
	private $value;
	
	public function __construct($name,$class){
		$this->name=$name;
		$this->class=$class;	
	}
	
	public function setvalue($value){
		$this->value=$value;
	}
	
	public function show(){
		echo "<input type=\"password\" name=\"{$this->name}\" id=\"{$this->name}\" class=\"{$this->class}\" value=\"{$this->value}\" />";
	}			
}