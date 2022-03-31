<?php

namespace helpers;

use function core\pre;

class TRadioGroup{
	private $nome;
	private $id;
	private $opcoes = array();
	private $value;
	private $checked;
	private $html;
	
	public function __construct($nome){
		$this->nome=$nome;
		$this->id=$nome;		
	}

	/**
	 * Cria os items para serem adicionados ao RadioGroup
	 */
	public function set_opcoes($opcoes){
		$this->opcoes=$opcoes;
	}
	
	public function set_valor($value){
		$this->value=$value;
		return $this->value;
	}
	
	public function set_padrao($opcao){
		$this->checked=$opcao;
		return $this->checked;
	}
	
	
	public function exibir(){
		foreach($this->opcoes as $valor=>$opcao){
		    if($this->checked == $valor){
				$this->html .="<input type=\"radio\" name=\"{$this->nome}\" id=\"{$this->id}\" value=\"{$valor}\" checked=\"checked\" /> <span class=\"opcao\">{$opcao}</span>  ";
			}else{
				$this->html .="<input type=\"radio\" name=\"{$this->nome}\" id=\"{$this->id}\" value=\"{$valor}\" /> <span class=\"opcao\">{$opcao}</span>  ";
			}
		}
		return $this->html;	
	}
	
}