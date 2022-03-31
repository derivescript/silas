<?php

namespace Helpers;

use function Core\basedir;
use Database;
use Helpers;

class Classbuilder{
	public $dbname;
    public $pasta;
	public $tables = array();
	private $config = array();
	private $pdo;
	private $consulta;
	
	/**
	 * Metodo construtor
	 * @param $dbname
	 * @param $pasta
	 */
	public function __construct(){
		
	}

	/**
	 * 
	 */
	public function set_banco($dbname)
	{
		$this->banco = $dbname;
		require appdir.'/config/database.php';
		foreach($config as $dbname){
			if($dbname['name']==$this->banco)
			{
				$this->pdo = $this->connect($this->banco);
				$this->gettables($this->getbanco());
			}			
		}		
	}
	/**
	 * 
	 */
	public function set_pasta($pasta)
	{
		$this->pasta= $pasta;
	}
	/**
	 * 
	 */
	public function exibir()
	{
		echo '<div style="column-count:2;">';

		foreach($this->tabelas as $table)
		{
			$opcao = new Helpers\TCheckbox('tabelas[]');
			$opcao->set_class('opcao');
			$opcao->set_value($table);
			$opcao->set_data('tabela',$table);
			echo $opcao->exibir();
			$label = new TLabel('tabela',$table);
			$label->set_class('label-opcao');
			$label->exibir();
			echo br;
		}
		echo '</div>';
	}
	/**
	 * Retorna o banco de onde serão gerados os arquivos
	 */
	public function getbanco()
	{
		return $this->banco;
	}
	/**
	 * Retorna a pasta onde serão gravados os arquivos
	 */
	public function get_pasta()
	{
		return $this->pasta;
	}
	/**
	 * Conecta ao banco de dados
	 * @param $dbname;
	 */
	public function connect($dbname)
	{
		try{
			$conexao = new Database\Conexao;
			$this->pdo = $conexao->conectar($this->banco);
			return $this->pdo;
		}catch(\Exception $e){
			echo $this->consulta."<br>".$e->getMessage();
		}
	}

	public function gerar()
	{

	}

	public function gettables($dbname){
		$sql=$this->pdo->query("SHOW TABLES FROM {$dbname}");
		while($table = $sql->fetch(\PDO::FETCH_ASSOC)){
			array_push($this->tabelas,$table['Tables_in_'.$this->banco]);			
		}		
	}

	public function gerarentidade($table){
			 
		$this->connect($this->banco);
		if(strpos($table,'_')){
			$partes = explode('_',$table);
			 $partes[0] = ucfirst($partes[0]);
			 $partes[1] = ucfirst($partes[1]);
			 $arquivo = $partes[0].$partes[1];
		 }else{
			$arquivo = ucfirst($table);
		 }
		if(!file_exists("{$this->pasta}/models/{$arquivo}.php"))
		{
			
			$sql = $this->pdo->query("SELECT * FROM {$table}");
			$total_column = $sql->columnCount();
			if($total_column>0){
				$fields = array();
				for ($counter = 0; $counter < $total_column; $counter ++) {
					$meta = $sql->getColumnMeta($counter);
					$nome = $meta['name'];
					$tipo = $meta['native_type'];
					array_push($fields, array("nome"=>$nome,"tipo"=>$tipo));		
				}															
			}
			$conteudo = "";
			$conteudo .= "<?php\n";
			$conteudo .= "/**\n";
			$conteudo .= "* Classe {$arquivo}\n";
			$conteudo .= "*/\n";
			$conteudo .= "namespace apps\\{$this->pasta}\models;\n\n";
			$conteudo .= "class {$arquivo}{\n";
			$conteudo .= "\t/**\n";
			$conteudo .= "\t*Lista de atributos\n";
			$conteudo .= "\t*/\n";
			$conteudo .= "\n";
			$conteudo .= "\tprivate \$dbname = '{$this->banco}';\n";
			foreach($fields as $atributo){
			$conteudo .= "\tprivate \${$atributo['nome']};\n";
			}
			$conteudo .= "\t/**\n";
			$conteudo .= "\t *Metodos\n";
			$conteudo .= "\t */\n";
			$conteudo .= "\tpublic function __construct(){\n\n";
			$conteudo .= "\t}\n";
			foreach($fields as $atributo){
			$conteudo .= "\t/**\n";
			$conteudo .= "\t * @var {$atributo['tipo']}\n";
			$conteudo .= "\t *\n";
			$conteudo .= "\t * @Column(nome=\"{$atributo['nome']}\", type=\"{$atributo['tipo']}\"\n";
			$conteudo .= "\t */\n";	 	
			$conteudo .= "\t public function set_{$atributo['nome']}(\$valor){";
			$conteudo .= "\t\n";
			$conteudo  .= "\t\t \$this->{$atributo['nome']}=\$valor;\n";
			$conteudo .= "\t}\n";	 
			}			 		
			foreach($fields as $atributo){
			$conteudo .="\t/**\n";
			$conteudo .="\t * Retorna o valor do atributo {$atributo['nome']}\n";
			$conteudo .="\t */\n";	 	
			$conteudo .= "\t public function get_{$atributo['nome']}(){\n";
			$conteudo  .= "\t\t return \$this->{$atributo['nome']};\n";
			$conteudo .= "\t\n";
			$conteudo .= "\t}\n";
			}
			#Cria o metodo inserir
			$conteudo .="\t/**\n";
			$conteudo .="\t * Faz a insercao dos dados na tabela\n";
			$conteudo .="\t */\n";
			$conteudo .="\tpublic function inserir(){\n\t";
			foreach($fields as $atributo){
			$conteudo .="\$fields['{$atributo['nome']}'] = \$this->get_{$atributo['nome']}();\n\t";
			}
			$conteudo.="\$insert = new Insert(\$this->banco,'{$table}',\$fields);\n";
			$conteudo .= "\t\tif(\$insert->run()==true){\n\t";
			$conteudo .= "\t\treturn true; \n";
			$conteudo .= "\t\t}else{\n\t";
			$conteudo .= "\t\treturn false; \n\t\t";
			$conteudo .= "}\n";
			$conteudo .="\t}\n\t";
			$conteudo .="/**\n\t";
			$conteudo .="* Faz o update dos dados na tabela\n\t";
			$conteudo .="*/\n\t";
			
			
			$conteudo .="public function atualizar(){\n\t";
			foreach($fields as $atributo){
			$conteudo .="\$fields['{$atributo['nome']}'] = \$this->get_{$atributo['nome']}();\n\t\t";
			}
			
			$conteudo .="\$update = new Update('{$table}',\$fields,\$this->get_id());\n\t\t";
			$conteudo .="if(\$update->run()==true){\n\t\t\t";
			$conteudo .= "return true; \n";
			$conteudo .= "\t\t}else{\n\t";
			$conteudo .= "\t\treturn false; \n\t\t";
			$conteudo .="}\n\t";
			$conteudo .="}\n\t";
			#Criando a funcao de excluir
			$conteudo .="/**\n\t";
			$conteudo .="* Faz a remocao de um registro da tabela\n\t";
			$conteudo .="*/\n\t";
			$conteudo .="public function excluir(){\n\t\t";
			$conteudo .="\$delete = new Delete(\$this->banco,'{$table}');\n\t\t";
			$conteudo .="\$delete->where(\"id=\".\$this->get_id());\n\t\t";
			$conteudo .="if(\$delete->run()==true){\n\t\t\t";
			$conteudo .= "return true; \n";
			$conteudo .= "\t\t}else{\n\t";
			$conteudo .= "\t\treturn false; \n\t\t";
			$conteudo .= "}\n\t";
			$conteudo .="}\n";
			#Fim da funcao excluir
			#Criando a funcao de listar
			$conteudo .="\t/**\n";
			$conteudo .="\t * Exibe os registros de uma tabela\n";
			$conteudo .="\t */\n";
			$conteudo .="\tpublic function listar(){\n\t";
			$conteudo .="//Sua implementacao\n\t";
			$conteudo .="}\n";
			#Fim da funcao excluir
			$conteudo .="\n}";
			
			if(!file_exists(basedir()."/apps/{$this->pasta}/models/{$arquivo}.php"))	
			{
				$file = fopen(basedir()."/apps/{$this->pasta}/models/{$arquivo}.php","w") or die('Nao consigo abrir');
				fwrite($file, $conteudo);
				fclose($file);
				chmod(basedir()."/apps/{$this->pasta}/models/{$arquivo}.php",0777);
				//
			echo '<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Concluído!</h4>
			Classe de modelo '.ucfirst($table).'.php gerado com sucesso!
		   </div>';
			//
			}			
		}	
	}


	public function is_strange($campo,$table)
	{
		$this->connect($this->banco);
		$schema = $this->pdo->query("SELECT * FROM  information_schema.KEY_COLUMN_USAGE WHERE table_schema =  '{$this->banco}' 
		AND table_name =  '{$table}' and column_name = '{$campo}'") or die("Erro");
		$chave = $schema->fetch(\PDO::FETCH_OBJ);
		if($chave==NULL)
		{
			return false;
		}else{
			return true;
		}

	}
	public function geraview($table){
	$tbreferenciada = '';
	$tbnome = '';
	$colname = '';
	$conteudo ='';
	$this->connect($this->banco);
	$sql = $this->pdo->query("Show columns FROM $table");
		
	$schema = $this->pdo->query("SELECT * FROM  information_schema.KEY_COLUMN_USAGE WHERE table_schema =  '{$this->banco}' 
	AND table_name =  '{$table}'") or die("Erro");
			   while($chave = $schema->fetch(\PDO::FETCH_OBJ)){
				   //$this->pre($chave);
				   if($chave->REFERENCED_COLUMN_NAME!=NULL){
					   $tbreferenciada = $chave->REFERENCED_TABLE_SCHEMA;
					   $tbnome =  $chave->REFERENCED_TABLE_NAME;
					   $colname = $chave->REFERENCED_COLUMN_NAME;
				   }	
	}				
			   
						   
	$fields = array();
	$conteudo = "";
	$conteudo .= '<form class="form-horizontal" action="/painel/'.$table.'/inserir" method="post">'."\n\t";
		while($fieldsbd = $sql->fetch(\PDO::FETCH_OBJ)){	
			if($fieldsbd->Field!='id')
			{
				$split = explode('(',$fieldsbd->Type);
		   $tipo = $split[0];
		   $valores = str_replace(')','',$tipo);
		   $conteudo .= "<div class=\"form-group\">\n\t\t";
		   $conteudo .= '<label class="col-sm-2 control-label" for="'.$fieldsbd->Field.'">'.ucfirst($fieldsbd->Field).'</label>'."\n\t\t";	
		   $conteudo .= '<div class="col-sm-4">'."\n\t\t\t";
			
				switch($tipo){
					case 'varchar':				
						$conteudo .= '<input class="form-control" type="text" name="'.$fieldsbd->Field.'" id="'.$fieldsbd->Field.'" />'."\n\t\t";
					break;
					
					case 'int':
						//é chave estrangeira?
						if($this->is_strange($fieldsbd->Field,$table)==true)
						{
							if($colname!=NULL || $colname!=''){
								$conteudo .= "<?php \n";
								$conteudo .= "$$fieldsbd->Field = new TDataSelect('$fieldsbd->Field','$this->banco','$tbnome','');\n";
								$conteudo .= "$$fieldsbd->Field->value('id');\n";
								$conteudo .= "$$fieldsbd->Field->option('');\n";
								$conteudo .= "$$fieldsbd->Field->exibir();\n\t\t";
								$conteudo .= "?>";	
							}
						}else{
							$conteudo .="<input type=\"radio\" name=\"{$fieldsbd->Field}\" id=\"{$fieldsbd->Field}\" value=\"1\"/> Sim\n\t\t\t";
							$conteudo .="<input type=\"radio\" name=\"{$fieldsbd->Field}\" id=\"{$fieldsbd->Field}\" value=\"0\"/> N&atilde;o\n\t\t";
						}						 																		
					break;

					case 'enum':
					$conteudo .="<input type=\"radio\" name=\"{$fieldsbd->Field}\" id=\"{$fieldsbd->Field}\" value=\"s\"/> Sim\n\t\t\t";
					$conteudo .="<input type=\"radio\" name=\"{$fieldsbd->Field}\" id=\"{$fieldsbd->Field}\" value=\"n\"/> N&atilde;o\n\t\t";
					break;
					
					case 'text':
					case 'longtext':
					case 'mediumtext':

					$conteudo .='<textarea class="form-control" name="'.$fieldsbd->Field.'" id="'.$fieldsbd->Field.'" /></textarea>'."\n\t";
					break;		
					
					case 'date':
						$conteudo .='<input type="date" class="form-control" name="'.$fieldsbd->Field.'" id="'.$fieldsbd->Field.'" />'."\n\t";	
					break;	
				}								
			$conteudo .= "</div>\n";		
			$conteudo .= "</div>\n\n";	
			}		   	
		   }				
		   $conteudo .='<div class="form-group">'."\n\t";
		   $conteudo .='<label  class="col-sm-2 control-label"></label>'."\n\t\t";
		   $conteudo .='<div class="col-sm-4">'."\n\t\t\t";
		   $conteudo .='<div class="button">'."\n\t\t\t\t";
		   $conteudo .='<button type="submit" class="btn btn-primary">Salvar</button>'."\n\t\t\t";
		   $conteudo .='</div>'."\n\t\t";
		   $conteudo .='</div>'."\n";
		   $conteudo .='</div>'."\n";
		   $conteudo .='</form>'."\n";
				   
			
		if(!is_dir(basedir()."/apps/{$this->pasta}/views/{$table}")){
			mkdir(basedir()."/apps/{$this->pasta}/views/{$table}");
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}",0777);			
		}
		
		if(!file_exists(basedir()."/apps/{$this->pasta}/views/{$table}/cadastro-{$table}.php"))	
		{
			$file = fopen(basedir()."/apps/{$this->pasta}/views/{$table}/cadastro-{$table}.php","w") or die('Nao consigo abrir');
			fwrite($file, $conteudo);
			fclose($file);
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/cadastro-{$table}.php",0777);
			//
			echo '<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Concluído!</h4>
			Arquivo cadastro-'.$table.'.php gerado com sucesso!
		   </div>';
			//
		}else{
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php",0777);
		}
		//O arquivo existe?	
		if(!file_exists(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php"))	
		{
			$file = fopen(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php","w") or die('Nao consigo abrir');
			fwrite($file, $conteudo);
			fclose($file);
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php",0777);
			//
			echo '<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Concluído!</h4>
			Arquivo editar-'.$table.'.php gerado com sucesso!
		   </div>';
			//
		}else{
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php",0777);
		}
		//O arquivo existe?	
		if(!file_exists(basedir()."/apps/{$this->pasta}/views/{$table}/lista-{$table}.php"))	
		{
			$conteudolista = '';
			$conteudolista .= '{lista}';
			$file = fopen(basedir()."/apps/{$this->pasta}/views/{$table}/lista-{$table}.php","w") or die('Nao consigo abrir');
			fwrite($file, $conteudolista);
			fclose($file);
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/lista-{$table}.php",0777);
			//
			echo '<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Concluído!</h4>
			Arquivo lista-'.$table.'.php gerado com sucesso!
		   </div>';
			//
			
		}else{
			chmod(basedir()."/apps/{$this->pasta}/views/{$table}/editar-{$table}.php",0777);
		}	
		
	}

		public function geracontroller($table){						
			$conteudo = ""; 
			$conteudo .= "<?php\n";
			$conteudo .= "/**\n";
			$conteudo .= " * Classe \n";
			$conteudo .= " */ \n";
			$conteudo .= "namespace apps\\{$this->pasta}\controllers;\n\n";
			$conteudo .= "class ".ucwords(str_replace('_','',$table))."Controller extends \core\Controller{\n\t";
			$conteudo .= "public function index(){\n\t\t";
			$conteudo .= "\$this->listar();\n\t";
			$conteudo .= "}\n\t";
			$fields = array();
			$this->pdo = $this->connect($this->banco);
			$sql = $this->pdo->query("Select * from {$table}");
			$total_column = $sql->columnCount();
					if($total_column>0){
					$fields = array();
					for ($counter = 0; $counter < $total_column; $counter ++) {
					$meta = $sql->getColumnMeta($counter);
					$nome = $meta['name'];
					$tipo = $meta['native_type'];
					array_push($fields, array("nome"=>$nome,"tipo"=>$tipo));		
					}														
			}
			$conteudo .= "/**\n\t";
			$conteudo .= "*\n\t";
			$conteudo .= "*/\n\t";		
			$conteudo .= "public function cadastro(){\n\t\t";
				$conteudo .="openbox('Cadastrar {$table}','Adiciona uma nova {$table} ao site');\n\t\t";
				$conteudo .="\$this->view('{$table}/cadastro-{$table}');\n\t\t";
				$conteudo .="closebox();\n\t";
			$conteudo .= "}\n\n\t";
			$objeto = strtolower(str_replace('_','',$table));
			$conteudo .= "public function inserir(){\n\t\t";
			$conteudo .= "\$$objeto = \$this->model('".ucwords($table)."');\n\t\t";
			foreach($fields as $atributo){
				$conteudo .= "\$"."{$objeto}->set_{$atributo['nome']}(filterpost('{$atributo['nome']}'));\n\t\t";	
			}		
			
			$conteudo .="\$mensagem['titulo'] = '';\n\t\t";		
			$conteudo .= "if(\$"."{$objeto}->inserir()==true)\n\t\t";
			$conteudo .= "{\n\t\t\t";
			$conteudo .="\$mensagem['mensagem'] = '[...] cadastrado com sucesso!';\n\t\t\t";		
			$conteudo .="\$this->view('modal',\$mensagem);\n\t\t\t";	
			$conteudo .="\$this->listar();\n\t\t";	
			$conteudo .= "}else{\n\t\t\t";		
			$conteudo .="\$mensagem['mensagem'] = 'Erro ao tentar inserir o registro!';\n\t\t\t";		
			$conteudo .="\$this->view('modal-erro',\$mensagem);\n\t\t";		
			$conteudo .= "}\n\n\t";			
			$conteudo .= "}\n\t";

			$conteudo .= "/**\n\t";
			$conteudo .= "*\n\t";
			$conteudo .= "*/\n\t";
			$conteudo .= "public function editar(\$id){\n\t";
			$conteudo .= "//Sua implementacao\n\t\t";
			$conteudo .="openbox('Editar {$table}','Edita uma {$table}');\n\t\t";
			$conteudo .= "\$$table = \$this->model('".ucwords($table)."');\n\t\t";
			$conteudo .= "\$dados = \${$table}->get(\$id);\n\t\t";
			$conteudo .="\$this->view('{$table}/editar-{$table}',\$dados);\n\t\t";
			$conteudo .="closebox();\n\t";
			$conteudo .= "}\n\t";
			$conteudo .= "/**\n\t";
			$conteudo .= "*\n\t";
			$conteudo .= "*/\n\t";

			$conteudo .= "public function listar(){\n\t\t";
				$conteudo .="openbox('Listar {$table}','Lista todas as {$table} do site');\n\t\t";
				$conteudo .="\$this->view('{$table}/lista-{$table}');\n\t\t";
				$conteudo .="closebox();\n\t";
			$conteudo .= "}\n\t";
			$conteudo .= "/**\n\t";
			$conteudo .= "*\n\t";
			$conteudo .= "*/\n\t";

			$conteudo .= "public function atualizar(){\n\t\t";
				$objeto = strtolower($table);
				$conteudo .= "\$$objeto = \$this->model('".ucwords($table)."');\n\t\t";
				foreach($fields as $atributo){
					$conteudo .= "\$"."{$objeto}->set_{$atributo['nome']}(filterpost('{$atributo['nome']}'));\n\t\t";	
				}
				$conteudo .="\$mensagem['titulo'] = '';\n\t\t";		
				$conteudo .= "if(\$"."{$objeto}->atualizar()==true)\n\t\t";
				$conteudo .= "{\n\t\t\t";
				$conteudo .="\$mensagem['mensagem'] = '[...] alterado com sucesso!';\n\t\t\t";		
				$conteudo .="\$this->view('modal',\$mensagem);\n\t\t\t";	
				$conteudo .="\$this->listar();\n\t\t";		
				$conteudo .= "}else{\n\t\t\t";		
				$conteudo .="\$mensagem['mensagem'] = 'Erro ao tentar alterar o registro!';\n\t\t\t";		
				$conteudo .="\$this->view('modal-erro',\$mensagem);\n\t\t";		
				$conteudo .= "}\n\n\t";		
				$conteudo .= "}\n\t";
				
				$conteudo .= "/**\n\t";
				$conteudo .= "*\n\t";
				$conteudo .= "*/\n\t";
			
				$conteudo .= "public function excluir(){\n\t\t";
				$conteudo .= "\$model=\$this->model('');\n\t";
				$conteudo .= "}\n";
				$conteudo .= "}";
				$arquivo = str_replace('_','',$table);
				if(!file_exists(basedir()."/apps/{$this->pasta}/controllers/{$arquivo}.php"))	
				{
					$file = fopen(basedir()."/apps/{$this->pasta}/controllers/{$arquivo}.php","w") or die('Nao consigo abrir');
					fwrite($file, $conteudo);
					fclose($file);
					chmod(basedir()."/apps/{$this->pasta}/controllers/{$arquivo}.php",0777);
					//
					echo '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Concluído!</h4>
					Classe controller '.$table.'.php gerada com sucesso!
					</div>';
					//
				}else{
					chmod(basedir()."/apps/{$this->pasta}/controllers/{$arquivo}.php",0777);
				}			
		}
	
}
