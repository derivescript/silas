<?php

class Calendario{
    private $mes;
    private $dia;
    private $ano;
    private $semana;
    private $uri;

    public function __construct()
    {
        $this->uri = new URI();
    }

    public function get_mes(){
        if($this->mes = $this->uri->getsegmento(4)!=''){
            $this->mes = $this->uri->getsegmento(4);
        }else{
            $this->mes = date('m');
        }
        return $this->mes;
    }

    public function hoje(){
        $dia = date('d');
        return $dia;
    }

    public function dia(){
        if($this->uri->getsegmento(5)!=''){
            $this->dia = $this->uri->getsegmento(5);
        }else{
            $this->dia = date('d');
        }        
        return $this->dia;
    }

    public function ano(){
        $this->ano = date('Y');
        return $this->ano;
    }
    /**
     * 
     * Deve retornar um total de dias, desconsiderando sabados e domingos de acordo com os parametros
     *
     * @return void
     */
    public function semana($diad,$fimdesemana=true){
        $dia = $this->dia();
        $mes = $this->get_mes();
        $ano = $this->ano();
        $primeiro = $this->diadasemana();
               
        switch($primeiro) {
            case"0": $semana = array('+0 days','+1 days','+2 days','+3 days','+4 days','+5 days','+6 days',	'+7 days','+8 days','+9 days','+10 days','+11 days','+12 days','+13 days');  break;
            case"1": $semana = array('-1 days','+0 days','+1 days','+2 days','+3 days','+4 days','+5 days',	'+6 days','+7 days','+8 days','+9 days','+10 days','+11 days','+12 days');  break;
            case"2": $semana = array('-2 days','-1 days','+0 days','+1 days','+2 days','+3 days','+4 days',	'+5 days','+6 days','+7 days','+8 days','+9 days','+10 days','+11 days');  break;
            case"3": $semana = array('-3 days','-2 days','-1 days','+0 days','+1 days','+2 days','+3 days',	'+4 days','+5 days','+6 days','+7 days','+8 days','+9 days','+10 days');  break;
            case"4": $semana = array('-4 days','-3 days','-2 days','-1 days','+0 days','+1 days','+2 days',	'+3 days','+4 days','+5 days','+6 days','+7 days','+8 days','+9 days');  break;
            case"5": $semana = array('-5 days','-4 days','-3 days','-2 days','-1 days','+0 days','+1 days',	'+2 days','+3 days','+4 days','+5 days','+6 days','+7 days','+8 days');  break;
            case"6": $semana = array('-6 days','-5 days','-4 days','-3 days','-2 days','-1 days','+0 days',	'+1 days','+2 days','+3 days','+4 days','+5 days','+6 days','+7 days');  break;
        }
        $data = "{$dia}-{$mes}-{$ano}";
        
        $resultados[0] = date('d/m/Y',strtotime($semana[0],strtotime($data)));  
        $resultados[1] = date('d/m/Y',strtotime($semana[1],strtotime($data))); 
        $resultados[2] = date('d/m/Y',strtotime($semana[2],strtotime($data))) ;  
        $resultados[3] = date('d/m/Y',strtotime($semana[3],strtotime($data))) ;  
        $resultados[4] = date('d/m/Y',strtotime($semana[4],strtotime($data))) ;  
        $resultados[5] = date('d/m/Y',strtotime($semana[5],strtotime($data))) ;  
        $resultados[6] = date('d/m/Y',strtotime($semana[6],strtotime($data))) ;
        
        $resultados[7] = date('d/m/Y',strtotime($semana[7],strtotime($data))) ;
        $resultados[8] = date('d/m/Y',strtotime($semana[8],strtotime($data))) ;
        $resultados[9] = date('d/m/Y',strtotime($semana[9],strtotime($data))) ;
        $resultados[10] = date('d/m/Y',strtotime($semana[10],strtotime($data))) ;
        $resultados[11] = date('d/m/Y',strtotime($semana[11],strtotime($data))) ;
        $resultados[12] = date('d/m/Y',strtotime($semana[12],strtotime($data))) ;
        $resultados[13] = date('d/m/Y',strtotime($semana[13],strtotime($data))) ;
        return $resultados[$diad];
        
    }

    /**
     * 
     */
    public function get_periodo($aula){
        
    }
    public function diadasemana(){
        $dia = $this->dia();
        $mes = $this->get_mes();
        $ano = $this->ano();
              
        $diadasemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes,$dia,$ano));
        return $diadasemana;
    }

    public static function databr($data){
        $databr = implode('/',array_reverse(explode('-',$data)));
        return $databr;
    }

    public static function data_us($data){
        $dataus = implode('-',array_reverse(explode('/',$data)));
        return $dataus;
    }
    
   public static function get_dia_n($data){
        $dia = explode('-', $data);
       return $dia[2];
   }
   
   public static function get_nome_mes($data){
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
}