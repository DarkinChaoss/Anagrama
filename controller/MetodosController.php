<?php
	class MetodosController {
	
		public static function insert($dados){
			$metodo = new MetodosModel();
			$metodo->met_id = $dados['id'];
			$metodo->met_nome = utf8_decode($dados['nome']);
			$metodo->met_descricao = utf8_decode($dados['descricao']);
			return $metodo->insert();
		}
		
		public static function update($dados){
			$metodo = new MetodosModel();
			$metodo->met_id = $dados['id'];
			$metodo->met_nome = utf8_decode($dados['nome']);
			$metodo->met_descricao = utf8_decode($dados['descricao']);
			return $metodo->update();
		}
		
		public static function delete($id){
			$metodo = new MetodosModel();
			return $metodo->delete($id);
		}
		
		public static function getMetodo($id){
			$metodo = new MetodosModel();
			return $metodo->selectMetodo($id);
		}
		
		public static function getMetodoSterilab($id){
			$metodo = new MetodosModel();
			return $metodo->selectMetodoSterilab($id);
		}
		
		public static function getMetodos($where){
			$metodo = new MetodosModel();
			return $metodo->selectAll($where);
		}
		
	}
?>