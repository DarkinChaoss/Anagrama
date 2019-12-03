<?php

	/* 
	Classe criada para controlar o estoque
	20/06/2017
	Weslen Augusto Marconcin
	*/

	class ControleEstoqueController{

		public static function select( $setor = null , $situacao = null, $nome_produto = null, $validade = null, $data1 = null , $data2 = null ){

			$controleEstoque = new ControleEstoqueModel();

			if( $situacao == 'E' ){
				$situacao = "'E','D'";
			}

			return $controleEstoque->select( $setor , $situacao, $nome_produto , $validade, $data1, $data2);	

		}

		public static function selectConsignados( $setor = null , $situacao = null, $nome_produto = null , $validade = null, $data1 = null , $data2 = null){
			
			$controleEstoque = new ControleEstoqueModel();

			if( $situacao == 'E' ){
				$situacao = "'E','D'";
			}

			return $controleEstoque->selectConsignados( $setor , $situacao, $nome_produto, $validade, $data1, $data2);

		}

		public static function getSetDestinoFilho($id_filho){
			$info = new ControleEstoqueModel();
			return $info->getSetDestinoFilho($id_filho);

		}
	}