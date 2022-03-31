<?php

namespace core;

use \core\Session;

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Arquivo Framework.php
| Responsavel por inicializar os objetos principais do sistema e fazer tudo rodar.
| Autor: Daniel da Costa e Faria
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Apenas para efeito de mostrar erros na fase de desenvolvimento, caso a diretiva esteja desativada em php.ini
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
ini_set('display_errors','on');
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Disparador de erros
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
require 'ExceptionHandler.php';
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Carrega o arquivo de funcoes utilitarias do sistema. Tem funcoes para imprimir saida de arrays, limpar caraceteres, renomear arquivos, entre outras
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
require_once 'Functions.php';

/*
|------------------------------------------------------------------------------------------------------------------------------------------------------------
| Helpers de bancos de dados. Carrega todas as classes de bancos de dados da pasta bd
|------------------------------------------------------------------------------------------------------------------------------------------------------------ 
 */
require('BDHelper.php');
BDHelper::carrega_bdhelpers();

/*
|-------------------------------------------------------------------------------------------------------------------------------------------------------------
| Helpers HTML. Lista de classes utilitárias para gerar qualquer tipo de pággina. Inclui componentes como campos de formulários dinâmicos, datagrids, tabelas
| calendario, entre outros.
|-------------------------------------------------------------------------------------------------------------------------------------------------------------
 */

require('Helper.php');
Helper::carrega_helpers();

/* 
|------------------------------------------------------------------------------------------------------------------------------------------------------------
| Carrega e inicia a classe Autoload, que carrega os aplicativos de terceiros instalados na pasta vendor
|------------------------------------------------------------------------------------------------------------------------------------------------------------ 
*/   
require('Autoload.php');
$autoload = new Autoload();

/*
 * 
 */  
require 'Imap.php';

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Chama a classe basica Controller. Todos os controllers da aplicacao sao baseadas nele
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
require "Controller.php";

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Classe Model. Com métodos básicos de painel ao banco de dados
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
require "Model.php";

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Dispara a pagina de excecoes, mostrando onde estão os erros de programação do sistema.
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
//require "ExceptionHandler.php";
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Classe utilitária para enviar arquivos ao servidor
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/ 
require "Upload.php";
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Classe para tradução de variáveis de template
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
 require 'Template.php';  

/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Chamada da funcao ob_start(), para prevenir erros de cabecalho
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/ 
ob_start();
/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Torna sessoes globais 
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
//session_start();
require 'Session.php';
$session = new Session();

/*
|------------------------------------------------------------------------------------------------------------------------------------------------------------
| 
|------------------------------------------------------------------------------------------------------------------------------------------------------------
*/
require 'Router.php';
$router = new Router;

echo $router->run();


/*
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Aqui é feita a chamada dos controllers. Na requisição feita ao servidor, chamamos o controller pelo url (www.dominio.com.br/cadastro, por exemplo);
| Caso nao chamemos nenhum controller, então o Framework irá carregar o home.php na pasta aplicacao/controllers/. Se um controller for passado pela url
| então o sistema irá procurar dentro de controllers pelo arquivo chamado. Também existe a opção de colocar os controllers dentro de uma subpasta 
| (admin/controllers, por exemplo).
|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/