<?php

namespace helpers;

 /**
  * Cria um campo radio no formalario
  */
 class TRadio{
	private $name;
	private $id;
	private $value;
	private $checked;
 	
	public function __construct($name){
		 $this->name=$name;
		 $this->id=$name;		
	}
 	
	public function set_value($value){
		 $this->value=$value;
		 return $this->value;
	}
 	
	public function check($val=1){
		 $this->checked=$val;
		 return $this->checked;
	}
 	
	public function exibir(){
	    if($this->checked=1){
		    echo "<input type=\"radio\" name=\"{$this->name}\" id=\"{$this->id}\" value=\"{$this->value}\" checked=\"checked\" />";
		}else{
		    echo "<input type=\"radio\" name=\"{$this->name}\" id=\"{$this->id}\" value=\"{$this->value}\" />";
		}	
    }
}