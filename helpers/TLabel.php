<?php

namespace Helpers;

/**
 * Classe para 
 */
class TLabel{
	private $for;
	private $text;
	private $class;
	
	public function __construct($for, $text){
		$this->for=$for;	
		$this->text=$text;
	}
	
	public function set_class($class){
		$this->class=$class;
	}
	
	public function exibir(){
		echo '<label for="'.$this->for.'" class="'.$this->class.'">'.$this->text.'</label>'."\n";
	}
}
