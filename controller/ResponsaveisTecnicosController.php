<?php
	class ResponsaveisTecnicosController {
	
		public static function insert($dados){
			$RTecnico = new ResponsaveisTecnicosModel();
			$RTecnico->rte_id = $dados['id'];
			$RTecnico->rte_nome = utf8_decode($dados['nome']);
			$RTecnico->rte_contato = $dados['contato'];
			$RTecnico->rte_coren = $dados['coren'];
			$RTecnico->rte_admin = $dados['admin'];
			$RTecnico->rte_permissao = $dados['permissao'];
			return $RTecnico->insert();
		}
		
		public static function update($dados){
			$RTecnico = new ResponsaveisTecnicosModel();
			$RTecnico->rte_id = $dados['id'];
			$RTecnico->rte_nome = utf8_decode($dados['nome']);
			$RTecnico->rte_contato = $dados['contato'];
			$RTecnico->rte_coren = $dados['coren'];
			$RTecnico->rte_admin = $dados['admin'];
			$RTecnico->rte_permissao = $dados['permissao'];
			return $RTecnico->update();
		}
		
		public static function delete($id){
			$RTecnico = new ResponsaveisTecnicosModel();
			return $RTecnico->delete($id);
		}
		
		public static function getRTecnico($id){
			$RTecnico = new ResponsaveisTecnicosModel();
			return $RTecnico->selectRTecnico($id);
		}
		
		public static function getRTecnicoSterilab($id){
			$RTecnico = new ResponsaveisTecnicosModel();
			return $RTecnico->selectRTecnicoSterilab($id);
		}
		
		public static function getRTecnicos($where){
			$RTecnico = new ResponsaveisTecnicosModel();
			return $RTecnico->selectAll($where);
		}
		
	}
?>