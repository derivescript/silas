<?php
/**
 * Classe para envio de arquivos
 */
namespace core;

use function \core\basedir;

class Upload{
	private $pasta;
	private $nomefinal;
	private $coderro;
	private $permitidos = array('jpg','jpeg','png');
	
	/**
	 * Aponta para qual diretorio o arquivo vai ser enviado
	 */
	public function setdir($pasta){
		$this->pasta=$pasta;
		return $this->pasta;
	}
	
	/**
	 * Seta o nome final do arquivo a ser gravado na pasta
	 */
	public function setnomearquivo($nome){
			$this->nomefinal = $nome;
	}
	
	/**
	 * Verifica se a extensão do arquivo corresponde aos tipos permitidos
	 */
	public function valida_ext($arquivo){
		$arr = explode('.', $arquivo['name']); 	
		$extensao = end($arr);
		if (array_search($extensao, $this->permitidos) === false) {
			return false;
		}else{
			return false;
		}		
	}
	
	/**
	 * Faz o upload de um documento
	 */	
	public function enviar($arquivo){
		$nomefinal = $this->nomefinal;
		if(!is_dir($this->pasta))
		{
			mkdir($this->pasta);
			chmod($this->pasta,'0777');
		}
		if(move_uploaded_file($arquivo['tmp_name'], $this->pasta."/{$nomefinal}")){
			chmod($this->pasta."/{$nomefinal}",'0777');
			return true;			
		}else{
			$arquivo['error'];
			$this->coderro=$arquivo['error'];
			return false;
		}							
	}

	/**
	 * Funcao para exibir o erro do upload
	 */
	public function get_erro()
	{

		$msg ='';
		switch($this->coderro)
		{
			case UPLOAD_ERR_INI_SIZE:
				$msg = "1: Arquivo maior que o permitido";
			break;
			case UPLOAD_ERR_PARTIAL:
				$msg = "2: Envio foi feito apenas parcialmente. Enviar novamente";
				break;
			case UPLOAD_ERR_NO_FILE:
				$msg = "4: Envio foi feito apenas parcialmente. Enviar novamente";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$msg = "6: A pasta temporaria do servidor não está disponível. Contate o administrador";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$msg = "7: Nao consigo salvar a foto na pasta porque ela esta somente como leitura";
				break;			
		}
		return $msg;
	}
	/**
	 * Faz o upload de um documento
	 */	
	public function deletar($arquivo){
		if(file_exists("../editais/documentos/{$this->pasta}/{$arquivo}")){
  		 if(unlink("../editais/documentos/{$this->pasta}/{$arquivo}")){
			return true;			
		 }else{
			return false;
		 }
	  }else{
	  		return true;
	  }						
	}
	
	
}
