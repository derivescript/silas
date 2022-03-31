<?php

namespace helpers;

/*Classe para manipulacao de formularios
 * atributos - nome, id, metodo, enctype
 * metodos - construct (herdado da classe HTML, addfield
 * 
 */
class TForm{
	private $nome;
	private $action;
	private $id;
	private $method;
	private $enctype;
	private $editable;
	protected $fields;	
	
	//metodo construtor
	public function __construct($name){
		$this->setname($name);
	}
	
	public function setname($name){
		$this->name=$name;
	}
	
	public function setenctype($type){
		switch($type){
			case "1":		
			$this->enctype="multipart/form-data";
			break;
			
			case "2":
			$this->enctype="application/x-www-form-urlencoded";	
			break;
			
			case "3";
			$this->enctype="text/plain";
			break;	
		}
		return $this->enctype;
	}
	
	public function setmethod($method){
		$this->method=$method;
		
	}
	public function setaction($action){
		$this->action=$action;	
	}
	
	public function setid($id){
		$this->id=$this->name;
	}
	
	public function show(){
		echo "<form action=\"{$this->action}\" method=\"{$this->method}\" name=\"{$this->name}\" id=\"{$this->name}\" enctype=\"{$this->enctype}\" accept-charset=\"latin1\">\n";		
	}
	
	public function close(){
		echo "</form>\n";
	}
	
}
?>