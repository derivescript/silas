<?php

namespace apps\controllers;

use function core\basedir;
use function core\renomear;

class HomeController extends \core\Controller
{
    public function index()
    {
       $this->view('Welcome',["titulo"=>"Ambiente de desenvolvimento S.I.L.A.S","nome"=>"Daniel"]);
    }
}



