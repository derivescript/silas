<?php

namespace helpers;

/**
 * Checkbox
 */

class TCheckbox{
	private $name;
	private $id;
	private $value;
	private $class;
	private $checked;
	private $data = array();

	
	public function __construct($name){
		$this->name=$name;
		$this->id=$name;		
	}
	
	public function set_value($value){
		$this->value=$value;
		return $this->value;
	}
	
	public function set_data($nome,$valor){
		$par = array('nome'=>$nome,'valor'=>$valor);
		 array_push($this->data,$par);
		 return $this->data;
	}
	
	public function set_class($class){
		$this->class=$class;
	}
	public function exibir(){
		$html ='<input type="checkbox" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'" class="'.$this->class.'"';
		foreach($this->data as $data){
			$html .= 'data-'.$data['nome'].'="'.$data['valor'].'"';
		}
		$html .= "/>";
		return $html;
	}
	
}