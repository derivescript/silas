<?php

namespace database;

use database\ErroBD;
/**
 * 
 */

class Conexao{
	/**
	 * Realiza a conexao com os bancos de dados
	 *
	 * @return void
	 */
	public static $conn;
    private static $last;
	
	public function conectar($dbname){
		$config = $this->getconfig($dbname);
		if($config){
			switch($config['driver']){
				case 'mysql';
				try{
					$pdo = new \PDO("mysql:host={$config['host']};port={$config['porta']};dbname={$config['name']}", $config['usuario'], $config['senha']);
					$pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
					return $pdo;
				}catch(\Exception $erro){
					return "Nao pude conectar. ".$erro->getMessage();
				}
									
				break;
				case 'postgres';
				
				try{
					$pdo = new \PDO("pgsql:dbname={$config['name']}; user={$config['usuario']}; password={$config['senha']};host={$config['host']};port={$config['porta']};");
					return $pdo;	
				}catch(\Exception $erro){
					exit("Nao pude conectar. ".$erro->getMessage());
				}	
				break;
				
				case 'firebird':
					$pdo = new \PDO("firebird:dbname={$config['name']}", "{$config['usuario']}", "{$config['senha']}");
					return $pdo;	
				break;	
			}
		}else{
			$numeroerro = 1;
			$erro = new \database\ErroBD($numeroerro);
			$pdo = $erro->getMensagem();
			return $pdo;
		}
	}

	public function getconfig($dbname){
		require appdir."/config/database.php";
		$config = $config;
		if(array_key_exists($dbname,$config))
		{
			return $config[$dbname];
		}else{
			return false;
		}
		
	}
}