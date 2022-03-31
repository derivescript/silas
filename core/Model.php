<?php

class Model{

    public function call($arquivo){
        if(file_exists($arquivo.".php")){
            require_once($arquivo.".php");
            return new $arquivo;
        }        
    }
}