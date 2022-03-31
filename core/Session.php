<?php
/**
 * Classe para manipulação de sessões
 */
namespace core;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public function __set($varname,$varvalue)
    {
        $_SESSION[$varname] = $varvalue;
    }

    public function __get($varname)
    {
        return $_SESSION[$varname];
    }
} 