<?php
	class SolicitacoesEmprestimoModel extends Conexao{
		
		public $sem_id;
		public $sem_masterclient;
		public $sem_data;
		public $sem_idsetor;
		public $sem_nomesolicitante;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_solicitacaoemprestimo (
						sem_masterclient, 
						sem_data,
						sem_idsetor, 
						sem_nomesolicitante
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . $this->sem_data . "',
						'" . $this->sem_idsetor . "',
						'" . DefaultHelper::acentos($this->sem_nomesolicitante) . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_solicitacaoemprestimo.";
				$this->gravaLog();
				//
			} else {
				$id = 0;
			}
			return $id;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_solicitacaoemprestimo SET
						sem_nomesolicitante = '" . DefaultHelper::acentos($this->sem_nomesolicitante) . "'
					WHERE sem_id = " . $this->sem_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->sem_id . " em tmsd_solicitacaoemprestimo.";
				$this->gravaLog();
				//
			}
			return $this->sem_id;
		}
		
		public function delete($id){
			$sql = "DELETE FROM tmsd_solicitacaoemprestimo 
					WHERE sem_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// apaga os itens relacionados ao emprщstimo
				$sql = "DELETE FROM tmsd_itensemprestimo 
						WHERE iem_idsem = " . $id;
				$res2 = mysql_query($sql);
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_solicitacaoemprestimo.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectSolicitacaoEmprestimo($id){
			$sql = "SELECT * FROM tmsd_solicitacaoemprestimo WHERE sem_id = " . $id . " AND sem_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new SolicitacoesEmprestimoModel();
			$obj->sem_id = $row['sem_id'];
			$obj->sem_masterclient = $row['sem_masterclient'];
			$obj->sem_data = $row['sem_data'];
			$obj->sem_idsetor = $row['sem_idsetor'];
			$obj->sem_nomesolicitante = $row['sem_nomesolicitante'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_solicitacaoemprestimo 
					WHERE sem_del IS NULL AND sem_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY sem_data";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SolicitacoesEmprestimoModel();
				$obj->sem_id = $row['sem_id'];
				$obj->sem_masterclient = $row['sem_masterclient'];
				$obj->sem_data = $row['sem_data'];
				$obj->sem_idsetor = $row['sem_idsetor'];
				$obj->sem_nomesolicitante = $row['sem_nomesolicitante'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function search($buscar){
			$sql = "SELECT * FROM tmsd_solicitacaoemprestimo 
					WHERE 
					(
						sem_nome LIKE '%" . $buscar . "%' 
					) 
					AND sem_del IS NULL 
					AND sem_masterclient = " . $_SESSION['usu_masterclient'] . " 
					ORDER BY sem_data";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SolicitacoesEmprestimoModel();
				$obj->sem_id = $row['sem_id'];
				$obj->sem_masterclient = $row['sem_masterclient'];
				$obj->sem_data = $row['sem_data'];
				$obj->sem_idsetor = $row['sem_idsetor'];
				$obj->sem_nomesolicitante = $row['sem_nomesolicitante'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>