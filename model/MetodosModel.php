<?php
	class MetodosModel extends Conexao{
		
		public $met_id;
		public $met_masterclient;
		public $met_nome;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_metodos (
						met_masterclient,
						met_nome,
						met_descricao
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->met_nome) . "',
						'" . DefaultHelper::acentos($this->met_descricao) . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_metodos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_metodos SET
						met_nome = '" . DefaultHelper::acentos($this->met_nome) . "',
						met_descricao = '" . DefaultHelper::acentos($this->met_descricao) . "'
					WHERE met_id = " . $this->met_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->met_id . " em tmsd_metodos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_metodos SET
						met_del = '*'
					WHERE met_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_metodos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectMetodo($id){
			$sql = "SELECT * FROM tmsd_metodos WHERE met_id = " . $id . " AND met_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new MetodosModel();
			$obj->met_id = $row['met_id'];
			$obj->met_masterclient = $row['met_masterclient'];
			$obj->met_nome = $row['met_nome'];
			$obj->met_descricao = $row['met_descricao'];
			return $obj;
		}
		
		// mesma busca que selectMetodo, porщm no banco Sterilab, para cruzamento de dados
		public function selectMetodoSterilab($id){
			$sql = "SELECT * FROM tmss_metodos WHERE met_id = " . $id . " AND met_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new MetodosModel();
			$obj->met_id = $row['met_id'];
			$obj->met_masterclient = $row['met_masterclient'];
			$obj->met_nome = $row['met_nome'];
			$obj->met_descricao = $row['met_descricao'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_metodos 
					WHERE met_del IS NULL AND met_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY met_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new MetodosModel();
				$obj->met_id = $row['met_id'];
				$obj->met_masterclient = $row['met_masterclient'];
				$obj->met_nome = $row['met_nome'];
				$obj->met_descricao = $row['met_descricao'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>