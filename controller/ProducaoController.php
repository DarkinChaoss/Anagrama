<?php
	class ProducaoController {
	
		public static function insert($dados){
			$producao = new ProducaosModel();
			$producao->pcao_id = $dados['id'];
			$producao->pcao_nome = utf8_decode($dados['nome']);
			return $producao->insert();
		}
		
		public static function update($dados){
			$producao = new ProducaosModel();
			$producao->pcao_id = $dados['id'];
			$producao->pcao_nome = utf8_decode($dados['nome']);
			return $producao->update();
		}
		
		public static function delete($id){
			$producao = new ProducaosModel();
			return $producao->delete($id);
		}
		
		public static function getProducao($id){
			$producao = new ProducaosModel();
			return $producao->selectProducao($id);
		}
		
		public static function getProducaos($where){
			$producao = new ProducaosModel();
			return $producao->selectAll($where);
		}
		
		public static function LastProduct(){
			$produtos = new ProdutosModel();
			return $produtos->LastProduct();
		}		
	}
?>