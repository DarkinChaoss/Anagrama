<?php
	class ItensSaidaModel extends Conexao{

		public $isa_id;
		public $isa_idsaida;
		public $isa_data;
		public $isa_sala;
		public $isa_idproduto;
		public $isa_lote;
		public $isa_validade;
		public $isa_reuso;
		public $isa_obs;
		public $isa_idsetororigem;
		public $isa_idsetordestino;
		
		/* Auxiliaes */

		public $isa_produto;
		public $isa_qrcode;
		public $isa_setorOrigem;
		public $isa_setorDestino;
		

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_itenssaida (
						isa_idsaida,
						isa_data,
						isa_sala,
						isa_idproduto,
						isa_lote,
						isa_validade,
						isa_reuso,
						isa_obs,
						isa_idsetorigem
					) VALUES (
						'" . $this->isa_idsaida . "',
						'" . $this->isa_data . "',
						'" . DefaultHelper::acentos($this->isa_sala) . "',
						'" . $this->isa_idproduto . "',
						'" . DefaultHelper::acentos($this->isa_lote) . "',
						'" . $this->isa_validade . "',
						'" . DefaultHelper::acentos($this->isa_reuso) . "',
						'" . $this->isa_obs . "',
						'" . $this->isa_idsetororigem . "'
					)";
			$res = mysql_query($sql);
			//error_log($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserção: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function updateSala($sma_id, $sma_sala){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_sala = '" . $sma_sala . "'
					WHERE isa_idsaida = " . $sma_id;
			$res = mysql_query($sql);
			//error_log($sql);
			if ($res) {
				return "UPDATE SALA >>>>>>>>>> <br>".$sql."<br><br><br>";
			} else {
				return "ERRO ".$sma_id." - SALA ".$sma_sala."<br><br><br>";
			}
		}

		public function selectItemSaida($id){
			$sql = "SELECT * FROM tmsd_itenssaida WHERE isa_id = " . $id . " AND isa_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ItensSaidaModel();
			$obj->isa_id = $row['isa_id'];
			$obj->isa_idsaida = $row['isa_idsaida'];
			$obj->isa_data = $row['isa_data'];
			$obj->isa_sala = $row['isa_sala'];
			$obj->isa_idproduto = $row['isa_idproduto'];
			$obj->isa_lote = $row['isa_lote'];
			$obj->isa_validade = $row['isa_validade'];
			$obj->isa_reuso = $row['isa_reuso'];
			$obj->isa_obs = $row['isa_obs'];
			return $obj;
		}

		public function selectAll($where, $join = false){

			if(isset($where)){
				$where = "AND " . $where;
			}

			if( $join ){
				$where .= " AND sma_tiposaida!='T'";
				$join = "LEFT JOIN tmsd_saidamateriais ON sma_id = isa_idsaida";
			}
			else{
				$join = '';
			}

			$sql = "SELECT * 
					FROM tmsd_itenssaida 
					{$join}
					WHERE (isa_del IS NULL OR isa_del != '*') " . $where . " ORDER BY isa_data";
					
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$obj->isa_idsetororigem = $row['isa_idsetorigem'];
				$obj->isa_idsetordestino = $row['isa_idsetordestino'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectItensTransferencia($id_tes){
			/** retorna todos os itens de saida no formato tranferencia de estoque **/
			
			$sql = "SELECT  tmsd_itenssaida.*, 
							tmss_produto.pro_id, 
							tmss_produto.pro_nome, 
							tmss_produto.pro_qrcode, 
							tmsd_setores.set_nome
			        FROM tmsd_itenssaida
			        INNER JOIN tmsd_saidamateriais ON sma_tiposaida = 'T' AND sma_del is NULL AND sma_id = isa_idsaida
			        INNER JOIN tmss_produto ON pro_id = isa_idproduto
			        LEFT JOIN tmsd_setores ON set_id = isa_idsetorigem
			        WHERE isa_idsaida =  " . $id_tes . " AND isa_del IS NULL";
			// error_log('selectTranferenciaItens - '.$sql);
			$res = mysql_query($sql);
			$itens = array();
			while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
    			$obj->isa_idproduto = $row['isa_idproduto'];
    			$obj->isa_idsetororigem = $row['isa_idsetorigem'];
    			

    			//auxiliar
    			$obj->isa_produto = $row['pro_nome'];
    			$obj->isa_qrcode = $row['pro_qrcode'];
    			$obj->isa_setorOrigem = ($row['set_nome'] == NULL ? 'Material Novo' : $row['set_nome']);

    			$itens[] = $obj;
				
			}
			return $itens;
		}

		public function selectItensSaidaBySetor($sma_idsetor = 0, $where, $order){
			
			// error_log($where);
		    if($where != '')
		        $where = ' AND ' . $where;

	        $sql = ' SELECT isa_id,isa_idsaida,isa_data,isa_sala,isa_idproduto,isa_lote,isa_validade,isa_reuso,isa_obs FROM tmsd_itenssaida'
	             . ' INNER JOIN tmsd_saidamateriais ON sma_id = isa_idsaida'
                 . ' AND (sma_del IS NULL OR sma_del != "*") '
	             . ' INNER JOIN tmsd_setores ON set_id = sma_idsetor'
				 . ' INNER JOIN tmss_produto ON pro_id = isa_idproduto AND pro_del IS NULL'
	             . ' AND pro_idcliente = '. $_SESSION['usu_masterclient']		             
                 . ' WHERE (isa_del IS NULL OR isa_del != "*")'
                 . ( $sma_idsetor > 0 ? ' AND sma_idsetor = '. $sma_idsetor : '' )
                 . $where
	             . ' ORDER BY ' . $order;
	             //. ' LIMIT 0, 1000 ';
             

	        $res = mysql_query($sql);
	        $a = array();
	        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
	            $obj = new ItensSaidaModel();
	            $obj->isa_id = $row['isa_id'];
	            $obj->isa_idsaida = $row['isa_idsaida'];
	            $obj->isa_data = $row['isa_data'];
	            $obj->isa_sala = $row['isa_sala'];
	            $obj->isa_idproduto = $row['isa_idproduto'];
	            $obj->isa_lote = $row['isa_lote'];
	            $obj->isa_validade = $row['isa_validade'];
	            $obj->isa_reuso = $row['isa_reuso'];
	            $obj->isa_obs = $row['isa_obs'];
	            $a[] = $obj;
	        }
	        return $a;
		}
		

		public function selectItemUltimaSaida($id, $wherePeriodo){
			$sql = "SELECT * FROM tmsd_itenssaida
					WHERE isa_idproduto = " . $id . " AND isa_del IS NULL
					". $wherePeriodo ."
					ORDER BY isa_data DESC";
			// error_log("-> " . $sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectItemUltimaMovimentacao($idProduto){
			// Retorna o ultimo registro de movimento de um produto seja Transferencia ou saida para uso 
			// Utilizado por produtosEstoque.php
			
			$sql = "select isa_id, isa_idsaida, isa_data, isa_sala, isa_idproduto, isa_lote, isa_validade, isa_reuso, isa_obs FROM tmsd_itenssaida
					WHERE ISA_ID = (
						SELECT max(isa_id) FROM tmsd_itenssaida
						WHERE isa_idproduto = " . $idProduto . " AND isa_del IS NULL)";
					
			
			// error_log("-> " . $sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
				$obj = new ItensSaidaModel();
				$obj->isa_id = $row['isa_id'];
				$obj->isa_idsaida = $row['isa_idsaida'];
				$obj->isa_data = $row['isa_data'];
				$obj->isa_sala = $row['isa_sala'];
				$obj->isa_idproduto = $row['isa_idproduto'];
				$obj->isa_lote = $row['isa_lote'];
				$obj->isa_validade = $row['isa_validade'];
				$obj->isa_reuso = $row['isa_reuso'];
				$obj->isa_obs = $row['isa_obs'];
				
			
			return $obj;
		}
		
		

		public function delete($id){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_del = '*'
					WHERE isa_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Devolução de material. Exclusão: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delItensTransf($id){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_del = '*'
					WHERE isa_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Tranferencia de estoque de material. Exclusão: registro " . $id . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectItemTransferenciaAberta($where){
			/*Verifica se item ja nao esta em alguma transferencia que ainda nao foi finalizada*/
		    if(isset($where) && $where != "")
		        $where = "AND " . $where;
		        $sql = "SELECT count(*) as total FROM tmsd_itenssaida
		        		INNER JOIN tmsd_saidamateriais
						ON sma_id = isa_idsaida
						AND sma_idsetor = 0
						AND sma_tiposaida = 'T'
						AND sma_del IS NULL
					WHERE isa_del IS NULL " . $where . ";";
		        $res = mysql_query($sql);
		        // error_log($sql);

		        $row = mysql_fetch_array($res, MYSQL_ASSOC);

		        return $row['total'];
		}
		
		public function limpaItensTransferencia(){
			/** Exclui as transferencias nao finalizadas, causadas por saída do procedimento se a sua canclusao**/
			$sql = "UPDATE tmsd_itenssaida 
					INNER JOIN tmsd_saidamateriais
					ON tmsd_saidamateriais.sma_id = isa_idsaida
					AND sma_idusuario = '".$_SESSION['usu_id']."'
					AND sma_masterclient = '".$_SESSION['usu_masterclient']."'
					AND sma_idsetor = '0'
					AND sma_del IS NULL
					SET isa_del = '*'
					WHERE isa_del is NULL; ";
			// error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: limpesa de itens da transferencia de estoque não concluidas em tmsd_transferenciaestoque.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function updateSetDestino(){
			$sql = "UPDATE tmsd_itenssaida SET
						isa_idsetordestino = '" . $this->isa_idsetordestino . "'
					WHERE isa_idsaida = " . $this->isa_idsaida." 
					AND isa_del is NULL ";
		 	// error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: idsaida " . $this->isa_idsaida . " em tmsd_itenssaida.";
				$this->gravaLog();
				//
				return $this->isa_idsaida;
			} else {
				return 0;
			} 
		}

	}
?>