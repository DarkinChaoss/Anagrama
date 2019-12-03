<?php
	class ItensSolicitacoesController {

        public static function insert($dados){

		
			//verifica qual o lote do detergente
			$tipodetergente = '';
			if($_POST['estandar'] == 0){
				$tipodetergente = 'V';
			}else{
				$tipodetergente = 'I';
			}
			
			$lotedetergente = '';
			if($_POST['loteenzimatico'] != ''){
				$lotedetergente = $_POST['loteenzimatico'];
			}else{
				$lotedetergente = $_POST['loteneutro'];
			}
			
			// atualiza status da solicita��o: 1 (confer�ncia)
			$ses = new SolicitacoesModel();
			$ses = $ses->selectSolicitacao($dados['idSolicitacao']);
			$ses->ses_status = "1";
			$ses->update();

			
			if($dados['qtde'] != ''){
					$itens = new ItensSolicitacaoModel();
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_qte = $dados['qtde'];
					//apenas para verificar quantidade
					$itens->pro_qtde = $dados['qtde'];
					
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
				
					$itens->insert();
				
				return 'OK';
			}else{
				// insere item
				if($dados['chProduto'] == 'pc'){
					$itens = new ItensSolicitacaoModel();
					$itens->iso_consignado = 1;
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
					$itens->insert();					
				}else if($dados['chProduto'] == 'pn'){
					$itens = new ItensSolicitacaoModel();
					$itens->iso_consignado = 0;
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
					$itens->insert();					
				}else{
					$itens = new ItensSolicitacaoModel();
					$itens->iso_consignado = 0;
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
					$itens->iso_verify_conf = 1; //quando for 1 � por est� inserido na tabela assim fazendo a contagem de quantos existem e quantos est�o fora da solicita��o da composi��o da caixa
					$itens->insert();						
				}
				//produtos normais est�o aqui
				return 'OK';			
			}
		}
		
		public static function insertProComQtd($dados){
			$tipodetergente = '';
			if($_POST['estandar'] == 0){
				$tipodetergente = 'V';
			}else{
				$tipodetergente = 'I';
			}
			
			$lotedetergente = '';
			if($_POST['loteenzimatico'] != ''){
				$lotedetergente = $_POST['loteenzimatico'];
			}else{
				$lotedetergente = $_POST['loteneutro'];
			}
			// atualiza status da solicita��o: 1 (confer�ncia)
			$ses = new SolicitacoesModel();
			$ses = $ses->selectSolicitacao($dados['idSolicitacao']);
			$ses->ses_status = "1";
			$ses->update();

			
			if($dados['qtde'] != ''){
				for ($i = 1; $i <= $dados['qtde']; $i++) {
					$itens = new ItensSolicitacaoModel();
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = 0;
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					//apenas para verificar quantidade
					$itens->pro_qtde = $dados['qtde'];
					
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
				
					$itens->insert();
				}	
				
				return 'OK';
			}else{
				// insere item
		
				if($dados['chProduto'] == 'pc'){
					$itens = new ItensSolicitacaoModel();
					$itens->iso_consignado = 1;
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
					$itens->insert();					
				}else if($dados['chProduto'] == 'pn'){
					$itens = new ItensSolicitacaoModel();
					$itens->iso_consignado = 0;
					$itens->iso_idses = $dados['idSolicitacao'];
					$itens->iso_idproduto = $dados['idProduto'];
					$itens->iso_idmetodo = $dados['metEsterilizacao'];
					$itens->iso_idequipamento = $dados['eqEsterilizacao'];
					$itens->iso_nreuso = $dados['nReuso'];
					$itens->iso_idrtecnico = $dados['rTecnico'];
					$itens->iso_loteequipamento = $dados['loteequipamento'];
					$itens->iso_tipodetergente = $tipodetergente;
					$itens->iso_lotedetergente = $lotedetergente;
					$itens->iso_referencia = ( array_key_exists('iditemsol', $dados ) ? $dados['iditemsol'] : null ) ;
					$itens->insert();					
				}

				return 'OK';			
			}
		}
		
		//cleverson
		public static function countComun($iso_id){
			$item = new ItensSolicitacaoModel();
			return $item->countItensTelaSolicitacao($iso_id);
		} 

		//cleverson
		public static function countConsignados($iso_id){
			$item = new ItensSolicitacaoModel();
			return $item->countItensTelaSolicitacaoConsignados($iso_id);
		} 

		public static function update($dados){
			$itens = new ItensSolicitacaoModel();
			$itens->iso_id = $dados['id'];
			$itens->iso_idses = $dados['IdSolicitacao'];
			$itens->iso_idproduto = $dados['Idproduto'];
			$itens->iso_idmetodo = $dados['metEsterilizacao'];
			$itens->iso_idequipamento = $dados['eqEsterilizacao'];
			$itens->iso_nreuso = $dados['nReuso'];
			$itens->iso_idrtecnico = $dados['slRTecnico'];
			$itens->iso_loteequipamento = $dados['loteequipamento'];
			$itens->iso_lote = $dados['lote'];
			$itens->iso_dataesterilizacao = $dados['dtEsterilizacao'];
			$itens->iso_datalimite = $dados['dataLimite'];
			$itens->iso_status = $dados['status'];
			$itens->iso_idequipamentoet = $dados['eqEsterilizacaoet'];
			$itens->iso_verify_conf = 0;
			return $itens->update();
		}
		
		public static function updateConfPai($dados){
			$itens = new ItensSolicitacaoModel();
			$itens->iso_id = $dados['id'];
			$itens->iso_dataconferencia = $dados['dataconferencia'];
			$itens->iso_conferidopor = $dados['conferidopor'];
			return $itens->updateConferenciaPai();	
		}

		public static function delete($iso_id){
			$itens = new ItensSolicitacaoModel();
			return $itens->delete($iso_id);
		}
		
		public static function countProdetiquetagem($id){
			$item = new ItensSolicitacaoModel();
			return $item->countProductEtiquetagem($id);
		}		

		public static function getItemCount($id, $qtde){
			$item = new ItensSolicitacaoModel();
			return $item->selectCountproduct($id, $qtde);
		}
		public static function getItemCountNew($id, $qtde){
			$item = new ItensSolicitacaoModel();
			return $item->selectCountproductNew($id, $qtde);
		}
		public static function verificarQte($id, $qtde){
			$item = new ItensSolicitacaoModel();
			return $item->verificarQte($id, $qtde);
		}

		public static function getLote($id){
			$item = new ItensSolicitacaoModel();
			return $item->getLote($id);
		}
		
		public static function selectUltimAuto($id){
			$item = new ItensSolicitacaoModel();
			return $item->selectUltimAutorizacao($id);		
		}
		
		

		public static function selectItemidproduct($id){
			$item = new ItensSolicitacaoModel();
			return $item->selectItemidproduct($id);		
		}
		
		public static function getItemUltimo($id){
			$item = new ItensSolicitacaoModel();
			return $item->selectItemUltimo($id);			
		}
		
		public static function getItem($id){
			$item = new ItensSolicitacaoModel();
			return $item->selectItem($id);
		}

		//AQUI VERIFICA A COMPOSI��O DA CAIXA E QUAIS PRODUTOS FORAM INSERIDOS J� 
		public static function getItens($where, $order = "pro_nome, iso_idses"){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAll($where, $order);
		}
		
		public static function getItens2($where, $order = ""){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAll2($where, $order);
		}
		
		public function selectProdCompostoOfClean($idcomposto, $ids){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectProdCompostoOfSolicitacaoClean($idcomposto, $ids);	
		}
		
		public static function selectProdCompostOf($idcomposto, $ids){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectProdCompostoOfSolicitacao($idcomposto, $ids);			
		} 

		public static function selectProdCompost($idcomposto){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectProdCompostoinSolicitacao($idcomposto);			
		} 

		public static function getItensLimite($where, $order = "pro_nome, iso_idses" , $limite = 1){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllLimite($where, $order, $limite);
		}

		public static function getCountItens($where){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllCount($where);
		}

		public static function getItensTelaSolicitacaoConsignado($ses_id){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectItensTelaSolicitacaoConsignados($ses_id);
		}

		// cleverson matias
		public static function countComumEtiquetagem(){
			$itens = new ItensSolicitacaoModel();
			return $itens->countItensEtiquetagem();
		}

		// cleverson matias
		public static function countConsigEtiquetagem(){
			$itens = new ItensSolicitacaoModel();
			return $itens->countItensEtiquetagemConsig();
		}
		
		public static function getItensTelaSolicitacao($ses_id){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectItensTelaSolicitacao($ses_id);
		}

		public static function getItensEtiquetagem($search){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectItensEtiquetagem($search);
		}

		// cleverson matias
		public static function getItensEtiquetagemConsignados(){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectItensEtiquetagemConsignados();
		}		
		
		public static function getLotes($where){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectLotes($where);
		}

		public static function getLotesEt($where){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectLotesEt($where);
		}

		public static function getLotes_equipamentos($where){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectequipamentos($where);
		}

		public static function getReprocessamentoItem($idProduto, $status = 'x'){ // se status � informado, conta apenas reprocessamentos naquele status
			$itens = new ItensSolicitacaoModel();
			return $itens->contItem($idProduto, $status);
		}
		
		public static function getReprocessamentoItemConsignado($idProduto, $status = 'x'){ // se status � informado, conta apenas reprocessamentos naquele status
			$itens = new ItensSolicitacaoModel();
			return $itens->contItemConsignado($idProduto, $status);
		}

		public static function getItemBySolicitacaoEProduto($idSes, $idProduto, $status = "x"){
			if($status != "x")
				$whereStatus = " AND iso_status = '" . $status . "'";
			else
				$whereStatus = "";
			$itens = new ItensSolicitacaoModel();
			$arr = $itens->selectAll("iso_idses = " . $idSes . " AND iso_idproduto = " . $idProduto . $whereStatus);
			$item = new ItensSolicitacaoModel();
			$item = $arr[0];
			return $item;
		}


		
		public static function getItemBySolicitacaoEProdutoConsignado($idSes, $idProduto, $status = "x"){
			if($status != "x")
				$whereStatus = " AND iso_status = '" . $status . "'";
			else
				$whereStatus = "";
			$itens = new ItensSolicitacaoModel();
			$arr = $itens->selectAllConsignado("iso_idses = " . $idSes . " AND iso_idproduto = " . $idProduto . $whereStatus);
			$item = new ItensSolicitacaoModel();
			$item = $arr[0];
			return $item;
		}

		public static function checkProdutoInSolicitacao($idProduto, $idSes){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllCount("iso_idses = " . $idSes . " AND iso_idproduto = " . $idProduto);
		}

		public static function getUltimoMetodo(){
			$item = new ItensSolicitacaoModel();
			return $item->selectLastMetodo();
		}

		public static function getUltimoRTecnico(){
			$item = new ItensSolicitacaoModel();
			return $item->selectLastRTecnico();
		}

		public static function getLastMetodoERespTec(){
			$item = new ItensSolicitacaoModel();
			return $item->selectLastMetodoERespTec();
		}

		public static function getUltimoLote(){
			$item = new ItensSolicitacaoModel();
			return $item->selectLastLote();
		}

		public static function getUltimoReprocDeItem($id){
			$item = new ItensSolicitacaoModel();
			return $item->selectLastReproc($id);
		}

		public static function relItens($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllOrder($where, $order);
		}

		public static function relItensConsignado($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllOrderConsignado($where, $order);
		}

		public static function relControleEsterilizacao($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectControleEsterilizacao($where, $order);
		}
		
		public static function responsavel_tecnico($where){
			$itens = new ItensSolicitacaoModel();
			return $itens->responsavel($where);
		}
		

		public static function relControleEsterilizacaoPrint($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectControleEsterilizacaoPrint($where, $order);
		}		


		public static function CustoControleEsterilizacao($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectcustoControleEsterilizacao($where, $order);
		}


		public static function relSecaoEsterilizacao($ano){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectSecaoEsterilizacao($ano);
		}

		public static function alteraDataLimite($id, $dataLimite){
		    $itens = new ItensSolicitacaoModel();
		    return $itens->setDataLimite($id, $dataLimite);
		}

		public static function relItensSterilab($where, $order){
			$itens = new ItensSolicitacaoModel();
			return $itens->selectAllOrderSterilab($where, $order);
		}

		public static function deleteDuplicados($idItem, $idSes, $idProduto){
			$itens = new ItensSolicitacaoModel();
			return $itens->deleteWhere($idItem, $idSes, $idProduto);
		}

		public static function byItemId($itemId){
			$itens = new ItensSolicitacaoModel();
			return $itens->byItemId($itemId);
		}

	}
?>