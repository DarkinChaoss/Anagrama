<?php
	class SetoresModel extends Conexao{
		
		public $set_id;
		public $set_masterclient;
		public $set_nome;
		public $set_fazsolicitacao;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_setores (
						set_masterclient, 
						set_nome, 
						set_fazsolicitacao
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . DefaultHelper::acentos($this->set_nome) . "', 
						'" . $this->set_fazsolicitacao . "'
					)";
			$res = mysql_query($sql);
			$id = mysql_insert_id();
			$con = mysql_query("SELECT LAST_INSERT_ID()") or die ("PROBLEMAS COM A CONSULTA: " . mysql_error());
			$res = mysql_fetch_row($con);
			if($res) {
				// log
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_setores.";
				$this->gravaLog();
				//
			}
			return $res[0];
		}
		
		public function update(){
			$sql = "UPDATE tmsd_setores SET
						set_nome = '" . DefaultHelper::acentos($this->set_nome) . "', 
						set_fazsolicitacao = '" . $this->set_fazsolicitacao . "' 
					WHERE set_id = " . $this->set_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->set_id . " em tmsd_setores.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_setores SET
						set_del = '*'
					WHERE set_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_setores.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectSetor($id){
			$sql = "SELECT * FROM tmsd_setores WHERE set_id = " . $id . " AND set_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new SetoresModel();
			$obj->set_id = $row['set_id'];
			$obj->set_masterclient = $row['set_masterclient'];
			$obj->set_nome = $row['set_nome'];
			$obj->set_fazsolicitacao = $row['set_fazsolicitacao'];
			return $obj;
		}

		public function getNomeSetor($id){
			$sql = "SELECT set_nome FROM tmsd_setores WHERE set_id = " . $id . " AND set_del IS NULL";
			$res = mysql_query($sql);
			$result = mysql_result($res,0);
			return $result;
		}

		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_setores 
					WHERE set_masterclient = " . $_SESSION['usu_masterclient'] . " 
					" . $where . "
					AND set_del IS NULL 
					ORDER BY set_nome";
			$res = mysql_query($sql);
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SetoresModel();
				$obj->set_id = $row['set_id'];
				$obj->set_masterclient = $row['set_masterclient'];
				$obj->set_nome = $row['set_nome'];
				$obj->set_fazsolicitacao = $row['set_fazsolicitacao'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function getCmeId(){
			$sql = "SELECT set_id FROM tmsd_setores where set_nome = 'CME' and set_masterclient = ".$_SESSION['usu_masterclient']."";
			$res = mysql_query($sql);
			return mysql_result($res, 0);
		}
	}
?>