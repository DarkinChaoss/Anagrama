<?php
	class ContatosModel extends Conexao{
		
		public $con_id;
		public $con_idsetor;
		public $con_nome;
		public $con_email;
		public $con_telefone;
		public $con_principal;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_contatos (
						con_idsetor,
						con_nome,
						con_email,
						con_telefone,
						con_principal
					) VALUES (
						" . $this->con_idsetor . ",
						'" . DefaultHelper::acentos($this->con_nome) . "',
						'" . DefaultHelper::acentos($this->con_email, 2) . "',
						'" . DefaultHelper::acentos($this->con_telefone) . "',
						'" . $this->con_principal . "'
					)";
			error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: contato " . $id . " pertencente ao setor " . $this->con_idsetor . " em tmsd_contatos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_contatos SET
						con_idsetor = " . $this->con_idsetor . ",
						con_nome = '" . DefaultHelper::acentos($this->con_nome) . "',
						con_email = '" . DefaultHelper::acentos($this->con_email, 2) . "',
						con_telefone = '" . DefaultHelper::acentos($this->con_telefone) . "',
						con_principal = '" . $this->con_principal . "'
					WHERE con_id = " . $this->con_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: contato " . $this->con_id . " pertencente ao setor " . $this->con_idsetor . " em tmsd_contatos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_contatos SET
						con_del = '*'
					WHERE con_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_contatos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectContato($id){
			$sql = "SELECT * FROM tmsd_contatos WHERE con_id = " . $id . " AND con_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ContatosModel();
			$obj->con_id = $row['con_id'];
			$obj->con_idsetor = $row['con_idsetor'];
			$obj->con_nome = $row['con_nome'];
			$obj->con_email = $row['con_email'];
			$obj->con_telefone = $row['con_telefone'];
			$obj->con_principal = $row['con_principal'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_contatos WHERE con_del IS NULL " . $where . " ORDER BY con_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ContatosModel();
				$obj->con_id = $row['con_id'];
				$obj->con_idsetor = $row['con_idsetor'];
				$obj->con_nome = $row['con_nome'];
				$obj->con_email = $row['con_email'];
				$obj->con_telefone = $row['con_telefone'];
				$obj->con_principal = $row['con_principal'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>