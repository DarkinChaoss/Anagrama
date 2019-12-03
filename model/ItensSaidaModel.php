<?php
	class ItensSaidaModel extends Conexao{

		public $isa_id;
		public $isa_idsaida;
		public $isa_data;
		public $isa_sala;
		public $isa_idproduto;
		// public $isa_lote;
		public $isa_validade;
		public $isa_reuso;
		public $isa_obs;
		public $isa_idsetororigem;
		public $isa_idsetordestino;
		public $isa_conferente;
		public $isa_dataconferencia;
		/* Auxiliaes */

		public $isa_produto;
		public $pro_id;
		public $isa_qte;
		public $loteref;
		public $isa_qrcode;
		public $isa_setorOrigem;
		public $isa_setorDestino;
		public $isa_consignado;

		public function __construct(){

			//O tipo de caracteres a ser usado
   			 header('Content-Type: text/html; charset=utf-8');

		   //Depois da tua conex?o a base de dados insere o seguinte c?digo abaixo.
		   //Esta parte vai resolver o teu problema!
		    mysql_query("SET NAMES 'utf8'");
		    mysql_query('SET character_set_connection=utf8');
		    mysql_query('SET character_set_client=utf8');
		    mysql_query('SET character_set_results=utf8');

			$this->conecta();
		}

		public function insert(){

			$qte=1;
			
			$sql = "INSERT INTO tmsd_itenssaida (
						isa_idsaida,
						isa_data,
						isa_sala,
						isa_idproduto,
						isa_lote,
						isa_validade,
						isa_reuso,
						isa_obs,
						isa_idsetorigem,
						isa_idsetordestino,
						isa_loteref,
						isa_qte,
						isa_consignado
					) VALUES (
						'" . $this->isa_idsaida . "',
						'" . $this->isa_data . "',
						'" . DefaultHelper::acentos($this->isa_sala) . "',
						'" . $this->isa_idproduto . "',
						'" . DefaultHelper::acentos($this->isa_lote) . "',
						'" . $this->isa_validade . "',
						'" . DefaultHelper::acentos($this->isa_reuso) . "',
						'" . $this->isa_obs . "',
						'" . $this->isa_idsetororigem . "',
						'" . $this->isa_idsetordestino . "',
						'" . $this->loteref . "',
						'" . $qte . "',
						'" . $this->isa_consignado . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inser??o: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function insertQte($dados){

			$idsai    = $dados['idSaida'];
			$loteref  = $dados['loteref'];
			$idprod   = $dados['idProduto'];
			$data     = date('Y-m-d H:i:s');
			$sala     = $dados['sala'];
			$lote     = $dados['lote'];
			$validade = $dados['validade'];
			$reuso    = $dados['reuso'];
			$obs      = $dados['obs'];
			$setor    = $dados['setor'];

			$setorDes = $dados['setorDestino'];
			$cliente  = $dados['cliente'];

			$sqlverifica = "SELECT COUNT(its.isa_idproduto) AS qte FROM tmsd_itenssaida  its 
							INNER JOIN tmsd_saidamateriais smat ON smat.sma_id = its.isa_idsaida
							WHERE smat.sma_tiposaida='S' AND smat.sma_del IS NULL AND smat.sma_id={$idsai}
							AND its.isa_idproduto={$idprod} AND its.isa_loteref={$loteref}";
							
			$resver = mysql_query($sqlverifica);			
			$row = mysql_fetch_array($resver, MYSQL_ASSOC);
			$qte=1;

			if($row['qte'] > 0){
				$sql= "UPDATE tmsd_itenssaida its SET its.isa_qte = its.isa_qte + {$qte} ,its.isa_qte_origin = its.isa_qte_origin + {$qte} WHERE its.isa_idsaida={$idsai} AND its.isa_idproduto={$idprod} AND its.isa_loteref = {$loteref}";
				error_log($sql);
			}
			else{
				
				$this->atualizaUltimoLancamento($idsai);
				$sql = "INSERT INTO tmsd_itenssaida (
					isa_idsaida,
					isa_data,
					isa_sala,
					isa_idproduto,
					isa_lote,
					isa_validade,
					isa_reuso,
					isa_obs,
					isa_idsetorigem,
					isa_idsetordestino,
					isa_loteref,
					isa_qte,
					isa_qte_origin,
					isa_consignado
				) VALUES (
					'" . $idsai. "',
					'" . $data . "',
					'" . DefaultHelper::acentos($sala) . "',
					'" . $idprod . "',
					'" . DefaultHelper::acentos($lote) . "',
					'" . DefaultHelper::converte_data($validade) . "',
					'" . DefaultHelper::acentos($reuso) . "',
					'" . $obs . "',
					'" . $setor . "',
					'" . $setorDes . "',
					'" . $loteref . "',
					'" . $qte . "',
					'" . $qte . "',
					'" . $this->isa_consignado . "'
				)";
			}
			$res = mysql_query($sql);

			$this->atualizarQtes($loteref, $idprod, 1, $setor, $cliente);


			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inser??o: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function updateConfereSaidaProduto( $dados , $where){

			$sql = "UPDATE tmsd_itenssaida SET
						isa_conferente = '{$dados['conferente']}',
						isa_dataconferencia = '" . date('Y-m-d H:i:s') . "'
					WHERE {$where}";
					
			$res = mysql_query($sql);

			return $res;

		}
		
		private function atualizaUltimoLancamento($id){
			$data = date('Y-m-d H:i:s');
			$sql = "UPDATE tmsd_saidamateriais s SET s.sma_ultimolancamento='{$data}' WHERE s.sma_id={$id} AND s.sma_tiposaida='S' ";
			error_log($sql);
			$res = mysql_query($sql);
		}

		
		private function atualizarQtes( $loteref, $pro_id,$qte,$setor, $client){
			error_log('loteref: '.$loteref. 'pro_id: '.$pro_id.'qte: '.$qte.'setor: '.$setor.'cliente: '.$client);
			$sqlsetor="SELECT se.set_id as id FROM tmsd_setores se WHERE se.set_nome='CME' and se.set_masterclient = {$client} ";
			$set = mysql_query($sqlsetor);
			$rowset = mysql_fetch_array($set, MYSQL_ASSOC);
			$cme = $rowset['id'];

			if($setor == $cme){
				$sqlcmeqte="  SELECT s.iso_qte , s.iso_id  FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$pro_id} AND s.iso_lote_referencia={$loteref} ";
				$cmeqte = mysql_query($sqlcmeqte);
				$rowcme = mysql_fetch_array($cmeqte, MYSQL_ASSOC);

				if($rowcme['iso_qte'] >= $qte ){
					$sqlup = "UPDATE tmsd_itenssolicitacao s SET s.iso_qte = s.iso_qte - {$qte}  WHERE s.iso_idproduto = {$pro_id} AND s.iso_lote_referencia = {$loteref}";
					mysql_query($sqlup);
				}
				else{					
					$qteRest =  $qte - $rowcme['iso_qte'];

					$sqlup = "UPDATE tmsd_itenssolicitacao s SET s.iso_qte =  s.iso_qte - s.iso_qte   WHERE s.iso_idproduto = {$pro_id} AND s.iso_lote_referencia = {$loteref}";
					mysql_query($sqlup);

					$saidas = $this->GetQteForItens($pro_id, $loteref, $qteRest, $setor);


					foreach ($saidas as $arr) {
						$qteUp =  $arr['qte'];
						$idSaiUp =  $arr['idsaida'];
						$sqlupdate = "UPDATE tmsd_itenssaida s SET s.isa_qte = s.isa_qte - {$qteUp} WHERE  s.isa_idproduto={$pro_id} AND s.isa_loteref = {$loteref} AND s.isa_id={$idSaiUp}";  
						
						mysql_query($sqlupdate);
					}

				}
				

			}
			else{

				$saidas = $this->GetQteForItens($pro_id, $loteref, $qte, $setor);

				foreach ($saidas as $arr) {
					$qteUp =  $arr['qte'];
					$idSaiUp =  $arr['idsaida'];
					$sqlupdate = "UPDATE tmsd_itenssaida s SET s.isa_qte = s.isa_qte - {$qteUp} WHERE  s.isa_idproduto={$pro_id} AND s.isa_loteref = {$loteref} AND s.isa_id={$idSaiUp}";  
					error_log($sqlupdate);
					mysql_query($sqlupdate);
				}

			}
		}

		public function updateConfereSaida( $dados , $in = null ){
			
			
			if( !empty( $in ) ){
				$in = " AND isa_idproduto IN (".implode(',',$in).")";
			}

			$sql = "UPDATE tmsd_itenssaida SET
						isa_conferente = '{$dados['conferente']}',
						isa_dataconferencia = '" . date('Y-m-d H:i:s') . "'
					WHERE isa_idsaida = {$dados['idsaida']} {$in}";
					
			$res = mysql_query($sql);

			return $res;	
		}

		public function updateConfereSaidaConsignado($dados, $in = null){
			if( !empty( $in ) ){
				$in = " AND isa_idproduto IN (".implode(',',$in).")";
			}

			$sql = "UPDATE tmsd_itenssaida SET
						isa_conferente = '{$dados['conferente']}',
						isa_dataconferencia = '" . date('Y-m-d H:i:s') . "'
					WHERE isa_consignado = 1 AND isa_idsaida = {$dados['idsaida']} {$in}";
					
			$res = mysql_query($sql);

			return $res;			
		}

		public function updateConfereSaida2( $dados , $id, $limite ){

			if( !empty( $id ) ){
				$where2 = " AND isa_idproduto = '$id'";
			}

			$sql = "UPDATE tmsd_itenssaida SET
						isa_conferente = '{$dados['conferente']}',
						isa_dataconferencia = '" . date('Y-m-d H:i:s') . "'
					WHERE isa_idsaida = {$dados['idsaida']} $where2 LIMIT $limite";
					
			$res = mysql_query($sql);

			return $res;

		}

		public function updateSala($sma_id, $sma_sala){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_sala = '" . $sma_sala . "'
					WHERE isa_idsaida = " . $sma_id;
			$res = mysql_query($sql);
			if ($res) {
				return "UPDATE SALA >>>>>>>>>> <br>".$sql."<br><br><br>";
			} else {
				return "ERRO ".$sma_id." - SALA ".$sma_sala."<br><br><br>";
			}
		}

		public function updateQte($isa_id, $loteref, $pro_id,$qte,$setor, $saida, $client){
			$sql = "UPDATE tmsd_itenssaida s SET s.isa_qte={$qte}, s.isa_qte_origin = {$qte} WHERE s.isa_idsaida='{$isa_id}' AND s.isa_loteref='{$loteref}'";
			mysql_query($sql);
			

			$sqlsetor="SELECT se.set_id as id FROM tmsd_setores se WHERE se.set_nome='CME' and se.set_masterclient = {$client} ";
			$set = mysql_query($sqlsetor);
			$rowset = mysql_fetch_array($set, MYSQL_ASSOC);
			$cme = $rowset['id'];

			if($setor == $cme){

				$sqlcmeqte="  SELECT s.iso_qte , s.iso_id  FROM tmsd_itenssolicitacao s WHERE s.iso_idproduto={$pro_id} AND s.iso_lote_referencia={$loteref} ";
				$cmeqte = mysql_query($sqlcmeqte);
				$rowcme = mysql_fetch_array($cmeqte, MYSQL_ASSOC);

				if($rowcme['iso_qte'] >= $qte ){
					$sqlup = "UPDATE tmsd_itenssolicitacao s SET s.iso_qte = s.iso_qte - {$qte}  WHERE s.iso_idproduto = {$pro_id} AND s.iso_lote_referencia = {$loteref}";
					mysql_query($sqlup);
				}
				else{					
					$qteRest =  $qte - $rowcme['iso_qte'];

					$sqlup = "UPDATE tmsd_itenssolicitacao s SET s.iso_qte =  s.iso_qte - s.iso_qte   WHERE s.iso_idproduto = {$pro_id} AND s.iso_lote_referencia = {$loteref}";
					mysql_query($sqlup);

					$saidas = $this->GetQteForItens($pro_id, $loteref, $qteRest, $setor);


					foreach ($saidas as $arr) {
						$qteUp =  $arr['qte'];
						$idSaiUp =  $arr['idsaida'];
						$sqlupdate = "UPDATE tmsd_itenssaida s SET s.isa_qte = s.isa_qte - {$qteUp} WHERE  s.isa_idproduto={$pro_id} AND s.isa_loteref = {$loteref} AND s.isa_id={$idSaiUp}";  
						mysql_query($sqlupdate);
					}

				}
				

			}
			else{

				$saidas = $this->GetQteForItens($pro_id, $loteref, $qte, $setor);

				foreach ($saidas as $arr) {
					$qteUp =  $arr['qte'];
					$idSaiUp =  $arr['idsaida'];
					$sqlupdate = "UPDATE tmsd_itenssaida s SET s.isa_qte = s.isa_qte - {$qteUp} WHERE  s.isa_idproduto={$pro_id} AND s.isa_loteref = {$loteref} AND s.isa_id={$idSaiUp}";  
					mysql_query($sqlupdate);
				}
			}
	
			return '400';
		}

		private function GetQteForItens($idProduto, $loteref, $qte, $idsetor){
			$qteorigin = $qte;
			$sql = "SELECT s.isa_id, s.isa_qte FROM tmsd_itenssaida s WHERE s.isa_idproduto={$idProduto} AND s.isa_idsetordestino={$idsetor} AND s.isa_loteref={$loteref} ORDER BY s.isa_id";
			error_log('sql qte:'.$sql);
			$res = mysql_query($sql) or die(mysql_error());
			$arrSaidas = array();

			while ($row = mysql_fetch_assoc($res)) {
				if($qte > 0){
					if($qte >= $row['isa_qte']){
						$qte = $qte - $row['isa_qte'];
						array_push($arrSaidas,
							array(
								'idsaida'=> $row['isa_id'],
								'qte'=> $row['isa_qte']
							)
						);
					}
					else{
						$qte =  $row['isa_qte'] - $qte;
						array_push($arrSaidas,
							array(
								'idsaida'=> $row['isa_id'],
								'qte'=> $qteorigin
							)
						);
						break;
					}
				}
				else{
					break;
				}
			}
		  return  $arrSaidas;
		}

		public function verificaqte($isa_id, $loteref){
			$sql = "UPDATE tmsd_itenssaida s SET s.isa_qte=s.isa_qte+1 WHERE s.isa_idsaida='{$isa_id}' AND s.isa_loteref='{$loteref}'";
			mysql_query($sql);
			
			$sqlqte="SELECT s.isa_qte as newqte, s.isa_idproduto as prod FROM tmsd_itenssaida s WHERE s.isa_idsaida='{$isa_id}'";
			$res = mysql_query($sqlqte);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row;
		}

		public function verificaSaida($qrcode, $loteref){
			
			$sqlsoli = "SELECT COUNT(s.iso_qte) AS qte  FROM tmsd_itenssolicitacao s
			INNER JOIN tmss_produto p ON s.iso_idproduto = p.pro_id
			WHERE p.pro_qrcode='{$qrcode}' AND s.iso_lote_referencia='{$loteref}' AND s.iso_qte > 0";
			$ressoli = mysql_query($sqlsoli);
			$rowsoli = mysql_fetch_array($ressoli, MYSQL_ASSOC);

			
			$sqlsai="SELECT SUM(s.isa_qte) AS qte 
			FROM tmsd_itenssaida s
			INNER JOIN tmsd_saidamateriais sm ON s.isa_idsaida = sm.sma_id
			INNER JOIN tmss_produto p ON s.isa_idproduto = p.pro_id
			WHERE s.isa_loteref='{$loteref}' AND p.pro_qrcode = '{$qrcode}' AND sm.sma_tiposaida='T' AND s.isa_del IS null AND s.isa_qte > 0";
			$ressai = mysql_query($sqlsai);
			$rowsai = mysql_fetch_array($ressai, MYSQL_ASSOC);


			if ($rowsoli['qte'] > 0 || $rowsai['qte'] > 0 ){
				return 1;
			}
			else{
				return 0;
			}

			
		}

		public function verificaProd($qrcode){
			$sql = "SELECT p.pro_status, p.pro_del  FROM tmss_produto p WHERE p.pro_qrcode='$qrcode'";

			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row;
		}

		public function selectItemSaida($id){
			$sql = "SELECT * FROM tmsd_itenssaida WHERE isa_id = " . $id . " AND isa_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensSaidaModel();
			$obj->isa_id = $row['isa_id'];
			$obj->isa_idsaida = $row['isa_idsaida'];
			$obj->isa_data = $row['isa_data'];
			$obj->isa_sala = $row['isa_sala'];
			$obj->isa_idproduto = $row['isa_idproduto'];
			$obj->isa_lote = $row['isa_lote'];
			$obj->isa_validade = $row['isa_validade'];
			$obj->isa_reuso = $row['isa_reuso'];
			$obj->isa_consignado = $row['isa_consignado'];
			$obj->isa_obs = $row['isa_obs'];
			return $obj;
		}

		public function selectAll($where, $join = false){
			
			if(isset($where)){
				$where = "AND " . $where; //isa_idproduto = pro_id
			}

			if( $join ){
				$where .= " AND sma_tiposaida!='T'";
				$join = "LEFT JOIN tmsd_saidamateriais ON sma_id = isa_idsaida INNER JOIN tmss_produto ON pro_id = isa_idproduto ";
			}
			else{
				$join = '';
			}

			$sql = "SELECT tmsd_itenssaida.* 
					FROM tmsd_itenssaida 
					{$join}
					WHERE (isa_del IS NULL OR isa_del != '*') " . $where . " ORDER BY isa_data";

			$res = mysql_query($sql);

			$a = null;
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_qrcode = $row['pro_qrcode'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$obj->isa_qte = $row['isa_qte'];
				$obj->loteref = $row['isa_loteref'];
				$obj->isa_idsetororigem = $row['isa_idsetorigem'];
				$obj->isa_idsetordestino = $row['isa_idsetordestino'];
				$obj->isa_consignado = $row['isa_consignado'];
				$a[] = $obj;
			}
			return $a;
		}

		public function getItemBySaidaEProduto($idsaida, $pro_id){
			$sql = "SELECT * FROM tmsd_itenssaida WHERE (isa_del IS NULL OR isa_del != '*') AND isa_idsaida = $idsaida ORDER BY isa_data desc limit 1";

			$res = mysql_query($sql);
			return mysql_fetch_assoc($res);
		}

		public function selectAllConsignado($where, $join = false){

			if(isset($where)){
				$where = "AND " . $where;
			}

			if( $join ){
				$where .= " AND sma_tiposaida!='T'";
				$join = "LEFT JOIN tmsd_saidamateriais ON sma_id = isa_idsaida INNER JOIN tmss_prodconsignado ON pro_id = isa_idproduto ";
			}
			else{
				$join = '';
			}

			$sql = "SELECT tmsd_itenssaida.* 
					FROM tmsd_itenssaida 
					{$join}
					WHERE (isa_del IS NULL OR isa_del != '*') " . $where . " ORDER BY isa_data";

			$res = mysql_query($sql);

			$a = null;
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_qrcode = $row['pro_qrcode'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$obj->isa_idsetororigem = $row['isa_idsetorigem'];
				$obj->isa_idsetordestino = $row['isa_idsetordestino'];
				$obj->isa_consignado = $row['isa_consignado'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectItensTransferencia($id_tes, $qrcode, $loteref=0){
			$sql = "select pro_composto from tmss_produto where pro_qrcode = '$qrcode' and pro_del is null";
			$is_box = mysql_result(mysql_query($sql), 0);

			if(false){ 
				$sql = "SELECT  tmsd_itenssaida.*, tmss_produto.pro_id, tmss_produto.pro_nome, tmss_produto.pro_qrcode, tmsd_setores.set_nome from tmsd_itenssaida LEFT JOIN  tmsd_saidamateriais ON isa_id = sma_id LEFT join tmss_produto ON pro_id = isa_idproduto
LEFT JOIN tmsd_setores ON isa_idsetordestino = set_id 
WHERE tmsd_itenssaida.isa_idsaida = $id_tes limit 1";
			}else{
				/** retorna todos os itens de saida no formato tranferencia de estoque **/
				if ($loteref > 0){
							$sql = "SELECT  tmsd_itenssaida.*, 
							tmss_produto.pro_id, 
							tmss_produto.pro_nome, 
							CASE
							WHEN
							tmsd_itenssaida.isa_loteref >0
							THEN
							CONCAT(tmss_produto.pro_qrcode,'.',tmsd_itenssaida.isa_loteref)
							ELSE
								tmss_produto.pro_qrcode
							END as pro_qrcode, 
							tmsd_setores.set_nome
					FROM tmsd_itenssaida
					INNER JOIN tmsd_saidamateriais ON sma_tiposaida = 'T' AND sma_del is NULL AND sma_id = isa_idsaida
					INNER JOIN tmss_produto ON pro_id = isa_idproduto
					LEFT JOIN tmsd_setores ON set_id = isa_idsetorigem
					WHERE isa_idsaida =  '" . $id_tes . "' AND isa_loteref ='{$loteref}' AND tmss_produto.pro_qrcode='{$qrcode}'  AND isa_del IS NULL limit 1";
				}
				else{
				$sql = "SELECT  tmsd_itenssaida.*, 
								tmss_produto.pro_id, 
								tmss_produto.pro_nome, 
								CASE
						   	    WHEN
						          tmsd_itenssaida.isa_loteref >0
						        THEN
						          CONCAT(tmss_produto.pro_qrcode,'.',tmsd_itenssaida.isa_loteref)
								ELSE
						     		tmss_produto.pro_qrcode
								END as pro_qrcode, 
								tmsd_setores.set_nome
				        FROM tmsd_itenssaida
				        INNER JOIN tmsd_saidamateriais ON sma_tiposaida = 'T' AND sma_del is NULL AND sma_id = isa_idsaida
				        INNER JOIN tmss_produto ON pro_id = isa_idproduto
				        LEFT JOIN tmsd_setores ON set_id = isa_idsetorigem
						WHERE isa_idsaida =  " . $id_tes . " AND tmss_produto.pro_qrcode='{$qrcode}' AND  tmsd_itenssaida.isa_id IN (SELECT MAX(sai.isa_id) AS  isa_id FROM  tmsd_itenssaida sai
						INNER JOIN tmss_produto prod ON prod.pro_id = sai.isa_idproduto
						  WHERE tmsd_itenssaida.isa_idsaida ='{$id_tes}' AND prod.pro_qrcode='{$qrcode}')  AND isa_del IS NULL limit 1";
				}

				echo $sql;

			}


			
			
	
			$res = mysql_query($sql) or die(mysql_error());;

			$itens = array();

			while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
    			$obj->isa_idproduto = $row['isa_idproduto'];
    			$obj->isa_idsetororigem = $row['isa_idsetorigem'];
    			
				
				//auxiliar
				$obj->loteref= $row['isa_loteref'];
				$obj->isa_qte= $row['isa_qte'];
				$obj->pro_id= $row['pro_id'];
				$obj->isa_produto = $row['pro_nome'];
    			$obj->isa_qrcode = $row['pro_qrcode'];
    			$obj->isa_setorOrigem = ($row['set_nome'] == NULL ? 'Material Novo' : $row['set_nome']);

    			$itens[] = $obj;
				
			}
			return $itens;
		}

		public function selectItensSaidaBySetor($sma_idsetor = 0, $where, $order){
			
		    if($where != '')
		        $where = ' AND ' . $where;

	        $sql = ' SELECT isa_id,isa_idsaida,isa_data,isa_sala,isa_idproduto,isa_lote,isa_validade,isa_reuso,isa_obs FROM tmsd_itenssaida'
	             . ' INNER JOIN tmsd_saidamateriais ON sma_id = isa_idsaida'
                 . ' AND (sma_del IS NULL OR sma_del != "*") '
	             . ' INNER JOIN tmsd_setores ON set_id = sma_idsetor'
				 . ' INNER JOIN tmss_produto ON pro_id = isa_idproduto AND pro_del IS NULL'
	             . ' AND pro_idcliente = '. $_SESSION['usu_masterclient']		             
                 . ' WHERE (isa_del IS NULL OR isa_del != "*")'
                 . ( $sma_idsetor > 0 ? ' AND sma_idsetor = '. $sma_idsetor : '' )
                 . $where
	             . ' ORDER BY ' . $order;
	             //. ' LIMIT 0, 1000 ';

	        $res = mysql_query($sql);
	        $a = array();
	        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
	            $obj = new ItensSaidaModel();
	            $obj->isa_id = $row['isa_id'];
	            $obj->isa_idsaida = $row['isa_idsaida'];
	            $obj->isa_data = $row['isa_data'];
	            $obj->isa_sala = $row['isa_sala'];
	            $obj->isa_idproduto = $row['isa_idproduto'];
	            $obj->isa_lote = $row['isa_lote'];
	            $obj->isa_validade = $row['isa_validade'];
	            $obj->isa_reuso = $row['isa_reuso'];
	            $obj->isa_obs = $row['isa_obs'];
	            $a[] = $obj;
	        }
	        return $a;
		}

		public function selectItemUltimaSaida($id, $wherePeriodo){
			$sql = "SELECT * FROM tmsd_itenssaida
					WHERE isa_idproduto = " . $id . " AND isa_del IS NULL
					". $wherePeriodo ."
					ORDER BY isa_data DESC";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$a[] = $obj;
			}
			return $a;
		}
	
		public function selectItemUltima($idProduto){
			// Retorna o ultimo registro de movimento de um produto seja Transferencia ou saida para uso 
			// Utilizado por produtosEstoque.php
			
			$sql = "select * FROM tmsd_itenssaida 
			LEFT JOIN tmsd_setores ON set_id = isa_idsetorigem 
			WHERE isa_idproduto = " . $idProduto . " AND isa_del IS NULL ORDER BY isa_id DESC LIMIT 1";
					
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$obj->isa_conferente = $row['isa_conferente'];
				$obj->isa_idsetororigem = $row['isa_idsetorigem'];
				$obj->isa_idsetordestino = $row['isa_idsetordestino'];
				$obj->isa_consignado = $row['isa_consignado'];				
			return $obj;
		}			
		
		public function selectItemUltimaMovimentacao($idProduto){
			// Retorna o ultimo registro de movimento de um produto seja Transferencia ou saida para uso 
			// Utilizado por produtosEstoque.php
			
			$sql = "select isa_id, isa_idsaida, isa_data, isa_sala, isa_idproduto, isa_lote, isa_validade, isa_reuso, isa_obs FROM tmsd_itenssaida
					WHERE ISA_ID = (
						SELECT max(isa_id) FROM tmsd_itenssaida
						WHERE isa_idproduto = " . $idProduto . " AND isa_del IS NULL)";
					
			
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				
			
			return $obj;
		}
		
		

		public function delete($id){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_del = '*'
					WHERE isa_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Devolu??o de material. Exclus?o: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function countItens($id){
			$sql = "SELECT COUNT(*)AS total FROM tmsd_itenssaida WHERE isa_idproduto =". $id . " AND isa_consignado = 0 AND isa_del IS NULL";
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['total'];
		}
		
		public function delItensTransf($id){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_del = '*'
					WHERE isa_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Tranferencia de estoque de material. Exclus?o: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectItemTransferenciaAberta($where){
			/*Verifica se item ja nao esta em alguma transferencia que ainda nao foi finalizada*/
		    if(isset($where) && $where != "")
		        $where = "AND " . $where;
		        $sql = "SELECT count(*) as total FROM tmsd_itenssaida
		        		INNER JOIN tmsd_saidamateriais
						ON sma_id = isa_idsaida
						AND sma_idsetor = 0
						AND sma_tiposaida = 'T'
						AND sma_del IS NULL
					WHERE isa_del IS NULL AND " . $where . ";";
		        $res = mysql_query($sql);

		        $row = mysql_fetch_array($res, MYSQL_ASSOC);

		        return $row['total'];
		}
		
		public function limpaItensTransferencia(){
			/** Exclui as transferencias nao finalizadas, causadas por sa?da do procedimento se a sua canclusao**/
			$sql = "UPDATE tmsd_itenssaida 
					INNER JOIN tmsd_saidamateriais
					ON tmsd_saidamateriais.sma_id = isa_idsaida
					AND sma_idusuario = '".$_SESSION['usu_id']."'
					AND sma_masterclient = '".$_SESSION['usu_masterclient']."'
					AND sma_idsetor = '0'
					AND sma_del IS NULL
					SET isa_del = '*'
					WHERE isa_del is NULL; ";
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclus?o: limpesa de itens da transferencia de estoque n?o concluidas em tmsd_transferenciaestoque.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function updateSetDestino(){
			$except = $this->filterItensBeforeTransfer($this->isa_idsaida);
			
			$sql = "UPDATE tmsd_itenssaida SET
						isa_idsetordestino = '" . $this->isa_idsetordestino . "'
					WHERE isa_idsaida = " . $this->isa_idsaida." 
					AND isa_del is NULL" . $except;
			$res = mysql_query($sql);

			if($res) {
				// log
				$this->log_acao = "Atualiza??o: idsaida " . $this->isa_idsaida . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
				return $this->isa_idsaida;
			} else {
				return 0;
			} 
		}

		private function filterItensBeforeTransfer($isa_idsaida){

			$sql = "SELECT `isa_idproduto`, `isa_id` FROM `tmsd_itenssaida` WHERE `isa_idsaida` = $isa_idsaida AND isa_del IS NULL";
			$res = mysql_query($sql);
			$idprod = array();
			while ($row = mysql_fetch_assoc($res)) {
			    array_push($idprod, $row);
			}



			$result = "";



			for($i=0; $i< count($idprod); $i++){
				
				$id_produto =  $idprod[$i]['isa_idproduto'];

				$sql2 = "SELECT pco_idpai FROM tmss_produtoscompostos WHERE `pco_idfilho` = $id_produto AND pco_del IS NULL";
				$res2 = mysql_query($sql2) or die('a?lsdkfj?alsdfj');
				$id_pai = mysql_result($res2, 0);

				$sql3 = "SELECT EXISTS (SELECT * FROM tmss_produtoscompostos WHERE `pco_idfilho` = $id_produto AND pco_del IS NULL)";
				$res3 = mysql_query($sql3);
				$is_compound_son = mysql_result($res3, 0);

				if(!$is_compound_son){
					$result .= '';
				}else{
					$sql4 = "SELECT MAX(`iso_contagem`) as maior FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $id_produto";
					$res4 = mysql_query($sql4);
					$maior_filho = mysql_result($res4, 0);

					$sql5 = "SELECT MAX(`iso_contagem`) as maior FROM `tmsd_itenssolicitacao` WHERE `iso_idproduto` = $id_pai";
					$res5 = mysql_query($sql5);
					$maior_pai = mysql_result($res5, 0);

					if($maior_filho < $maior_pai){
							$result .= " AND isa_idproduto != $id_produto ";
							$sql6 = "UPDATE tmsd_itenssaida a, tmsd_itenssaida b \n"
    						. "SET b.isa_idsetordestino = a.isa_idsetorigem\n"
    						. "WHERE a.isa_idsaida = $isa_idsaida\n"
    						. " AND b.isa_idproduto = $id_produto";
    						$res = mysql_query($sql6);
					}else{
						$result .= '';
					}
					
				}	


			}
			
			return $result;

		}

		public function selectIntensBySolicitacao($id) {
			$sql = "SELECT isa_idproduto, isa_idsetorigem, isa_idsetordestino FROM `tmsd_itenssaida` WHERE `isa_idsaida` = $id";

			$res = mysql_query($sql) or die(mysql_error());

			$response = array();
			while ($row = mysql_fetch_array($res)) {

				if($this->isCompoundItem($row['isa_idproduto'])){
					continue;
				}
				

				$prodInfo = $this->getProdInfo($row['isa_idproduto']);
				$row['isa_nome_produto'] = $prodInfo[0]['pro_nome'];
				$row['isa_qrcode_produto'] = $prodInfo[0]['pro_qrcode'];

			  	array_push($response, $row);
			}

			return $response;
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


		public function getcombo($qrcode, $loteref, $master){

			$temCme = false;

			$sqlsetor="SELECT se.set_id as id FROM tmsd_setores se WHERE se.set_nome='CME' and se.set_masterclient = {$master}";
			$set = mysql_query($sqlsetor);
			$rowset = mysql_fetch_array($set, MYSQL_ASSOC);
			$cme = $rowset['id'];
			
			$sqlIsa = "SELECT DISTINCT  s.isa_idsetordestino  as idsetor, setor.set_nome as nome

					   FROM tmsd_itenssaida s
					   INNER JOIN tmsd_saidamateriais sa ON sa.sma_id = s.isa_idsaida
				       INNER JOIN tmss_produto p  ON s.isa_idproduto=p.pro_id
					   INNER JOIN tmsd_setores setor ON setor.set_id=s.isa_idsetordestino

					   WHERE s.isa_loteref='{$loteref}' AND p.pro_qrcode='{$qrcode}' AND s.isa_idsetordestino IS NOT null AND sa.sma_tiposaida='T' AND s.isa_qte > 0 ";
			$resIsa = mysql_query($sqlIsa);

			$sqlIso = "SELECT DISTINCT setor.set_id as id, setor.set_nome as nome
				       FROM tmsd_setores setor ,tmsd_itenssolicitacao s  
			           INNER JOIN tmss_produto p  ON s.iso_idproduto=p.pro_id
					   WHERE s.iso_lote_referencia='{$loteref}' AND p.pro_qrcode='{$qrcode}' AND setor.set_nome='CME' AND s.iso_qte > 0 and setor.set_masterclient = {$master}";

			 $resIso = mysql_query($sqlIso);

			 $setores = array();
			
				while ($rowIsa = mysql_fetch_assoc($resIsa)) {

					if ($rowIsa['idsetor'] == $cme){
						$temCme = true;
					}

					array_push($setores,
						array(
							'idsetor'=>$rowIsa['idsetor'],
							'nome'=>$rowIsa['nome']
						)
					 );
				}

				if($temCme == false){
					while ($rowIso = mysql_fetch_assoc($resIso)) {
						array_push($setores,
							array(
								'idsetor'=>$rowIso['id'],
								'nome'=>$rowIso['nome']
							)
						 );
					}
				}		
					
			return  $setores;

		}


		private function getProdInfo ($id) {
			$sql = "SELECT pro_nome,pro_qrcode FROM `tmss_produto` WHERE pro_id = '$id'";
			$res = mysql_query($sql) or die(mysql_error());
			$response = array();

			while ($row = mysql_fetch_array($res)) {
			    array_push($response, $row);
			}

			//setlocale(LC_CTYPE, '');
			//$response[0]['pro_nome'] = mb_detect_encoding($response[0]['pro_nome']);
			$response[0]['pro_nome'] = iconv('ASCII', 'ASCII//IGNORE', $response[0]['pro_nome']);
			

			return $response;
		}

		public function getSetorName($id){

			if($id == 0){
				return 'PRODUTO NOVO';
			}

			$sql = "SELECT set_nome FROM `tmsd_setores` WHERE `set_id` = '$id'";

			$result = mysql_query($sql);

			return mysql_result($result, 0);
		}

		public function getQte($loteref, $qrcode, $setor, $usu){

		$sqlsetor="SELECT se.set_id as id FROM tmsd_setores se WHERE se.set_nome='CME' and se.set_masterclient = {$usu}";
		$set = mysql_query($sqlsetor);
		$rowset = mysql_fetch_array($set, MYSQL_ASSOC);
		$cme = $rowset['id'];
			if($setor != $cme){
				$sql="SELECT  SUM(saida.isa_qte) as qte, saida.isa_idproduto as id 
				FROM tmsd_itenssaida saida 
				INNER JOIN tmss_produto prod ON saida.isa_idproduto = prod.pro_id
				WHERE saida.isa_loteref='{$loteref}' AND prod.pro_qrcode='{$qrcode}' AND saida.isa_idsetordestino= {$setor}";

				
			}
			else{
				
				$sql ="SELECT s.iso_qte as qte , s.iso_idproduto as id FROM
				tmsd_itenssolicitacao s
				INNER JOIN tmss_produto p ON p.pro_id = s.iso_idproduto
				WHERE s.iso_lote_referencia={$loteref} AND p.pro_qrcode='{$qrcode}'";

			}	
			$res = mysql_query($sql);

			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$count = count($row);
			if ($count < 1){
				return '404';
			}
			else{

				if($setor == $cme ){

					$sqlCme = "SELECT sum(s.isa_qte) AS qte, s.isa_idproduto AS id FROM tmsd_itenssaida s
					INNER JOIN tmss_produto p ON s.isa_idproduto= p.pro_id 
					WHERE s.isa_loteref={$loteref}  AND p.pro_qrcode='{$qrcode}' and s.isa_idsetordestino = {$setor} ";

					$setCme = mysql_query($sqlCme);
					$rowCme = mysql_fetch_array($setCme, MYSQL_ASSOC);

					$row['qte'] = $row['qte'] + $rowCme['qte'];

					return $row;

				}
				else{
					return $row;
				}		

			}
		}

		public function getLastSmaIdFromFather($id){
			$sql = "SELECT isa_idsaida FROM `tmsd_itenssaida` 
			left join tmsd_saidamateriais on sma_id = isa_idsaida WHERE `isa_idproduto` = '$id' AND sma_tiposaida != 'S' AND isa_del is null order by isa_id desc limit 1";
			return mysql_result(mysql_query($sql), 0);
		}

		public function getSetorOrigin($pro_id){
			$sql = "SELECT isa_idsetordestino FROM `tmsd_itenssaida` 
			left join tmsd_saidamateriais on sma_id = isa_idsaida WHERE `isa_idproduto` = '$pro_id' AND isa_del is null order by isa_id desc limit 1";

			return mysql_result(mysql_query($sql), 0);
		}

		public function getSonsByFather($pro_id){

			$sql = "SELECT max(iso_contagem) from tmsd_itenssolicitacao where iso_idproduto = $pro_id and iso_del is null";
			$contagem_pai = mysql_result(mysql_query($sql), 0);
		

			$sql = "SELECT pco_idfilho from tmss_produtoscompostos where pco_idpai = $pro_id AND pco_del is NULL";
			$res = mysql_query($sql);
			$ids = '';
			while ($row = mysql_fetch_assoc($res)) {
				$ids .= $row['pco_idfilho'] . ',';
			}
			$ids = substr($ids, 0, -1); // "5,7,6,4"
			

			$sql = "SELECT iso_idproduto FROM `tmsd_itenssolicitacao` WHERE iso_idproduto IN($ids) AND iso_status = 1 AND iso_del IS NULL AND iso_contagem = $contagem_pai";
			
			$res = mysql_query($sql);
			$arr = array();
			while ( $row = mysql_fetch_assoc($res)) {
				array_push($arr, $row['iso_idproduto']);
			}
			return $arr;
		}


		public function getLastSetorDestino($id){
			$sql = "SELECT isa_idsetordestino FROM tmsd_itenssaida left JOIN tmsd_saidamateriais ON isa_idsaida = sma_id WHERE isa_idproduto = $id AND isa_del IS NULL AND sma_tiposaida = 'T' ORDER BY isa_id DESC LIMIT 1";
			$res = mysql_query($sql);
			return mysql_result($res, 0);
		}
		

	}
?>