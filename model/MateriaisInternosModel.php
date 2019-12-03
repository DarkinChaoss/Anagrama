<?php
	class MateriaisInternosModel extends Conexao{
		
		public $mai_id;
		public $mai_masterclient;
		public $mai_cod;
		public $mai_nome;
		public $mai_qtde;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_materiaisinternos (
						mai_masterclient, 
						mai_cod, 
						mai_nome, 
						mai_qtde
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . $this->mai_cod . "',
						'" . DefaultHelper::acentos($this->mai_nome) . "', 
						" . $this->mai_qtde . "
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_materiaisinternos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_materiaisinternos SET
						mai_cod = '" . $this->mai_cod . "',
						mai_nome = '" . DefaultHelper::acentos($this->mai_nome) . "',
						mai_qtde = " . $this->mai_qtde . "
					WHERE mai_id = " . $this->mai_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->mai_id . " em tmsd_materiaisinternos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_materiaisinternos SET
						mai_del = '*'
					WHERE mai_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_materiaisinternos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectMaterialInterno($id){
			$sql = "SELECT * FROM tmsd_materiaisinternos WHERE mai_id = " . $id . " AND mai_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new MateriaisInternosModel();
			$obj->mai_id = $row['mai_id'];
			$obj->mai_masterclient = $row['mai_masterclient'];
			$obj->mai_cod = $row['mai_cod'];
			$obj->mai_nome = $row['mai_nome'];
			$obj->mai_qtde = $row['mai_qtde'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_materiaisinternos 
					WHERE mai_del IS NULL AND mai_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY mai_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new MateriaisInternosModel();
				$obj->mai_id = $row['mai_id'];
				$obj->mai_masterclient = $row['mai_masterclient'];
				$obj->mai_cod = $row['mai_cod'];
				$obj->mai_nome = $row['mai_nome'];
				$obj->mai_qtde = $row['mai_qtde'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function search($buscar){
			$sql = "SELECT * FROM tmsd_materiaisinternos 
					WHERE 
					(
						mai_nome LIKE '%" . $buscar . "%'
						OR
						mai_cod LIKE '%" . $buscar . "%'
					) 
					AND mai_del IS NULL 
					AND mai_masterclient = " . $_SESSION['usu_masterclient'] . " 
					ORDER BY mai_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new MateriaisInternosModel();
				$obj->mai_id = $row['mai_id'];
				$obj->mai_masterclient = $row['mai_masterclient'];
				$obj->mai_cod = $row['mai_cod'];
				$obj->mai_nome = $row['mai_nome'];
				$obj->mai_qtde = $row['mai_qtde'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>