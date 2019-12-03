<?php
	class InfoLoteController {

		public static function insert($dados){
			//is there a register already on database?
			$infoLote = new InfoLoteModel();

			$exists = $infoLote->findWhere($dados->lote, $dados->equipment, $dados->date);
			

			if($exists){
				return self::update($dados);
			}
			
			if(!$exists){
				$info = new InfoLoteModel();
				$info->lote = $dados->lote;
				$info->equipment = $dados->equipment;
				$info->tipo_carga = $dados->tipo_carga;
				$info->tipo_carga_expurgo = $dados->tipo_carga_expurgo;
				$info->date = $dados->date;
				$info->contem_implantes = $dados->contem_implantes;
				$info->liberado = $dados->liberado;
				$info->resultado = $dados->resultado;
				$info->temperatura = $dados->temperatura;
				$info->ini_ciclo = $dados->ini_ciclo;
				$info->final_ciclo = $dados->final_ciclo;
				$info->resp_leitura_final = $dados->resp_leitura_final;
				$info->horario_retirada = $dados->horario_retirada;
				$info->leitura_resultado = $dados->leitura_resultado;
				$info->resp_retirada = $dados->resp_retirada;
				$info->resp_incubacao = $dados->resp_incubacao;
				$info->incub_data = $dados->incub_data;
				$info->incub_horario = $dados->incub_horario;
				$info->resp_leitura_final_last = $dados->resp_leitura_final_last;	
				return $info->insert();
			}
			
		}
		
		public static function getInfoLote($data){

			$info = new InfoLoteModel();
			return $info->getInfoLote($data);
		}
		
		public static function update($dados){
			$info = new InfoLoteModel();
			$info->lote = $dados->lote;
			$info->equipment = $dados->equipment;
			$info->tipo_carga = $dados->tipo_carga;
			$info->tipo_carga_expurgo = $dados->tipo_carga_expurgo;
			$info->date = $dados->date;
			$info->contem_implantes = $dados->contem_implantes;
			$info->liberado = $dados->liberado;
			$info->resultado = $dados->resultado;
			$info->temperatura = $dados->temperatura;
			$info->ini_ciclo = $dados->ini_ciclo;
			$info->final_ciclo = $dados->final_ciclo;
			$info->resp_leitura_final = $dados->resp_leitura_final;
			$info->horario_retirada = $dados->horario_retirada;
			$info->leitura_resultado = $dados->leitura_resultado;
			$info->resp_retirada = $dados->resp_retirada;
			$info->resp_incubacao = $dados->resp_incubacao;
			$info->incub_data = $dados->incub_data;
			$info->incub_horario = $dados->incub_horario;
			$info->resp_leitura_final_last = $dados->resp_leitura_final_last;	
			return $info->update();
		
		}
	
		

	}
?>