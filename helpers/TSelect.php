<?php

namespace helpers;

/**
 * Campo select sem painel a dados
 */
class TSelect{
	private $nome;
	private $id;
	private $items;
	private $padrao;
	private $class;
	private $html ='';	
	private $texto;
	/**
	 * 
	 */ 
	public function __construct($nome){
		$this->nome=$nome;
	}
	/**
	 * 
	 */ 
	public function set_class($classes){
		$this->class=$classes;
		return $this->class;
	}
		
	/**
	 * Abre a tag do select 
	 */	 
	public function abrir(){
		if($this->class){
			$this->html.="<select name=\"{$this->nome}\" id=\"{$this->nome}\" class=\"{$this->class}\">\n";	
		}else{
			$this->html.="<select name=\"{$this->nome}\" id=\"{$this->nome}\">\n";
		}	
	}
	/**
	 * 
	 */
	public function set_texto($texto)
	{
		$this->texto=$texto;
		return $this->texto;
	}
	/**
	 * Cria os items para serem adicionados ao select
	 */
	public function set_items($items){
		$this->items=$items;
	}

	/**
	 * Popular o array $items
	 */
	public function popular($max = 10){
		for($i=1;$i<=$max;$i++){
			$this->items[$i]=$i;
		}
	}
	/**
	 * Isto retorna seleciona qual o value do select que ira
	 * aparecer como selected
	 */
	public function selected($value){
	 	$this->padrao=$value;
		return 	$this->padrao;
	}
	
	public function getitems(){
		$this->html .='<option selected="selected">'.$this->texto.'</option>';
	    foreach ($this->items as $value => $option) {
			if(is_array($option)){
				$indices = array_keys($option);
				$val = $option[$indices[0]];
				$opcao = $option[$indices[1]];
				if($this->padrao && $this->padrao==$value){
					$this->html .="<option value=\"{$val}\" selected=\"selected\">{$opcao}</option>\n";	
				}else{
					$this->html .="<option value=\"{$val}\">{$opcao}</option>\n";	
				}			
			}elseif(is_object($option)){
				$attr = array();
				$vars = get_object_vars($option);
				foreach($vars as $campo){
					array_push($attr,$campo);
				}
				$value = $attr[0];
				$opcao = $attr[1];
				if($this->padrao && $this->padrao==$value){
					$this->html .="<option value=\"{$value}\" selected=\"selected\">{$opcao}</option>\n";	
				}else{
					$this->html .="<option value=\"{$value}\">{$opcao}</option>\n";	
				}
			}else{
				if($this->padrao && $this->padrao==$value){
					$this->html .="<option value=\"{$value}\" selected=\"selected\">{$option}</option>\n";	
				}else{
					$this->html .="<option value=\"{$value}\">{$option}</option>\n";	
				}
			}						
		}	
	}
	
	public function fechar(){
		$this->html .="</select>";
	}
	
	/**
	 * Exibe o objeto na tela 
	 */
	public function exibir(){
		$this->abrir();
		$this->getitems();
		$this->fechar();
	    return $this->html;
	}

	/**
	 * Retorna o HTML do elemento select
	 */
	public function __toString(){
		return $this->html;
	}
	
}