<?php
	class NomesProdutosModel extends Conexao{
		
		public $nop_id;
		public $nop_masterclient;
		public $nop_nome;

		public $nop_img_url;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_nomesprodutos (
						nop_masterclient, 
						nop_nome,
						nop_img_url
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . DefaultHelper::acentos($this->nop_nome) . "',
						'" . $this->nop_img_url . "'
					)";
			$res = mysql_query($sql);
			return $res;
		}

		public function insertHoldId(){
			$sql = "INSERT INTO tmsd_nomesprodutos (
						nop_masterclient, 
						nop_nome
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "', 
						'" . DefaultHelper::acentos($this->nop_nome) . "'
					)";
			mysql_query($sql);
			return mysql_insert_id(); 
		}
		
		public function update(){
			$sql = "UPDATE tmsd_nomesprodutos SET
						nop_nome = '" . DefaultHelper::acentos($this->nop_nome) . "',
						nop_img_url = '" . $this->nop_img_url . "'
					WHERE nop_id = " . $this->nop_id;
			$res = mysql_query($sql);

			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_nomesprodutos SET
						nop_del = '*'
					WHERE nop_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_nomesprodutos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delNotUsed(){
			$sql = "DELETE FROM tmsd_nomesprodutos WHERE
						nop_nome = '' OR nop_nome is Null";
			$res = mysql_query($sql);
			return $res;
		}

		
		public function selectNomeProduto($id){
			$sql = "SELECT * FROM tmsd_nomesprodutos WHERE nop_id = " . $id . " AND nop_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new NomesProdutosModel();
			$obj->nop_id = $row['nop_id'];
			$obj->nop_masterclient = $row['nop_masterclient'];
			$obj->nop_nome = $row['nop_nome'];
			return $obj;
		}
		
		public function selectAll($where){
                      		  
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_nomesprodutos 
					WHERE nop_del IS NULL AND nop_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY nop_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new NomesProdutosModel();
				$obj->nop_id = $row['nop_id'];
				$obj->nop_masterclient = $row['nop_masterclient'];
				$obj->nop_nome = $row['nop_nome'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function search($buscar){
			$sql = "SELECT * FROM tmsd_nomesprodutos 
					WHERE 
					(
						nop_nome LIKE '%" . $buscar . "%' 
					) 
					AND nop_del IS NULL 
					AND nop_masterclient = " . $_SESSION['usu_masterclient'] . " 
					ORDER BY nop_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new NomesProdutosModel();
				$obj->nop_id = $row['nop_id'];
				$obj->nop_masterclient = $row['nop_masterclient'];
				$obj->nop_nome = $row['nop_nome'];
				$a[] = $obj;
			}
			return $a;
		}


		public function getIdByName($name){
			$sql = "SELECT nop_id FROM tmsd_nomesprodutos where nop_nome = '$name' and nop_del is null";
			return mysql_result(mysql_query($sql), 0);
		}

	}
?>