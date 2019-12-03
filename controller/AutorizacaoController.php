<?php
	class AutorizacaoController {
		public static function insert($dados){
			$autorizacao = new AutorizacaoModel();
			$autorizacao->ac_nome_filho = $dados['nome_filho'];
			$autorizacao->ac_iditemsol = $dados['iditemsol'];
			$autorizacao->ac_idpai = $dados['idpai'];
			$autorizacao->ac_composicao = $dados['composicao'];
			$autorizacao->ac_qrcode_composicao = $dados['qrcomposicao'];
			$autorizacao->ac_responsavel = $dados['responsavel'];
			$autorizacao->ac_pagina = $dados['pagina'];
			$autorizacao->ac_modo = $dados['modo'];
			return $autorizacao->insert();
		}
		
		public static function ultimaautorizacao($id){
			$autorizacao = new AutorizacaoModel();
			return $autorizacao->ultima_autorização_composto($id);
		}
	}
?>