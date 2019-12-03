<?php
	class QrcodesController {
	
		public static function insert($dados){

			$qrcode = new QrcodesModel();

			$qrcode->qrc_idproduto = $dados['idproduto'];
			$qrcode->qrc_antigo_qrcode = utf8_decode($dados['qrcode_atual']);
			$qrcode->qrc_idusuario = $_SESSION['usu_id'];

			return $qrcode->insert( $qrcode );

		}

		public static function select( $where = null ){

			$qrcodes = new QrcodesModel();
			return $qrcodes->selectAll( $where );

		}
		
	}
?>