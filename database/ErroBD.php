<?php

namespace database;

class ErroBD{
    private $numero;
    private $mensagem;

    public function __construct($numero)
    {
        $this->numero = $numero;
    }

    public function getMensagem(){
        $this->mensagem = $this->findError($this->numero);
        return $this->mensagem;
    }

    public function findError($numero)
    {
        $numbers = array(
            1=>"O banco de dados informado não existe",
            2=>"Nao existe a tabela informada no banco de dados",
            3=>"Um campo informado no cadastro nao existe na lista de campos"
        );

        return $numbers[$numero];
    }
}