<?php

namespace helpers;

class CampoBusca{
	/**
	 * 
	 */
	public function __construct($nome,$label,$controller,$tamanho,$value=''){
		echo '<div class="form-group">
      			<label class="col-sm-2 control-label">'.$label.'</label>
      			<div class="col-sm-8">
      				<input type="text" class="form-control input-'.$tamanho.' campobusca" name="'.$nome.'" id="'.$nome.'" data-controller="'.$controller.'" autocomplete="off" data-id="" placeholder="'.$value.'">
		              <ul id="sugestoes-'.$controller.'" class="lista-sugestoes">
		      
		              </ul>
			    </div>
      		</div>';
	}
}