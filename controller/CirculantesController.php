<?php
	class CirculantesController {
	
		public static function insert($dados){
			$circulante = new CirculanteModel();
			$circulante->cir_id = $dados['id'];
			$circulante->cir_nome = utf8_decode($dados['nome']);
			$circulante->cir_contato = $dados['contato'];
			return $circulante->insert();
		}
		
		public static function update($dados){
			$circulante = new CirculanteModel();
			$circulante->cir_id = $dados['id'];
			$circulante->cir_nome = utf8_decode($dados['nome']);
			$circulante->cir_contato = $dados['contato'];
			return $circulante->update();
		}
		
		public static function delete($id){
			$circulante = new CirculanteModel();
			return $circulante->delete($id);
		}
		
		public static function getCirculante($id){
			$circulante = new CirculanteModel();
			return $circulante->selectCirculante($id);
		}
		
		public static function getCirculantes($where){
			$circulante = new CirculanteModel();
			return $circulante->selectAll($where);
		}
		
	}