<?php
	class ItensSaidaController {

		public static function insert($dados){
			$itemSaida = new ItensSaidaModel();
			$itemSaida->isa_idsaida = $dados['idSaida'];
			$itemSaida->isa_data = date('Y-m-d H:i:s');
			$itemSaida->isa_sala = $dados['sala'];
			$itemSaida->isa_idproduto = $dados['idProduto'];
			$itemSaida->isa_lote = utf8_decode($dados['lote']);
			$itemSaida->isa_validade = DefaultHelper::converte_data($dados['validade']);
			$itemSaida->isa_reuso = utf8_decode($dados['reuso']);
			$itemSaida->isa_obs = utf8_decode($dados['obs']);
            
            return $itemSaida->insert();
		}

		public static function updateSala($sma_id, $sma_sala){
			$itemSaida = new ItensSaidaModel();
			return $itemSaida->updateSala($sma_id, $sma_sala);
		}

		public static function getItemSaida($id){
			$itemSaida = new ItensSaidaModel();
			return $itemSaida->selectItemSaida($id);
		}

		public static function getItensSaida($where, $join = false){
			$itemSaida = new ItensSaidaModel();
			return $itemSaida->selectAll($where, $join);
		}

		/*
			testes para gerar o relatorio de controle do estoque
			Weslen Augusto Marconcin
			09-06-2017
		*/

		public static function selectItensMaiorSaidaBySetor($sma_idsetor, $where = '', $order = 'isa_data, set_nome'){

		    $itemSaida = new ItensSaidaModel();
		    return $itemSaida->selectItensMaiorSaidaBySetor($sma_idsetor, $where, $order);

		}		

		public static function getItensSaidaBySetor($sma_idsetor, $where = '', $order = 'isa_data, set_nome'){

		    $itemSaida = new ItensSaidaModel();
		    return $itemSaida->selectItensSaidaBySetor($sma_idsetor, $where, $order);

		}
		
		public static function getItemUltimaMovimentacao($idProduto){
			
		    $itemSaida = new ItensSaidaModel();
		    return $itemSaida->selectItemUltimaMovimentacao($idProduto);
		}

		public static function getItemUltimaSaida($idProduto, $wherePeriodo = ''){
			$itens = new ItensSaidaModel();
			return $itens->selectItemUltimaSaida($idProduto, $wherePeriodo);
		}
		
		public static function getItensSaidaMaiorBySetor($sma_idsetor, $wherePeriodo = ''){
			$itens = new ItensSaidaModel();
			return $itens->selectItensMaiorSaidaBySetor($sma_idsetor, $wherePeriodo);
		}

		public static function delete($id){
			$itemSaida = new ItensSaidaModel();
			return $itemSaida->delete($id);
		}

	}