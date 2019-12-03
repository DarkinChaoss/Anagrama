<?php
	class ConveniosModel extends Conexao{
		
		public $cvn_id;
		public $cvn_masterclient;
		public $cvn_nome;
		public $cvn_obs;
		public $cvn_ativo;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_convenios (
						cvn_masterclient,
						cvn_nome,
						cvn_obs,
						cvn_ativo
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->cvn_nome) . "', 
						'" . DefaultHelper::acentos($this->cvn_obs) . "',
						'" . DefaultHelper::acentos($this->cvn_ativo) . "' 
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_convenios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_convenios SET
						cvn_nome = '" . DefaultHelper::acentos($this->cvn_nome) . "', 
						cvn_obs = '" . DefaultHelper::acentos($this->cvn_obs) . "',
						cvn_ativo = '" . DefaultHelper::acentos($this->cvn_ativo) . "' 
					WHERE cvn_id = " . $this->cvn_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->cvn_id . " em tmsd_convenios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_convenios SET
						cvn_del = '*'
					WHERE cvn_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_convenios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectConvenio($id){
			$sql = "SELECT * FROM tmsd_convenios WHERE cvn_id = " . $id . " AND cvn_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ConveniosModel();
			$obj->cvn_id = $row['cvn_id'];
			$obj->cvn_masterclient = $row['cvn_masterclient'];
			$obj->cvn_nome = $row['cvn_nome'];
			$obj->cvn_obs = $row['cvn_obs'];
			$obj->cvn_ativo = $row['cvn_ativo'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_convenios 
					WHERE cvn_del IS NULL AND cvn_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY cvn_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ConveniosModel();
				$obj->cvn_id = $row['cvn_id'];
				$obj->cvn_masterclient = $row['cvn_masterclient'];
				$obj->cvn_nome = $row['cvn_nome'];
				$obj->cvn_obs = $row['cvn_obs'];
				$obj->cvn_ativo = $row['cvn_ativo'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>