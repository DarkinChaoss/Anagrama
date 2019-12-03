<?php
	class OcorrenciasModel extends Conexao{
		
		public $oco_id;
		public $oco_masterclient;
		public $oco_sigla;
		public $oco_nome;
		public $oco_descricao;
		public $oco_descarte;
		public $oco_efeitoespecial;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_ocorrencias (
						oco_masterclient,
						oco_sigla,
						oco_nome,
						oco_descricao,
						oco_descarte, 
						oco_efeitoespecial
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . DefaultHelper::acentos($this->oco_sigla) . "',
						'" . DefaultHelper::acentos($this->oco_nome) . "',
						'" . DefaultHelper::acentos($this->oco_descricao) . "',
						'" . $this->oco_descarte . "', 
						'" . $this->oco_efeitoespecial . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_ocorrencias.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_ocorrencias SET
						oco_sigla = '" . DefaultHelper::acentos($this->oco_sigla) . "',
						oco_nome = '" . DefaultHelper::acentos($this->oco_nome) . "',
						oco_descricao = '" . DefaultHelper::acentos($this->oco_descricao) . "',
						oco_descarte = '" . $this->oco_descarte . "',
						oco_efeitoespecial = '" . $this->oco_efeitoespecial . "'
					WHERE oco_id = " . $this->oco_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->oco_id . " em tmsd_ocorrencias.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_ocorrencias SET
						oco_del = '*'
					WHERE oco_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_ocorrencias.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectOcorrencia($id){
			$sql = "SELECT * FROM tmsd_ocorrencias WHERE oco_id = " . $id . " AND oco_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new OcorrenciasModel();
			$obj->oco_id = $row['oco_id'];
			$obj->oco_masterclient = $row['oco_masterclient'];
			$obj->oco_sigla = $row['oco_sigla'];
			$obj->oco_nome = $row['oco_nome'];
			$obj->oco_descricao = $row['oco_descricao'];
			$obj->oco_descarte = $row['oco_descarte'];
			$obj->oco_efeitoespecial = $row['oco_efeitoespecial'];
			return $obj;
		}
		
		// mesma busca que selectOcorrencia, porém no banco Sterilab, para cruzamento de dados
		public function selectOcorrenciaSterilab($id){
			$sql = "SELECT * FROM tmss_ocorrencias WHERE oco_id = " . $id . " AND oco_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new OcorrenciasModel();
			$obj->oco_id = $row['oco_id'];
			$obj->oco_masterclient = $row['oco_masterclient'];
			$obj->oco_sigla = $row['oco_sigla'];
			$obj->oco_nome = $row['oco_nome'];
			$obj->oco_descricao = $row['oco_descricao'];
			$obj->oco_descarte = $row['oco_descarte'];
			$obj->oco_efeitoespecial = $row['oco_efeitoespecial'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_ocorrencias 
					WHERE oco_del IS NULL AND oco_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " 
					ORDER BY oco_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasModel();
				$obj->oco_id = $row['oco_id'];
				$obj->oco_masterclient = $row['oco_masterclient'];
				$obj->oco_sigla = $row['oco_sigla'];
				$obj->oco_nome = $row['oco_nome'];
				$obj->oco_descricao = $row['oco_descricao'];
				$obj->oco_descarte = $row['oco_descarte'];
				$obj->oco_efeitoespecial = $row['oco_efeitoespecial'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectAllIndMasterclient($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_ocorrencias
					WHERE oco_del IS NULL " . $where . "
					ORDER BY oco_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasModel();
				$obj->oco_id = $row['oco_id'];
				$obj->oco_masterclient = $row['oco_masterclient'];
				$obj->oco_sigla = $row['oco_sigla'];
				$obj->oco_nome = $row['oco_nome'];
				$obj->oco_descricao = $row['oco_descricao'];
				$obj->oco_descarte = $row['oco_descarte'];
				$obj->oco_efeitoespecial = $row['oco_efeitoespecial'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/
?>