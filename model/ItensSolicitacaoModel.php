<?php
	class ItensSolicitacaoModel extends Conexao{

		public $iso_id;
		public $iso_idses;
		public $iso_idproduto;
		public $iso_idmetodo;
		public $iso_idequipamento;
		public $iso_nreuso;
		public $iso_idrtecnico;
		public $iso_nivelrpreparo;
		public $iso_refrpreparo;
		public $iso_del;
		public $iso_lote;
		public $iso_dataesterilizacao;
		public $iso_horaesterilizacao;
		public $iso_datalimite;
		public $iso_data;
		public $iso_status;
		public $iso_verify_conf;
		public $iso_loteequipamento;
		public $iso_qte;
		// auxiliares
		public $iso_ses_status;
		public $iso_set_nome;
		public $iso_qrcodenew;
		public $iso_met_nome;
		public $iso_rte_nome;
		public $iso_rte_coren;
		public $iso_pro_nome;
		public $iso_pro_qrcode;
		public $iso_pro_composto;
		public $iso_pro_calibre;
		public $iso_pro_curvatura;
		public $iso_pro_comprimento;
		public $iso_pro_diametrointerno;
		public $iso_consignado;
		public $n;
		public $custo;
		public $iso_tipodetergente;
		public $iso_lotedetergente;

		public $pro_nome;
		public $pro_qrcode;
		public $pro_id;
		// referente ? atualiza?~~ao de conferencia de material -> saida da esterilizacao
		public $iso_dataconferencia;
		public $iso_conferidopor;
        public $iso_referencia;
		//se adiciona campo para update en la tabla tmsd_itenssolicitacao
		public $iso_idequipamentoet;
		public $color;
		public $novaSoli;
        

		public function __construct(){
            $this->iso_referencia = null;
			$this->conecta();
		}

		public function insert(){
			if($this->iso_consignado == 1){
				$ja = $this->selectAllConsignado("iso_idses = " . $this->iso_idses . " AND iso_idproduto = " . $this->iso_idproduto . " AND iso_status = '0'");					
			}else{
				$ja = $this->selectAll("iso_idses = " . $this->iso_idses . " AND iso_idproduto = " . $this->iso_idproduto . " AND iso_status = '0'");					
			}
			
			if(count($ja) > 0){
				error_log("BLOQUEOU ADD ITEM! J? TEM.");
				return false;
			} else {
				$sql_max_last_entry = "SELECT MAX(`iso_contagem`) as maior FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $this->iso_idproduto";
				$res_max = mysql_query($sql_max_last_entry) or die(mysql_error());
				$iso_contagem = mysql_fetch_assoc($res_max);
				$sql = "INSERT INTO tmsd_itenssolicitacao (
							iso_idses,
							iso_idproduto,
							iso_idmetodo,
							iso_idequipamento,
							iso_loteequipamento,
							iso_nreuso,
							iso_idrtecnico,
							iso_lote,
							iso_dataesterilizacao,
							iso_horaesterilizacao,
							iso_datalimite,
							iso_data,
							iso_referencia,
							iso_consignado,
							iso_verify_conf,
							iso_tipodetergente,
							iso_lotedetergente,
							iso_qte,
							iso_qte_origin,
							iso_contagem
						) VALUES (
							'" . $this->iso_idses . "',
							'" . $this->iso_idproduto . "',
							'" . $this->iso_idmetodo. "',
							'" . $this->iso_idequipamento. "',
							'" . $this->iso_loteequipamento. "',
							'" . $this->iso_nreuso . "',
							'" . $this->iso_idrtecnico . "',
							'" . $this->iso_lote . "',
							'" . $this->iso_dataesterilizacao . "',
							'" . $this->iso_horaesterilizacao . "',
							'" . $this->iso_datalimite . "',
							'" .  date('Y-m-d H:i:s')."',
							'" . $this->iso_referencia . "',
							'" . $this->iso_consignado . "',
							'" . $this->iso_verify_conf . "',
							'" . $this->iso_tipodetergente . "',
							'" . $this->iso_lotedetergente . "',
							'" . $this->iso_qte . "',
							'" . $this->iso_qte . "',
							'" . $iso_contagem['maior'] . "'
						)";
				error_log('sql>>>>'.$sql);
				$res = mysql_query($sql) or die(mysql_error());
					
				$id_last_insert =  mysql_insert_id();
				$set_contagem_compostos = $this->set_contagem_compostos($id_last_insert, $this->iso_idproduto);

				if($res) {
					// log
					$this->log_acao = "Inser??o: item " . $id . " pertencente a solicita??o " . $this->iso_idses . " em tmsd_itenssolicitacao.";
					$this->gravaLog();
					$res = $id;
					//
				}
				return $res;
			}	
		
		}

		private function checkDataLimite($iso_id){
			$sql = "SELECT `iso_id`,`iso_datalimite` FROM `tmsd_itenssolicitacao` WHERE `iso_del` IS NULL AND `iso_id` = $iso_id";
			$res = mysql_query($sql);
			$result = mysql_fetch_assoc($res);

			if($result['iso_datalimite'] != '0000-00-00'){
				return 1;
			}else{
				$sql = "UPDATE tmsd_itenssolicitacao SET `iso_datalimite` = '".$this->iso_datalimite."' WHERE `iso_id` =". $result['iso_id'] ."";
				mysql_query($sql);
			}
		}

		private function set_contagem_compostos($id_last_insert, $idproduto)
		{
			
			$sql = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idpai` = $idproduto OR `pco_idfilho` = $idproduto AND pco_del IS NULL)";
			$res = mysql_query($sql);
			$is_compound = mysql_result($res, 0);

			

			if(!$is_compound){
				return false;
			}else{
				$sql2 = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idpai` = $idproduto AND pco_del IS NULL)";
				$res2 = mysql_query($sql2);
				$is_father = mysql_result($res2, 0);

				if($is_father){
					$sql3 = "UPDATE tmsd_itenssolicitacao SET `iso_contagem` = (iso_contagem + 1) WHERE `iso_id` = $id_last_insert";
					$res3 = mysql_query($sql3) or die(mysql_error());
				}else{
					$sql4 = "SELECT pco_idpai FROM tmss_produtoscompostos WHERE `pco_idfilho` = $idproduto AND pco_del IS NULL";
					$res4 = mysql_query($sql4) or die(mysql_error());
					$id_pai = mysql_result($res4, 0);


					$sql_max_last_entry = "SELECT MAX(`iso_contagem`) as maior FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $id_pai";
					$res_max = mysql_query($sql_max_last_entry) or die(mysql_error());
					$iso_contagem = mysql_fetch_assoc($res_max);

					$sql5 = "UPDATE tmsd_itenssolicitacao SET `iso_contagem` = ".$iso_contagem['maior']." WHERE `iso_id` = $id_last_insert";
					$res5 = mysql_query($sql5) or die(mysql_error());

				}
			}
		}

		public function update(){
			$sql = "UPDATE tmsd_itenssolicitacao SET
						iso_idses = " . $this->iso_idses . ",
						iso_idproduto = " . $this->iso_idproduto . ",
						iso_idmetodo = " . $this->iso_idmetodo . ",
						iso_nreuso = " . $this->iso_nreuso . ",
						iso_idrtecnico = " . $this->iso_idrtecnico . ",
						iso_nivelrpreparo = '" . $this->iso_nivelrpreparo . "',
						iso_refrpreparo = " . $this->iso_refrpreparo . ",
						iso_lote = '" . $this->iso_lote . "',
						iso_dataesterilizacao = '" . $this->iso_dataesterilizacao . "',
						iso_horaesterilizacao = '" . $this->iso_horaesterilizacao . "',
						iso_datalimite = '" . $this->iso_datalimite . "',
						iso_status = '" . $this->iso_status . "',
						iso_idequipamentoet = '" . $this->iso_idequipamentoet . "',
						iso_verify_conf = '" . $this->iso_verify_conf . "'
					WHERE iso_id = " . $this->iso_id;
			error_log('sql update: '.$sql);
			$res = mysql_query($sql);


			$data_limite_check = $this->checkDataLimite($this->iso_id);


			if($res) {
				// log
				$this->log_acao = "Atualiza??o: item " . $this->iso_id . " pertencente a solicita??o " . $this->iso_idses . " em tmsd_usuarios.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function updateConferencia(){
			$sql = "UPDATE tmsd_itenssolicitacao SET			
						iso_dataconferencia = '" . $this->iso_dataconferencia . "',
						iso_conferidopor = '" . $this->iso_conferidopor . "'
					WHERE iso_id = " . $this->iso_id;
			error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualiza??o: item " . $this->iso_id . " confer?ncia da solicita??o de esteriliza??o " . $this->iso_idses . " em tmsd_itenssolicitacao.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function VerificaSaidaComposto( $idpai ){

			$sqlFilhos ="SELECT p.pco_idfilho as id FROM 
						tmss_produtoscompostos p
						INNER JOIN tmss_produto ON pro_id = pco_idfilho
						WHERE pco_del IS NULL AND pco_idpai = {$idpai} ORDER BY pro_nome";
			$resFilhos = mysql_query($sqlFilhos);
			$idfilhos;
			$i = 0;
			while($row = mysql_fetch_array($resFilhos, MYSQL_ASSOC)){
				if($i == 0){
					$idfilhos=$row['id'];
				}
				else{
					$idfilhos = $idfilhos .','.$row['id'];
				}
				$i++;
			}

			$sqlcontagem="SELECT s.iso_contagem as cont FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$idpai} ORDER BY s.iso_id DESC LIMIT 1";
			$resContagem = mysql_query($sqlcontagem);
			$cont = mysql_fetch_array($resContagem, MYSQL_ASSOC);
			$contagem = $cont['cont'];


			$sql = "SELECT COUNT(s.iso_id) as contagem FROM tmsd_itenssolicitacao s WHERE s.iso_contagem={$contagem} AND s.iso_idproduto IN({$idfilhos}) AND s.iso_dataconferencia != '' ";
			error_log($sql);
			$res = mysql_query($sql);
			$qte = mysql_fetch_array($res, MYSQL_ASSOC);

			if($qte['contagem'] == 0){
				return '404';
			}
			else{
				return $qte['contagem'];
			}
			
		}
		
		public function updateConferenciaPai(){
			$sql = "UPDATE tmsd_itenssolicitacao SET			
						iso_dataconferencia = '" . $this->iso_dataconferencia . "',
						iso_conferidopor = '" . $this->iso_conferidopor . "',
						iso_verify_conf = '1'
					WHERE iso_id = " . $this->iso_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualiza??o: item " . $this->iso_id . " confer?ncia da solicita??o de esteriliza??o " . $this->iso_idses . " em tmsd_itenssolicitacao.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delete($id){
			$sql = "UPDATE tmsd_itenssolicitacao SET
						iso_del = '*'
					WHERE iso_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclus?o: registro " . $id . " em tmsd_itenssolicitacao.";
				$this->gravaLog();
				//
			}
			return $res;
		}


		public function selectUltimaSolicitacao( $idproduto ){
	       $sql = "SELECT iso.* 
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso.iso_idproduto = pro.pro_id)
					JOIN tmsd_itenssolicitacao as iso2 on iso2.iso_id = tmsd_ultimaIdItemSolicitacao( {$idproduto} ) and iso2.iso_status = 1
					WHERE iso.iso_del IS NULL AND 
					pro.pro_idcliente = {$_SESSION['usu_masterclient']} AND 
					iso.iso_referencia =  iso2.iso_id";


			$res = mysql_query($sql) or die(mysql_error());
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
				$obj->iso_conferidopor = $row['iso_conferidopor'];
				$obj->iso_dataconferencia = $row['iso_dataconferencia'];
				$a[] = $obj;
			}
			return $sql;
			return $a;					


		}


		//conta etiquetagem
		public function selectCountproduct($iso_idproduto, $qtde){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					WHERE iso_idproduto = " . $iso_idproduto . "
					AND iso_del IS NULL AND iso_status <> 1 ORDER BY iso_id ASC LIMIT $qtde  ";
			error_log('select item: '.$sql);
			$res = mysql_query($sql);
			$a = array();

			while ($row = mysql_fetch_assoc($res)) {
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row["iso_id"];
				$obj->iso_idproduto = $row["iso_idproduto"];
				$a[] = $obj;
			}
			
			return $a;
			//return mysql_free_result($result);
		}

		public function selectCountproductNew($iso_idproduto, $qtde){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					WHERE iso_idproduto = " . $iso_idproduto . "
					AND iso_del IS NULL AND iso_status <> 1 ORDER BY iso_id ASC LIMIT $qtde  ";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			
			return $row;
			//return mysql_free_result($result);
		}

		public function verificarQte($iso_idproduto, $qtde){

			$sql = " SELECT soli.iso_qte AS pro_qtde, soli.iso_id  FROM tmsd_itenssolicitacao soli	 WHERE soli.iso_idproduto = {$iso_idproduto} AND soli.iso_lote ='' ";
			$res = mysql_query($sql);
			$prod = mysql_fetch_array($res, MYSQL_ASSOC);

			$qteOrigin = $prod['pro_qtde'];
			$iso_id = $prod['iso_id'];

			//

			$sqlVerLote = "SELECT MAX(s.iso_lote_referencia) AS ref FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$iso_idproduto}";
			$res    	= mysql_query($sqlVerLote);
			$row 	    = mysql_fetch_array($res, MYSQL_ASSOC);
			if ($row['ref'] != ''){
				$temLote = true;
			}

			//
			if($qtde < $qteOrigin){
				 self::updateQte($iso_id, $qtde, $iso_idproduto );
				 $newQte = $qteOrigin - $qtde ;
				 self::duplicarSoli($iso_id,$newQte);

				return $iso_id;
			}
			elseif($qtde == $qteOrigin && $temLote == true){
				self::updateLote($iso_id, $iso_idproduto );
				return '400';
			}
			elseif($qtde == $qteOrigin && $temLote == false){
				self::updateLote($iso_id, $iso_idproduto,$lote=1 );
				return '400';
			}
			else{
				return '404';
			}
		}
		
		public function countProductEtiquetagem($iso_idproduto){
			$sql = "SELECT COUNT(*) as itens FROM tmsd_itenssolicitacao AS iso
					WHERE iso_idproduto = " . $iso_idproduto . "
					AND iso_del IS NULL AND iso_status <> 1";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row;			
		}
		
		public function selectItemUltimo($id){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso 
			LEFT JOIN tmsd_metodos AS met ON (iso_idmetodo = met_id) LEFT JOIN tmsd_responsaveistecnicos AS rte ON (iso_idrtecnico = rte_id)
			WHERE iso_idproduto = " . $id . " AND iso_status = 1 AND iso_del IS NULL";

			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensSolicitacaoModel();
			$obj->iso_id = $row['iso_id'];
			$obj->iso_idses = $row['iso_idses'];
			$obj->iso_idproduto = $row['iso_idproduto'];
			$obj->iso_idmetodo = $row['iso_idmetodo'];
			$obj->iso_idequipamento = $row['iso_idequipamento'];
			$obj->iso_nreuso = $row['iso_nreuso'];
			$obj->iso_idrtecnico = $row['iso_idrtecnico'];
			$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
			$obj->iso_refrpreparo = $row['iso_refrpreparo'];
			$obj->iso_lote = $row['iso_lote'];
			$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
			$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
			$obj->iso_datalimite = $row['iso_datalimite'];
			$obj->iso_data = $row['iso_data'];
			$obj->iso_status = $row['iso_status'];
			$obj->iso_del = $row['iso_del'];
			// auxiliares
			$obj->iso_met_nome = $row['met_nome'];
			$obj->iso_rte_nome = $row['rte_nome'];
			$obj->iso_rte_coren = $row['rte_coren'];
            $obj->iso_conferidopor = $row['iso_conferidopor'];
            $obj->iso_dataconferencia= $row['iso_dataconferencia'];

			return $obj;			
		}

		public function selectItem($iso_id, $rempre = false){
			//echo $iso_id;
			error_log('rempre:::'.$rempre);
			if($rempre == true){
				
				$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
							 LEFT JOIN tmsd_metodos AS met ON (iso_idmetodo = met_id)
							 LEFT JOIN tmsd_responsaveistecnicos AS rte ON (iso_idrtecnico = rte_id)
						WHERE iso.iso_idproduto = " . $iso_id . "
							AND iso_del IS NULL";		
				error_log($sql);							
			}
			else{
				$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
							 LEFT JOIN tmsd_metodos AS met ON (iso_idmetodo = met_id)
							 LEFT JOIN tmsd_responsaveistecnicos AS rte ON (iso_idrtecnico = rte_id)
						WHERE iso_id = " . $iso_id . "
							AND iso_del IS NULL";				
			}
			

			

			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensSolicitacaoModel();
			$obj->iso_id = $row['iso_id'];
			$obj->iso_idses = $row['iso_idses'];
			$obj->iso_idproduto = $row['iso_idproduto'];
			$obj->iso_idmetodo = $row['iso_idmetodo'];
			$obj->iso_idequipamento = $row['iso_idequipamento'];
			$obj->iso_nreuso = $row['iso_nreuso'];
			$obj->iso_idrtecnico = $row['iso_idrtecnico'];
			$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
			$obj->iso_refrpreparo = $row['iso_refrpreparo'];
			$obj->iso_lote = $row['iso_lote'];
			$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
			$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
			$obj->iso_datalimite = $row['iso_datalimite'];
			$obj->iso_data = $row['iso_data'];
			$obj->iso_status = $row['iso_status'];
			$obj->iso_del = $row['iso_del'];
			// auxiliares
			$obj->iso_met_nome = $row['met_nome'];
			$obj->iso_rte_nome = $row['rte_nome'];
			$obj->iso_rte_coren = $row['rte_coren'];
            $obj->iso_conferidopor = $row['iso_conferidopor'];
            $obj->iso_dataconferencia= $row['iso_dataconferencia'];

			return $obj;
		}

		// mesma busca que selectItem, por?m no banco Sterilab, para cruzamento de dados
		public function selectItemSterilab($iso_id){
			$sql = "SELECT * FROM tmss_itenssolicitacao
					WHERE iso_id = " . $iso_id . "
						AND iso_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensSolicitacaoModel();
			$obj->iso_id = $row['iso_id'];
			$obj->iso_idses = $row['iso_idses'];
			$obj->iso_idproduto = $row['iso_idproduto'];
			$obj->iso_idmetodo = $row['iso_idmetodo'];
			$obj->iso_idequipamento = $row['iso_idequipamento'];
			$obj->iso_nreuso = $row['iso_nreuso'];
			$obj->iso_idrtecnico = $row['iso_idrtecnico'];
			$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
			$obj->iso_refrpreparo = $row['iso_refrpreparo'];
			$obj->iso_lote = $row['iso_lote'];
			$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
			$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
			$obj->iso_datalimite = $row['iso_datalimite'];
			$obj->iso_data = $row['iso_data'];
			$obj->iso_status = $row['iso_status'];
			$obj->iso_del = $row['iso_del'];
			return $obj;
		}
	
		//aqui pega os produtos da compois??o da caixa e os q1ue est?o na solicita??o j? 
		public function selectAll($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			if($order != "")
				$order = "ORDER BY " . $order;
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					" . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_qrcode = $row['pro_qrcode'];
                                
				$a[] = $obj;
			}
			return $a;
		}
		

		public function selectAll2($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			if($order != "")
				$order = "ORDER BY " . $order;
				$sql = "SELECT * FROM tmsd_itenssolicitacao AS its
					INNER JOIN tmss_produtoscompostos AS cpro ON its.iso_idproduto = cpro.pco_idfilho
					LEFT JOIN tmss_produto AS pro ON cpro.pco_idfilho = pro.pro_id
					WHERE its.iso_del IS NULL AND its.iso_consignado = 0 AND pro.pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					" . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_qrcode = $row['pro_qrcode'];
                                
				$a[] = $obj;
			}
			return $a;
		}		

		public function selectAllConsignado($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			if($order != "")
				$order = "ORDER BY " . $order;
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_consignado = 1 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					" . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];
                                
				$a[] = $obj;
			}
			return $a;
		}		
		

		//seleciona os que N?O est?o na solicita??o		
		public function selectProdCompostoOfSolicitacao($idcomposto, $ids){
			$sql = "SELECT * FROM tmss_produtoscompostos 
						 LEFT JOIN tmss_produto AS pro ON (pco_idfilho = pro_id) 
						 WHERE pco_idpai = ". $idcomposto ." 
						 AND  pco_idfilho NOT IN (" .$ids. ") AND pco_del IS NULL";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_idfilho = $row['pco_idfilho'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_id = $row['pro_id'];
				$a[] = $obj;
			}
			return $a;
		}
	
		//seleciona os que N?O est?o na solicita??o	quando nao foi inserindo nenhum na caixa
		public function selectProdCompostoOfSolicitacaoClean($idcomposto, $ids){
			$sql = "SELECT * FROM tmss_produtoscompostos 
						 LEFT JOIN tmss_produto AS pro ON (pco_idfilho = pro_id) 
						 WHERE pco_idpai = ". $idcomposto ." ";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_idfilho = $row['pco_idfilho'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_id = $row['pro_id'];
				$a[] = $obj;
			}
			return $a;
		}	
	
		//seleciona os que est?o na solicita??o
		public function selectProdCompostoinSolicitacao($idcomposto){
			$sql = "SELECT * FROM tmss_produtoscompostos 
					LEFT JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
					INNER JOIN tmsd_itenssolicitacao AS iso ON (iso_idproduto = pro_id)			
					WHERE pco_idpai = ".$idcomposto." AND pco_del IS NULL AND iso_del IS NULL AND iso_verify_conf = 1 ";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_idfilho = $row['pco_idfilho'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_id = $row['pro_id'];
				$a[] = $obj;
			}
			return $a;

		}		
		
		public function selectUltimAutorizacao($id){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso WHERE iso_id = $id AND iso_status = 0 ORDER BY iso_id DESC LIMIT 1";

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];                
			}
			
			return $obj;
		}

		public function selectItemidproduct($id){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso LEFT JOIN tmss_produto AS pro ON pro_id = iso_idproduto WHERE iso_idproduto = $id AND iso_status = 0 ORDER BY iso_id DESC LIMIT 1";

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];                
			}
			
			return $obj;
		}

        public function selectAllLimite($where, $order, $limite){
			if(isset($where))
				$where = "AND " . $where;
			if(!empty( $order ) )
				$order = "ORDER BY " . $order;
			if( !empty( $limite ) )
				$limite = " LIMIT 1,{$limite}";

			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "	" . $order . $limite;

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];                
				$a[] = $obj;
			}
			
			return $a;
		}
		
		public function verifyqtdeInSolicitation($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT COUNT(*) AS n FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL AND iso_consignado = 0" . $where;
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['n'];		
		}

		public function selectAllCount($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT COUNT(iso_id) AS n FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL " . $where . " and iso_status <> 1 AND iso_consignado = 0";
					
			//echo $sql;
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['n'];
		}
		
		//Produtos da tela de solicita??o
		public function selectItensTelaSolicitacao($ses_id){
			$sql = "SELECT
						ses_status,
						iso_id,
						iso_idproduto,
						pro_nome,
						pro_qrcode,
						pro_qtde,
						pro_composto,
						pro_calibre,
						pro_curvatura,
						pro_comprimento,
						pro_diametrointerno,
                        iso_data,
						pro_detailproduct
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						AND ses_id = " . $ses_id . "
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL
						AND iso_status = '0' AND iso_consignado = 0 
					/*ORDER BY pro_nome*/";
			error_log($sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){


				$obj = new ItensSolicitacaoModel();

				// Ir? esconder os produtos filhos de compostos ao trazer do banco.
				if($this->filho_de_composto($row['iso_idproduto'])){
					//continue; // Se descomentar n?o mostra os filhos na solicita??o
					$obj->color = '#ececec';

				};

				$obj->iso_id = $row['iso_id'];
				// auxiliares
				$obj->iso_ses_status = $row['ses_status'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_pro_qrcode = $row['pro_qrcode'];
				$obj->iso_pro_composto = $row['pro_composto'];
				$obj->iso_pro_calibre = $row['pro_calibre'];
				$obj->iso_pro_qtde = $row['pro_qtde'];
				$obj->iso_pro_curvatura = $row['pro_curvatura'];
				$obj->iso_pro_comprimento = $row['pro_comprimento'];
				$obj->iso_pro_diametrointerno = $row['pro_diametrointerno'];
                $obj->iso_data = $row['iso_data'];
                $obj->pro_detailproduct = $row['pro_detailproduct'];
				$a[] = $obj;
			}
			return $a;
		}

		public function filho_de_composto($iso_idproduto){
			$sql = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idfilho` = '$iso_idproduto')";
			$res = mysql_query($sql) or die(mysql_error());
			$response = mysql_result($res, 0);
			if($response){
				return true;
			}else{
				return false;
			}
		}

		//cleverson
		//conta itens da solicita??o
		public function countItensTelaSolicitacao($ses_id){
			$sql = "SELECT
						COUNT(iso_id) AS total
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						AND ses_id = " . $ses_id . "
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL
						AND iso_status = '0'
						AND iso_consignado = 0
					/*ORDER BY pro_nome*/";
			$res = mysql_query($sql) or die(mysql_error());
			$total = mysql_fetch_array($res, MYSQL_ASSOC);
			print_r($total['total']);
		}
	
		
		//conta intens consignados da solicita??o
		//cleverson
		//conta itens da solicita??o
		public function countItensTelaSolicitacaoConsignados($ses_id){
			$sql = "SELECT
						COUNT(iso_id) AS total
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						AND ses_id = " . $ses_id . "
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL
						AND iso_status = '0'
						AND iso_consignado = 1
					/*ORDER BY pro_nome*/";
			$res = mysql_query($sql) or die(mysql_error());
			$total = mysql_fetch_array($res, MYSQL_ASSOC);
			print_r($total['total']);
		}


		public function selectItensTelaSolicitacaoConsignados($ses_id){
			$sql = "SELECT
						ses_status,
						iso_id,
						iso_idproduto,
						pro_nome,
						pro_qrcode,
						pro_qtde,
						pro_composto,
						pro_calibre,
						pro_curvatura,
						pro_comprimento,
						pro_diametrointerno,
                        iso_data,
						pro_detailproduct
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						AND ses_id = " . $ses_id . "
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL
						AND iso_status = '0' AND iso_consignado = 1
					/*ORDER BY pro_nome*/";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				// auxiliares
				$obj->iso_ses_status = $row['ses_status'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_pro_qrcode = $row['pro_qrcode'];
				$obj->iso_pro_qtde = $row['pro_qtde'];
				$obj->iso_pro_composto = $row['pro_composto'];
				$obj->iso_pro_calibre = $row['pro_calibre'];
				$obj->iso_pro_curvatura = $row['pro_curvatura'];
				$obj->iso_pro_comprimento = $row['pro_comprimento'];
				$obj->iso_pro_diametrointerno = $row['pro_diametrointerno'];
                $obj->iso_data = $row['iso_data'];
                $obj->pro_detailproduct = $row['pro_detailproduct'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectItensEtiquetagem($search){
			$sql = "SELECT
			            iso_id,
			            iso_data,
			            pro_id,
						pro_qrcode,
						pro_nome,
						iso_qte
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					/*
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					LEFT JOIN tmsd_setores AS setor ON (ses_idsetor = set_id)
					*/
					WHERE iso_del IS NULL
                        AND iso_status = '0'
                        AND iso_consignado = 0
                        AND pro_qrcode LIKE '%$search%'
                    ORDER BY iso_data DESC

					";
			//error_log($sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				if($this->isCompoundItem($row['pro_id'])){
					continue;
				}
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_pro_qrcode = $row['pro_qrcode'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_idproduto = $row['pro_id'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_qte = $row['iso_qte'];
				$a[] = $obj;
			}
			//mysql_close();
			return $a;
		}

		private function isCompoundItem($idProduto){
			$sql = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idfilho` = $idProduto AND `pco_del` IS NULL)";
			$res = mysql_query($sql) or die(mysql_error());
			$response = mysql_result($res, 0);

			if($response){
				return true;
			}else{
				return false;
			}
		}

		public static function duplicarSoli($iso_id , $qtde){

			$sqlMax = "SELECT  MAX(s.iso_id) AS id FROM tmsd_itenssolicitacao AS s";
			$res    = mysql_query($sqlMax);
			$max    = mysql_fetch_array($res, MYSQL_ASSOC);

			
			$id    = $max['id'] + 1;

			$sql = "
					INSERT INTO tmsd_itenssolicitacao (`iso_id`,	`iso_idses`, `iso_idproduto` , `iso_idmetodo` , `iso_idequipamento` , `iso_loteequipamento` , `iso_tipodetergente` ,	`iso_lotedetergente` ,	`iso_nreuso` ,
					`iso_idrtecnico` , `iso_nivelrpreparo` , `iso_refrpreparo` , `iso_lote` ,	`iso_dataesterilizacao` ,	`iso_horaesterilizacao` ,	`iso_datalimite` ,	`iso_data` ,`iso_status` ,
					`iso_del` ,	`iso_dataconferencia` ,	`iso_conferidopor` ,	`iso_referencia` ,	`iso_consignado` ,	`iso_verify_conf`,	`iso_contagem` ,	`iso_qte` , iso_qte_origin
					)
					SELECT
					{$id} ,
					`iso_idses`, `iso_idproduto` , `iso_idmetodo` ,	`iso_idequipamento` ,
					`iso_loteequipamento` ,	`iso_tipodetergente` ,	`iso_lotedetergente` ,
					`iso_nreuso` ,	 `iso_idrtecnico` ,	`iso_nivelrpreparo` ,	`iso_refrpreparo` ,
					`iso_lote` ,	`iso_dataesterilizacao` ,	`iso_horaesterilizacao` ,
					`iso_datalimite` ,	`iso_data` ,	`iso_status` ,	`iso_del` ,
					`iso_dataconferencia` ,	`iso_conferidopor` ,	`iso_referencia` ,	`iso_consignado` ,
					`iso_verify_conf`,	`iso_contagem` ,	{$qtde} , {$qtde} 
					FROM tmsd_itenssolicitacao  s
					WHERE s.iso_id = {$iso_id}
			";
			mysql_query($sql);

		}

		public static function updateQte($iso_id , $qtde, $iso_idproduto){

			$sql 		= "SELECT MAX(s.iso_lote_referencia) AS ref FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$iso_idproduto}";
		
			$res    	= mysql_query($sql);
			$row 	    = mysql_fetch_array($res, MYSQL_ASSOC);
			if ($row['ref'] == ''){
				$refLote=1;
			}
			else{
				$refLote= $row['ref'] + 1;
			}

			$sql = "UPDATE tmsd_itenssolicitacao SET iso_qte={$qtde}, iso_qte_origin={$qtde}, iso_lote_referencia={$refLote}  WHERE iso_id={$iso_id}";
			mysql_query($sql);

		}

		public static function updateLote($iso_id, $iso_idproduto,$lote=0){

			$sql 	= "SELECT MAX(s.iso_lote_referencia) AS ref FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$iso_idproduto}";
			$res    = mysql_query($sql);
			$row    = mysql_fetch_array($res, MYSQL_ASSOC);

			$refLote= $row['ref'] + 1;

			if($lote == 1){
				$sql = "UPDATE tmsd_itenssolicitacao SET  iso_lote_referencia=1 WHERE iso_id={$iso_id}";	
			}
			else{
				$sql = "UPDATE tmsd_itenssolicitacao SET  iso_lote_referencia={$refLote}  WHERE iso_id={$iso_id}";
			}
			mysql_query($sql);

		}

		public static function getLote($iso_id){

			$sql     = "SELECT s.iso_lote_referencia AS ref FROM tmsd_itenssolicitacao s WHERE s.iso_id={$iso_id}";
			$res     = mysql_query($sql);
			$row 	 = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['ref'];
		}


		// cleverson matias
		public function countItensEtiquetagem(){
			$sql = "SELECT
			            COUNT(iso_id) as total
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					/*
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					LEFT JOIN tmsd_setores AS setor ON (ses_idsetor = set_id)
					*/
					WHERE iso_del IS NULL
						AND iso_status = '0'
						AND iso_consignado = '0'
					ORDER BY iso_data DESC
					";
			//error_log($sql);
			$res = mysql_query($sql) or die(mysql_error());
			$total = mysql_fetch_array($res, MYSQL_ASSOC);
			print_r($total['total']);
		}

		// cleverson matias
		public function countItensEtiquetagemConsig(){
			$sql = "SELECT
			            COUNT(iso_id) as total
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					/*
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					LEFT JOIN tmsd_setores AS setor ON (ses_idsetor = set_id)
					*/
					WHERE iso_del IS NULL
						AND iso_status = '0'
						AND iso_consignado = '1'
					ORDER BY iso_data DESC
					";
			//error_log($sql);
			$res = mysql_query($sql) or die(mysql_error());
			$total = mysql_fetch_array($res, MYSQL_ASSOC);
			print_r($total['total']);
		}

		// cleverson matias
		public function selectItensEtiquetagemConsignados(){
			$sql = "SELECT
			            iso_id,
			            iso_data,
			            pro_id,
						pro_qrcode,
						pro_nome,
						iso_consignado
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					/*
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					LEFT JOIN tmsd_setores AS setor ON (ses_idsetor = set_id)
					*/
					WHERE iso_del IS NULL
						AND iso_status = '0'
						AND iso_consignado = '1'
					ORDER BY iso_data DESC
					";
			//error_log($sql);
			$res = mysql_query($sql);
			$total_rows = mysql_num_rows($res);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_pro_qrcode = $row['pro_qrcode'];
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_idproduto = $row['pro_id'];
				$obj->iso_data = $row['iso_data'];
				$a[] = $obj;
			}
			//mysql_close();
			return $a;
		}		
		
		public function selectLotes($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT iso_lote,iso_idequipamentoet,iso_loteequipamento, iso_idequipamento, iso_dataesterilizacao FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_loteequipamento != '' AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					GROUP BY iso_idequipamento
					ORDER BY iso_lote";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_idequipamentoet = $row['iso_idequipamentoet'];
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$a[] = $obj;
			}
			return $a;
		}// Fin of the class selectLotes
		
		public function selectLotesEt($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT iso_lote,iso_idequipamentoet,iso_loteequipamento, iso_idequipamento, iso_dataesterilizacao FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_lote != '' AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					GROUP BY iso_idequipamentoet
					ORDER BY iso_lote";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_idequipamentoet = $row['iso_idequipamentoet'];
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$a[] = $obj;
			}
			return $a;
		}// Fin of the class selectLotes		


		public function selectequipamentos($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT iso_loteequipamento FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_lote != '' AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					GROUP BY iso_loteequipamento
					ORDER BY iso_loteequipamento";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$a[] = $obj;
			}
			return $a;
		}



		// conta a quantidade de vezes que um produto aparece nas tabelas itenssolicitacao - Durazzo e Sterilab - (reprocessamento)
		public function contItem($idProduto, $status){
			if($status != 'x')
				$whereStatus = "AND iso_status = '" . $status . "'";
			else
				$whereStatus = "";
			$sql = "SELECT iso_id
					FROM tmsd_itenssolicitacao AS a
						INNER JOIN tmsd_solicitacaoesterilizacao AS b ON (a.iso_idses = b.ses_id)
							AND ses_del IS NULL
					WHERE
						iso_idproduto = " . $idProduto . "
						" . $whereStatus . "
						AND iso_del IS NULL AND iso_consignado = 0";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$a[] = $obj;
			}
			// mesma contagem em Durazzo
			$sql = "SELECT iso_id
					FROM tmss_itenssolicitacao AS a
						INNER JOIN tmss_solicitacaoesterilizacao AS b ON (a.iso_idses = b.ses_id)
							AND ses_del IS NULL
					WHERE
						iso_idproduto = " . $idProduto . "
						" . $whereStatus . "
						AND iso_del IS NULL AND iso_consignado = 0";
			$res = mysql_query($sql);
			$b = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$b[] = $obj;
			}
			return sizeof($a) + sizeof($b);
		}
		
		public function contItemConsignado($idProduto, $status){
			if($status != 'x')
				$whereStatus = "AND iso_status = '" . $status . "'";
			else
				$whereStatus = "";
			$sql = "SELECT iso_id
					FROM tmsd_itenssolicitacao AS a
						INNER JOIN tmsd_solicitacaoesterilizacao AS b ON (a.iso_idses = b.ses_id)
							AND ses_del IS NULL
					WHERE
						iso_idproduto = " . $idProduto . "
						" . $whereStatus . "
						AND iso_del IS NULL AND iso_consignado = 1";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$a[] = $obj;
			}
			// mesma contagem em Durazzo
			$sql = "SELECT iso_id
					FROM tmss_itenssolicitacao AS a
						INNER JOIN tmss_solicitacaoesterilizacao AS b ON (a.iso_idses = b.ses_id)
							AND ses_del IS NULL
					WHERE
						iso_idproduto = " . $idProduto . "
						" . $whereStatus . "
						AND iso_del IS NULL AND iso_consignado = 0";
			$res = mysql_query($sql);
			$b = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$b[] = $obj;
			}
			return sizeof($a) + sizeof($b);
		}

		public function selectLastMetodo(){
			$sql = "SELECT iso_idmetodo FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					WHERE ses_masterclient = " . $_SESSION['usu_masterclient'] . " /*AND iso_lote <> '' AND iso_dataesterilizacao <> '0000-00-00'*/ AND iso_del IS NULL
					/*ORDER BY iso_dataesterilizacao DESC, iso_horaesterilizacao DESC*/
					ORDER BY iso_id DESC
					LIMIT 1";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['iso_idmetodo'];
		}

		public function selectLastRTecnico(){
			$sql = "SELECT iso_idrtecnico FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
					WHERE ses_masterclient = " . $_SESSION['usu_masterclient'] . " /*AND iso_lote <> '' AND iso_dataesterilizacao <> '0000-00-00'*/ AND iso_del IS NULL
					/*ORDER BY iso_dataesterilizacao DESC, iso_horaesterilizacao DESC*/
					ORDER BY iso_id DESC
					LIMIT 1";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['iso_idrtecnico'];
		}

		public function selectLastMetodoERespTec(){
			$sql = "SELECT iso_idmetodo, iso_idrtecnico, iso_idequipamento 
					FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_del IS NULL
						AND iso_id = (
							SELECT MAX(iso_id) FROM tmsd_itenssolicitacao AS iso
							INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
								AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
							WHERE iso_del IS NULL
						)";
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['iso_idmetodo'] . "*;*" . $row['iso_idrtecnico'] . "*;*" . $row['iso_idequipamento'];
		}

		public function selectLastLote(){
			$sql = "SELECT iso_lote
						FROM tmsd_itenssolicitacao AS iso
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
							AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
					WHERE iso_lote <> ''
						AND iso_dataesterilizacao <> '0000-00-00'
						AND iso_del IS NULL
					ORDER BY
						iso_id DESC
						/*
						iso_dataesterilizacao DESC,
						iso_horaesterilizacao DESC
						*/
					LIMIT 1";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			/*
			$iso = $row['iso_id'];
			$sql = "SELECT iso_lote FROM tmsd_itenssolicitacao WHERE iso_id = " . $iso;
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			*/
			return $row['iso_lote'];
		}

		public function selectLastReproc($id){
			$sql = "SELECT * FROM tmsd_itenssolicitacao WHERE iso_idproduto = " . $id . " AND iso_status = '1' AND iso_del IS NULL ORDER BY iso_data DESC";
			$res = mysql_query($sql);
			$row1 = mysql_fetch_array($res, MYSQL_ASSOC); // pega o registro do topo da pesquisa - Durazzo
			$sql = "SELECT * FROM tmss_itenssolicitacao WHERE iso_idproduto = " . $id . " AND iso_status = '1' AND iso_del IS NULL ORDER BY iso_data DESC";
			$res = mysql_query($sql);
			$row2 = mysql_fetch_array($res, MYSQL_ASSOC); // pega o registro do topo da pesquisa - Sterilab
			if($row1['iso_nreuso'] > $row2['iso_nreuso'])
				return ItensSolicitacaoModel::selectItem($row1['iso_id']);
			else
				return ItensSolicitacaoModel::selectItemSterilab($row2['iso_id']);

		}

		public function selectAllOrder($where, $order){
			if(isset($where))
				$where = "AND " . $where;
					$sql = "SELECT 
							case when iso.iso_lote_referencia IS NOT null then
								CONCAT(pro.pro_qrcode,'.',iso.iso_lote_referencia)
							else
								 pro.pro_qrcode
							END AS qrcodenew,
					iso.*,pro.* FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_consignado = 0 " . $where . "
					ORDER BY " . $order;
			error_log('aqui esta >>>'.$sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->qrcodenew = $row['qrcodenew'];
				$obj->qte = $row['iso_qte_origin'];
				$obj->iso_loteequipamento = $row['iso_loteequipamento'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectAllOrderConsignado($where, $order){
			if(isset($where))
				$where = "AND " . $where;
					$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_prodconsignado AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL AND iso_consignado = 1 " . $where . "
					ORDER BY " . $order;

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];
				$a[] = $obj;
			}
			return $a;
		}
	
		//aqui provavelmente faz a contagem dos produtos que foram inseridos e conferidos na solicita??o
		
		//ALTERAR ISO_DATAESTERILIZACAO PARA ISO_DATA
		
		public function selectControleEsterilizacao($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			 $sql = "SELECT pro_nome, count(iso_id) AS n, iso_id, iso_idequipamento, iso_idproduto, iso_dataesterilizacao, iso_lotedetergente, pro_composto FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_consignado = 0 AND iso_del IS NULL 
					" . $where . "
					ORDER BY " . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_lotedetergente = $row['iso_lotedetergente'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_pro_composto = $row['pro_composto'];
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idproduto = $row['iso_idproduto'];				
				$obj->n = $row['n'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectControleEsterilizacaoPrint($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT pro_nome, COUNT(iso_id) AS n FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_consignado = 0 AND iso_del IS NULL
					AND pro_id NOT IN (
						SELECT pco_idfilho FROM tmss_produtoscompostos AS pco
						INNER JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
						WHERE pco_del IS NULL AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					)
					" . $where . "
					ORDER BY " . $order;
			$res = mysql_query($sql);
		
			return $res;
		}

			public function selectcustoControleEsterilizacao($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT pro_nome, sum(pro_custo) as total, COUNT(iso_id) AS n FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_consignado = 0 AND iso_del IS NULL
					AND pro_id NOT IN (
						SELECT pco_idfilho FROM tmss_produtoscompostos AS pco
						INNER JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
						WHERE pco_del IS NULL AND iso_consignado = 0 AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					)
					
					" . $where . "
					ORDER BY " . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->n = $row['n'];
				$obj->custo = $row['total'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectSecaoEsterilizacao($ano){
			$a = array(
					1 => array("mes" => "Janeiro", "arr" => array()),
					2 => array("mes" => "Fevereiro", "arr" => array()),
					3 => array("mes" => "Março", "arr" => array()),
					4 => array("mes" => "Abril", "arr" => array()),
					5 => array("mes" => "Maio", "arr" => array()),
					6 => array("mes" => "Junho", "arr" => array()),
					7 => array("mes" => "Julho", "arr" => array()),
					8 => array("mes" => "Agosto", "arr" => array()),
					9 => array("mes" => "Setembro", "arr" => array()),
					10 => array("mes" => "Outubro", "arr" => array()),
					11 => array("mes" => "Novembro", "arr" => array()),
					12 => array("mes" => "Dezembro", "arr" => array())
			);

			// Caixas
			$sql = "SELECT
						MONTH(iso_dataesterilizacao) AS mes,
						met_nome,
						COUNT(iso_id) AS cont
					FROM tmsd_itenssolicitacao AS iso
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						LEFT JOIN tmss_produtoscompostos AS pco ON (iso_idproduto = pco_idfilho)
						INNER JOIN tmsd_metodos AS met ON (iso_idmetodo = met_id)
					WHERE ses_masterclient = " . $_SESSION['usu_masterclient'] . "
						AND YEAR(iso_dataesterilizacao) = " . $ano . "
						AND ses_del IS NULL
						AND iso_del IS NULL
						AND iso_status = '1'
						AND pro_composto = '1'
					GROUP BY MONTH(iso_dataesterilizacao), iso_idmetodo
					ORDER BY MONTH(iso_dataesterilizacao)";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$a[$row["mes"]]["arr"][] = array("metodo" => $row["met_nome"], "caixas" => $row["cont"]);
			}

			// Pacotes
			$sql = "SELECT
						MONTH(iso_dataesterilizacao) AS mes,
						met_nome,
						COUNT(iso_id) AS cont
					FROM tmsd_itenssolicitacao AS iso
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
						INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
						INNER JOIN tmsd_metodos AS met ON (iso_idmetodo = met_id)
					WHERE ses_masterclient = " . $_SESSION['usu_masterclient'] . "
						AND YEAR(iso_dataesterilizacao) = " . $ano . "
						AND ses_del IS NULL
						AND iso_del IS NULL
						AND iso_status = '1'
						AND iso_idmetodo != 0
						AND
						(
							pro_composto = '0'
							OR pro_composto = ''
							OR pro_composto IS NULL
						)
						AND pro_id NOT IN
							(
								SELECT pco_idfilho
								FROM tmss_produtoscompostos
								WHERE pco_del IS NULL
							)
					GROUP BY MONTH(iso_dataesterilizacao), iso_idmetodo
					ORDER BY MONTH(iso_dataesterilizacao)";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$a[$row["mes"]]["arr"][] = array("metodo" => $row["met_nome"], "pacotes" => $row["cont"]);
			}

			return $a;
		}

		// mesma busca que selectAllOrder, por?m no banco Sterilab, para cruzamento de dados
		public function selectAllOrderSterilab($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_consignado AND iso_del IS NULL " . $where . "
					ORDER BY " . $order;

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
				$a[] = $obj;
			}
			return $a;
		}

		public function deleteWhere($idItem, $idSes, $idProduto){
			$sql = "DELETE FROM tmsd_itenssolicitacao
					WHERE iso_id <> " . $idItem . "
						AND iso_idses = " . $idSes . "
						AND iso_idproduto = " . $idProduto . "
						AND iso_status = '0'
						AND iso_del IS NULL";
			$res = mysql_query($sql);
			return $res;
		}

		public function setDataLimite($id, $data){

		    $sql = "UPDATE tmsd_itenssolicitacao SET
						iso_datalimite = '" . $data . "'
					WHERE iso_id = " . $id;
		    $res = mysql_query($sql);
		    if($res) {
		        // log
		        $this->log_acao = "Atualiza??o: (iso_datalimite) item " . $id . " em tmsd_itenssolicitacao.";
		        $this->gravaLog();
		        //
		    }
		    return $res;
		}

	
	//get responsavel tmsd_responsaveistecnicos--rte_id---rte_nome
		public function responsavel($where){
			if(isset($where))
				$where =  $where;
			$sql = "SELECT rte_nome FROM tmsd_responsaveistecnicos AS rt 
			LEFT JOIN tmsd_itenssolicitacao AS itn ON rt.rte_id = itn.iso_idrtecnico 
			WHERE rte_del IS NULL AND "
			. $where; 

			$res = mysql_query($sql);			
			
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
			
			$obj = new ItensSolicitacaoModel();
			$obj->rte_nome = $row['rte_nome'];
			}
			return $obj->rte_nome;
		}
	
	/*public function selectItemidproduct($id){
			$sql = "SELECT * FROM tmsd_itenssolicitacao AS iso LEFT JOIN tmss_produto AS pro ON pro_id = iso_idproduto WHERE iso_idproduto = $id AND iso_status = 0 ORDER BY iso_id DESC LIMIT 1";

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];                
			}
			
			return $obj;
		}}*/
			
	
	//get responsavel tmsd_responsaveistecnicos--rte_id---rte_nome
	
	
	public function byItemId($itemId){
		$sql = "SELECT * FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $itemId AND iso_del IS NULL";
		$res = mysql_query($sql);

		$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_id = $row['iso_id'];
				$obj->iso_idses = $row['iso_idses'];
				$obj->iso_idproduto = $row['iso_idproduto'];
				$obj->pro_qrcode = $row['pro_qrcode'];
				$obj->iso_idmetodo = $row['iso_idmetodo'];
				$obj->iso_idequipamento = $row['iso_idequipamento'];
				$obj->iso_nreuso = $row['iso_nreuso'];
				$obj->iso_idrtecnico = $row['iso_idrtecnico'];
				$obj->iso_nivelrpreparo = $row['iso_nivelrpreparo'];
				$obj->iso_refrpreparo = $row['iso_refrpreparo'];
				$obj->iso_lote = $row['iso_lote'];
				$obj->iso_dataesterilizacao = $row['iso_dataesterilizacao'];
				$obj->iso_horaesterilizacao = $row['iso_horaesterilizacao'];
				$obj->iso_datalimite = $row['iso_datalimite'];
				$obj->iso_data = $row['iso_data'];
				$obj->iso_status = $row['iso_status'];
				$obj->iso_del = $row['iso_del'];
                $obj->iso_conferidopor = $row['iso_conferidopor'];
                $obj->iso_dataconferencia= $row['iso_dataconferencia'];                
			}
			
			return $obj;

	}
	
	
	
	public function getQte($qrcode){

		$sql = "SELECT its.iso_qte as qte FROM tmsd_itenssolicitacao  its 
				INNER JOIN tmss_produto prod ON its.iso_idproduto = prod.pro_id
				WHERE prod.pro_qrcode='{$qrcode}' AND its.iso_lote_referencia IS NULL AND its.iso_status = 0 AND its.iso_del IS null";
		error_log('sql::::::'.$sql);
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res, MYSQL_ASSOC);
		
		return $row;
	}
	
	public function getDados($qrcode, $lote){

		$sql = "SELECT prod.pro_qrcode, prod.pro_nome, it.iso_dataesterilizacao, it.iso_datalimite, met.met_nome, 
				CONCAT(prod.pro_qrcode,'.',it.iso_lote_referencia) AS qr, prod.pro_id AS item
				FROM tmsd_itenssolicitacao it
				INNER JOIN tmss_produto prod 
					ON it.iso_idproduto = prod.pro_id
				INNER JOIN tmsd_metodos met 
					ON met.met_id = it.iso_idmetodo
				WHERE it.iso_lote_referencia={$lote} AND prod.pro_qrcode='{$qrcode}'";
				
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res, MYSQL_ASSOC);
		
		$data = new DefaultHelper();		
		$row['iso_dataesterilizacao'] =  $data::converte_data($row['iso_dataesterilizacao']);
		$row['iso_datalimite'] =  $data::converte_data($row['iso_datalimite']);
		return $row;
	}

	public function getEquipamentDataExpurgo($date, $equipment = 0){
		$case_equipment = $equipment === 0 ? '' : " AND iso_idequipamento = $equipment";
		$sql = "SELECT iso_idproduto, iso_loteequipamento, iso_idequipamento, iso_tipodetergente, iso_lotedetergente FROM tmsd_itenssolicitacao AS a WHERE iso_data BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND iso_del IS NULL" . $case_equipment;
		$res = mysql_query($sql);
		$arr = array();
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
			$row['iso_nome_equipamento'] = $this->getEquipamentName($row['iso_idequipamento']);
			array_push($arr, $row);
		}
		return $arr;
	}

	public function getEquipamentDataEsterilizacao($date, $equipment = 0){
		$case_equipment = $equipment === 0 ? '' : " AND iso_idequipamentoet = $equipment";
		$sql = "SELECT iso_idproduto, iso_lote, iso_idequipamentoet FROM tmsd_itenssolicitacao AS a WHERE iso_data BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND iso_del IS NULL" . $case_equipment;
		error_log($sql . 'matiassssss');
		$res = mysql_query($sql);
		$arr = array();
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
			$row['iso_nome_equipamento'] = $this->getEquipamentName($row['iso_idequipamentoet']);
			array_push($arr, $row);
		}
		return $arr;
	}
	

	private function getEquipamentName($idequipamento){
		$sql = "SELECT `eq_descricao` FROM `tmsd_equipamento` WHERE `eq_id` = $idequipamento AND `eq_del` IS NULL";
		$res = mysql_query($sql);
		return mysql_result($res, 0);
	}

	public function responsavel_tecnico_esterilizacao($lote, $equipment, $date){
		$date = join('-', array_reverse(split('/', $date)));
		$sql = "SELECT `iso_idrtecnico` FROM `tmsd_itenssolicitacao` WHERE `iso_lote` = '$lote' AND `iso_idequipamentoet` = $equipment AND `iso_del` IS NULL AND `iso_data` like '$date %' LIMIT 1";
		$res = mysql_query($sql) or die(mysql_error());
		$id_rtec = mysql_result($res, 0);
		$rtec_nome = $this->getRtecNome($id_rtec);
		return $rtec_nome;
	}

	public function responsavel_tecnico_expurgo($lote, $equipment, $date){
		$date = join('-', array_reverse(split('/', $date)));
		$sql = "SELECT `iso_idrtecnico` FROM `tmsd_itenssolicitacao` WHERE `iso_loteequipamento` = '$lote' AND `iso_idequipamento` = $equipment AND `iso_del` IS NULL AND `iso_data` like '$date %' LIMIT 1";
		$res = mysql_query($sql) or die(mysql_error());
		$id_rtec = mysql_result($res, 0);
		$rtec_nome = $this->getRtecNome($id_rtec);
		return $rtec_nome;
	}
	
	private function getRtecNome($id_rtec){
		$sql = "SELECT `rte_nome` FROM `tmsd_responsaveistecnicos` WHERE `rte_id` = $id_rtec AND `rte_del` is null";
		$res = mysql_query($sql);
		return mysql_result($res, 0);
	}

	public function getItensWhere($equipment_id, $lote, $date){
		$date = join('-', array_reverse(split('/', $date)));
		$sql = "SELECT iso_idproduto FROM `tmsd_itenssolicitacao` WHERE `iso_idequipamentoet` = 
		$equipment_id AND `iso_lote` = '$lote' AND iso_data LIKE '$date %' AND iso_del is Null";
		$res = mysql_query($sql) or die(mysql_error());
		$result = array();
		while ($row = mysql_fetch_assoc($res)) {
			// remove todos os filhos de compostos
			if($this->isCompoundItem($row['iso_idproduto'])){
				continue;
			}

			// caso seja pai deixa marcado
			if($this->is_item_box($row['iso_idproduto'])){
				$row['is_box'] = 1;
			}
			else {
				$row['is_box'] = 0;
			}

			$row['item_nome'] = $this->getProductName($row['iso_idproduto']);
			array_push($result, $row);
		}

		return $result;
	}

	public function getItensWhereExpurgo($equipment_id, $lote, $date){
		$date = join('-', array_reverse(split('/', $date)));
		$sql = "SELECT iso_idproduto FROM `tmsd_itenssolicitacao` WHERE `iso_idequipamento` = 
		$equipment_id AND `iso_loteequipamento` = '$lote' AND iso_data LIKE '$date %' AND iso_del is Null";
		$res = mysql_query($sql) or die(mysql_error());
		$result = array();
		while ($row = mysql_fetch_assoc($res)) {
			// remove todos os filhos de compostos
			if($this->isCompoundItem($row['iso_idproduto'])){
				continue;
			}

			// caso seja pai deixa marcado
			if($this->is_item_box($row['iso_idproduto'])){
				$row['is_box'] = 1;
			}
			else {
				$row['is_box'] = 0;
			}

			$row['item_nome'] = $this->getProductName($row['iso_idproduto']);
			array_push($result, $row);
		}

		return $result;
	}

	private function getProductName($iso_id){
		$sql = "SELECT pro_nome FROM tmss_produto where pro_id = $iso_id";
		$res = mysql_query($sql);
		return mysql_result($res, 0);
	}

	private function is_item_box($item_id){
		$sql = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idpai` = $item_id)";
		$res = mysql_query($sql);
		return mysql_result($res, 0);
	}
	
	public function getCountmodel($id){
		$sql_max_last_entry = "SELECT MAX(`iso_contagem`) as maior FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $id";
		$res_max = mysql_query($sql_max_last_entry) or die(mysql_error());
		return mysql_result($res_max, 0);
	}

	public function getProdId($id){
		$sql = "SELECT iso_idproduto FROM `tmsd_itenssolicitacao` WHERE `iso_id` = $id";
		return mysql_result(mysql_query($sql), 0);
	}

	public function wasItemInMountConference($id_pai){
		$sql = "SELECT iso_conferidopor FROM tmsd_itenssolicitacao WHERE iso_idproduto = $id_pai AND iso_del is null ORDER BY iso_id DESC LIMIT 1 ";
		$res = mysql_query($sql) or die(mysql_error());
		return mysql_result($res, 0);
	}

	public function getItensFilhos($id_pai){
		$sql = "SELECT max(iso_contagem) from tmsd_itenssolicitacao where iso_idproduto = $id_pai and iso_del is null";
		$contagem_pai = mysql_result(mysql_query($sql), 0);

		$sql = "SELECT pco_idfilho FROM tmss_produtoscompostos LEFT JOIN tmsd_itenssolicitacao ON iso_idproduto = pco_idfilho WHERE pco_idpai = $id_pai AND iso_contagem = $contagem_pai AND iso_del IS NULL AND pco_del IS null";
		$res = mysql_query($sql) or die(mysql_error());

		$a = array();
		while ($row = mysql_fetch_assoc($res)) {
		  array_push($a, $row['pco_idfilho']);


		}
		return $a;
	}


	public function sonCanProgress($id_filho){
		$sql = "Select pco_idpai from tmss_produtoscompostos where pco_idfilho = $id_filho and pco_del is null";
		$id_pai = mysql_result(mysql_query($sql), 0);

		$sql = "Select max(iso_contagem) from tmsd_itenssolicitacao where iso_idproduto = $id_pai and iso_del is null";
		$cont_pai = mysql_result(mysql_query($sql), 0);

		$sql = "Select max(iso_contagem) as contagem_filho, iso_status from tmsd_itenssolicitacao where iso_idproduto = $id_filho and iso_del is null LIMIT 1";
		$info_son = mysql_fetch_assoc(mysql_query($sql));

		if(($cont_pai  == $info_son['contagem_filho']) && $info_son['iso_status'] == '1'){
			return true;
		}else{
			return false;
		}

	}

	
	
}


	
?>