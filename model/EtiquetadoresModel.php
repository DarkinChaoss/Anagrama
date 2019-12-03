<?php
	class EtiquetadoresModel extends Conexao{
		
		public $eti_id;
		public $eti_masterclient;
		public $eti_nome;
		public $eti_contato;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_etiquetadores (
						eti_masterclient,
						eti_nome,
						eti_contato,
						eti_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->eti_nome) . "',
						'" . DefaultHelper::acentos($this->eti_contato) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_etiquetadores.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_etiquetadores SET
						eti_nome = '" . DefaultHelper::acentos($this->eti_nome) . "',
						eti_contato = '" . DefaultHelper::acentos($this->eti_contato) . "' 
					WHERE eti_id = " . $this->eti_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->eti_id . " em tmsd_etiquetadores.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_etiquetadores SET
						eti_del = '*'
					WHERE eti_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_etiquetadores.";
				$this->gravaLog();
				//
				// apaga também o usuário referente ao etiquetador, se existir
				$sql = "UPDATE tmsd_usuarios SET
							usu_del = '*'
						WHERE usu_nivel = '3' AND usu_referencia = " . $id;
				$res = mysql_query($sql);
				if($res) {
					// log
					$this->log_acao = "Exclusão: usuário referente ao etiquetador " . $id . " em tmsd_usuarios.";
					$this->gravaLog();
					//
				}
			}
			return $res;
		}
		
		public function selectEtiquetador($id){
			$sql = "SELECT * FROM tmsd_etiquetadores WHERE eti_id = " . $id . " AND eti_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new EtiquetadoresModel();
			$obj->eti_id = $row['eti_id'];
			$obj->eti_masterclient = $row['eti_masterclient'];
			$obj->eti_nome = $row['eti_nome'];
			$obj->eti_contato = $row['eti_contato'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_etiquetadores 
					WHERE (eti_del IS NULL OR eti_del != '*') AND eti_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY eti_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new EtiquetadoresModel();
				$obj->eti_id = $row['eti_id'];
				$obj->eti_masterclient = $row['eti_masterclient'];
				$obj->eti_nome = $row['eti_nome'];
				$obj->eti_contato = $row['eti_contato'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>