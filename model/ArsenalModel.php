<?php
	class ArsenalModel extends Conexao{
		
		public $ars_id;
		public $ars_masterclient;
		public $ars_nome;
		public $ars_contato;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_arsenal (
						ars_masterclient,
						ars_nome,
						ars_contato,
						ars_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->ars_nome) . "',
						'" . DefaultHelper::acentos($this->ars_contato) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_arsenal.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_arsenal SET
						ars_nome = '" . DefaultHelper::acentos($this->ars_nome) . "',
						ars_contato = '" . DefaultHelper::acentos($this->ars_contato) . "' 
					WHERE ars_id = " . $this->ars_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->ars_id . " em tmsd_arsenal.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_arsenal SET
						ars_del = '*'
					WHERE ars_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_arsenal.";
				$this->gravaLog();
			}
			return $res;
		}
		
		public function selectArsenal($id){
		  
			$sql = "SELECT * FROM tmsd_arsenal WHERE ars_id = " . $id . " AND ars_del IS NULL";
            
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ArsenalModel();
            
			$obj->ars_id = $row['ars_id'];
			$obj->ars_masterclient = $row['ars_masterclient'];
			$obj->ars_nome = $row['ars_nome'];
			$obj->ars_contato = $row['ars_contato'];
            
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_arsenal 
					WHERE (ars_del IS NULL OR ars_del != '*') AND ars_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY ars_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
			 
				$obj = new ArsenalModel();
				$obj->ars_id = $row['ars_id'];
				$obj->ars_masterclient = $row['ars_masterclient'];
				$obj->ars_nome = $row['ars_nome'];
				$obj->ars_contato = $row['ars_contato'];
				$a[] = $obj;
                
			}
			return $a;
		}
		
	}
?>