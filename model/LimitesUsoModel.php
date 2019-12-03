<?php
	class LimitesUsoModel extends Conexao{

		public $liu_id;
		public $liu_masterclient;
		//public $liu_descricao;
		public $liu_qtde;
		public $liu_description;
		public $liu_periodo;
		public $liu_ultimo;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_limitesuso (
						liu_masterclient,
						liu_qtde,
						liu_description,
						liu_periodo,
						liu_ultimo
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $this->liu_qtde . "',
						'" . DefaultHelper::acentos($this->liu_description) . "',
						'" . $this->liu_periodo. "',
						'" . $this->liu_ultimo . "'
					)";
			$res = mysql_query($sql) or die(mysql_error());
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_limiteuso.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function update(){
			$sql = "UPDATE tmsd_limitesuso SET
						liu_qtde = '". $this->liu_qtde ."',
						liu_description = '" . DefaultHelper::acentos($this->liu_description) . "',
						liu_periodo = '". $this->liu_periodo ."',
						liu_ultimo = '" . $this->liu_ultimo . "'
					WHERE liu_id = " . $this->liu_id;
			$res = mysql_query($sql);
			error_log($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->liu_id . " em tmsd_limiteuso.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delete($id){
			$sql = "UPDATE tmsd_limitesuso SET
						liu_del = '*'
					WHERE liu_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_limitesuso.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectLimiteUso($id){
			$sql = "SELECT * FROM tmsd_limitesuso WHERE liu_id = " . $id . " AND liu_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new LimitesUsoModel();
			$obj->liu_id = $row['liu_id'];
			$obj->liu_masterclient = $row['liu_masterclient'];
			$obj->liu_qtde = $row['liu_qtde'];
			$obj->liu_description = $row['liu_description'];
			$obj->liu_periodo = $row['liu_periodo'];
			$obj->liu_ultimo = $row['liu_ultimo'];
			return $obj;
		}

		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_limitesuso
					WHERE liu_del IS NULL AND liu_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . "
					ORDER BY liu_description";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new LimitesUsoModel();
				$obj->liu_id = $row['liu_id'];
				$obj->liu_masterclient = $row['liu_masterclient'];
				$obj->liu_qtde = $row['liu_qtde'];
				$obj->liu_description = $row['liu_description'];
				$obj->liu_periodo = $row['liu_periodo'];
				$obj->liu_ultimo = $row['liu_ultimo'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectUltimoLimiteUso(){
		    $sql = "SELECT * FROM tmsd_limitesuso WHERE liu_ultimo = '1' AND liu_del IS NULL";
		    $res = mysql_query($sql);
		    $row = mysql_fetch_array($res, MYSQL_ASSOC);

		    $obj = new LimitesUsoModel();
		    $obj->liu_id = $row['liu_id'];
		    $obj->liu_masterclient = $row['liu_masterclient'];
		    $obj->liu_qtde = $row['liu_qtde'];
			$obj->liu_description = $row['liu_description'];
			$obj->liu_periodo = $row['liu_periodo'];
		    $obj->liu_ultimo = $row['liu_ultimo'];

		    return $obj;
		}

		public function limpaUltimo(){
			$sql = "UPDATE tmsd_limitesuso SET
						liu_ultimo = ''
					WHERE liu_ultimo = '1'";
			$res = mysql_query($sql);
			return $res;
		}

	}
?>