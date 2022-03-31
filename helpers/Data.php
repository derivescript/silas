<?php

namespace helpers;

use function core\converte_data_us;
use function core\pre;

/**
 * Classe para manipular datas no sistema
 */
class DATA{
	
	protected $timezone;

	public function maketimezone()
	{
		require(appdir.'/config/parameters.php');
		$this->timezone = $param['timezone'];
	}

	public static function data_br($data){
 		$databr = implode('/',array_reverse(explode('-',$data)));
		return $databr;
	 }
	 
	public static function get_dia_n($data,$separador='-'){
		$dia = explode("{$separador}", $data);
		return $dia[2];
	}

	public static function get_mes_n($data,$separador='-'){
		$data = explode("{$separador}", $data);
	    return $data[1];
   }
	
	public static function get_mes($data){
		$strdata = explode('-',$data);
		switch($strdata[1]){
			case '01':
				$mes = 'Janeiro';
			break;
			
			case '02':
				$mes = 'Fevereiro';
			break;
			
			case '03':
				$mes = 'Mar&ccedil;o';
			break;
			
			case '04':
				$mes = 'Abril';
			break;
			
			case '05':
				$mes = 'Maio';
			break;
			
			case '06':
				$mes = 'Junho';
			break;
			
			case '07':
				$mes = 'Julho';
			break;
			
			case '08':
				$mes = 'Agosto';
			break;
			
			case '09':
				$mes = 'Setembro';
			break;
			
			case '10':
				$mes = 'Outubro';
			break;
			
			case '11':
				$mes = 'Novembro';
			break;
			
			case '12':
				$mes = 'Dezembro';
			break;				
		}
		
		return $mes;
	}

	public static function ano()
	{
		return date('Y');
	}

	public function number_of_days($month)
	{
		echo $mont;
	}
	/**
	 * Returns day of a week from a date
	 */
	public function weekday($data)
	{
		
	}

	public static function datanow()
	{
		return date('Y-m-d');
	}

	public static function horanow(){
		return date('h:m:s');
	}
	
}
