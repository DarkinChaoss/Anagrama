<?php
	class DefaultHelper{
		
		public static function converte_data($data){
			if($data == "0000-00-00") {
				return "-";
			} else {
				if (strstr($data, "/")){
					$A = explode ("/", $data);
					$V_data = $A[2] . "-". $A[1] . "-" . $A[0];
				} else {
					$A = explode ("-", $data);
					
					if (strstr($A[2]," ")){
						$B = explode(" ", $A[2]);
						$V_data = $B[0]."/". $A[1] . "/" . $A[0]." ".$B[1];
					}else{
						$V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
					}
				}
				return $V_data;
			}
		}
		
				public static function converte_data_permanente($data){
			if($data == "0000-00-00") {
				return "USO PERMANENTE";
			} else {
				if (strstr($data, "/")){
					$A = explode ("/", $data);
					$V_data = $A[2] . "-". $A[1] . "-" . $A[0];
				} else {
					$A = explode ("-", $data);
					
					if (strstr($A[2]," ")){
						$B = explode(" ", $A[2]);
						$V_data = $B[0]."/". $A[1] . "/" . $A[0]." ".$B[1];
					}else{
						$V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
					}
				}
				return $V_data;
			}
		}


		public static function converte_fullDate($date = null) {
			if(!$date){
				return false;
			}

			if(strstr($date, '/')){
				$temp = explode(' ', $date);
				$hora = $temp[1];
				$response = join('-', array_reverse(explode('/', $temp[0])));
				return $response . ' ' . $hora;
			}

			if(strstr($date, '-')){
				$temp = explode(' ', $date);
				$hora = $temp[1];
				$response = join('/', array_reverse(explode('-', $temp[0])));
				return $response . ' ' . $hora;
			}

			return false;
		}
	

public static function removerAcentos($var)
    {
        $var = ereg_replace("[ÁÀÂÃ]","A",$var);
        $var = ereg_replace("[áàâãª]","a",$var);
        $var = ereg_replace("[ÉÈÊ]","E",$var);
        $var = ereg_replace("[éèê]","e",$var);
	 $var = ereg_replace("[ÍÌ]","I",$var);
	 $var = ereg_replace("[íì]","i",$var);
        $var = ereg_replace("[ÓÒÔÕ]","O",$var);
        $var = ereg_replace("[óòôõ]","o",$var);
        $var = ereg_replace("[ÚÙÛ]","U",$var);
        $var = ereg_replace("[úùû]","u",$var);
        $var = str_replace("[Ç]","C",$var);
        $var = str_replace("[ç]","c",$var);
   	 $var = str_replace("[/\]","-",$var);
	 $var = str_replace("º","",$var);

        return trim($var);

    }
		
		public static function acentos($str, $tipo = 1){ // por padrão, deixa em caixa alta
			if ($tipo == 1) // caixa alta
				$palavra = strtr(strtoupper($str), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
			if ($tipo == 2) // caixa baixa
				$palavra = strtr(strtolower($str), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
			elseif ($tipo == 0)
				$palavra = strtr($str, "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
			return $palavra;
		}
		
		public static function permissoesMenu($nivel){
			/*
			 * Script chamado após a montagem do menu:
			 * Todos os itens de menu iniciam-se ocultos, o script revela apenas aqueles
			 * que pertencem ao nível do usuário logado.
			 * Para tal, é necessário que os itens a serem revelados possuam id.
			 */
			$str = "";

			switch ($nivel){
				case 2:
					$str .= "<script>
								//$('#menuCadastros').show();
								//$('#menuSolicitacoes').show();
								$('#menu li').show();
							</script>";
					break;
				case 3:
					$str .= "";
					break;
				case 4:
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				case 5: // master client
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				case 6: // master client
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				case 7: // master client
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				case 8:
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				case 9:
					$str .= "<script>
								$('#menu li').show();
							</script>";
					break;
				default:
					break;
			}
			return $str;
		}
		
		public static function permissoesReduzidas($nivel){
			/*
			 * Script chamado após a montagem da tela onde há permissões reduzidas:
			 * Algumas telas permitem que certo nível de usuário acesse, crie e edite,
			 * mas não que apague registros; esse tipo de permissão é a chamada de "reduzida".
			 * O script oculta as operações de apagar registros para o nível passado por parâmetro. 
			 */
			$str = "";
			if($nivel == $_SESSION['usu_nivel']){
				$str .= "<script>
							$('.delete').hide();
						</script>";
			}
			return $str;
		}
		
		public static function acessoPermitido($arr){
			/*
			 * Cada página do sistema chama essa função e informa um array com 
			 * todo os níveis permitidos a acessá-la.
			 * Se o usuário logado encaixar dentro desse array, a página é montada.
			 * O array vem com seu primeiro valor igual a 'x' sempre para evitar
			 * confusão no IF (confunde zero da posição retornada com FALSE no teste).
			 */
			$str = "";
			if(!array_search($_SESSION['usu_nivel'], $arr)){
				$str .= "<script>
							location.href = 'home';
						</script>";
			}
			return $str;
		}


			public static function real($custo){
				
				if ($custo == 0 || $custo =='') {
					$custo ='0,00';
				
				}else
					$custo = number_format( $custo , 2, ',', '.');	
					return $custo;
		    }
		 


		    public static function teste($custo){

		  		 $resultado = str_replace ( "$",  '', $custo);
		  		 $resultado1 = str_replace ( "R",  '', $resultado);
		  		 


		  		 return $resultado1;
		  		 
		 



		    }




	}
?>