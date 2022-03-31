<?php

class Imap{
	
	private $conexao;
	
	
	public static function conecta(){
	require(appdir.'/config/email.php');
		DEFINE('SERVIDOR', $email['popserver']); 
		DEFINE('PORTA', '110');
		DEFINE('USUARIO', $email['usuario']);
		DEFINE('SENHA', $email['senha']);
		$mail_box = imap_open("{" . SERVIDOR . ":" . PORTA . "/pop3/novalidate-cert}INBOX", USUARIO, SENHA);			
	}
	
	
}
