<?php
	class CirculanteModel extends Conexao{
		
		public $cir_id;
		public $cir_masterclient;
		public $cir_nome;
		public $cir_contato;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_circulante (
						cir_masterclient,
						cir_nome,
						cir_contato,
						cir_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->cir_nome) . "',
						'" . DefaultHelper::acentos($this->cir_contato) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_circulante.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_circulante SET
						cir_nome = '" . DefaultHelper::acentos($this->cir_nome) . "',
						cir_contato = '" . DefaultHelper::acentos($this->cir_contato) . "' 
					WHERE cir_id = " . $this->cir_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->cir_id . " em tmsd_circulante.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_circulante SET
						cir_del = '*'
					WHERE cir_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_circulante.";
				$this->gravaLog();
			}
			return $res;
		}
		
		public function selectCirculante($id){
			$sql = "SELECT * FROM tmsd_circulante WHERE cir_id = " . $id . " AND cir_del IS NULL";
			
            $res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new CirculanteModel();
			
            $obj->cir_id = $row['cir_id'];
			$obj->cir_masterclient = $row['cir_masterclient'];
			$obj->cir_nome = $row['cir_nome'];
			$obj->cir_contato = $row['cir_contato'];
            
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_circulante 
					WHERE (cir_del IS NULL OR cir_del != '*') AND cir_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY cir_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new CirculanteModel();
				$obj->cir_id = $row['cir_id'];
				$obj->cir_masterclient = $row['cir_masterclient'];
				$obj->cir_nome = $row['cir_nome'];
				$obj->cir_contato = $row['cir_contato'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>