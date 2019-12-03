<?php
	class NomesProdutosController {
	
		public static function insert($dados){
			$nomeProduto = new NomesProdutosModel();
			$nomeProduto->nop_id = $dados['id'];

			$nomeProduto->nop_img_url = $dados['img_url'];

			$nomeProduto->nop_nome = utf8_decode($dados['nome']);
			$nomeProduto->nop_nome = DefaultHelper::removerAcentos($nomeProduto->nop_nome );		
			return $nomeProduto->insert();
		}

		public static function insertHoldId(){
			$nomeProduto = new NomesProdutosModel();
			$nomeProduto->nop_id = '';
			$nomeProduto->nop_nome = '';	
			return $nomeProduto->insertHoldId();
		}

		
		public static function update($dados){
			$nomeProduto = new NomesProdutosModel();
			$nomeProduto->nop_id = $dados['id'];

			$nomeProduto->nop_img_url = $dados['img_url'];

			$nomeProduto->nop_nome = utf8_decode($dados['nome']);
			$nomeProduto->nop_nome = DefaultHelper::removerAcentos($nomeProduto->nop_nome );
			return $nomeProduto->update();
		}
		
		public static function delete($id){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->delete($id);
		}


		public static function delNotUsed(){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->delNotUsed();
		}

		
		public static function getNomeProduto($id){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->selectNomeProduto($id);
		}
		
		public static function getNomesProdutosBuscar($buscar){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->search($buscar);
		}
		
		public static function getNomesProdutos($where){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->selectAll($where);
		}

		public static function nextId(){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->nextId();
		}

		public static function getIdByName($name){
			$nomeProduto = new NomesProdutosModel();
			return $nomeProduto->getIdByName($name);
		}
		
		
	}
?>