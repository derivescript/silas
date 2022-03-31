<?php

class Template{
	private $arquivo;
	private $pasta;
	public $valores;
	private $html = '';
	
	
	public function __construct($arquivo,$dados=array()){
		//Crio uma nova instancia da classe URI e pego a pasta admin
		$uri = new URI;
		$this->pasta = $uri->getpasta();
		$this->arquivo = $arquivo;
		$this->valores =$dados;
		return $this->arquivo;
		return $this->valores;
	}
	
	public function __set($variavel='', $valor=''){
		$this->valores[$variavel] = $valor;
	}
	
	public function existe($variavel){
		
	}
	/**
	 * Substitui as variaveis do template por codigo HTML
	 */
	public function parse($dados=''){
		if($this->pasta==''){
			$content = file_get_contents(appdir."/view/{$this->arquivo}");		
		}else{
			$content = file_get_contents(appdir."/{$this->pasta}/view/{$this->arquivo}");		
		}	
		
		foreach($this->valores as $tag=>$replace){
			$content = str_replace("{".$tag."}", $replace, $content);
		}
		$this->html = $content;
		echo $this->html;		
	}
}
