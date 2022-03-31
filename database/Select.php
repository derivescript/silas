<?php

namespace database;

class Select{
	private $driver;
	private $dbname;
	private $tablaname;
	private $query;
	private $registros = array();
	private $fields;
	private $numerofields;
	private $criterio;
	private $limiteinicial;
	private $limitefinal;
	private $pdo;
	/**
	 * Undocumented function
	 *
	 * @param [type] $fields
	 * @param [type] $dbname
	 * @param [type] $tablaname
	 */
	public function __construct($fields,$dbname,$tablaname)
	{	
		$this->dbname = $dbname;
		$this->query .="SELECT {$fields} from {$dbname}.{$tablaname}";
	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $filter
	 * @return void
	 */
	public function where($filter)
	{
		$this->query.=" where {$filter}";
	}
	/**
	 * 
	 */
	public function andwhere($filter)
	{
		$this->query.=" and {$filter} ";
	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $filter
	 * @return void
	 */
	public function orwhere($filter)
	{
		$this->query.=" or {$filter}";
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function between(){

	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $campo
	 * @param [type] $filtro
	 * @return void
	 */
	public function having($campo,$filtro){
		$this->query .= " having {$campo} = {$filtro}";
	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $inicial
	 * @param [type] $final
	 * @return void
	 */
	public function limit($inicial,$final){
		$this->query.=" LIMIT {$inicial},{$final}";				
	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $ordem
	 * @return void
	 */
	public function order($ordem)
	{
		$this->query .= " order by {$ordem}";
	}

	/**
	 * 
	 */
	public function not_in($query)
	{
		$this->query.=" not in({$query})";
	}
	
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_num_fields(){
		return $this->numerofields;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function get_fields(){
		return $this->fields;		
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function getcriterio(){
		return $this->criterio;
	}		
	/**
	 * Retorna o resultadoa string de uma query
	 *
	 * @return void
	 */
	public function show_query(){
		return $this->query.br;
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function run()
	{
		try{
			$conexao = new Conexao;
			$this->pdo = $conexao->conectar($this->dbname);
			$sql = $this->pdo->query($this->query);
			while($linha = $sql->fetch(\PDO::FETCH_OBJ))
			{
				array_push($this->registros,$linha);
			}
			return $this->registros;
		}catch(\Exception $e){
			echo $this->query."<br>".$e->getMessage();
		}
	}
}