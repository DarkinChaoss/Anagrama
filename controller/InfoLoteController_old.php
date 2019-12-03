<?php
	class InfoLoteController {

		public static function insert($dados){
			
			$info = new InfoLoteModel();
			$info->infl_temperatura = $dados['infl_temperatura'];
			$info->infl_iniciociclo = $dados['infl_iniciociclo'];
			$info->infl_finciclo = $dados['infl_finciclo'];
			$info->infl_responfin = $dados['infl_responfin'];
			$info->infl_horario = $dados['infl_horario'];
			$info->infl_leitura = $dados['infl_leitura'];
			$info->infl_retiradacarga = $dados['infl_retiradacarga'];
			$info->infl_incubacao = $dados['infl_incubacao'];
			$info->infl_responfinal = $dados['infl_responfinal'];
			$info->infl_horario_incubacao = $dados['infl_horario_incubacao'];
			$info->infl_data_incubacao = $dados['infl_data_incubacao'];
			$info->infl_lote = $dados['infl_lote'];			
			return $info->insert();
		}
		
		public static function getInfo($lote){
			$info = new InfoLoteModel();
			return $info->selectInfo($lote);
		}
		
		public static function update($dados){
			$info = new InfoLoteModel();
			$info->infl_temperatura = $dados['infl_temperatura'];
			$info->infl_iniciociclo = $dados['infl_iniciociclo'];
			$info->infl_finciclo = $dados['infl_finciclo'];
			$info->infl_responfin = $dados['infl_responfin'];
			$info->infl_horario = $dados['infl_horario'];
			$info->infl_leitura = $dados['infl_leitura'];
			$info->infl_retiradacarga = $dados['infl_retiradacarga'];
			$info->infl_incubacao = $dados['infl_incubacao'];
			$info->infl_responfinal = $dados['infl_responfinal'];
			$info->infl_horario_incubacao = $dados['infl_horario_incubacao'];
			$info->infl_data_incubacao = $dados['infl_data_incubacao'];
			$info->infl_lote = $dados['infl_lote'];			
			return $info->update();
		
		}
		

	

	


		

	
		
		
		
		

	}
?>