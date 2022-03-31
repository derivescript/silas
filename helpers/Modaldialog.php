<?php

namespace helpers;

class ModalMessage{
	private $mensagem;
	private $titulo;

public function modal($titulo,$mensagem){
	echo "<div id=\"modal\">&nbsp;</div>
	    <div id=\"msg-modal\" class=\"jan-modal\">
	    	<div class=\"topomodal\">
		    	<div class=\"titulo_modal\">{$titulo}</div>
		    	<div class=\"controles\">
		    		<button type=\"button\" class=\"btn btn-danger botao-fechar\"><b>X</b></button>
		    	</div>    	
	    	</div>
	    	<div class=\"mensagem\">
	    		<p class=\"msg\">{$mensagem}</p>
	    		<div class=\"botoes\">
	    			<button type=\"button\" class=\"btn btn-ok\">Ok</button>
	    		</div>
	    	</div>	
	    </div>";
	}	
}
?>