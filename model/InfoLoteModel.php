<?php 
		
		class InfoLoteModel extends Conexao{
		
		public $lote;
		public $equipment;
		public $tipo_carga;
		public $tipo_carga_expurgo;
		public $date;
		public $contem_implantes;
		public $liberado;
		public $resultado;
		public $temperatura;
		public $ini_ciclo;
		public $final_ciclo;
		public $resp_leitura_final;
		public $horario_retirada;
		public $leitura_resultado;
		public $resp_retirada;
		public $resp_incubacao;
		public $incub_data;
		public $incub_horario;
		public $resp_leitura_final_last;
		
		
		public function __construct(){
			$this->conecta();
		}
		
		
		public function insert(){
			
			$sql = "INSERT INTO tmsd_infolote (
			
			infl_lote,
			infl_equipment,
			infl_tipo_carga,
			infl_tipo_carga_expurgo,
			infl_date,
			infl_contem_implantes,
			infl_liberado,
			infl_resultado,
			infl_temperatura,
			infl_ini_ciclo,
			infl_final_ciclo,
			infl_resp_leitura_final,
			infl_horario_retirada,
			infl_leitura_resultado,
			infl_resp_retirada,
			infl_resp_incubacao,
			infl_incub_data,
			infl_incub_horario,
			infl_resp_leitura_final_last
			) VALUES (
			
				'" . utf8_decode($this->lote) . "',
				'" . utf8_decode($this->equipment) . "',
				'" . utf8_decode($this->tipo_carga) . "',
				'" . utf8_decode($this->tipo_carga_expurgo) . "',
				'" . utf8_decode($this->date) . "',
				'" . utf8_decode($this->contem_implantes) . "',
				'" . utf8_decode($this->liberado) . "',
				'" . utf8_decode($this->resultado) . "',
				'" . utf8_decode($this->temperatura) . "',
				'" . utf8_decode($this->ini_ciclo) . "',
				'" . utf8_decode($this->final_ciclo) . "',
				'" . utf8_decode($this->resp_leitura_final) . "',
				'" . utf8_decode($this->horario_retirada) . "',
				'" . utf8_decode($this->leitura_resultado) . "',
				'" . utf8_decode($this->resp_retirada) . "',
				'" . utf8_decode($this->resp_incubacao) . "',
				'" . utf8_decode($this->incub_data) . "',
				'" . utf8_decode($this->incub_horario) . "',
				'" . utf8_decode($this->resp_leitura_final_last) . "'				
			)";
			
			$res = mysql_query($sql) or die(mysql_error());
			
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_infolote.";
				$this->gravaLog();
				//
			}
			
			return $res;
		
		}//Final of the method insert
				
			public function getInfoLote($data){
				$lote = strtoupper($data['lote']);
				$date = $data['date'];
				$equipment = utf8_decode($data['equipment']);

				
			 	$sql = "SELECT * FROM tmsd_infolote WHERE infl_lote = '$lote' AND infl_equipment = '$equipment'  AND infl_date = '$date' limit 1";
				$res = mysql_query($sql);
			//$row = mysql_fetch_array($res, MYSQL_ASSOC);
				
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new InfoLoteModel();
				$obj->lote = utf8_encode($row['infl_lote']);
				$obj->equipment = '';
				$obj->tipo_carga = utf8_encode($row['infl_tipo_carga']);
				$obj->tipo_carga_expurgo = utf8_encode($row['infl_tipo_carga_expurgo']);
				$obj->date = utf8_encode($row['infl_date']);
				$obj->contem_implantes = utf8_encode($row['infl_contem_implantes']);
				$obj->liberado = utf8_encode($row['infl_liberado']);
				$obj->resultado = utf8_encode($row['infl_resultado']);
				$obj->temperatura = utf8_encode($row['infl_temperatura']);
				$obj->ini_ciclo = utf8_encode($row['infl_ini_ciclo']);
				$obj->final_ciclo = utf8_encode($row['infl_final_ciclo']);
				$obj->resp_leitura_final = utf8_encode($row['infl_resp_leitura_final']);
				$obj->horario_retirada = utf8_encode($row['infl_horario_retirada']);
				$obj->leitura_resultado = utf8_encode($row['infl_leitura_resultado']);
				$obj->resp_retirada =utf8_encode( $row['infl_resp_retirada']);
				$obj->resp_incubacao = utf8_encode($row['infl_resp_incubacao']);
				$obj->incub_data = $row['infl_incub_data'];
				$obj->incub_horario = $row['infl_incub_horario'];
				$obj->resp_leitura_final_last = utf8_encode($row['infl_resp_leitura_final_last']);
			}
			return $obj;
		}
		
			public function update(){

			$lote = utf8_decode($this->lote);	
			$equipment = utf8_decode($this->equipment);
			$date = utf8_decode($this->date);

			$sql = "UPDATE tmsd_infolote SET
					infl_lote = '" . utf8_decode($this->lote) . "',
					infl_equipment = '" . utf8_decode($this->equipment) . "',
					infl_tipo_carga = '" . utf8_decode($this->tipo_carga) . "',
					infl_tipo_carga_expurgo = '" . utf8_decode($this->tipo_carga_expurgo) . "',
					infl_date = '" . utf8_decode($this->date) . "',
					infl_contem_implantes = '" . utf8_decode($this->contem_implantes) . "',
					infl_liberado = '" . utf8_decode($this->liberado) . "',
					infl_resultado = '" . utf8_decode($this->resultado) . "',
					infl_temperatura = '" . utf8_decode($this->temperatura) . "',
					infl_ini_ciclo = '" . utf8_decode($this->ini_ciclo) . "',
					infl_final_ciclo = '" . utf8_decode($this->final_ciclo) . "',
					infl_resp_leitura_final = '" . utf8_decode($this->resp_leitura_final) . "',
					infl_horario_retirada = '" . utf8_decode($this->horario_retirada) . "',
					infl_leitura_resultado = '" . utf8_decode($this->leitura_resultado) . "',
					infl_resp_retirada = '" . utf8_decode($this->resp_retirada) . "',
					infl_resp_incubacao = '" . utf8_decode($this->resp_incubacao) . "',
					infl_incub_data = '" . utf8_decode($this->incub_data) . "',
					infl_incub_horario = '" . utf8_decode($this->incub_horario) . "',
					infl_resp_leitura_final_last = '" . utf8_decode($this->resp_leitura_final_last) . "'
					WHERE infl_lote = '$lote' AND infl_equipment = '$equipment' AND infl_date = '$date'";
			$res = mysql_query($sql);
			return $res;
		}
		
		public function findWhere($lote, $equipment, $date){
			$equipment = utf8_decode($equipment);
			$sql = "SELECT EXISTS(SELECT * FROM tmsd_infolote WHERE infl_lote = '$lote' AND infl_equipment = '$equipment' AND infl_date = '$date' )";
			$res = mysql_query($sql);
			return mysql_result($res, 0);
		}
} 
?>