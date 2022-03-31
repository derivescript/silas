<?php

namespace database;

/**
 * Grava os dados no banco de dados
 */
class Update{

	private $table;
	private $fields = array();
	private $valores = array();
	private $consulta;
	private $pdo;
	
	public function __construct($dbname,$table,$fields)
	{
		$this->consulta .="Update {$dbname}.{$table} set ";
		foreach ($fields as $campo => $valor) {
			$this->campos[$campo]="{$campo}='{$valor}'";	
		}
		$this->consulta.=implode(',',$this->campos);
		$conexao = new Conexao;
		$this->pdo = $conexao->conectar($dbname);		
	}

	public function where($condicao)
	{
		$this->consulta.=" where {$condicao}";
	}

	public function run(){
		try{
			$this->pdo->query($this->consulta);
			return true;
		}catch(\Exception $erro){
			echo $erro->getMessage();
			return $erro;
			return false;
		}
	}
	
	public function get_consulta(){
		return $this->consulta;
	}
}