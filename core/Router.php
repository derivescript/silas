<?php

namespace core;

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Classe Router - Todas as solicitacoes do sistema precisam passar por aqui!!!
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
use core\URI;


class Router
{
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|   Atributo para armazenar o objeto URI, que traz a requisicao do navegador
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    private $uri;
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Diretorio da aplicacao. Localizado dentro de apps, pode ter qualquer nome definido pelo usuario. Isso permite criar aplicacoes diferentes com uma unica
| instalacao do S.I.L.A.S
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    private $appfolder;
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Como cada aplicacao pode optar por ter um painel separado dos demais, entao teremos uma subpasta dentro de $appfolder. O nome pode ser definido pelo 
| desenvolvedor. Ex.: painel, admin, administracao, etc. Essa pasta tem suas proprias pastas controllers, models e views
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/ 
    private $subfolder;
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Definimos o nome do controlador padrao da aplicacao. Se nenhum controller for chamado na url, entao camaremos o controller indicado aqui.
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    protected $controller = 'home';
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Metodo inicial do controller. Se nenhum for chamado na url, entao chamamos o index.
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    protected $metodo = 'index';
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|   Id Passado pela url
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    protected $id;	
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
| array com os segmentos da url
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    protected $segmentos;

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|  Atributo para armazenar paginas
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/  
    protected $pagina;

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|  Atributo para armazenar paginas
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    protected $ordem;
    public function __construct()
    {
/*
|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
| Chama a classe URI, que mapeia as urls do site
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/ 
        require_once 'URI.php';
        $this->uri = new URI;
        $this->segmentos = $this->uri->set_segmentos();
        $this->set();   
    }
/*
|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
| Define se o diretorio da aplicacao será a pasta apps ou uma subpasta dentro dela.
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    public function set_app_dir()
    {
        /*
        * Diretorio raiz da aplicacao, que esta em 
        */
        
        $dir =  basename(basedir());
        /*
        Estamos em localhost ou em um dominio?
        */
        if($this->segmentos[0]==$dir)
        {
            unset($this->segmentos[0]);
            foreach($this->segmentos as $key=>$segmento)
            {
                $key = $key - 1;
                $this->partes[$key]= $segmento;
            }
            
        }else{
            foreach($this->segmentos as $key=>$segmento)
            {
                //$key = $key +1;
                $this->partes[$key]= $segmento;
            }
            
        }
        $this->segmentos = $this->partes;
        //O primeiro segmento da requisicao deve apontar para um controller ou uma subpasta de apps
        if(isset($this->segmentos[0]) and $this->segmentos[0] !='')
        {
            if(is_dir(appdir."/{$this->segmentos[0]}"))
            {
                $this->appfolder = $this->segmentos[0];
            }    
        }
        return $this->appfolder;       
    }
/*
|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
| Se a aplicacao esta salva em uma subpasta de apps, ela pode ter um painel de controle separado das outras aplicacoes. Nesse caso definimos qual parte da
| url aponta para a subpasta
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    public function set_sub_folder()
    {
        $this->set_app_dir();
        $pos_appfolder = array_search($this->appfolder,$this->segmentos);
        $pos_subfolder = $pos_appfolder+1;
        //Esta setada a appfolder e dentro dela tem uma subpasta?
        if(isset($this->appfolder))
        {
            if(isset($this->segmentos[$pos_subfolder]) and is_dir(appdir."/{$this->appfolder}/{$this->segmentos[$pos_subfolder]}"))
            $this->subfolder = $this->segmentos[$pos_subfolder];
        }   
        return $this->subfolder;  
    }
    /**
     * Determina qual parte da url é o controller a ser invocado
     */
    public function set_controller()
    {
    /*
    |--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
    | Existem 3 possibilidades: o controller e o primeiro, ou segundo ou terceiro item da url.
    |---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    */
        //Estou em uma pasta dentro de apps
        if(isset($this->appfolder) and $this->appfolder!='')
        {
            //Pegamos a posicao da pasta na url
            $pos_appfolder = array_search($this->appfolder,$this->segmentos);
            //Esta pasta e uma subpasta?
            if(isset($this->subfolder) and $this->subfolder!='')
            {
                $pos_subfolder = array_search($this->subfolder,$this->segmentos);
                $posicao_controller = $pos_subfolder+1;
                $filecontroller = appdir."/{$this->appfolder}/{$this->subfolder}/controllers/{$this->controller}.php";
            }else{
                $posicao_controller = $pos_appfolder+1;
                if(!isset($this->segmentos[$posicao_controller]) || $this->segmentos[$posicao_controller]=='')
                {
                    $this->controller = 'home';
                }else{
                    $this->controller = $this->segmentos[$posicao_controller];
                }
                
                $filecontroller = appdir."/{$this->appfolder}/controllers/{$this->controller}.php";
            }
        //Bom, nao estou em nenhuma posicao, entao estou na raiz: a pasta apps    
        }else{
            $posicao_controller = 0;
            if(isset($this->segmentos[$posicao_controller]) and $this->segmentos[$posicao_controller]!='')
            {
                $this->controller = $this->segmentos[$posicao_controller];            
                $filecontroller = appdir."/controllers/{$this->controller}.php";
            }else{
                $filecontroller = appdir."/controllers/{$this->controller}.php";
            }
            
        }
        return $filecontroller;
        return $this->controller;
    }
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Determina o metodo que sera executado 
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
    public function set_metodo()
    {
       
        if(isset($this->controller) and $this->controller!='')
        {
            $posicao_controller = array_search($this->controller,$this->segmentos);
        }else{
            echo "Nao chamou ninguem";
        }
        //Onde esta o controller?
        
        if($this->segmentos[$posicao_controller]!='')
        {
           //Se eu chamei um controller, entao o metodo e o proximo
           $posicao_metodo = $posicao_controller+1;

            if(isset($this->segmentos[$posicao_metodo]) and $posicao_metodo!='')
            {
                $this->metodo = $this->segmentos[$posicao_metodo];
            }    
        }else{
           
        }
       // echo $this->segmentos[$posicao_metodo];
    }

    public function set_id()
    {
        $posicao_metodo = array_search($this->metodo,$this->segmentos);
        $posicao_id = $posicao_metodo+1;
        if(isset($this->segmentos[$posicao_id]) and $this->segmentos[$posicao_id]!='')
        {
            $this->id = $this->segmentos[$posicao_id];
        }
        return $this->id;
    }

    public function get_app_dir()
    {
        return $this->appfolder;
    }

    public function get_controller()
    {
        return $this->controller;
    }

    public function get_sub_folder()
    {
        return $this->subfolder;
    }

    public function get_pagina()
    {
        $posicao_metodo = array_search($this->metodo,$this->segmentos);
        $pagina = $posicao_metodo+2;
        if(isset($this->segmentos[$pagina]) and $this->segmentos[$pagina]!='')
        {
            $this->pagina = $this->segmentos[$pagina];
        }else{
            $this->pagina=1;
        }        

        return $this->pagina;
    }

    public function get_ordem()
    {
        return 'nome';
    }
    public function set()
    {
        //$this->set_request();
        $this->set_app_dir();
        $this->set_sub_folder();
        $this->set_controller();
        $this->set_metodo();   
        $this->set_id(); 
        $this->get_pagina();        
    }

    public function parse_route($request){
    
        $nrotas = array();
        require_once(appdir.'/config/rotas.php');
        foreach($rotas as $rota=>$caminho)
        {
            $rota = str_replace(array("(any)","(num)"),array('[A-Za-z0-9-]+','[0-9]+'),$rota);
            //Achou a rota ou nao?
            if (!preg_match('#^'.$rota.'$#', $request, $matches))
            {
                $rota = str_replace(array('[A-Za-z0-9-]+','[0-9]+'),array("(any)","(num)"),$rota);
                unset($rotas[$rota]);
            }else{
                $request = array_push($nrotas,$caminho);
            }
        }
        return $nrotas;
    }

    public function set_request()
    {
        /*echo $this->appfolder.br;
        echo $this->subfolder.br;
        echo $this->controller.br;
        echo $this->metodo.br;
        echo $this->id.br;
        */
    }

    public function run()
    {
        #pre($this);
        $filecontroller = $this->set_controller();
        if(file_exists($filecontroller))
        {
            $namespace = str_replace(basename($filecontroller),'',$filecontroller);
            $classe = rtrim(ucfirst($this->controller)."Controller",'/');
            require $filecontroller;
            $namespace = str_replace('/','\\',$namespace);
            $exec = '\\'.$namespace.$classe;            
            $cname = $this->controller;  
            $controller = new $exec;
            $metodo = $this->metodo;
            //Caso nenhum metoo seja chamado, chamo o index() do controller
            if($metodo=='')
            {
                $controller->index();
            }
            if(method_exists($controller,$metodo))
            {
                /*
                |---------------------------------------------------------------------------------------------------------------------------------
                | Se estamos passando um dado pela url, entao passamos esse dado para o metodo
                |---------------------------------------------------------------------------------------------------------------------------------
                */
                if(isset($this->id) and $this->id!='')
                {
                    $controller->$metodo($this->id);                      
                }else{
                /*
                |---------------------------------------------------------------------------------------------------------------------------------
                | Executando o metodo sem passar nenhum argumento
                |---------------------------------------------------------------------------------------------------------------------------------
                */
                    $controller->$metodo();  
                }
            }else{
                require appdir."/erro404.html";
            }           
        }else{
            require appdir."/erro404.html";
        }
    }
}
