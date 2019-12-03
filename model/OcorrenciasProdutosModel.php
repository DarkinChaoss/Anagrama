<?php
	class OcorrenciasProdutosModel extends Conexao{

		public $opr_id;
		public $opr_idproduto;
		public $opr_idocorrencia;
		public $opr_data;
		public $opr_obs;
		public $opr_aux;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
			$sql = "INSERT INTO tmsd_ocorrenciasprodutos (
						opr_idproduto,
						opr_idocorrencia,
						opr_data,
						opr_obs,
						opr_aux,
						opr_nome_pai,
						opr_pai_id
					) VALUES (
						'" . $this->opr_idproduto . "',
						'" . $this->opr_idocorrencia . "',
						'" . date('Y-m-d H:i:s') . "',
						'" . $this->opr_obs . "',
						'" . $this->opr_aux . "',
						'" . $this->opr_nome_pai . "',
						'" . $this->opr_pai_id . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Associação: ocorrência " . $this->opr_idocorrencia . " ao produto " . $this->opr_idproduto . " em tmsd_ocorrenciasprodutos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function update(){
			$sql = "UPDATE tmsd_ocorrenciasprodutos SET
						opr_idproduto = '" . $this->opr_idproduto . "',
						opr_idocorrencia = '" . $this->opr_idocorrencia . "',
						opr_data = '" . $this->opr_data . "',
						opr_obs = '" . $this->opr_obs . "',
						opr_aux = '" . $this->opr_aux . "',
						opr_nome_pai = '" . $this->opr_nome_pai . "',
						opr_pai_id = '" . $this->opr_pai_id . "'
					WHERE opr_id = " . $this->opr_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualização associação: ocorrência " . $this->opr_idocorrencia . " ao produto " . $this->opr_idproduto . " em tmsd_ocorrenciasprodutos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function delete($id){
			$sql = "UPDATE tmsd_ocorrenciasprodutos SET
						opr_del = '*'
					WHERE opr_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmsd_ocorrenciasprodutos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectOcorrenciaProduto($id){
			$sql = "SELECT * FROM tmsd_ocorrenciasprodutos WHERE opr_id = " . $id . " AND opr_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new OcorrenciasProdutosModel();
			$obj->opr_id = $row['opr_id'];
			$obj->opr_idproduto = $row['opr_idproduto'];
			$obj->opr_idocorrencia = $row['opr_idocorrencia'];
			$obj->opr_data = $row['opr_data'];
			$obj->opr_obs = $row['opr_obs'];
			$obj->opr_aux = $row['opr_aux'];
			return $obj;
		}

		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = ' SELECT * FROM tmsd_ocorrenciasprodutos '
			     . ' INNER JOIN tmsd_ocorrencias ON oco_id = opr_idocorrencia'
			     . ' WHERE opr_del IS NULL ' . $where;
			error_log(' OCO AQUI - ' . $sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasProdutosModel();
				$obj->opr_id = $row['opr_id'];
				$obj->opr_idproduto = $row['opr_idproduto'];
				$obj->opr_idocorrencia = $row['opr_idocorrencia'];
				$obj->opr_data = $row['opr_data'];
				$obj->opr_obs = $row['opr_obs'];
				$obj->opr_aux = $row['opr_aux'];
				$a[] = $obj;
			}
			return $a;
		}

		// mesma busca que selectAll, porém no banco Sterilab, para cruzamento de dados
		public function selectAllSterilab($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_ocorrenciasprodutos WHERE opr_del IS NULL " . $where;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasProdutosModel();
				$obj->opr_id = $row['opr_id'];
				$obj->opr_idproduto = $row['opr_idproduto'];
				$obj->opr_idocorrencia = $row['opr_idocorrencia'];
				$obj->opr_data = $row['opr_data'];
				$obj->opr_obs = $row['opr_obs'];
				$obj->opr_aux = $row['opr_aux'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectDescarteByProduto($id){ // retorna a quantidade de ocorrências que causam descarte que um produto ($id) possui.
			$sql = "SELECT * FROM tmsd_ocorrenciasprodutos AS a
						INNER JOIN tmsd_ocorrencias AS b ON (a.opr_idocorrencia = b.oco_id)
					WHERE opr_idproduto = " . $id . "
						AND oco_descarte = 'S'
						AND opr_del IS NULL";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasProdutosModel();
				$obj->opr_id = $row['opr_id'];
				$obj->opr_idproduto = $row['opr_idproduto'];
				$obj->opr_idocorrencia = $row['opr_idocorrencia'];
				$obj->opr_data = $row['opr_data'];
				$obj->opr_obs = $row['opr_obs'];
				$obj->opr_aux = $row['opr_aux'];
				$a[] = $obj;
			}
			return sizeof($a);
		}

		public function selectByEfeitoEspecial($pro, $efeito){
			$a = array();
			// busca ocorrências de efeito especia R em Durazzo
			$sql = "SELECT * FROM tmsd_ocorrenciasprodutos AS a
						INNER JOIN tmsd_ocorrencias AS b ON (a.opr_idocorrencia = b.oco_id)
					WHERE opr_idproduto = " . $pro . "
						AND oco_efeitoespecial = '" . $efeito . "'
						AND opr_del IS NULL";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasProdutosModel();
				$obj->opr_id = $row['opr_id'];
				$obj->opr_idproduto = $row['opr_idproduto'];
				$obj->opr_idocorrencia = $row['opr_idocorrencia'];
				$obj->opr_data = $row['opr_data'];
				$obj->opr_obs = $row['opr_obs'];
				$obj->opr_aux = $row['opr_aux'];
				$a[] = $obj;
			}

			// busca ocorrências de efeito especia R em Sterilab
			$sql = "SELECT * FROM tmss_ocorrenciasprodutos AS a
						INNER JOIN tmss_ocorrencias AS b ON (a.opr_idocorrencia = b.oco_id)
					WHERE opr_idproduto = " . $pro . "
						AND oco_efeitoespecial = '" . $efeito . "'
						AND opr_del IS NULL";
			$res = mysql_query($sql);
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new OcorrenciasProdutosModel();
				$obj->opr_id = $row['opr_id'];
				$obj->opr_idproduto = $row['opr_idproduto'];
				$obj->opr_idocorrencia = $row['opr_idocorrencia'];
				$obj->opr_data = $row['opr_data'];
				$obj->opr_obs = $row['opr_obs'];
				$obj->opr_aux = $row['opr_aux'];
				$a[] = $obj;
			}
			return $a;
		}
        
        public function selectAllOrder($where, $order){
		    if(isset($where) && $where != "")
		        $where = "AND " . $where;
		        $sql = "SELECT * FROM tmsd_ocorrenciasprodutos AS opr
		            INNER JOIN tmsd_ocorrencias AS oco ON (oco_id = opr_idocorrencia) AND (oco_masterclient = {$_SESSION['usu_masterclient']})
					INNER JOIN tmss_produto AS pro ON (opr_idproduto = pro_id)
					INNER JOIN tmsd_gruposmateriais AS gma ON (pro_idgrupomateriais = gma_id)
					INNER JOIN tmss_clientes AS cli ON (pro_idcliente = cli_id)
					WHERE opr_del IS NULL " . $where . "
					ORDER BY " . $order;
		       //echo $sql;
		        $res = mysql_query($sql);
		        $a = array();
		        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
		            $obj = new OcorrenciasProdutosModel();
		            $obj->opr_id = $row['opr_id'];
		            $obj->opr_idproduto = $row['opr_idproduto'];
		            $obj->opr_idocorrencia = $row['opr_idocorrencia'];
		            $obj->opr_data = $row['opr_data'];
		            $obj->opr_obs = $row['opr_obs'];
		            $obj->opr_aux = $row['opr_aux'];
		            $obj->opr_pro_nome = $row['pro_nome'];
		            $obj->opr_pro_qrcode = $row['pro_qrcode'];
		            $obj->opr_gma_nome = $row['gma_nome'];
		            $obj->opr_cli_nome = $row['cli_nome'];
		            $a[] = $obj;
		        }
		        return $a;
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