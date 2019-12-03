<?php
	class ItensEmprestimoModel extends Conexao{
		
		public $iem_id;
		public $iem_idsem;
		public $iem_idmai;
		public $iem_turno;
		public $iem_qtdeentregue;
		public $iem_qtdesujo;
		public $iem_qtdesemuso;
		// auxiliares
		public $iem_mai_cod;
		public $iem_mai_nome;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_itensemprestimo (
						iem_idsem,
						iem_idmai,
						iem_turno,
						iem_qtdeentregue
					) VALUES (
						'" . $this->iem_idsem . "',
						'" . $this->iem_idmai . "',
						'" . $this->iem_turno. "',
						'" . $this->iem_qtdeentregue . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: item " . $id . " pertencente a solicitaчуo " . $this->iem_idsem . " em tmsd_itensemprestimo.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_itensemprestimo SET
						iem_qtdesujo = " . $this->iem_qtdesujo . ",
						iem_qtdesemuso = '" . $this->iem_qtdesemuso . "'
					WHERE iem_id = " . $this->iem_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: item " . $this->iem_id . " pertencente a solicitaчуo " . $this->iem_idsem . " em tmsd_usuarios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "DELETE FROM tmsd_itensemprestimo
					WHERE iem_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_itensemprestimo.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectItem($iem_id){
			$sql = "SELECT * FROM tmsd_itensemprestimo
					WHERE iem_id = " . $iem_id . " AND iem_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensEmprestimoModel();
			$obj->iem_id = $row['iem_id'];
			$obj->iem_idsem = $row['iem_idsem'];
			$obj->iem_idmai = $row['iem_idmai'];
			$obj->iem_turno = $row['iem_turno'];
			$obj->iem_qtdeentregue = $row['iem_qtdeentregue'];
			$obj->iem_qtdesujo = $row['iem_qtdesujo'];
			$obj->iem_qtdesemuso = $row['iem_qtdesemuso'];
			return $obj;
		}
		
		public function selectAll($buscar, $where){
			if(isset($where) && $where != "")
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_itensemprestimo AS iem
					INNER JOIN tmsd_solicitacaoemprestimo AS sem ON (iem_idsem = sem_id)
					INNER JOIN tmsd_materiaisinternos AS mai ON (iem_idmai = mai_id)
					WHERE iem_del IS NULL 
					AND sem_masterclient = " . $_SESSION['usu_masterclient'] . " 
					" . $where . " 
					AND
					(
						sem_nomesolicitante LIKE '%" . $buscar . "%'
						OR
						mai_cod LIKE '%" . $buscar . "%'
						OR
						mai_nome LIKE '%" . $buscar . "%'
					)
					ORDER BY sem_data, sem_id";
			$res = mysql_query($sql);
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensEmprestimoModel();
				$obj->iem_id = $row['iem_id'];
				$obj->iem_idsem = $row['iem_idsem'];
				$obj->iem_idmai = $row['iem_idmai'];
				$obj->iem_turno = $row['iem_turno'];
				$obj->iem_qtdeentregue = $row['iem_qtdeentregue'];
				$obj->iem_qtdesujo = $row['iem_qtdesujo'];
				$obj->iem_qtdesemuso = $row['iem_qtdesemuso'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>