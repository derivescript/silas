<?php

namespace helpers;

/**
 * Classe para campos tipo File
 */
 
class TFile{
    private $id;
    private $name;
    private $size;
   
   /**
    * Método construtor
    * @param $name;
    */
    public function __construct($name){
        $this->set_id($name);
        $this->set_name($name);
    }
   /**
    * Atribui o valor para o atributo $id
    * @param $id
    */
    public function set_id($id){
        $this->id=$id;
    }
    /**
    * Atribui o valor para o atributo $name
    * @param $name
    */
     public function set_name($name){
        $this->name=$name;
    }
    /**
    * Atribui o valor para o atributo $size
    * @param $size
    */
     public function set_sise($size){
        $this->size=$size;
    }
    /**
    * Retorna o valor do atributo $id
    */
    public function getid(){
        return $this->id;
    }
    /**
    * Retorna o valor do atributo $name
    */
    public function get_name(){
        return $this->name;
    }
    /**
    * Retorna o valor do atributo $size
    */
    public function get_size(){
        return $this->size;
    }
    /**
    * Método exibir. Mostra o elemento na tela
    */
    public function exibir(){
        echo "<input type=\"file\" name=\"{$this->name}\" id=\"{$this->id}\" size=\"{$this->size}\" />";
    }
}