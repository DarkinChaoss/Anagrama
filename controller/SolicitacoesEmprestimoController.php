<?php
	class SolicitacoesEmprestimoController {
	
		public static function insert($dados){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			$solicitacaoEmprestimo->sem_data = date("Y-m-d H:i:s");
			$solicitacaoEmprestimo->sem_idsetor = $dados['setor'];
			$solicitacaoEmprestimo->sem_nomesolicitante = utf8_decode($dados['nomesolicitante']);
			return $solicitacaoEmprestimo->insert();
		}
		
		public static function update($dados){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			$solicitacaoEmprestimo->sem_id = $dados['id'];
			//$solicitacaoEmprestimo->sem_data = $dados['data'];
			//$solicitacaoEmprestimo->sem_idsetor = $dados['setor'];
			$solicitacaoEmprestimo->sem_nomesolicitante = utf8_decode($dados['nomesolicitante']);
			return $solicitacaoEmprestimo->update();
		}
		
		public static function delete($id){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			return $solicitacaoEmprestimo->delete($id);
		}
		
		public static function getSolicitacaoEmprestimo($id){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			return $solicitacaoEmprestimo->selectSolicitacaoEmprestimo($id);
		}
		
		public static function getSolicitacoesEmprestimoBuscar($buscar){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			return $solicitacaoEmprestimo->search($buscar);
		}
		
		public static function getSolicitacoesEmprestimo($where){
			$solicitacaoEmprestimo = new SolicitacoesEmprestimoModel();
			return $solicitacaoEmprestimo->selectAll($where);
		}
		
	}
?>