<?php

namespace core;

ob_start();

class ExceptionHandler{

    public function __construct() {
        
        set_exception_handler(array($this, 'fire'));
    }

    public function fire($exception) {
        //Limpa qualquer dado que ja tenha sido exibido
        ob_end_clean();
        //Instancia o Router para recuperar os dados da 
        $router = new Router();
        $pasta = 'core';
        $file = basedir().'/core/ErrorTemplate.php';
        $content = file_get_contents($file);
        $content = str_replace('{message}',$exception->getMessage(),$content);
        $content = str_replace('{folder}',substr(dirname($exception->getFile(),1),strlen(basedir()),30),$content);
        $content = str_replace('{mainline}',$exception->getLine(),$content);
        $classname = basename($exception->getFile());
        $content = str_replace('{class}',$classname,$content); 
        $f = $exception->getFile();
        $code = fopen($exception->getFile(),'r');
        $mycode = '';
        if($f)
        {            
            $line = 1;
            while (($buffer = fgets($code, 4096)) !== false) {
                if($line == 1)
                {
                   $mycode =  '<span class="line">'.$line.'.</span> '.str_replace("<?php",'<span class="php">&lt;?php</span>',$buffer);
                }else if($line == $exception->getLine()){
                    
                    $mycode .= '<span class="line">'.$line.'.</span> '.'<span class="line-error common-line">'.$buffer.'</span>';
                }else{
                    $mycode .= '<span class="line">'.$line.'.</span> <span class="common-line">'.$buffer.'</span>';
                }                
                $line++;
            }
        }else{
            echo "Nao posso abrir $f";
        }
        $content = str_replace('{code}',$mycode,$content);
        $traces = '';
        for($i=0;$i<sizeof($exception->getTrace());$i++)
        {
            $traces .= '<div class="trace">';
            $traces .= '<p class="arquivo">Arquivo:'.$exception->getTrace()[$i]['file'];
            $traces .= '<p class="linha">Linha: '.$exception->getTrace()[$i]['line'].'';
            $traces .= '<p class="funcao">Função: '.$exception->getTrace()[$i]['function'].'';
            $traces .='</div>';
        }
        // echo $content;
        $content = str_replace('{traces}',$traces,$content);        
        echo $content;
        // Save a copy for the sys-admins ;)
        error_log($exception->getMessage());

    }

    public function traducao($mensagem){
        setlocale (LC_ALL, 'ptb');
        echo $mensagem;
    }

    public function pasta(){

    }

}

// Construct & Initialize our ExceptionHandler
// within a different namespace: new \ErrorHandling\ExceptionHandler();
new ExceptionHandler();