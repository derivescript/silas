<?php

namespace helpers;

use core\Functions;

use function core\base_url;

class toolbar{
        
    private $nome;
    private $label;
    private $link;
    private $icone;
    private $alt;
    private $id;
    
   
    
    public function addbotao($label,$link,$icone,$alt,$id){
        
    echo '<div class="botao">'."\n";
    	echo "<a href=\"$link\" title=\"$alt\" id=\"{$id}\" class=\"tool-link\">\n";
    		echo "<img src=\"".base_url()."assets/admin/imagens/".$icone."\" class=\"iconebotao\" />\n";
    		echo "<br>";
    		echo $label."</a>\n";
    echo "</div>\n";
    }
    
   }

class ToolbarEdit{
    public function __construct(){
        echo "<div class=\"toolbar-container\">";   
        echo "<div id=\"toolbar\">";
        $toolbar = new toolbar();
        $toolbar-> addbotao("Salvar","#","save_f2.png","Salvar","salvar");
        $toolbar-> addbotao("Cancelar","#","cancel_f2.png","cancelar","cancelar");
        echo "</div>";
        echo "</div>";
    }
}


class ToolbarPadrao{
    public function __construct(){
        echo '<div class="row">';
        echo "<div class=\"toolbar-container\">";   
        echo "<div id=\"toolbar\">";
        $toolbar = new toolbar();
        $toolbar-> addbotao("Novo","#","add.png","Adicionar novo","novo");
        $toolbar-> addbotao("Publicar","#","publicar.png","Publicar usu&aacute;rio","publicar");
        $toolbar-> addbotao("Despublicar","#","despublicado.png","Despublicar registro","despublicar");        
        $toolbar-> addbotao("Editar","#","edit.png","Editar menu","editar");
        $toolbar-> addbotao("Excluir","#","cancel_f2.png","Excluir menu","excluir");
		echo "</div></div></div>";
		
		
    }
}

class ToolbarResumo{
    public function __construct(){
        echo "<div class=\"toolbar-container\">";   
        echo "<div id=\"toolbar\">";
        $toolbar = new toolbar();
        $toolbar-> addbotao("Novo","#","add.png","Adicionar novo","novo");
        $toolbar-> addbotao("Excluir","#","cancel_f2.png","Excluir resumo","excluir");
        //$toolbar-> addbotao("Responder","#","kate.png","Enviar resposta para o autor","responder");
        echo "</div>";        
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "<div class=\"titulo-lista\">";
        //echo "<h2>".$titulo."</h2>";
        echo "</div>"; 
    }
}