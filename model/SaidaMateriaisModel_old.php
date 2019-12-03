<?php
	class SaidaMateriaisModel extends Conexao{

		public $sma_id;
		public $sma_masterclient;
		public $sma_idusuario;
		public $sma_prontuario;
		public $sma_paciente;
		public $sma_sala;
		public $sma_idsetor;
		public $sma_idconvenio;
		public $sma_data;
		public $sma_ultimolancamento;
		
		//exclusivos de transferencia de estoque
		public $sma_tiposaida;
		
		// auxiliares
		public $sma_set_nome;
		public $sma_cvn_nome;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_saidamateriais (
						sma_masterclient,
						sma_idusuario,
						sma_prontuario,
						sma_paciente,
						/*sma_sala,*/
						sma_idsetor,
						sma_idconvenio,
						sma_data,
						sma_tiposaida
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $_SESSION['usu_id'] . "', 
						'" . DefaultHelper::acentos($this->sma_prontuario) . "',
						'" . DefaultHelper::acentos($this->sma_paciente) . "',
						/*'" . DefaultHelper::acentos($this->sma_sala) . "',*/
						'" . $this->sma_idsetor . "',
						'" . $this->sma_idconvenio . "',
						'" . date('Y-m-d H:i:s') . "',
						'" . $this->sma_tiposaida . "' 
					)";
			// error_log("saida de materias " . $sql);
			$executa = mysql_query($sql);
			if ($executa){
				// $id = mysql_insert_id();
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}else{
				$id = 0;
			}
			return $id;
		}

		public function update(){
			$sql = "UPDATE tmsd_saidamateriais SET
						sma_prontuario = '" . DefaultHelper::acentos($this->sma_prontuario) . "',
						sma_paciente = '" . DefaultHelper::acentos($this->sma_paciente) . "',
						/*sma_sala = '" . DefaultHelper::acentos($this->sma_sala) . "',*/
						sma_idsetor = '" . $this->sma_idsetor . "',
						sma_idconvenio = '" . $this->sma_idconvenio . "'
						" . (($this->sma_ultimolancamento != 'x') ? ", sma_ultimolancamento = '" . $this->sma_ultimolancamento . "'" : "") . "
					WHERE sma_id = " . $this->sma_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->sma_id . " em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function updateSetorDestino(){
			$sql = "UPDATE tmsd_saidamateriais SET
						sma_idsetor = '" . $this->sma_idsetor . "'
						" . (($this->sma_ultimolancamento != 'x') ? ", sma_ultimolancamento = '" . date('Y-m-d H:i:s') . "'" : "") . "
					WHERE sma_id = " . $this->sma_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Transferencia Indicacao Setor Destino: registro " . $this->sma_id . " em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectSaidaMateriais($id){
			$sql = "SELECT * FROM tmsd_saidamateriais AS sma
					LEFT JOIN tmsd_setores AS s ON (sma_idsetor = set_id)
					LEFT JOIN tmsd_convenios AS cvn ON (sma_idconvenio = cvn_id)
					WHERE sma_id = " . $id . " AND sma_del IS NULL";
			// error_log("saida de materiais " . $sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new SaidaMateriaisModel();
			$obj->sma_id = $row['sma_id'];
			$obj->sma_masterclient = $row['sma_masterclient'];
			$obj->sma_prontuario = $row['sma_prontuario'];
			$obj->sma_paciente = $row['sma_paciente'];
			//$obj->sma_sala = $row['sma_sala'];
			$obj->sma_idsetor = $row['sma_idsetor'];
			$obj->sma_idconvenio = $row['sma_idconvenio'];
			$obj->sma_data = $row['sma_data'];
			$obj->sma_ultimolancamento = $row['sma_ultimolancamento'];
			$obj->sma_tiposaida = $row['sma_tiposaida'];
			// auxiliares
			$obj->sma_set_nome = $row['set_nome'];
			$obj->sma_cvn_nome = $row['cvn_nome'];
			return $obj;
		}

		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_saidamateriais AS sma
					LEFT JOIN tmsd_setores AS s ON (sma_idsetor = set_id)
					LEFT JOIN tmsd_convenios AS cvn ON (sma_idconvenio = cvn_id)
					WHERE (sma_del IS NULL OR sma_del != '*')
			        AND sma_masterclient = " . $_SESSION['usu_masterclient'] . "
			        " . $where . "
					ORDER BY sma_ultimolancamento DESC";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new SaidaMateriaisModel();
				$obj->sma_id = $row['sma_id'];
				$obj->sma_masterclient = $row['sma_masterclient'];
				$obj->sma_prontuario = $row['sma_prontuario'];
				$obj->sma_paciente = $row['sma_paciente'];
				$obj->sma_sala = $row['sma_sala'];
				$obj->sma_idsetor = $row['sma_idsetor'];
				$obj->sma_idconvenio = $row['sma_idconvenio'];
				$obj->sma_data = $row['sma_data'];
				$obj->sma_ultimolancamento = $row['sma_ultimolancamento'];
				// auxiliares
				$obj->sma_set_nome = $row['set_nome'];
				$obj->sma_cvn_nome = $row['cvn_nome'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectMaiorRegistro($idProd){
			/** retorna o maior registro encontrado de um material **/
			
			$sql = "select max(sma_id) as sma_id from tmsd_saidamateriais
					inner join tmsd_itenssaida
					on tmsd_itenssaida.isa_idsaida = tmsd_saidamateriais.sma_id
					and tmsd_itenssaida.isa_del is null
					WHERE tmsd_saidamateriais.sma_del IS NULL
					AND tmsd_itenssaida.isa_idproduto =  ".$idProd;
			// error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			// error_log("id " . $row['sma_id']);
			return $row['sma_id'];
		}
		
		public function selectNull(){
			/*** Traz a quantidade e transferencia nao finalizadas ***/
			$sql = "SELECT COUNT(*) AS TOTAL FROM tmsd_saidamateriais WHERE sma_idsetor = 0 AND sma_tiposaida='T' AND sma_del is null";
			// error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			
			return $row['TOTAL']; 
			
		}
		
		public function limpaTransferencia(){
			/** Exclui as transferencias nao finalizadas, causadas por saída do procedimento se a sua canclusao**/
			$sql = "UPDATE tmsd_saidamateriais SET
						sma_del = '*'
					WHERE sma_idsetor = '0'
					AND sma_tiposaida = 'T'
					AND sma_idusuario = '".$_SESSION['usu_id']."'
					AND sma_masterclient = '".$_SESSION['usu_masterclient']."' 
					AND sma_del is NULL; ";
			// error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: limpesa de transferencia de estoque não concluida em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2015
*/
?>