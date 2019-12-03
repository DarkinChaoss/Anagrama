<?php 
		
		class InfoLoteModel extends Conexao{
		
		//public $infl_id;
		public $infl_temperatura;
		public $infl_iniciociclo;
		public $infl_finciclo;
		public $infl_responfin;
		public $infl_horario;
		public $infl_leitura;
		public $infl_retiradacarga;
		public $infl_incubacao;
		public $infl_responfinal;
		public $infl_horario_incubacao;
		public $infl_data_incubacao;
		
		public $infl_lote;
		
		
		public function __construct(){
			$this->conecta();
		}// this is the final of the construct
		
		
		public function insert(){
		
			$sql = "INSERT INTO tmsd_infolote (
			
			infl_temperatura,
			infl_iniciociclo,
			infl_finciclo,
			infl_responfin,
			infl_horario,
			infl_leitura,
			infl_retiradacarga,
			infl_incubacao,
			infl_responfinal,
			infl_horario_incubacao,
			infl_data_incubacao,
			infl_lote
			) VALUES (
			
				'" . DefaultHelper::acentos($this->infl_temperatura) . "',
				'" . DefaultHelper::acentos($this->infl_iniciociclo) . "',
				'" . DefaultHelper::acentos($this->infl_finciclo) . "',
				'" . DefaultHelper::acentos($this->infl_responfin) . "',
				'" . $this->infl_horario . "',
				'" . DefaultHelper::acentos($this->infl_leitura) . "',
				'" . DefaultHelper::acentos($this->infl_retiradacarga) . "',
				'" . DefaultHelper::acentos($this->infl_incubacao) . "',
				'" . DefaultHelper::acentos($this->infl_responfinal) . "',
				'" . $this->infl_horario_incubacao . "',
				'" . $this->infl_data_incubacao . "',
				'" . DefaultHelper::acentos($this->infl_lote) . "'
			)";
			
			$res = mysql_query($sql);
			
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_infolote.";
				$this->gravaLog();
				//
			}
			
			return $res;
		
		}//Final of the class insert
				
			public function selectInfo($lote){
			$sql = "SELECT * FROM tmsd_infolote WHERE infl_lote = '$lote' ";

			//error_log('getInfo - '.$sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);

			$obj = new InfoLoteModel();
			$obj->infl_temperatura = $row['infl_temperatura'];
			$obj->infl_iniciociclo = $row['infl_iniciociclo'];
			$obj->infl_finciclo = $row['infl_finciclo'];
			$obj->infl_responfin = $row['infl_responfin'];
			$obj->infl_horario = $row['infl_horario'];
			$obj->infl_leitura = $row['infl_leitura'];
			$obj->infl_retiradacarga = $row['infl_retiradacarga'];
			$obj->infl_incubacao = $row['infl_incubacao'];
			$obj->infl_responfinal = $row['infl_responfinal'];
			$obj->infl_horario_incubacao = $row['infl_horario_incubacao'];
			$obj->infl_data_incubacao = $row['infl_data_incubacao'];
			$obj->infl_lote = $row['infl_lote'];
			return $obj;
		}
		
			public function update(){
			
			
			 $sql = "UPDATE tmsd_infolote SET
						infl_temperatura = '" . DefaultHelper::acentos($this->infl_temperatura) . "',
						infl_iniciociclo = '" . DefaultHelper::acentos($this->infl_iniciociclo) . "',
						infl_finciclo = '" . DefaultHelper::acentos($this->infl_finciclo) . "',
						infl_responfin = '" . DefaultHelper::acentos($this->infl_responfin) . "',
						infl_horario = '" . DefaultHelper::acentos($this->infl_horario) . "',
						infl_leitura = '" . DefaultHelper::acentos($this->infl_leitura) . "',
						infl_retiradacarga = '" . DefaultHelper::acentos($this->infl_retiradacarga) . "',
						infl_incubacao = '" . DefaultHelper::acentos($this->infl_incubacao) . "',
						infl_horario_incubacao = '" . DefaultHelper::acentos($this->infl_horario_incubacao) . "',
						infl_data_incubacao = '" . DefaultHelper::acentos($this->infl_data_incubacao) . "',						
						infl_responfinal = '" . DefaultHelper::acentos($this->infl_responfinal) . "'
						
					WHERE infl_lote = '$this->infl_lote'";
			$res = mysql_query($sql);
			
			error_log($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->infl_lote . " em tmsd_infolote.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		
		
		
		
		
		
		
		
		
		
		} // This is the final of the class InfoLoteModel
?>