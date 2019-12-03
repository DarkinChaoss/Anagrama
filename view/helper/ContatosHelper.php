<?php
	class ContatosHelper{
		
		public static function listaContatos($idsetor){
			$html = "";
			foreach(ContatosController::getContatos("con_idsetor = " . $idsetor) as $a){
				if($a->con_principal == '1')
					$checked = "checked";
				else
					$checked = "";
				$html .= "	<div class='contatoPai' id='contatoPai".$a->con_id."'>
								<label class='radio span1' id='principal".$a->con_id."' style='margin: 12px 0 0 10px;'>
									<input type='radio' class='marcarContato pull-left' name='principal' value='" . $a->con_id . "' " . $checked . " title='Marcar como contato principal'>
								</label>
								<label class='contato' id='contato".$a->con_id."'>
									<div class='btn-group pull-left' style='margin-right: 10px;'>
										<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
											<i class='icon-user pull-left'></i>
										</a>
										<ul class='dropdown-menu' style='text-align: left;'>
											<li><a onclick='apagarContato(" . $a->con_id . ")'><i class='icon-remove'></i> Apagar</a></li>
											<li><a onclick='editarContato(" . $a->con_id . ")'><i class='icon-pencil'></i> Editar</a></li>
										</ul>
									</div>
									<span class='pull-left'>" . $a->con_nome . "</span><span>" . $a->con_telefone . "</span>
									<label>" . $a->con_email . "</label>
								</label>
							</div>";
			}
			return $html;
		}
		
		public static function alimentaContato($obj){
			if(is_null($obj)) {
				return "";
			} else {
				return $obj->con_id . ";" . $obj->con_nome . ";" . $obj->con_email. ";" . $obj->con_telefone;
			}
		}
		
	}
?>