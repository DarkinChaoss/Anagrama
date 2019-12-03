<?php
	class TurnosTrabalhoModel extends Conexao{
		
		public $tur_id;
		public $tur_masterclient;
		public $tur_nome;
		public $tur_inicio;
		public $tur_fim;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_turnostrabalho (
						tur_masterclient, 
						tur_nome,
						tur_inicio,
						tur_fim
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . DefaultHelper::acentos($this->tur_nome) . "',
						'" . $this->tur_inicio . "',
						'" . $this->tur_fim . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_turnostrabalho.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_turnostrabalho SET
						tur_nome = '" . DefaultHelper::acentos($this->tur_nome) . "',
						tur_inicio = '" . $this->tur_inicio . "',
						tur_fim = '" . $this->tur_fim . "'
					WHERE tur_id = " . $this->tur_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->tur_id . " em tmsd_turnostrabalho.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_turnostrabalho SET
						tur_del = '*'
					WHERE tur_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_turnostrabalho.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectTurnoTrabalho($id){
			$sql = "SELECT * FROM tmsd_turnostrabalho WHERE tur_id = " . $id . " AND tur_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new TurnosTrabalhoModel();
			$obj->tur_id = $row['tur_id'];
			$obj->tur_masterclient = $row['tur_masterclient'];
			$obj->tur_nome = $row['tur_nome'];
			$obj->tur_inicio = $row['tur_inicio'];
			$obj->tur_fim = $row['tur_fim'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_turnostrabalho 
					WHERE tur_del IS NULL AND tur_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY tur_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new TurnosTrabalhoModel();
				$obj->tur_id = $row['tur_id'];
				$obj->tur_masterclient = $row['tur_masterclient'];
				$obj->tur_nome = $row['tur_nome'];
				$obj->tur_inicio = $row['tur_inicio'];
				$obj->tur_fim = $row['tur_fim'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function search($buscar){
			$sql = "SELECT * FROM tmsd_turnostrabalho 
					WHERE 
					(
						tur_nome LIKE '%" . $buscar . "%' 
					) 
					AND tur_del IS NULL 
					AND tur_masterclient = " . $_SESSION['usu_masterclient'] . " 
					ORDER BY tur_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new TurnosTrabalhoModel();
				$obj->tur_id = $row['tur_id'];
				$obj->tur_masterclient = $row['tur_masterclient'];
				$obj->tur_nome = $row['tur_nome'];
				$obj->tur_inicio = $row['tur_inicio'];
				$obj->tur_fim = $row['tur_fim'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>