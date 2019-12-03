<?php
	class GruposMateriaisModel extends Conexao{
		
		public $gma_id;
		public $gma_masterclient;
		public $gma_nome;
		public $gma_obs;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_gruposmateriais (
						gma_masterclient,
						gma_nome,
						gma_obs
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . DefaultHelper::acentos($this->gma_nome) . "',
						'" . DefaultHelper::acentos($this->gma_obs) . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_gruposmateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_gruposmateriais SET
						gma_nome = '" . DefaultHelper::acentos($this->gma_nome) . "',
						gma_obs = '" . DefaultHelper::acentos($this->gma_obs) . "'
					WHERE gma_id = " . $this->gma_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->gma_id . " em tmsd_gruposmateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_gruposmateriais SET
						gma_del = '*'
					WHERE gma_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_gruposmateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectMetodo($id){
			$sql = "SELECT * FROM tmsd_gruposmateriais WHERE gma_id = " . $id . " AND gma_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			//error_log("grupo --> " . $sql);
			$obj = new GruposMateriaisModel();
			$obj->gma_id = $row['gma_id'];
			$obj->gma_masterclient = $row['gma_masterclient'];
			$obj->gma_nome = $row['gma_nome'];
			$obj->gma_obs = $row['gma_obs'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_gruposmateriais 
					WHERE gma_del IS NULL AND gma_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY gma_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new GruposMateriaisModel();
				$obj->gma_id = $row['gma_id'];
				$obj->gma_masterclient = $row['gma_masterclient'];
				$obj->gma_nome = $row['gma_nome'];
				$obj->gma_obs = $row['gma_obs'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>