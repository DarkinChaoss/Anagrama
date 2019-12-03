<?php
	class AutorizacaoModel extends Conexao{
		
		public $ac_id;
		public $ac_nome_filho;
		public $ac_responsavel;
		public $ac_masterclient;
		public $ac_composicao;
		public $ac_qrcode_composicao;
		public $ac_pagina;

		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_autorizacao_composicao (
						ac_nome_filho,
						ac_iditemsol,
						ac_idpai,
						ac_composicao,
						ac_qrcode_composicao,
						ac_responsavel,
						ac_pagina,
						ac_data,
						ac_masterclient,
						ac_modo
					) VALUES (
						'" . $this->ac_nome_filho . "',
						'" . $this->ac_iditemsol . "',
						'" . $this->ac_idpai . "',
						'" . $this->ac_composicao . "',
						'" . $this->ac_qrcode_composicao . "',
						'" . $this->ac_responsavel . "',
						'" . $this->ac_pagina . "',
						'" . date('Y-m-d H:i:s') . "',
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $this->ac_modo . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_autorizacao_composicao";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function ultima_autorização_composto($id){
			$sql = "SELECT * FROM tmsd_autorizacao_composicao WHERE ac_idpai = $id ORDER BY ac_id desc LIMIT 1 ";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new AutorizacaoModel();
				$obj->ac_responsavel = $row['ac_responsavel'];
				$obj->ac_iditemsol = $row['ac_iditemsol'];
				$obj->ac_idpai = $row['ac_idpai'];
				$obj->ac_composicao = $row['ac_composicao'];
				$obj->ac_qrcode_composicao = $row['ac_qrcode_composicao'];
				$obj->ac_nome_filho = $row['ac_nome_filho'];
				$obj->ac_qrcode_filho = $row['ac_qrcode_filho'];
				$obj->ac_pagina = $row['ac_pagina'];
				$obj->ac_data = $row['ac_data'];
				$obj->ac_modo = $row['ac_modo'];
			}
			return $obj;
		}		
	}
?>