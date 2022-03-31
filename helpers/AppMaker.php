<?php

class AppMaker
{
    /**
 * Function create - Usada para criar objetos pela linha de comando
 * @param $type = tipo do objeto (application, controller, view, etc)
 * @param $name = nome do objeto
 */
    public function create($type='',$name){
        if($type==''){
            echo "\e[1;37;41m Comando create incompleto \e[0m";
            echo "\e[1;37;41m Tente usar um destes comandos:
            
                create:application Cria uma nova aplicacao 
                create:controller  Cria um novo controller na pasta indicada 
                create:adminlte    Cria um painel de amdinistracao baseado no AdminLte 2 
                create:view        Cria uma nova view na pasta indicada  
                create:model       Cria um model baseado na entidade indicada  
            \e[0m\n ";
        }else{
            switch ($type) {

                case 'application':
                    try{
                        mkdir(appdir.'/'.$name);
                        mkdir(appdir.'/'.$name.'/controllers');
                        mkdir(appdir.'/'.$name.'/models');
                        mkdir(appdir.'/'.$name.'/views');
                        $handle = fopen(appdir.'/'.$name.'/home.php','w');
                        $content  = "<?php \n";
                        $content .= "class HomeController extends \core\Controller{ \n\t ";
                        $content .= "public function index(){ \n\t\t";
                        $content .= "//Implementacao \n\t";    
                        $content .= "}\n ";             
                        $content .= "}\n\t ";
                        fwrite($handle,$content);
                        fclose($handle);
                        echo "\e[0;32;12m Aplicacao {$name} criada com sucesso!\e[0m\n ";
                    }catch(Exception $e){
                        echo "\e[0;32;12m Erro ao criar aplicação!\e[0m\n ";
                    }
                    
                break;    
                case 'controller':
                    try{
                        $location  = explode('/',$name);
                        if(sizeof($location)>1){
                            if(!is_dir(appdir.'/'.$location[0]))
                            {
                                mkdir(appdir.'/'.$location[0]);
                            }
                            
                            if(!is_dir(appdir.'/'.$location[0].'/controllers'))
                            {
                                mkdir(appdir.'/'.$location[0].'/controllers');
                            }
                            $arquivo = appdir.'/'.$location[0].'/controllers/'.$location[1].'.php';
                            $class = $location[1];
                        }else{
                            $arquivo = appdir.'/controllers/'.$location[0].'.php';
                            $class = $location[0];
                        }                    
                        
                        $class = ucfirst($class);
                        $handle = fopen($arquivo,'w');
                        $content  = "<?php \n";
                        $content .= "class {$class}Controller extends \core\Controller{ \n\t ";
                        $content .= "public function index(){ \n\t\t";
                        $content .= "//Implementacao \n\t";    
                        $content .= "}\n ";             
                        $content .= "}\n\t ";
                        fwrite($handle,$content);
                        fclose($handle);
                        echo "\e[0;32;12m Controller criado com sucesso!\e[0m\n ";
                    }catch(Exception $e){
                        echo "\e[0;32;12m Controller criado com sucesso!\e[0m\n ";
                    }
                    
                break;
                
                case 'view':
                    echo "\e[0;37;44m View criado com sucesso!\e[0m\n ";
                break;    
            }
        }
    }
}