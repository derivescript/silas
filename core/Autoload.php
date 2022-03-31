<?php

namespace core;

class Autoload{
	
	public function __construct(){
		$this->load();
	}
	public function load(){
		//Busca o arquivo autoload que esta na pasta config dentro de aplicacao
		require_once appdir.'/config/autoload.php';
		//Sepra as biliotecas por virgula
		$bibliotecas = explode(',',$autoload['bibliotecas']);
		//Separa as pastas por vírgula
		$pastas = explode(',',$autoload['pastas']);
		if(sizeof($bibliotecas)>0){
			foreach($bibliotecas as $classe){
				if(file_exists(basedir().'/'.appdir."/vendor/{$classe}/{$classe}.php")){
					require_once(basedir().'/'.appdir."/vendor/{$classe}/{$classe}.php");
				}
			}	
		}
		//Testando se existe pelo menos uma pasta
		if(sizeof($pastas)>0){
			foreach($pastas as $pasta){
				$helper = scandir(basedir().'/'.appdir."/vendor/{$pasta}");
				sort($helper);
				foreach($helper as $arquivo){
					$include_file = basedir().'/'.appdir."/vendor/{$pasta}/{$arquivo}";
					if($include_file != '.' && $include_file != '..' && !is_dir($include_file) && mime_content_type($include_file)=='text/x-php'){						
						require_once($include_file);					
					}
				}
			}	
		}else{
			exit('Nenhuma pasta carregada');
		}
		//Fim do teste de pasta		
	}
	//Fim da função load 
}
