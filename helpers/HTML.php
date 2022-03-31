<?php

namespace helpers;

/**
 * Classe para gerar e manipular html
 * atributos 
 * ->name - nome da tag
 * ->atributos -
 * 
 * metodos
 * ->open - abre a tag na tela
 * ->addattr - adiciona os atributos 
 */

class html{
	private $doctype;	
	private $name;
	private $atributos;
	protected $children;
	private $html;
	//metodo setttype - define o tipo do documento, dependendo do doctype do documento
	
	//metodo construtor
	public function __construct($name)
	{
		$this->name = $name;
	} 
	
	/*
	 * intercepta as atribuicoes de valores as propriedades da tag
	 *  
	*/
	
	public function __set($name, $value)
	{
		$this->atributos[$name] = $value;
	}
	/**
	 * Adiciona o elemento filho a tag
	 */
	public function add($child)
	{
		$this->children[]=$child;
	}
	
	/*metodo para abertura da tag
	 */
	
	private function open()
	{
		$this->html = "<{$this->name}";
		if($this->atributos)
		{
			//percorre as propriedades do elemento
			foreach ($this->atributos as $name => $value) 
			{
				$this->html .= " {$name}=\"{$value}\"";					
			}
		}
		$this->html .= ">\n";
	}
	
	/*metodo exibir 
	 * exibe a tag na tela, junto com seu conteudo
	 */
	public function exibir()
	{
		//abre a tag
		$this->open();
		//se possui conteudo
		if($this->children)
		{
			//percorre todos os objetos filhos
			foreach ($this->children as $child) {
				//se for objeto
				if(is_object($child))
				{
					$this->html .= $child->exibir();
				}else if((is_string($child)) or (is_numeric($child))){
					//se for texto
					$this->html .=  $child; 
				}
			}
			 //fecha a tag
			 $this->close();
		}

		return $this->html;
	}
	
	private function close()
	{
		$this->html .=  "</{$this->name}>\n";
	}

	
}
?>