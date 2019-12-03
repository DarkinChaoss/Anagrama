<?php
	class ProdutosController {

		public static function insert($dados){
		    $produto = new ProdutosModel();
			$produto->pro_idsetor = $dados['setor'];
			$produto->pro_qrcode = utf8_decode($dados['qrcode']);
			$produto->pro_qtde = $dados['qtde'];
			$produto->pro_nome = utf8_decode($dados['nome']);
			$produto->pro_curvatura = $dados['curvatura'];
			$produto->pro_calibre = utf8_decode($dados['calibre']);
			$produto->pro_comprimento = $dados['comprimento'];
			$produto->pro_diametrointerno = $dados['diametrointerno'];
			$produto->pro_fabricante = utf8_decode($dados['fabricante']);
			$produto->pro_numserie = $dados['numserie'];
			$produto->pro_datafabricacao = DefaultHelper::converte_data($dados['datafabricacao']);
			$produto->pro_marca = $dados['marca'];
			$produto->pro_anvisa = $dados['anvisa'];
			$produto->pro_lotefabricacao = $dados['lotefabricacao'];
			$produto->pro_referencias = utf8_decode($dados['referencias']);
			$produto->pro_validacaofabricacao = ($dados['validacaofabricacao'] == "")
												? (($dados['datafabricacao'] == "")
													? date("Y-m-d", strtotime("+5 years"))
													: DefaultHelper::converte_data(date("Y-m-d", strtotime("+5 years", strtotime($dados['datafabricacao'])))))
												: DefaultHelper::converte_data($dados['validacaofabricacao']);
			$produto->pro_idgrupomateriais = $dados['grupomaterial'];
			$produto->pro_maxqtdprocessamento = $dados['qtdmaxima'];

			$teste = str_replace('.', '', $dados['custo']);
			$teste = str_replace(',', '.', $teste);
			$teste = str_replace('R$ ', '', $teste);

			$produto->pro_custo = $teste;

			$produto->pro_foto = $dados['foto'];
			$produto->pro_composto = $dados['composto'];
			$produto->pro_status = '0';
			$produto->pro_alerta = $dados['alerta'];
			$produto->pro_alertamsg = $dados['alertamsg'];
			$produto->pro_idusuario = $_SESSION['usu_id'];
			$produto->pro_detailproduct = trim($dados['detailproduct']);
			//print_r($produto);
			return $produto->insert();
		}

		public static function update($dados){
			$produto = new ProdutosModel();

			$produto->pro_id = $dados['id'];
			$produto->pro_idsetor = $dados['setor'];
			$produto->pro_qrcode = utf8_decode($dados['qrcode']);
			$produto->pro_qtde = $dados['qtde'];
			$produto->pro_nome = utf8_decode($dados['nome']);
			$produto->pro_curvatura = $dados['curvatura'];
			$produto->pro_calibre = utf8_decode($dados['calibre']);
			$produto->pro_comprimento = $dados['comprimento'];
			$produto->pro_diametrointerno = $dados['diametrointerno'];
			$produto->pro_fabricante = utf8_decode($dados['fabricante']);
			$produto->pro_numserie = $dados['numserie'];
			$produto->pro_datafabricacao = DefaultHelper::converte_data($dados['datafabricacao']);
			$produto->pro_marca = utf8_decode($dados['marca']);
			$produto->pro_anvisa = $dados['anvisa'];
			$produto->pro_lotefabricacao = $dados['lotefabricacao'];
			$produto->pro_referencias = $dados['referencias'];
			$produto->pro_validacaofabricacao = DefaultHelper::converte_data($dados['validacaofabricacao']);
			$produto->pro_idgrupomateriais = $dados['grupomaterial'];
			$produto->pro_maxqtdprocessamento = $dados['qtdmaxima'];

			$teste = str_replace('.', '', $dados['custo']);
			$teste = str_replace(',', '.', $teste);
			$teste = str_replace('R$ ', '', $teste);

			$produto->pro_custo = $teste;

			$produto->pro_foto = $dados['foto'];
			$produto->pro_composto = $dados['composto'];
			$produto->pro_alerta = $dados['alerta'];
			$produto->pro_alertamsg = $dados['alertamsg'];
			$produto->pro_detailproduct = trim($dados['detailproduct']);
			return $produto->update();
		}

		public static function updateNome( $dados ){

        	$produto = new ProdutosModel();
			return $produto->updateNome( $dados );

		}
		
		public static function updateSetor($idpro, $idSetor){
		
        	$produto = new ProdutosModel();
			$produto->pro_id = $idpro;
			$produto->pro_idsetor = $idSetor;
			return $produto->updateSetor();
		
        }
		
        public static function delete($pro_id){
			$produto = new ProdutosModel();
			return $produto->delete($pro_id);
		}

		public static function getProduto($pro_id){
			$produto = new ProdutosModel();
			return $produto->selectProduto($pro_id);
		}
		
		// cleverson retorna quantidade do produto
		public static function getquantidade($pro_id){
			$produto = new ProdutosModel();
			return $produto->getquantidade($pro_id);
		}
		
		public static function getProdutoByQrCode($qrcode){
		    $produto = new ProdutosModel();
		    return $produto->selectProdutoByQrCode($qrcode);
		}

		public static function selectProdutoParaSaida($qrcode){
			$produtos = new ProdutosModel();
			return $produtos->selectProdutoParaSaida($qrcode);
		}        

		public static function getProdutos($where, $order = ""){
			$produtos = new ProdutosModel();
			return $produtos->selectAll($where, $order);
		}

		public static function getProdutosApagados($where){
			$produtos = new ProdutosModel();
			return $produtos->selectAllDel($where);
		}

		public static function getProdutosBuscar($buscar, $limit = "", $where = "", $order = ""){
			$produtos = new ProdutosModel();
			return $produtos->search($buscar, $limit, $where, $order);
		}
		
		// cleverson matias
		public static function getCountProntos($qrcode){
			
			$produtos = new ProdutosModel();
			$qtd = $produtos->getquantidadeByQr($qrcode);
			$qtdPronto = $produtos->getqtdprontos($qrcode);
			//$totals = ['qtdPronto' => $qtdPronto, 'qtd' => $qtd];
			//print_r($totals);
			return trim($qtdPronto . ';' . $qtd);
		}

		public static function getProdutosBuscarCount($buscar, $limit = "", $where = ""){
			$produtos = new ProdutosModel();
			return $produtos->searchCount($buscar, $limit, $where);
		}

		public static function getProdutoParaSolicitacao($qrcode){
			$produto = new ProdutosModel();
			return $produto->selectProdutoParaSolicitacao($qrcode);
		}

		public static function getidSetor($idPro){
			$produto = new ProdutosModel();
			return $produto->selectSetorByProduto($idPro);
		}

		public static function getIdGMaterial($idPro){
			$produto = new ProdutosModel();
			return $produto->selectGMaterialByProduto($idPro);
		}

		public static function getProdutoInSolicitacao2($qrCode, $ses = null, $stat = null){
			$produtos = new ProdutosModel();
			return $produtos->selectProdutoInSolicitacao2($qrCode, $ses, $stat);
		}

		public static function getProdutoInSolicitacao($qrCode, $ses = null, $stat = null){
			$produtos = new ProdutosModel();
			return $produtos->selectProdutoInSolicitacao($qrCode, $ses, $stat);
		}

		public static function getProdutosDescartados($dataInicio, $dataFinal){
		    $produtos = new ProdutosModel();
		    return $produtos->selectProdutosDescartados($dataInicio, $dataFinal);
		}

		public static function setStatus($id, $status){
			$produto = new ProdutosModel();
			$produto = ProdutosController::getProduto($id);
			$produto->pro_status = $status;
			return $produto->update();
		}

		public static function setStatusFilhos($id, $status){
			$a = array();
			foreach(ProdutosCompostosController::getProdutosCompostos("pco_idpai = ".$id) as $pco){
				$produto = new ProdutosModel();
				$produto = ProdutosController::getProduto($pco->pco_idfilho);
				if($produto->pro_status == '1'){
					$produto->pro_status = $status;
					$res = $produto->update();
					if($res){
						$a[] = $pco->pco_idfilho;
					}
				}
			}
			return $a;
		}

		public static function relProdutos($where, $order, $reuso = false){
			$produtos = new ProdutosModel();
			return $produtos->selectAllOrder($where, $order, $reuso);
		}
		
		// cleverson matias
		public static function setProntos($qr, $value){
			$produto = new ProdutosModel();
			$qtdatual = $produto->getqtdprontos($qr);
			$produto = ProdutosController::getProdutoByQrCode($qr);
			$produto->pro_prontos = $qtdatual - $value;
			return $produto->update();
		}
		
		//cleverson matias
		public static function setProntosById($id, $value){
			$produto = new ProdutosModel();
			$qtdatual = $produto->getqtdprontosById($id);
			$produto = ProdutosController::getProduto($id);
			$produto->pro_prontos = $qtdatual + $value;
			return $produto->update();
		}

		public static function replicarProduto($idPro, $qte, $qrcode,$user){
			$produtos = new ProdutosModel();
			$array = $produtos->getProdutoDesc($idPro);
			$res = $produtos->ReplicarItem($array,$qte,$qrcode,$user);
			return $res;
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