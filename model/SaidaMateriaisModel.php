<?php
	class SaidaMateriaisModel extends Conexao{

		public $sma_id;
		public $sma_masterclient;
		public $sma_idusuario;
		public $sma_prontuario;
		public $sma_paciente;
		public $sma_sala;
		public $sma_idsetor; // setor de destino
		public $sma_idconvenio;
		public $sma_data;
		public $sma_ultimolancamento;
		public $sma_retirado_por;
		
		//exclusivos de transferencia de estoque
		public $sma_tiposaida;
		
		// auxiliares
		public $sma_set_nome;
		public $sma_cvn_nome;
		public $qte;
		public $loteref;
		public $qrcodenew;

		public function __construct(){
			$this->conecta();

			//O tipo de caracteres a ser usado
   			 header('Content-Type: text/html; charset=utf-8');

		   //Depois da tua conexão a base de dados insere o seguinte código abaixo.
		   //Esta parte vai resolver o teu problema!
		    mysql_query("SET NAMES 'utf8'");
		    mysql_query('SET character_set_connection=utf8');
		    mysql_query('SET character_set_client=utf8');
		    mysql_query('SET character_set_results=utf8');
		}

		
		public function getFullUserName($id_saida){
			$sql = "SELECT tmsd_saidamateriais.*, tmsd_usuarios.usu_nivel, tmsd_usuarios.usu_referencia\n"
					. "FROM tmsd_saidamateriais\n"
					. "LEFT JOIN tmsd_usuarios ON\n"
					. "tmsd_saidamateriais.sma_idusuario = tmsd_usuarios.usu_id\n"
					. "WHERE tmsd_saidamateriais.sma_id = $id_saida AND tmsd_saidamateriais.sma_del IS NULL";
			error_log($sql);
			$res = mysql_query($sql) or die(mysql_error());
			$data = '';
			while ($row = mysql_fetch_assoc($res)) {
				$data = $this->getUserName($row['usu_nivel'], $row['usu_referencia']);
			}
			return $data;
		
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_saidamateriais (
						sma_masterclient,
						sma_idusuario,
						sma_prontuario,
						sma_paciente,
						sma_idsetor,
						sma_idconvenio,
						sma_data,
						sma_tiposaida
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $_SESSION['usu_id'] . "', 
						'" . DefaultHelper::acentos($this->sma_prontuario) . "',
						'" . DefaultHelper::acentos($this->sma_paciente) . "',
						'" . $this->sma_idsetor . "',
						'" . $this->sma_idconvenio . "',
						'" . date('Y-m-d H:i:s') . "',
						'" . $this->sma_tiposaida . "' 
					)";
			$executa = mysql_query($sql);
			$id = mysql_insert_id();
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
		
		public function updateConferencia( $idsaida ){

			$sql = "UPDATE tmsd_saidamateriais SET
								sma_conferido = '*'
					WHERE sma_id = {$idsaida}";
			$res = mysql_query($sql);

			if($res) {
				// log
				$this->log_acao = "Saida Conferida " . $this->sma_id . " em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;

		}

		public function updateSetorDestino(){
			$sql = "UPDATE tmsd_saidamateriais SET
						sma_retirado_por = '".$this->sma_retirado_por."',
						sma_idsetor = '" . $this->sma_idsetor . "'" . (($this->sma_ultimolancamento != 'x') ? ", sma_ultimolancamento = '" . date('Y-m-d H:i:s') . "'" : "") . "
					WHERE sma_id = '" . $this->sma_id ."'";
			$res = mysql_query($sql);

			if($res) {
				// log
				$this->log_acao = "Transferencia Indicacao Setor Destino: registro " . $this->sma_id . " em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectSaidaMateriais($id, $lote = null){

			if($lote != null){
				$whrelote = "and isa_loteref = {$lote} "; 
			}else{
				$whrelote='';
			}
			$sql = "SELECT sma.sma_retirado_por AS ret, sma.*,s.*,cvn.*,isa.*,prod.* FROM tmsd_saidamateriais AS sma
					LEFT JOIN tmsd_setores AS s ON (sma_idsetor = set_id)
					LEFT JOIN tmsd_convenios AS cvn ON (sma_idconvenio = cvn_id)
					INNER JOIN tmsd_itenssaida AS isa ON isa.isa_idsaida = sma.sma_id 
					INNER JOIN tmss_produto AS  prod  ON prod.pro_id= isa.isa_idproduto

					WHERE sma_id = " . $id . " AND sma_del IS NULL {$whrelote}";

			error_log("saida de materiais " . $sql);
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

			$obj->qte = $row['isa_qte_origin'];

			$obj->loteref = $row['isa_loteref'];
			$obj->qrcodenew = $row['pro_qrcode'];
			$obj->sma_retirado_por = $row['ret'];
			return $obj;
		}

		public function selectAll($where , $limit){
			if(isset($where))
				$where = "AND " . $where;
				$sql = "SELECT * FROM tmsd_saidamateriais AS sma
					LEFT JOIN tmsd_setores AS s ON (sma_idsetor = set_id)
					LEFT JOIN tmsd_convenios AS cvn ON (sma_idconvenio = cvn_id)
					WHERE (sma_del IS NULL OR sma_del != '*')
			        AND sma_masterclient = " . $_SESSION['usu_masterclient'] . "
			        " . $where . "
					ORDER BY sma_ultimolancamento DESC";

				error_log('sqlllll'.$sql);


			if( !empty( $limit ) ){
				$sql .= " LIMIT {$limit}";
			}


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
				$obj->sma_tiposaida = $row['sma_tiposaida'];
				// auxiliares
				$obj->sma_set_nome = $row['set_nome'];
				$obj->sma_cvn_nome = $row['cvn_nome'];
				$a[] = $obj;
			}

			error_log('aaaaaaaaaaaaaa'.count($a));

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
			error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			error_log("id " . $row['sma_id']);
			return $row['sma_id'];
		}
		
		public function selectNull(){
			/*** Traz a quantidade e transferencia nao finalizadas ***/
			$sql = "SELECT COUNT(*) AS TOTAL FROM tmsd_saidamateriais WHERE sma_idsetor = 0 AND sma_tiposaida='T' AND sma_del is null";
			error_log($sql);
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
			error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: limpesa de transferencia de estoque não concluida em tmsd_saidamateriais.";
				$this->gravaLog();
				//
			}
			return $res;
		}


		public function getSaidasMateriaisRange($dataInit, $dataEnd, $setorDestino = null) {

			if($setorDestino){

				$sql = "SELECT \n"
    . "	tmsd_saidamateriais.*, tmsd_setores.set_nome, tmsd_usuarios.usu_referencia, tmsd_usuarios.usu_nivel\n"
    . "FROM \n"
    . "	tmsd_saidamateriais\n"
    . "JOIN tmsd_setores ON\n"
    . "	tmsd_saidamateriais.sma_idsetor = tmsd_setores.set_id\n"
    . "JOIN tmsd_usuarios ON\n"
    . "	tmsd_saidamateriais.sma_idusuario = tmsd_usuarios.usu_id\n"
    . "WHERE tmsd_saidamateriais.sma_data BETWEEN '". $dataInit ."' AND '". $dataEnd ."' AND tmsd_saidamateriais.sma_idsetor = $setorDestino "
    . "AND `sma_del` IS NULL "
    . "AND `sma_tiposaida` = 'T' " 
    . "AND `sma_retirado_por` != 'sistema' "
    . "ORDER BY `sma_data` DESC";


			}else {
				$sql = "SELECT \n"
    . "	tmsd_saidamateriais.*, tmsd_setores.set_nome, tmsd_usuarios.usu_referencia, tmsd_usuarios.usu_nivel\n"
    . "FROM \n"
    . "	tmsd_saidamateriais\n"
    . "JOIN tmsd_setores ON\n"
    . "	tmsd_saidamateriais.sma_idsetor = tmsd_setores.set_id\n"
    . "JOIN tmsd_usuarios ON\n"
    . "	tmsd_saidamateriais.sma_idusuario = tmsd_usuarios.usu_id\n"
    . "WHERE tmsd_saidamateriais.sma_data BETWEEN '" . $dataInit ."' AND '". $dataEnd ."'"
    . "AND `sma_del` IS NULL "
    . "AND `sma_tiposaida` = 'T' "
    . "AND `sma_retirado_por` != 'sistema' "
    . "ORDER BY `sma_data` DESC";
			}
			

			$res = mysql_query($sql) or die(mysql_error());

			$result = array();
			while($row = mysql_fetch_array($res)) {

				$row['sma_idusuario'] = $this->getUserName($row['usu_nivel'],$row['usu_referencia']);


				$row['qtde_itens'] = $this->getQtdeItens($row['sma_id']) - $this->qtdCompostos($row['sma_id']);

				array_push($result, $row);
			}

			return $result;
		}

		public function getUserNamebyiduser($iduser){
			$sql = "SELECT u.usu_nivel as nivel, u.usu_referencia as ref FROM tmsd_usuarios u WHERE u.usu_id={$iduser}";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res);
			
			$nivel = $row['nivel'];
			$ref = $row['ref'];
			
			$name = $this->getUserName($nivel, $ref);
			
			return $name;
			
		}

		private function getUserName($nivel, $usu_referencia){
			switch ($nivel) {
				case  4:
					$sql = "SELECT rte_nome FROM `tmsd_responsaveistecnicos` WHERE `rte_id` = $usu_referencia";
					break;
				case  2:
					$sql = "SELECT cnf_nome FROM `tmsd_conferentes` WHERE `cnf_id` = $usu_referencia";
					break;
				case  3:
					$sql = "SELECT eti_nome FROM `tmsd_etiquetadores` WHERE `eti_id` = $usu_referencia";
					break;
				case  8:
					$sql = "SELECT pcao_nome FROM `tmsd_producao` WHERE `pcao_id` = $usu_referencia";
					break;
				case  6:
					$sql = "SELECT ars_nome FROM `tmsd_arsenal` WHERE `ars_id` = $usu_referencia";
					break;
				case  7:
					$sql = "SELECT cir_nome FROM `tmsd_circulante` WHERE `cir_id` = $usu_referencia";
					break;
				default:
					// code...
					break;
			}
			$result = mysql_query($sql);
			return mysql_result($result, 0);
		}

		public function getTransfData($id){


			$sql = "SELECT \n"
    . " tmsd_saidamateriais.sma_data, tmsd_saidamateriais.sma_retirado_por, tmsd_saidamateriais.sma_idusuario, tmsd_usuarios.usu_nivel, tmsd_usuarios.usu_referencia FROM `tmsd_saidamateriais`\n"
    . "LEFT JOIN tmsd_usuarios ON\n"
    . " tmsd_saidamateriais.sma_idusuario = tmsd_usuarios.usu_id\n"
    . "WHERE `sma_id` = $id";

			$res = mysql_query($sql) or die(mysql_error());

			$result = array();
			while ($row = mysql_fetch_array($res)) {
				$row['usu_nome'] = $this->getUserName($row['usu_nivel'],$row['usu_referencia']);
			    array_push($result, $row);
			}
			

			return $result;
		}

		public function getQtdeItens($id) {
			$sql = "SELECT COUNT(*) as total FROM `tmsd_itenssaida` WHERE `isa_idsaida` = $id";

			$res = mysql_query($sql) or die(mysql_error());

			return mysql_result($res, 0);
		}

		public function qtdCompostos($id) {
			$sql = "SELECT isa_idproduto  FROM `tmsd_itenssaida` WHERE `isa_idsaida` = $id";

			$res = mysql_query($sql) or die(mysql_error());

			$results = array();
			while ($row = mysql_fetch_array($res)) {
			    array_push($results, $row);
			}


			$qtdCompostos = 0;
			foreach ($results as $result) {
				$sql2 = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idfilho` ='".$result['isa_idproduto']."' AND `pco_del` IS NULL)";
				$res = mysql_query($sql2) or die(mysql_error());
				if(mysql_result($res, 0)){
					$qtdCompostos++;
				}	
			}

			return $qtdCompostos;
		}

		public function deleteLastEntry($pro_id){
			$sql = "UPDATE tmsd_itenssaida set isa_del = '*' where isa_idproduto = $pro_id order by isa_id desc limit 1";
			return mysql_query($sql);
		}

		public function cleanUnfinished($id_saida){
			$sql = "DELETE FROM tmsd_saidamateriais where sma_id = $id_saida and sma_retirado_por is null";
			$res = mysql_query($sql) or die(mysql_error());

			if(mysql_affected_rows()){
				$sql = "DELETE FROM tmsd_itenssaida where isa_idsaida = $id_saida";
				mysql_query($sql) or die(mysql_error());
			}
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