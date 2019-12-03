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
        $var = ereg_replace("[����]","A",$var);
        $var = ereg_replace("[����]","a",$var);
        $var = ereg_replace("[���]","E",$var);
        $var = ereg_replace("[���]","e",$var);
	 $var = ereg_replace("[��]","I",$var);
	 $var = ereg_replace("[��]","i",$var);
        $var = ereg_replace("[����]","O",$var);
        $var = ereg_replace("[����]","o",$var);
        $var = ereg_replace("[���]","U",$var);
        $var = ereg_replace("[���]","u",$var);
        $var = str_replace("[�]","C",$var);
        $var = str_replace("[�]","c",$var);
   	 $var = str_replace("[/\]","-",$var);
	 $var = str_replace("�","",$var);

        return trim($var);

    }
		
		public static function acentos($str, $tipo = 1){ // por padr�o, deixa em caixa alta
			if ($tipo == 1) // caixa alta
				$palavra = strtr(strtoupper($str), "������������������������������", "������������������������������");
			if ($tipo == 2) // caixa baixa
				$palavra = strtr(strtolower($str), "������������������������������", "������������������������������");
			elseif ($tipo == 0)
				$palavra = strtr($str, "������������������������������", "������������������������������");
			return $palavra;
		}
		
		public static function permissoesMenu($nivel){
			/*
			 * Script chamado ap�s a montagem do menu:
			 * Todos os itens de menu iniciam-se ocultos, o script revela apenas aqueles
			 * que pertencem ao n�vel do usu�rio logado.
			 * Para tal, � necess�rio que os itens a serem revelados possuam id.
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
			 * Script chamado ap�s a montagem da tela onde h� permiss�es reduzidas:
			 * Algumas telas permitem que certo n�vel de usu�rio acesse, crie e edite,
			 * mas n�o que apague registros; esse tipo de permiss�o � a chamada de "reduzida".
			 * O script oculta as opera��es de apagar registros para o n�vel passado por par�metro. 
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
			 * Cada p�gina do sistema chama essa fun��o e informa um array com 
			 * todo os n�veis permitidos a acess�-la.
			 * Se o usu�rio logado encaixar dentro desse array, a p�gina � montada.
			 * O array vem com seu primeiro valor igual a 'x' sempre para evitar
			 * confus�o no IF (confunde zero da posi��o retornada com FALSE no teste).
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