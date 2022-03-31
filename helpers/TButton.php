<?php

namespace helpers;

/**
 * Classe para gerar botao em formularios
 */
class TButton{
	private $label;
	private $class;
     private $id;
     private $type = "button";
     private $value;
     private $html;
	/**
     * Metodo construtor
     * @param $label;
     */
	public function __construct($label){
		$this->label=$label;
	}
	/**
     * Define o valor do atributo class do botao
     * @param $class;
     */
	public function set_class($class){
		$this->class=$class;
		return $this->class;
	}
	/**
     * Define o valor do atributo id do botao
     * @param $id;
     */
	public function set_id($id){
		$this->id=$id;
    }
    /**
     * Define o tipo do botao
     * @param $tipo
     */
    public function set_tipo($tipo="button"){
        $this->type=$tipo;
        return $this->type;
    }
    /**
     * 
     */
    public function set_value($valor)
    {
          $this->value=$valor;         
    }

    /**
     * 
     */

     public function get_value()
     {
          return $this->value;
     }
	/**
     * Exibe o botao na tela com seus atributos
     */	
	public function exibir(){
          $this->html = '<button type="'.$this->type.'" id="'.$this->id.'" class="'.$this->class.'" data-value="'.$this->get_value().'">'.$this->label.'</button>';
          return $this->html;
	}
}