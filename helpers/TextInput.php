<?php

namespace helpers;

class TextInput{
	private $name;
	private $class;
	private $value;
	private $html;
	
	public function __construct($name){
		$this->name=$name;
	}

	public function set_class($class){
		$this->class=$class;
	}
	
	public function setvalue($value){
		$this->value=$value;
	}
	
	public function exibir(){
		$this->html .= '<input type="text" name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'" value="'.$this->value.'" />';
		return $this->html;
	}		
}