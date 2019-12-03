<?php
	class ConferentesModel extends Conexao{
		
		public $cnf_id;
		public $cnf_masterclient;
		public $cnf_nome;
		public $cnf_contato;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_conferentes (
						cnf_masterclient,
						cnf_nome,
						cnf_contato,
						cnf_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->cnf_nome) . "',
						'" . DefaultHelper::acentos($this->cnf_contato) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_conferentes.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_conferentes SET
						cnf_nome = '" . DefaultHelper::acentos($this->cnf_nome) . "',
						cnf_contato = '" . DefaultHelper::acentos($this->cnf_contato) . "' 
					WHERE cnf_id = " . $this->cnf_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->cnf_id . " em tmsd_conferentes.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_conferentes SET
						cnf_del = '*'
					WHERE cnf_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_conferentes.";
				$this->gravaLog();
				//
				// apaga também o usuário referente ao conferente, se existir
				$sql = "UPDATE tmsd_usuarios SET
							usu_del = '*'
						WHERE usu_nivel = '2' AND usu_referencia = " . $id;
				$res = mysql_query($sql);
				if($res) {
					// log
					$this->log_acao = "Exclusão: usuário referente ao conferente " . $id . " em tmsd_usuarios.";
					$this->gravaLog();
					//
				}
			}
			return $res;
		}
		
		public function selectConferente($id){
			$sql = "SELECT * FROM tmsd_conferentes WHERE cnf_id = " . $id . " AND cnf_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ConferentesModel();
			$obj->cnf_id = $row['cnf_id'];
			$obj->cnf_masterclient = $row['cnf_masterclient'];
			$obj->cnf_nome = $row['cnf_nome'];
			$obj->cnf_contato = $row['cnf_contato'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_conferentes 
					WHERE (cnf_del IS NULL OR cnf_del != '*') AND cnf_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY cnf_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ConferentesModel();
				$obj->cnf_id = $row['cnf_id'];
				$obj->cnf_masterclient = $row['cnf_masterclient'];
				$obj->cnf_nome = $row['cnf_nome'];
				$obj->cnf_contato = $row['cnf_contato'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>