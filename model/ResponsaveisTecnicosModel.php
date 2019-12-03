<?php
	class ResponsaveisTecnicosModel extends Conexao{
		
		public $rte_id;
		public $rte_masterclient;
		public $rte_nome;
		public $rte_contato;
		public $rte_coren;
		public $rte_admin;
		public $rte_permissao;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_responsaveistecnicos (
						rte_masterclient,
						rte_nome,
						rte_contato,
						rte_coren,
						rte_data, 
						rte_admin,
						rte_permissao
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->rte_nome) . "',
						'" . DefaultHelper::acentos($this->rte_contato) . "',
						'" . strtoupper($this->rte_coren) . "',
						'" . date('Y-m-d H:i:s') . "', 
						'" . $this->rte_admin . "',
						'" . $this->rte_permissao . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_responsaveistecnicos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_responsaveistecnicos SET
						rte_nome = '" . DefaultHelper::acentos($this->rte_nome) . "',
						rte_contato = '" . DefaultHelper::acentos($this->rte_contato) . "',
						rte_coren = '" . strtoupper($this->rte_coren) . "',
						rte_admin = '" . $this->rte_admin . "',
						rte_permissao = '" . $this->rte_permissao . "'
					WHERE rte_id = " . $this->rte_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->rte_id . " em tmsd_responsaveistecnicos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_responsaveistecnicos SET
						rte_del = '*'
					WHERE rte_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_responsaveistecnicos.";
				$this->gravaLog();
				//
				// apaga tambщm o usuсrio referente ao responsсvel tщcnico (administrador), se existir
				$sql = "UPDATE tmsd_usuarios SET
							usu_del = '*'
						WHERE usu_nivel = '4' AND usu_referencia = " . $id;
				$res = mysql_query($sql);
				if($res) {
					// log
					$this->log_acao = "Exclusуo: usuсrio referente ao responsсvel tщcnico (administrador) " . $id . " em tmsd_usuarios.";
					$this->gravaLog();
					//
				}
			}
			return $res;
		}
		
		public function selectRTecnico($id){
			$sql = "SELECT * FROM tmsd_responsaveistecnicos WHERE rte_id = " . $id . " AND rte_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			//error_log($sql);
			$obj = new ResponsaveisTecnicosModel();
			$obj->rte_id = $row['rte_id'];
			$obj->rte_masterclient = $row['rte_masterclient'];
			$obj->rte_nome = $row['rte_nome'];
			$obj->rte_contato = $row['rte_contato'];
			$obj->rte_coren = $row['rte_coren'];
			$obj->rte_admin = $row['rte_admin'];
			$obj->rte_permissao = $row['rte_permissao'];
			return $obj;
		}
		
		// mesma busca que selectRTecnico, porщm no banco Sterilab, para cruzamento de dados
		public function selectRTecnicoSterilab($id){
			$sql = "SELECT * FROM tmss_responsaveistecnicos WHERE rte_id = " . $id . " AND rte_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			//error_log($sql);
			$obj = new ResponsaveisTecnicosModel();
			$obj->rte_id = $row['rte_id'];
			$obj->rte_masterclient = $row['rte_masterclient'];
			$obj->rte_nome = $row['rte_nome'];
			$obj->rte_contato = $row['rte_contato'];
			$obj->rte_coren = $row['rte_coren'];
			$obj->rte_admin = $row['rte_admin'];
			$obj->rte_permissao = $row['rte_permissao'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_responsaveistecnicos 
					WHERE (rte_del IS NULL OR rte_del != '*') AND rte_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY rte_nome";
					
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ResponsaveisTecnicosModel();
				$obj->rte_id = $row['rte_id'];
				$obj->rte_masterclient = $row['rte_masterclient'];
				$obj->rte_nome = $row['rte_nome'];
				$obj->rte_contato = $row['rte_contato'];
				$obj->rte_coren = $row['rte_coren'];
				$obj->rte_admin = $row['rte_admin'];
				$obj->rte_permissao = $row['rte_permissao'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>