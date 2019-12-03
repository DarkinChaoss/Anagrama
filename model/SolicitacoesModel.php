<?php
	class SolicitacoesModel extends Conexao{

		public $ses_id;
		public $ses_masterclient;
		public $ses_idsetor;
		public $ses_lote;
		public $ses_dataesterilizacao;
		public $ses_dataentrada;
		public $ses_datasaida;
		public $ses_status;
		// auxiliar
		public $ses_iso_lote;
		public $ses_iso_id;
		public $ses_iso_dataesterilizacao;
		public $ses_iso_horaesterilizacao;
		public $ses_iso_data;
		public $ses_pro_nome;
		public $ses_pro_qrcode;
		public $ses_set_nome;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_solicitacaoesterilizacao (
						ses_masterclient,
						ses_idsetor,
						ses_dataentrada,
						ses_status
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $this->ses_idsetor . "',
						'" . date('Y-m-d H:i:s') . "',
						'" . $this->ses_status . "'
					)";
			$executa = mysql_query($sql);
			if ($executa) {
				$id = mysql_insert_id();
				// log
				$this->log_acao = "Inserido registro " . $id . " em tmsd_solicitacaoesterilizacao.";
				$this->gravaLog();
				//
			} else {
				$id = 0;
			}
			return $id;
		}

		public function update(){
			$sql = "UPDATE tmsd_solicitacaoesterilizacao SET
						ses_idsetor = '" . $this->ses_idsetor . "',
						ses_dataesterilizacao = '" . $this->ses_dataesterilizacao . "',
						ses_datasaida = '" . $this->ses_datasaida . "',
						ses_status = '" . $this->ses_status . "'
					WHERE ses_id = " . $this->ses_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->ses_id . " em tmsd_solicitacaoesterilizacao.";
				$this->gravaLog();
				//
				return $this->ses_id;
			} else {
				return 0;
			}
		}

		public function delete($id){
			$sql = "UPDATE tmsd_solicitacaoesterilizacao SET
						ses_del = '*'
					WHERE ses_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_solicitacaoesterilizacao.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function lastProduct($qrcode){
			//clasula que busca o ultimo produto baseado no qrcodebase e trás só os ultimos numeros que o indentifica como produto unico
			$sql = "SELECT * FROM tmss_produto WHERE pro_qrcode LIKE '%$qrcode%' ORDER BY pro_id desc LIMIT 1";
			$res = mysql_query($sql);

			$row = mysql_fetch_array($res, MYSQL_ASSOC);


			$obj = new SolicitacoesModel();
			$obj->ses_pro_qrcode = $row['pro_qrcode'];
			
			return $obj;
		}

		public function selectSolicitacao($id){
			$sql = "SELECT * FROM tmsd_solicitacaoesterilizacao WHERE ses_id = " . $id . " AND ses_del IS NULL";
			error_log('selectSolicitacao - '.$message);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new SolicitacoesModel();
			$obj->ses_id = $row['ses_id'];
			$obj->ses_masterclient = $row['ses_masterclient'];
			$obj->ses_idsetor = $row['ses_idsetor'];
			$obj->ses_lote = $row['ses_lote'];
			$obj->ses_dataesterilizacao = $row['ses_dataesterilizacao'];
			$obj->ses_dataentrada = $row['ses_dataentrada'];
			$obj->ses_datasaida = $row['ses_datasaida'];
			$obj->ses_status = $row['ses_status'];
			return $obj;
		}

		public function selectAll($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			if($order == "")
				$order = "ses_id";
			$sql = "SELECT * FROM tmsd_solicitacaoesterilizacao
					WHERE ses_status <> 'x'
						AND ses_del IS NULL
						AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
						" . $where . "
					ORDER BY " . $order;
			error_log('AQUI - '. $sql);

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SolicitacoesModel();
				$obj->ses_id = $row['ses_id'];
				$obj->ses_masterclient = $row['ses_masterclient'];
				$obj->ses_idsetor = $row['ses_idsetor'];
				$obj->ses_lote = $row['ses_lote'];
				$obj->ses_dataesterilizacao = $row['ses_dataesterilizacao'];
				$obj->ses_dataentrada = $row['ses_dataentrada'];
				$obj->ses_datasaida = $row['ses_datasaida'];
				$obj->ses_status = $row['ses_status'];
				$a[] = $obj;
			}
			return $a;
		}

		public function search($buscar, $limit){
			$sql = "SELECT * FROM tmsd_solicitacaoesterilizacao AS ses
					INNER JOIN tmsd_setores AS setor ON (ses_idsetor = set_id)
					WHERE
					(
						ses_id = '" . $buscar . "'
						OR set_nome LIKE '%" . $buscar . "%'
					)
					AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
					AND ses_status <> 'x'
					AND ses_del IS NULL
					ORDER BY ses_id
					" . $limit;
			$res = mysql_query($sql);
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SolicitacoesModel();
				$obj->ses_id = $row['ses_id'];
				$obj->ses_masterclient = $row['ses_masterclient'];
				$obj->ses_idsetor = $row['ses_idsetor'];
				$obj->ses_lote = $row['ses_lote'];
				$obj->ses_dataesterilizacao = $row['ses_dataesterilizacao'];
				$obj->ses_dataentrada = $row['ses_dataentrada'];
				$obj->ses_datasaida = $row['ses_datasaida'];
				$obj->ses_status = $row['ses_status'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectAllOrder($where, $order){
			if(isset($where) && $where != "")
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_solicitacaoesterilizacao AS s
					INNER JOIN tmsd_setores AS t ON (t.set_id = s.ses_idsetor)
					INNER JOIN tmsd_itenssolicitacao AS i ON (i.iso_idses = s.ses_id)
					INNER JOIN tmss_produto AS p ON (i.iso_idproduto = p.pro_id)
					WHERE ses_del IS NULL AND ses_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . "
					ORDER BY " . $order;
			$res = mysql_query($sql);
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SolicitacoesModel();
				$obj->ses_id = $row['ses_id'];
				$obj->ses_idsetor = $row['ses_idsetor'];
				$obj->ses_lote = $row['ses_lote'];
				$obj->ses_dataesterilizacao = $row['ses_dataesterilizacao'];
				$obj->ses_dataentrada = $row['ses_dataentrada'];
				$obj->ses_datasaida = $row['ses_datasaida'];
				$obj->ses_status = $row['ses_status'];
				$obj->ses_iso_lote = $row['iso_lote'];
				$obj->ses_iso_id = $row['iso_id'];
				$obj->ses_iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->ses_iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->ses_iso_data = $row['iso_data'];
				$obj->ses_pro_nome = $row['pro_nome'];
				$obj->ses_pro_qrcode = $row['pro_qrcode'];
				$obj->ses_set_nome = $row['set_nome'];
				$a[] = $obj;
			}
			return $a;
		}
        
		public function selectMaiorRegistro($idProd){
			/** retorna o maior registro encontrado de um material **/
			
			$sql = "select max(ses_id) as id from tmsd_solicitacaoesterilizacao
					inner join tmsd_itenssolicitacao
					on tmsd_itenssolicitacao.iso_idses = tmsd_solicitacaoesterilizacao.ses_id
					and tmsd_itenssolicitacao.iso_del is null
					WHERE tmsd_solicitacaoesterilizacao.ses_del IS NULL
					AND tmsd_itenssolicitacao.iso_idproduto = ".$idProd;
			// error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			
			return $row['id'];
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