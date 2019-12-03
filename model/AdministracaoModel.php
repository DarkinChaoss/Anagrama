<?php
	class AdministracaoModel extends Conexao{
		
		public $adm_id;
		public $adm_masterclient;
		public $adm_nome;

		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_administracao (
						adm_masterclient,
						adm_nome,
						adm_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->adm_nome) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_administracao.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_administracao SET
						adm_nome = '" . DefaultHelper::acentos($this->adm_nome) . "'
					WHERE adm_id = " . $this->adm_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->adm_id . " em tmsd_administracao.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_administracao SET
						adm_del = '*'
					WHERE adm_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_administracao.";
				$this->gravaLog();
				//
				// apaga também o usuário referente ao conferente, se existir
				$sql = "UPDATE tmsd_usuarios SET
							usu_del = '*'
						WHERE usu_nivel = '9' AND usu_referencia = " . $id;
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
		
		public function selectAdministracao($id){
			$sql = "SELECT * FROM tmsd_administracao WHERE adm_id = " . $id . " AND adm_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new AdministracaoModel();
			$obj->adm_id = $row['adm_id'];
			$obj->adm_masterclient = $row['adm_masterclient'];
			$obj->adm_nome = $row['adm_nome'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;

			$sql = "SELECT * FROM tmsd_administracao 
					WHERE (adm_del IS NULL OR adm_del != '*') AND adm_masterclient = " . $_SESSION['usu_masterclient'] . " ORDER BY adm_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new AdministracaoModel();
				$obj->adm_id = $row['adm_id'];
				$obj->adm_masterclient = $row['adm_masterclient'];
				$obj->adm_nome = $row['adm_nome'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>