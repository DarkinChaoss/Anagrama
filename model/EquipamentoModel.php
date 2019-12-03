<?php
	class EquipamentoModel extends Conexao{

		public $eq_id;
		public $eq_masterclient;
		public $eq_descricao;
		public $eq_enzimatico;
		public $eq_neutro;
		public $eq_equitipo;
		public $eq_formatoimp;
		public $eq_ultimo;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_equipamento (
						eq_masterclient,
						eq_descricao,
						eq_enzimatico,
						eq_neutro,
						eq_equitipo,
						eq_formatoimp,
						eq_ultimo
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->eq_descricao) . "',
						'" . DefaultHelper::acentos($this->eq_enzimatico) . "',
						'" . DefaultHelper::acentos($this->eq_neutro) . "',
						'" . DefaultHelper::acentos($this->eq_equitipo) . "',
						'" . DefaultHelper::acentos($this->eq_formatoimp) . "',
						'" . $this->eq_ultimo . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_equipamento.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function update(){
			$sql = "UPDATE tmsd_equipamento SET
						eq_descricao = '" . DefaultHelper::acentos($this->eq_descricao) . "',
						eq_enzimatico = '" . DefaultHelper::acentos($this->eq_enzimatico) . "',
						eq_neutro = '" . DefaultHelper::acentos($this->eq_neutro) . "',
						eq_equitipo = '" . DefaultHelper::acentos($this->eq_equitipo) . "',
						eq_formatoimp = '" . DefaultHelper::acentos($this->eq_formatoimp) . "',
						eq_ultimo = '" . $this->eq_ultimo . "'
					WHERE eq_id = " . $this->eq_id;
			$res = mysql_query($sql);
			error_log($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->eq_id . " em tmsd_equipamento.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delete($id){
			$sql = "UPDATE tmsd_equipamento SET
						eq_del = '*'
					WHERE eq_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_equipamento.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectEquipamento($id){
			$sql = "SELECT * FROM tmsd_equipamento WHERE eq_id = " . $id . " AND eq_del IS NULL";
			error_log('getEquipamento - '.$sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new EquipamentoModel();
			$obj->eq_id = $row['eq_id'];
			$obj->eq_masterclient = $row['eq_masterclient'];
			$obj->eq_descricao = $row['eq_descricao'];
			$obj->eq_enzimatico = $row['eq_enzimatico'];
			$obj->eq_neutro = $row['eq_neutro'];
			$obj->eq_equitipo = $row['eq_equitipo'];
			$obj->eq_formatoimp = $row['eq_formatoimp'];
			$obj->eq_ultimo = $row['eq_ultimo'];
			return $obj;
		}

		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_equipamento
					WHERE eq_del IS NULL AND eq_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . "
					ORDER BY eq_descricao";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new EquipamentoModel();
				$obj->eq_id = $row['eq_id'];
				$obj->eq_masterclient = $row['eq_masterclient'];
				$obj->eq_descricao = $row['eq_descricao'];
				$obj->eq_enzimatico = $row['eq_enzimatico'];
				$obj->eq_neutro = $row['eq_neutro'];
				$obj->eq_ultimo = $row['eq_ultimo'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectUltimoEquipamento(){
		    $sql = "SELECT * FROM tmsd_equipamento WHERE eq_ultimo = '1' AND eq_del IS NULL";
		    $res = mysql_query($sql);
		    $row = mysql_fetch_array($res, MYSQL_ASSOC);

		    $obj = new EquipamentoModel();
		    $obj->eq_id = $row['eq_id'];
		    $obj->eq_masterclient = $row['eq_masterclient'];
		    $obj->eq_descricao = $row['eq_descricao'];
		    $obj->eq_ultimo = $row['eq_ultimo'];

		    return $obj;
		}

		public function limpaUltimo(){
			$sql = "UPDATE tmsd_equipamento SET
						eq_ultimo = ''
					WHERE eq_ultimo = '1'";
			$res = mysql_query($sql);
			return $res;
		}
		
		
		//metodo para seleccionar enzimatico
		
		     public function selectEquipamento_standar($id){
			$sql = "SELECT * FROM tmsd_equipamento WHERE eq_id = " . $id . " AND eq_del IS NULL";
			error_log('getEquipamento - '.$sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new EquipamentoModel();
			$obj->eq_id = $row['eq_id'];
			$obj->eq_masterclient = $row['eq_masterclient'];
			$obj->eq_descricao = $row['eq_descricao'];
			$obj->eq_enzimatico = $row['eq_enzimatico'];
			$obj->eq_neutro = $row['eq_neutro'];
			$obj->eq_ultimo = $row['eq_ultimo'];
			return $obj;
		}
		
		//----

	}
?>