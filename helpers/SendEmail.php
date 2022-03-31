<?php

namespace helpers;

use PHPMailer;
use phpmailerException;

use function core\pre;

class SendEmail{
	private $assunto;
	private $nome_destinatario;
	private $nome_remetente;
	private $email_remetente;
	private $email_destinatario;
	private $rtelefone; 
	private $corpo;
	private $anexo;
	/**
	 * 
	 */
	public function __construct($nomefrom,$emailfrom,$nome,$email,$rtelefone,$assunto,$corpo,$anexo=''){
		$this->nome_remetente = $nomefrom;
		$this->email_remetente = $emailfrom;
		$this->assunto = $assunto;
		$this->nome_destinatario = $nome;
		$this->email_destinatario = $email;
		$this->rtelefone=$rtelefone;
        $this->corpo = $corpo;
        $this->anexo = $anexo;        
	}
	
	public function get_email_remetente(){
		return $this->email_remetente;
	}
	
	public function get_email_destinatario(){
		return $this->email_destinatario;
	}
	
	public function get_nome_remetente(){
		return $this->nome_remetente;
	}
	
	public function get_nome_destinatario(){
		return $this->nome_destinatario;
	}
	
	public function get_telefone_remetente(){
		return $this->rtelefone;
	}
	
	public function get_assunto(){
		return $this->assunto;
	}
	
	public function get_corpo(){
		return $this->corpo;	
	}
	
	public function enviar(){
        require appdir."/config/email.php";
		// Inicia a classe PHPMailer
		$mail = new PHPMailer(true);
		 
		// Define os dados do servidor e tipo de conexa
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->IsSMTP(); // Define que a mensagem sera SMTP
		 
		try {
	     $mail->Host 	   = $email['host']; 
	     $mail->SMTPAuth   = $email['smtpauth'];
	     $mail->Port       = $email['porta']; 
	     $mail->Username = $email['usuario']; 
	     $mail->Password = $email['senha']; 	 
	     //Define o remetente
	     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
	     $mail->SetFrom($email['usuario'], $email['nome']);
	     $mail->AddReplyTo($email['usuario'], $email['nome']);
	     $mail->Subject = $this->get_assunto();
	 
	 	 $mail-> SMTPOptions = array(
						        'ssl' => array(
						            'verify_peer' => false,
						            'verify_peer_name' => false,
						            'allow_self_signed' => true
						        )
						    );
	     //Define os destinatario(s)
	     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     $mail->AddAddress($this->get_email_destinatario(), $this->get_nome_destinatario());
	 
         //Adicionar anexo
         if($this->anexo!=''){
           // $mail->AddAttachment($this->anexo['tmp_name'],renomear($this->anexo['name']));
           pre($this->anexo);
         }
	     
	     //$dados = $this->get_nome_remetente()." (".$this->get_email_remetente().") ".$this->get_telefone_remetente()." escreveu: <br>";
	 	 
	 
	     //Define o corpo do email
	     //$mail->MsgHTML($dados.$this->get_corpo()); 
		 $mail->MsgHTML($this->get_corpo()); 
	     ////Caso queira colocar o conteudo de um arquivo utilize o metodo abaixo ao inves da mensagem no corpo do e-mail.
	     //$mail->MsgHTML(file_get_contents('arquivo.html'));
	 	 $mail->Send();
	 	 return true;
	 
	    //caso apresente algum erro e apresentado abaixo com essa excecao
	    }catch (phpmailerException $e) {
	      $erro = $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
	      return $erro;
	      return false;
		}
	}
}
