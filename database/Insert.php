<?php

namespace database;

use Exception;

use function core\pre;

/**
 * Grava os dados no banco de dados
 */
class Insert{
	private $dbname;
	private $table;
	private $fields = array();
	private $valores = array();
	private $consulta;
	private $pdo;
	private $mensagem;

	public function __construct($dbname,$table,$fields)
	{
		$this->banco = $dbname;
		$this->consulta .="INSERT into {$dbname}.{$table}";
		foreach ($fields as $campo => $valor) {
			if($valor==''){
				$valor = str_replace("'",'',$valor);
			}
			array_push($this->campos,$campo);
			array_push($this->valores,$valor);
		}
		$fields = implode(',',$this->campos);
		$valores = implode("','",$this->valores);
		$this->consulta .=" ({$fields}) values 
		('{$valores}')"; 
		$conexao = new Conexao;
		$this->pdo = $conexao->conectar($this->banco);
	}

	public function run(){
		$ex = new Exception('OI, eu usou uma excecao');
		echo $ex->getMessage();
		exit;
		try{					
			$this->pdo->query($this->consulta);
			return true;
		}catch(\Exception $erro){
			pre($erro);
			$numeroerro = $erro->getCode();
			echo $erro->getMessage();
			$erro = new \database\ErroBD($numeroerro);
			$this->mensagem = $erro->getMensagem();
			return false;
		}	
	}
    
    public function get_consulta(){
        return $this->consulta;
    }

	public function ultimo_id(){
		$id = $this->pdo->lastInsertId();
		return $id;
	}
	
	public function retorno(){
		return $this->mensagem;
	}
}