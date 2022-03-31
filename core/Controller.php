<?php

namespace core;

class Controller{

	public function __construct(){
		
	}
	/**
	 * Carrega uma view 
	 * @author: Daniel Faria 
	 */
	public function view($view,$dados=''){
		//Crio uma nova instancia da classe router e pego a pasta admin
		$router = new Router;
		$pasta = $router->set_app_dir();
		$subpasta = $router->set_sub_folder();
		//Se estou em uma pasta dentro de apps, entao vou buscar uma view dentro de apps/pasta/views
		if(isset($pasta)){
			$viewfile = appdir."/".$pasta."/views/{$view}.php";
		}else{
			$viewfile = appdir."/views/{$view}.php";
		}
		
		//Verificando se o arquivo existe
		if(file_exists($viewfile)){
			//Ler o conteudo da view
			$content = file_get_contents($viewfile);
			//Passando dados para a view
			
			if(isset($dados) && $dados!=''){
				//Estou passando um array
				if(is_array($dados) && count($dados,COUNT_NORMAL)>0)
			    {
					//Substituir dados na view					
			        foreach($dados as $chave=>$valor){						
						//Se o indice for um array
						if(is_array($dados[$chave]))
						{
							$code ='';
							foreach($dados[$chave] as $key=>$val)
							{
								$pattern = '/{'.$chave.'}/';
								if(preg_match($pattern,$content,$matches))
								{
									$code .= $dados[$chave][$key]->nome;
									
								}								
							}
							$content = str_replace('{'.$chave.'}',$code,$content);
						}else{
							
							$content = str_replace('{'.$chave.'}',$valor,$content);
						}			            
					}
					$content = str_replace('{'.$chave.'}',$valor,$content);			        
					print eval('?>'. $content);
				//Se for um objeto
				}else{					
					$content = str_replace('{'.$chave.'}',$valor,$content);
				}
			//Sem dados, apenas retornar a view				    	
			}else{
				include_once(appdir."/".$pasta."/views/{$view}.php");
			}
			
		    //O arquivo indicado não existe, então mostro mensagem de erro
		}else{
		    echo "Arquivo {$view} nao encontrado";
		}
				
	}

	public function model($model){
		//Crio uma nova instancia da classe router e pego a pasta admin
		$router = new Router;
		$pasta = $router->set_app_dir();
        //Se estou na pasta admin, entao vou buscar um model dentro de site/admin/model
		if(isset($pasta)){
			$modelfile = appdir."/".$pasta."/models/{$model}.php";
			//Encontrei o model. Entao crio uma nova instancia de Model e retorno ela para o Controller
			$class = appdir.'\\'.$pasta.'\\models\\'.$model;
		}else{
			$modelfile = modeldir."/{$model}.php";
			$class = appdir.'\\models\\'.$model;
		}	
		
		if(file_exists($modelfile)){
			require_once $modelfile;
		}else{
			echo "Arquivo {$modelfile} não encontrado";
		}
		$model = new $class;
		return $model;		
	}

	/**
	 * Carrega uma view e seus dados para dentro de um template.
	 * Deve ser capaz nao so de carregar os templates, mas capaz de subsitituir variaveis de template
	 * @param $template - arquivo usado como template
	 * @param $view - Arquivo que sera carregado dentro do template
	 * @param $dados - Conjunto de dados que serao passados para a view
	 * @param $global - Define se o template sera enxergavel atraves de toda a aplicacao ou apenas na
	 * pasta onde o controller esta sendo carregado 
	 */
	public function template($template,$view='',$dados='',$global=false){
		/*
		 * Crio uma nova instancia da classe router e pego a pasta de 
		 * onde estou acessando o controller
 		 */
		$router = new router;
		$controldir = $router->set_app_dir();
		//$dados = $dados;
		/*
		 * Se o parametro global for setado como false, o controller deve buscar o template dentro da
		 * pasta aplicacao/subpasta/view, caso contrario, busca o template na pasta
		 * aplicao/view 
		 */
		if(isset($controldir)){
			$template = appdir."/".$controldir."/views/{$template}.php";
			$viewfile = appdir."/".$controldir."/views/{$view}.php";
		}else{
			$template = appdir."/views/{$template}.php";
			$viewfile = appdir."/views/{$view}.php";
		}
		
		//Carrega o template
		if(file_exists($template)){
			
			/*
			Leitura de dados passados para a view
			*/
			if(isset($dados) && $dados!='' && count($dados,COUNT_NORMAL)>0){
				//Ler o conteudo da view
				$content = file_get_contents($viewfile);
				$tcontent = file_get_contents($template); 
				//Substituir dados na view
				foreach($dados as $chave=>$valor){
					$content = str_replace('{'.$chave.'}',$valor,$content);
					$tcontent = str_replace('{'.$chave.'}',$valor,$tcontent);
					$template = $tcontent;
					$conteudo = $content;
				}
				$viewfile = $content;
				print eval("?>". $template);
			}else{
				include($template);
			}
			/* Fim da leitura de dados*/
		}else{
			//o template informado nao existe
			echo "Arquivo {$template} nao encontrado";
		}		
		//Fim else		
		
	}

	public function load($pasta,$classe){
		require_once basedir().'/sistema/'.$pasta.'/'.$classe.'.php';
		$classe = new $classe;
		return $classe;
	}
}