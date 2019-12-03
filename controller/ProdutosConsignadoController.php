<?php
	class ProdutosConsignadoController {

		public static function insert($dados){
			$consignado = new ProdutosConsignadoModel();
			$consignado->pro_idsetor = $dados['setor'];
			$consignado->pro_qrcode = $dados['qrcode'];
			$consignado->pro_qtde = $dados['qtde'];
			$consignado->pro_nome = utf8_decode($dados['nome']);
			$consignado->pro_curvatura = $dados['curvatura'];
			$consignado->pro_calibre = utf8_decode($dados['calibre']);
			$consignado->pro_comprimento = $dados['comprimento'];
			$consignado->pro_diametrointerno = $dados['diametrointerno'];
			$consignado->pro_fabricante = utf8_decode($dados['fabricante']);
			$consignado->pro_numserie = $dados['numserie'];
			$consignado->pro_datafabricacao = DefaultHelper::converte_data($dados['datafabricacao']);
			$consignado->pro_marca = $dados['marca'];
			$consignado->pro_anvisa = $dados['anvisa'];
			$consignado->pro_lotefabricacao = $dados['lotefabricacao'];
			$consignado->pro_referencias = utf8_decode($dados['referencias']);
			$consignado->pro_validacaofabricacao = ($dados['validacaofabricacao'] == "")
												? (($dados['datafabricacao'] == "")
													? date("Y-m-d", strtotime("+5 years"))
													: DefaultHelper::converte_data(date("Y-m-d", strtotime("+5 years", strtotime($dados['datafabricacao'])))))
												: DefaultHelper::converte_data($dados['validacaofabricacao']);
			$consignado->pro_idgrupomateriais = $dados['grupomaterial'];
			$consignado->pro_maxqtdprocessamento = $dados['qtdmaxima'];
			$consignado->pro_custo = $dados['custo'];
			$consignado->pro_controle = $dados['controle'];
			$consignado->pro_numeroentrada = $dados['numeroentrada'];
			if(!$dados['numerosaida']){
				$consignado->pro_numerosaida = $dados['numeroentrada'];				
			}else{
				$consignado->pro_numerosaida = $dados['numerosaida'];				
			}
			$consignado->pro_validadeesterilizacao = DefaultHelper::converte_data($dados['validadeesterilizacao']);
			$consignado->pro_foto = $dados['foto'];
			$consignado->pro_composto = $dados['composto'];
			$consignado->pro_status = '0';
			$consignado->pro_alerta = $dados['alerta'];
			$consignado->pro_alertamsg = $dados['alertamsg'];
			$consignado->pro_idusuario = $_SESSION['usu_id'];
			$consignado->pro_detailproduct = trim($dados['detailproduct']);
			//print_r($consignado);
			return $consignado->insert();
		}

		public static function update($dados){
			$consignado = new ProdutosConsignadoModel();
			$consignado->pro_id = $dados['id'];
			$consignado->pro_idsetor = $dados['setor'];
			$consignado->pro_qrcode = $dados['qrcode'];
			$consignado->pro_qtde = $dados['qtde'];
			$consignado->pro_nome = utf8_decode($dados['nome']);
			$consignado->pro_curvatura = $dados['curvatura'];
			$consignado->pro_calibre = utf8_decode($dados['calibre']);
			$consignado->pro_comprimento = $dados['comprimento'];
			$consignado->pro_diametrointerno = $dados['diametrointerno'];
			$consignado->pro_fabricante = utf8_decode($dados['fabricante']);
			$consignado->pro_numserie = $dados['numserie'];
			$consignado->pro_datafabricacao = DefaultHelper::converte_data($dados['datafabricacao']);
			$consignado->pro_marca = utf8_decode($dados['marca']);
			$consignado->pro_anvisa = $dados['anvisa'];
			$consignado->pro_lotefabricacao = $dados['lotefabricacao'];
			$consignado->pro_referencias = $dados['referencias'];
			$consignado->pro_validacaofabricacao = DefaultHelper::converte_data($dados['validacaofabricacao']);
			$consignado->pro_idgrupomateriais = $dados['grupomaterial'];
			$consignado->pro_maxqtdprocessamento = $dados['qtdmaxima'];
			$consignado->pro_custo = $dados['custo'];
			$consignado->pro_controle = $dados['controle'];
			$consignado->pro_numeroentrada = $dados['numeroentrada'];
			$consignado->pro_numerosaida = $dados['numerosaida'];
			$consignado->pro_validadeesterilizacao = DefaultHelper::converte_data($dados['validadeesterilizacao']);
			if($dados['ckdevolvido']){
				$consignado->pro_datadevolucao = DefaultHelper::converte_data($dados['devolvido']);
			}else{
				$consignado->pro_datadevolucao = '0000-00-00';
			}
			$consignado->pro_foto = $dados['foto'];
			$consignado->pro_composto = $dados['composto'];
			$consignado->pro_alerta = $dados['alerta'];
			$consignado->pro_alertamsg = $dados['alertamsg'];
			$consignado->pro_detailproduct =  trim($dados['detailproduct']);
			return $consignado->update();
		}

		public static function updateNome( $dados ){

        	$consignado = new ProdutosConsignadoModel();
			return $consignado->updateNome( $dados );

		}
		
		public static function updateSetor($idpro, $idSetor){
		
        	$consignado = new ProdutosConsignadoModel();
			$consignado->pro_id = $idpro;
			$consignado->pro_idsetor = $idSetor;
			return $consignado->updateSetor();
		
        }
		
        public static function delete($pro_id){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->delete($pro_id);
		}

		// cleverson retorna quantidade do produto
		public static function getquantidade($pro_id){
			$produto = new ProdutosConsignadoModel();
			return $produto->getquantidade($pro_id);
		}

		public static function getProdutoConsignado($pro_id){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->selectProdutoConsignado($pro_id);
		}
		
		public static function getProdutoConsignadoByQrCode($qrcode){
		    $consignado = new ProdutosConsignadoModel();
		    return $consignado->selectProdutoConsignadoByQrCode($qrcode);
		}

		public static function selectProdutoConsignadoParaSaida($qrcode){
			$consignados = new ProdutosConsignadoModel();
			return $consignados->selectProdutoParaSaida($qrcode);
		}        

		public static function getProdutosConsignado($where, $order = ""){
			$consignados = new ProdutosConsignadoModel();
			return $consignados->selectAll($where, $order);
		}

		public static function getProdutosConsignadoApagados($where){
			$consignados = new ProdutosConsignadoModel();
			return $consignados->selectAllDel($where);
		}

		public static function getProdutosConsignadosBuscar($buscar, $limit = "", $where = "", $order = ""){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->search($buscar, $limit, $where, $order);
		}

		public static function getProdutosConsignadoBuscarCount($buscar, $limit = "", $where = ""){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->searchCount($buscar, $limit, $where);
		}

		public static function getProdutoConsignadoParaSolicitacao($qrcode){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->selectProdutoParaSolicitacao($qrcode);
		}

		public static function getidSetor($idPro){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->selectSetorByProduto($idPro);
		}

		public static function getIdGMaterial($idPro){
			$consignado = new ProdutosConsignadoModel();
			return $consignado->selectGMaterialByProduto($idPro);
		}

		public static function getProdutoConsignadoInSolicitacao($qrCode, $ses = null, $stat = null){
			$consignados = new ProdutosConsignadoModel();
			return $consignados->selectProdutoInSolicitacao($qrCode, $ses, $stat);
		}

		public static function getProdutosConsignadoDescartados($dataInicio, $dataFinal){
		    $consignados = new ProdutosConsignadoModel();
		    return $consignados->selectProdutosDescartados($dataInicio, $dataFinal);
		}

		public static function setStatus($id, $status){
			$consignado = new ProdutosConsignadoModel();
			$consignado = ProdutosConsignadoController::getProdutoConsignado($id);
			$consignado->pro_status = $status;
			return $consignado->update();
		}

		public static function setStatusFilhos($id, $status){
			$a = array();
			foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = ".$id) as $pco){
				$consignado = new ProdutosConsignadoModel();
				$consignado = ProdutosController::getProdutoConsignado($pco->pco_idfilho);
				if($consignado->pro_status == '1'){
					$consignado->pro_status = $status;
					$res = $consignado->update();
					if($res){
						$a[] = $pco->pco_idfilho;
					}
				}
			}
			return $a;
		}

		public static function relProdutos($where, $order, $reuso = false){
			$consignados = new ProdutosConsignadoModel();
			return $consignados>selectAllOrder($where, $order, $reuso);
		}

	}
?>