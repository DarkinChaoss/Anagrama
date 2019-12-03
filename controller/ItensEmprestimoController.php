<?php
	class ItensEmprestimoController {
		
		public static function insert($dados){
			$item = new ItensEmprestimoModel();
			$item->iem_idsem = $dados['idEmprestimo'];
			$item->iem_idmai = $dados['idMaterial'];
			$item->iem_turno = $dados['turno'];
			$item->iem_qtdeentregue = $dados['entregue'];
			return $item->insert();
		}
		
		public static function update($dados){
			$item = new ItensEmprestimoModel();
			$item->iem_id = $dados['id'];
			$item->iem_qtdesujo = $dados['sujo'];
			$item->iem_qtdesemuso = $dados['semUso'];
			return $item->update();
		}
		
		public static function delete($iem_id){
			$item = new ItensEmprestimoModel();
			return $item->delete($iem_id);
		}
		
		public static function getItem($id){
			$item = new ItensEmprestimoModel();
			return $item->selectItem($id);
		}
		
		public static function getItens($buscar, $where){
			$item = new ItensEmprestimoModel();
			return $item->selectAll($buscar, $where);
		}
		
	}
?>