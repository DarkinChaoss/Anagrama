<?php
	
	/* 
	Classe criada para controlar a substituicao de QRCODE
	24/07/2017
	Weslen Augusto Marconcin
	*/

	class QrcodesModel extends Conexao{

		public $qrc_id;
		public $qrc_idproduto;
		public $qrc_idusuario;
		public $qrc_data;
		public $qrc_antigo_qrcode;
		public $qrc_del;	

		// auxiliar para a consulta
		public $usu_nome;
		public $qrc_data_convertida;				
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert( $qrcode ){
			$sql = "INSERT INTO tmsd_qrcodes (
						qrc_idproduto,
						qrc_idusuario,
						qrc_data,
						qrc_antigo_qrcode
					) VALUES (
						'" . $qrcode->qrc_idproduto . "',
						'" . $qrcode->qrc_idusuario . "',
						'" . date("Y-m-d H:i:s") ."',
						'" . $qrcode->qrc_antigo_qrcode."'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Substituição de QRCode: " . $id . " em tmsd_qrcodes.";
				$this->gravaLog();

			}
			return $res;
		}

		public function selectAll( $where = null ){

			if(isset($where))
				$where = "AND " . $where;

			$sql = "SELECT  tmsd_qrcodes.*,
							tmsd_usuarios.usu_login,
							DATE_FORMAT( qrc_data ,'%d/%m/%Y %H:%i:%s') as data_convertida
					FROM tmsd_qrcodes 
					JOIN tmsd_usuarios ON usu_id = qrc_idusuario					
					WHERE qrc_del IS NULL " . $where . " 
					ORDER BY qrc_id DESC";

			$res = mysql_query($sql);

			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){

				$obj = new QrcodesModel();

				$obj->qrc_id = $row['qrc_id'];
				$obj->qrc_idproduto = $row['qrc_idproduto'];
				$obj->qrc_idusuario = $row['qrc_idusuario'];
				$obj->qrc_data = $row['qrc_data'];
				$obj->qrc_antigo_qrcode = $row['qrc_antigo_qrcode'];
				$obj->qrc_del = $row['qrc_del'];	

				$obj->qrc_data_convertida = $row['data_convertida'];
				$obj->usu_nome = $row['usu_login'];

				$a[] = $obj;
			}
			// se for apenas um resultado ja retorna o objeto e não array de objeto
			return $a;
		}
		
	}