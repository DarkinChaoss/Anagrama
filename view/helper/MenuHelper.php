<?php
	class MenuHelper{

		public static function montaMenu(){
			$html = "";
			foreach(MenuController::getMenu("men_pai = 0") as $a){

				if(strripos($a->men_visualizacao, $_SESSION['usu_nivel'])){
					// item nível 0 COM sub-menu

					if($a->men_sub){

    			        $html .= "	<li class='dropdown'>
    								<a href='" . $a->men_url . "' class='dropdown-toggle' data-toggle='dropdown'>" . $a->men_nome . " <b class='caret'></b></a>
    								<ul class='dropdown-menu'>";
    			        foreach(MenuController::getMenu("men_pai = " . $a->men_id) as $b){
    			            if(strripos($b->men_visualizacao, $_SESSION['usu_nivel'])){

    			                // item nível 1 COM sub-menu
    			                if($b->men_sub){
    			                    $html .= "	<li class='dropdown-submenu'>
    											<a href='" . $b->men_url . "'>" . $b->men_nome . "</a>
    											<ul class='dropdown-menu'>";
    			                    foreach(MenuController::getMenu("men_pai = " . $b->men_id) as $c){
    			                        // item nível 2
    			                        if(strripos($c->men_visualizacao, $_SESSION['usu_nivel'])){
    			                            $html .= "		<li><a href='" . $c->men_url . "'>" . $c->men_nome . "</a></li>";
    			                            // divider nível 2
    			                            if($b->men_divider){
    			                                $html .= "	<li class='divider'></li>";
    			                            }
    			                        }
    			                    }
    			                    $html .= "		</ul>
    										</li>";
    			                }
    			                // item nível 1 SEM sub-menu
    			                else {

    			                    if( $b->men_id == '48' ){	 // Etiquetagem em Massa
    			                        if( $_SESSION['usu_masterclient'] == '856' || $_SESSION['usu_masterclient'] == '6' ){
    			                             $html .= "	<li><a href='" . $b->men_url . "'>" . $b->men_nome . "</a></li>";
    			                        }
    			                    }else{
    			                        $html .= "	<li><a href='" . $b->men_url . "'>" . $b->men_nome . "</a></li>";
    			                    }

    			                }
    			                // divider nível 1
    			                if($b->men_divider){
    			                    $html .= "	<li class='divider'></li>";
    			                }
    			            }
    			        }
    			        $html .= "	</ul>
    						</li>";

					}
					// item nível 0 SEM sub-menu
					else {
					    $html .= "	<li><a href='" . $a->men_url . "'>" . $a->men_nome . "</a></li>";
					}
				}
			}
			return $html;
		}

	}
?>