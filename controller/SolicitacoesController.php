<?php
	class SolicitacoesController {
		
		public static function insert($dados){
			$solicitacoes = new SolicitacoesModel();
			$solicitacoes->ses_idsetor = $dados['setor'];
			$solicitacoes->ses_status = "0";
			return $solicitacoes->insert();
		}
		
		public static function update($dados){
			$solicitacoes = new SolicitacoesModel();
			$solicitacoes->ses_id = $dados['id'];
			$solicitacoes->ses_idsetor  = $dados['setor'];
			$solicitacoes->ses_dataesterilizacao = $dados['dtEsterilizacao'];
			$solicitacoes->ses_status = $dados['status'];
			return $solicitacoes->update();
		}
		
		public static function delete($ses_id){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->delete($ses_id);
		}
		
		public static function lastproduct($qrcode){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->lastProduct($qrcode);
		}
		
		public static function getSolicitacao($ses_id){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->selectSolicitacao($ses_id);
		}
		
		public static function getSolicitacoes($where, $order = ""){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->selectAll($where, $order);
		}
		
		public static function getSolicitacoesBuscar($buscar, $limit = ""){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->search($buscar, $limit);
		}
		
		public static function relSolicitacao($where, $order){
			$solicitacoes = new SolicitacoesModel();
			return $solicitacoes->selectAllOrder($where, $order);
		}

		public static function getMaiorSolicitacaoProd($idPro){
			$solicitacao = new SolicitacoesModel();
			return $solicitacao->selectMaiorRegistro($idPro);
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