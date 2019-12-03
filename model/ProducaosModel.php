<?php
	class ProducaosModel extends Conexao{
		
		public $pcao_id;
		public $pcao_masterclient;
		public $pcao_nome;

		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_producao (
						pcao_masterclient,
						pcao_nome,
						pcao_data
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->pcao_nome) . "',
						'" . date('Y-m-d H:i:s') . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_producao.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_producao SET
						pcao_nome = '" . DefaultHelper::acentos($this->pcao_nome) . "'
					WHERE pcao_id = " . $this->pcao_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->pcao_id . " em tmsd_producao.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_producao SET
						pcao_del = '*'
					WHERE pcao_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_producao.";
				$this->gravaLog();
				//
				// apaga também o usuário referente ao conferente, se existir
				$sql = "UPDATE tmsd_usuarios SET
							usu_del = '*'
						WHERE usu_nivel = '8' AND usu_referencia = " . $id;
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
		
		public function selectProducao($id){
			$sql = "SELECT * FROM tmsd_producao WHERE pcao_id = " . $id . " AND pcao_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProducaosModel();
			$obj->pcao_id = $row['pcao_id'];
			$obj->pcao_masterclient = $row['pcao_masterclient'];
			$obj->pcao_nome = $row['pcao_nome'];
			return $obj;
		}
		
		public function selectAll($where){
			print_r($this->conecta());
			if(isset($where))
				$where = "AND " . $where;

			$sql = "SELECT * FROM tmsd_producao 
					WHERE (pcao_del IS NULL OR pcao_del != '*') AND pcao_masterclient = " . $_SESSION['usu_masterclient'] . " ORDER BY pcao_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProducaosModel();
				$obj->pcao_id = $row['pcao_id'];
				$obj->pcao_masterclient = $row['pcao_masterclient'];
				$obj->pcao_nome = $row['pcao_nome'];
				$a[] = $obj;
			}
			return $a;
		}
				
		public static function LastProduct(){
			$produtos = new ProdutosModel();
			return $produtos->LastProduct();
		}
		
	}
?>