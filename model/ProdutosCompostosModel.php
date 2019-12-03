<?php
	class ProdutosCompostosModel extends Conexao{

		public $pco_id;
		public $pco_idpai;
		public $pco_idfilho;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmss_produtoscompostos (
						pco_idpai,
						pco_idfilho
					) VALUES (
						" . $this->pco_idpai . ",
						" . $this->pco_idfilho . "
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Associação: produto filho " . $this->pco_idfilho . " ao produto pai " . $this->pco_idpai . " em tmss_produtoscompostos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function update(){
			$sql = "UPDATE tmss_produtoscompostos SET
						pco_idpai = " . $this->pco_idpai . ",
						pco_idfilho = " . $this->pco_idfilho . "
					WHERE pco_id = " . $this->pco_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização associação: produto filho " . $this->pco_idfilho . " ao produto pai " . $this->pco_idpai . " em tmss_produtoscompostos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delete($id){
			$sql = "UPDATE tmss_produtoscompostos SET
						pco_del = '*'
					WHERE pco_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmss_produtoscompostos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectAll2($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_produtoscompostos INNER JOIN tmss_produto ON pro_id = pco_idfilho WHERE pco_del IS NULL " . $where .' ORDER BY pro_nome';
			
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_id = $row['pco_id'];
				$obj->pco_idpai = $row['pco_idpai'];
				$obj->pco_idfilho = $row['pco_idfilho'];
		
			}
			return $obj;
		}

		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_produtoscompostos INNER JOIN tmss_produto ON pro_id = pco_idfilho WHERE pco_del IS NULL " . $where .' ORDER BY pro_nome';
			 error_log("prod composto " . $sql);

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_id = $row['pco_id'];
				$obj->pco_idpai = $row['pco_idpai'];
				$obj->pco_idfilho = $row['pco_idfilho'];
				$a[] = $obj;
			}
			return $a;
		}


		public function selectCountFilhos($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT COUNT(pco_id) AS total FROM tmss_produtoscompostos INNER JOIN tmss_produto ON pro_id = pco_idfilho WHERE pro_descarte != '*' AND pco_del IS NULL " . $where;
			//echo $sql;
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row["total"];
		}

		public function selectAllInner($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT pco_id FROM tmss_produtoscompostos AS pco
					INNER JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
						AND pro_del IS NULL
					WHERE pco_del IS NULL " . $where;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_id = $row['pco_id'];
				$obj->pco_idpai = $row['pco_idpai'];
				$obj->pco_idfilho = $row['pco_idfilho'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectAllInnerCount($where){
			if(isset($where))
				$where = $where. " AND";
			$sql = "SELECT COUNT(pco_id) AS n FROM tmss_produtoscompostos AS pco
					INNER JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
						AND pro_del IS NULL
					WHERE " . $where . "
					pco_del IS NULL
					";
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['n'];
		}
		
		public function selectCompostoInSolicitacao($where){
			if(isset($where))
				$where = "AND " . $where;
			//verificar essa query
			$sql = "SELECT pco_id, pco_idpai, pco_idfilho FROM tmss_produtoscompostos LEFT JOIN tmsd_itenssolicitacao ON tmss_produtoscompostos.pco_idfilho = tmsd_itenssolicitacao.iso_idproduto WHERE pco_del IS NULL AND iso_del IS NULL " . $where . " AND iso_status = 0";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosCompostosModel();
				$obj->pco_id = $row['pco_id'];
				$obj->pco_idpai = $row['pco_idpai'];
				$obj->pco_idfilho = $row['pco_idfilho'];
				$a[] = $obj;
			}
			return $a;
		}

		public function isCompoundSon($qrcode){
			$sql = "Select pro_id, pro_nome from tmss_produto where pro_qrcode = '$qrcode'";
			$res = mysql_query($sql);
			$pro_info = mysql_fetch_assoc($res);
			$pro_id = $pro_info['pro_id'];
			

			$sql = "SELECT pco_idpai, pco_idfilho FROM tmss_produtoscompostos where pco_idfilho =  $pro_id AND pco_del IS NULL";
			$res = mysql_query($sql);
			$return = mysql_fetch_assoc($res);
			$id_pai = $return['pco_idpai'];

			$sql = "SELECT isa_idsetordestino FROM tmsd_itenssaida WHERE isa_idproduto = '$id_pai' AND isa_del IS NULL ORDER BY isa_id desc LIMIT 1";
			$res = mysql_query($sql) or die(mysql_error());
			$id_setor = mysql_result($res, 0);

			$sql = "SELECT set_nome from tmsd_setores where set_id = $id_setor";
			$res = mysql_query($sql);
			$setor_pai_nome = mysql_result($res, 0);
		
			$sql = "Select pro_nome, pro_qrcode from tmss_produto where pro_id = $id_pai";
			$res = mysql_query($sql);
			$data = mysql_fetch_assoc($res);
			$nome_pai = $data['pro_nome'];
			$qrcode_pai = $data['pro_qrcode'];

			$sql = "Select max(iso_contagem) from tmsd_itenssolicitacao where iso_idproduto = '$pro_id' AND iso_del IS NULL";
			$res = mysql_query($sql);
			$pro_contagem = mysql_result($res, 0);
			
			$sql = "Select max(iso_contagem) from tmsd_itenssolicitacao where iso_idproduto = ".$return['pco_idpai']." AND iso_del IS NULL";
			$res = mysql_query($sql);
			$pro_contagem_pai = mysql_result($res, 0);

			$response = [
				'contagem_pai' => $pro_contagem_pai, 
				'filho' => $return['pco_idfilho'],
				'contagem_filho' => $pro_contagem,
				'nome_pai' => $nome_pai,
				'nome_setor_pai' => $setor_pai_nome,
				'qrcode_pai' => $qrcode_pai
			];

			return $response;
		}

		public function getFatherId($son_id){
			$sql = "SELECT pco_idpai FROM tmss_produtoscompostos WHERE pco_idfilho = $son_id and pco_del is null limit 1";
			return mysql_result(mysql_query($sql), 0);
		}

		public function getRepetition($son_id, $id_pai){
			$sql = "SELECT pco_idfilho, pro_nome FROM tmss_produtoscompostos
			join tmss_produto on pco_idfilho = pro_id
			 WHERE pco_idpai = $id_pai and pco_del is null";

			 $res = mysql_query($sql);

			 $arr_nomes = array();
			 $nome_where_id = '';
			 while ($row = mysql_fetch_assoc($res)) {
			 	array_push($arr_nomes, $row['pro_nome']);
			 	if($row['pco_idfilho'] == $son_id){
			 		$nome_where_id = $row['pro_nome'];
			 	}
			 }

			 $indexes = array_keys($arr_nomes, $nome_where_id);

			return count($indexes);

		}
	}
?>