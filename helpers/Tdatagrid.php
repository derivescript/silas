<?php

namespace helpers;

use core\Router;
use core\URI;
use database\Select;

use function core\base_url;
use function core\pre;

class TDatagrid extends Tabela{
	/*atributos*/	
	private $controller;
	private $entidade;
	private $dbname;
	private $fields;
	private $ordem;
	private $pagina;
	private $primeiro_registro;
	private $max_paginas;
	private $linhas=20;
	private $filtro;
	private $html ='';
	private $acoes = array();
	private $joins = array();
	private $titulos = array();
	private $data;
	private $actions = array();
	private $assets_path = '/assets/admin/imagens';
	
	public function __construct($fields,$dbname,$table)
	{
		$router = new Router();
		$this->controller = $router->get_controller();
		$this->campos=$fields;
		$this->banco=$dbname;
		$this->entidade=$table;
		$this->set_class('table table-bordered table-hover dataTable dtr-inline');
	}

	public function set_class($class)
	{
		$this->class = parent::set_classe($class);
		
	}
	/**
	 * Definir quantidade de linhas
	 */

	public function linhas($qtde)
	{
		$this->linhas = $qtde;
	}

	public function actions($actions){		
		
	}

	public function action($acao,$icone,$target="_self"){		
		array_push($this->acoes, array("metodo"=>$acao,"imagem"=>$icone,"target"=>$target));
	}

	/**
	 * Retorna a acao de uma coluna
	 */ 
	public function get_acao($acao,$icone,$id,$target){
		$uri = new URI();
		
		$img = new html('img');
		$img->src=$this->assets_path."/{$icone}.png";
		$img->alt="{$acao}";
		$img->title="{$acao}";
		$img->value='';
		$link= new html('a');
		$link->class="{$acao} lnk-{$acao} ajax-link";
		$link->id='';
		$link->href="/painel/{$this->controller}/{$acao}/{$id}";
		$link->target=$target;
		$link->data=$id;
		$link->add($img);
		return $link;
	}
	
	public function open()
	{
		$this->html .='<table class="'.$this->class.'">';
	}
	/**
	 * Permite pegar dados de uma consulta e montar uma grid com eles
	 */
	public function prepare($data)
	{
		$this->data = $data;
	}

	public function close()
	{
		$this->html .= parent::fechar();
	}

	/**
	 * $datagrid->replace('id_secao','secoes','nome','$registro->id_secao')
	 */
	public function replace($campoatual,$table,$novocampo){
		$this->joins[$campoatual]['tabela'] = $table;
		$this->joins[$campoatual]['novocampo'] = $novocampo;
	}

	public function filtro($where){
		$this->filtro=$where;
		return $this->filtro;
	}

	public function ordem($campo){
		$this->ordem=$campo;
		return $this->ordem;	
	}

	public function view()
	{
		$router = new Router();
		$this->pagina = $router->get_pagina();
		$select = new Select($this->campos,$this->banco,$this->entidade);
		//Setando o primeiro registro
		if(!isset($this->pagina) || $this->pagina ==''){
			$this->primeiro_registro = 0;	
		}else{
			$this->primeiro_registro = ($this->pagina*$this->linhas) - $this->linhas;
		}

		if($this->filtro){
			$select->where($this->filtro);
		}

		if($this->ordem!=''){
			$select->ordem($this->ordem);	
		}
		
		$select->limite($this->primeiro_registro,$this->linhas);
		
		$this->data = $select->run();
		if(sizeof($this->data) == 0)
		{
			echo '<div class="row">Nenhum registro lançado no momento</div>';
			
		}else{
			$this->open();
			parent::abrelinha();
			//Checkbox para selecionar todos
			$selecionartudo = new TCheckbox('selectall');		
			parent::addcoluna('selecionartudo','',$selecionartudo->exibir());
			if(is_array($this->data) and sizeof($this->data)){

			}else{

			}

			foreach($this->acoes as $indice => $acao){
				parent::addhead(ucfirst($acao['metodo']));								
			}
			
			//Gerar cabecalho de turmas
			foreach($this->data[0] as $key=>$value)
			{
				parent::addhead(ucfirst(str_replace('id_','',$key)));
			}
			
			parent::fechalinha();
			//Gerar as linhas
			foreach($this->data as $key => $values)
			{
				parent::abrelinha();
				/**
				 * Checkbox de cada linha
				 */
				$checkbox = new TCheckbox('id');
				$checkbox->set_value($this->data[$key]->id);
				$cb = $checkbox->exibir();
				parent::addcoluna('','',$checkbox->exibir());
				/**
				 * Adiciona as funcoes dos botoes
				 */			
				foreach($this->acoes as $indice => $acao){
					$action = $this->get_acao($acao['metodo'],$acao['imagem'],$this->data[$key]->id,$acao['target']); 
					parent::addcoluna('','',$action->exibir());								
				}
				
				foreach($values as $coluna=>$valor)
				{
					if($coluna=='data')
					{
						$valor = Data::data_br($valor);
					}
					if(array_key_exists($coluna,$this->joins))
					{
						$select =new Select($this->joins[$coluna]['novocampo'],$this->banco,$this->joins[$coluna]['tabela']);
						$select->where("id='{$valor}'");
						$novosvalores = $select->run();
						if(sizeof($novosvalores)>0)
						{
							$fk = $novosvalores[0];
							$novocampo = $this->joins[$coluna]['novocampo'];
							$valor =  str_replace($valor,$fk->$novocampo,$valor);
						}else{
							$valor =  str_replace($valor,'-',$valor);
						}
						parent::addcoluna('editable-field',$coluna,$valor);
					}else{
						parent::addcoluna('editable-field',$coluna,$valor);
					}
				}
				parent::fechalinha();
			}
			$this->close();
			$this->paginate();
			return $this->html;
		}		
	}

	public function paginate()
	{
		$router = new Router();
		$pagina_atual = $router->get_pagina();
		$select = new Select($this->campos,$this->banco,$this->entidade);
		$this->data = $select->run();
		//total de paginas
		$this->max_paginas = ceil(sizeof($this->data)/$this->linhas);
		//Pagina anterior = atual - 1
		if($pagina_atual==1)
		{
			$paginaanterior = 1;
		}else{
			$paginaanterior = $pagina_atual - 1;
		}
		
		//Primeiro botao
		$primeira = new TButton('Primeira','button');
		$primeira->set_class('btn btn-default');
		$primeira->set_id("primeira");
		$primeira->set_value(1);
		
		//Gerar html da barra de paginas
		$this->html.= '<div>';
		$this->html.= $primeira->exibir();
		//Anterior
		$anterior = new TButton('Anterior','button');
		$anterior->set_class('btn btn-default');
		$anterior->set_id("anterior");
		$anterior->set_value($paginaanterior);
		$this->html .= $anterior->exibir();
		for($i=1;$i<=$this->max_paginas;$i++)
		{
			if($i==$pagina_atual)
			{
				$this->html.='<button type="button" class="btn btpagina btatual btn-primary">'.$i.'</button>';
			}else{
				$this->html.='<button type="button" class="btn btpagina btn-default">'.$i.'</button>';
			}			
		}
		//Proxima
		//Pagina anterior = atual - 1
		if($pagina_atual==$this->max_paginas)
		{
			$proximapagina = $pagina_atual;
		}else{
			$proximapagina = $pagina_atual + 1;
		}
		$proxima = new TButton('Próxima','button');
		$proxima->set_class('btn btn-default');
		$proxima->set_id("proxima");
		$proxima->set_value($proximapagina);
		$this->html .= $proxima->exibir();
		//Ultimo botao
		$ultima = new TButton('Última','button');
		$ultima->set_class('btn btn-default');
		$ultima->set_id("ultima");
		$ultimapagina = $this->max_paginas;
		$ultima->set_value($ultimapagina);
		$this->html.= $ultima->exibir();
		$this->html .= '</div>';
		$this->html .= '<div id="resposta"></div>';

	}
}