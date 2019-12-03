<?php
	class EtiquetaQtdModel extends Conexao{
		
		public $eqm_id;
		public $eqm_user;
		public $eqm_time;
		public $eqm_qtd;
		public $eqm_lote;
		public $eqm_dados;
		public $eqm_nome_produto;
		
		public function __construct(){
			
			$this->conecta();

			//O tipo de caracteres a ser usado
   			 header('Content-Type: text/html; charset=utf-8');

		   
		    mysql_query("SET NAMES 'utf8'");
		    mysql_query('SET character_set_connection=utf8');
		    mysql_query('SET character_set_client=utf8');
		    mysql_query('SET character_set_results=utf8');
		}
		
		public function insert(){
			$sql = 'INSERT INTO tmsd_etiquetas (
						eqm_user,
						eqm_time,
						eqm_nome_produto,
						eqm_qtd,
						eqm_lote,
						eqm_dados
					) VALUES (
						"' . $_SESSION["usu_login"] . '",
						"' . date('Y-m-d H:i:s') . '",
						"' . $this->eqm_nome_produto . '",
						"' . $this->eqm_qtd . '",
						"' . $this->eqm_lote . '",
						"' . $this->eqm_dados . '"
					)';
				
			$res = mysql_query($sql) or die(mysql_error());

			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = 'Inserção: registro ' . $id . ' em tmsd_etiquetadores.';
				$this->gravaLog();
				//
			}
			return $res;
		}



		public function getLastEtiquetas() {
			$sql = 'SELECT * FROM `tmsd_etiquetas` WHERE eqm_user = \''.$_SESSION["usu_login"] .'\' ORDER BY eqm_time DESC LIMIT 1';
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['eqm_dados'];
		}

		public function getEspecificEtiquetas($id) {
			$sql = 'SELECT * FROM `tmsd_etiquetas` WHERE eqm_id = '.$id.'';
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['eqm_dados'];
		}

		public function getLastEtiquetas48Hours(){
			$sql = "SELECT * FROM `tmsd_etiquetas` WHERE `eqm_time` > DATE_SUB(now(), INTERVAL 48 HOUR) ORDER BY `eqm_time` DESC";
			$res = mysql_query($sql) or die(mysql_error());

			$response = array();

			while($row = mysql_fetch_array($res)){
				array_push($response, $row);
			}

			return $response;
		}


		public function deleteOldRegisters() {
			// Deletar registros com mais de 48Horas
		}
		
	}