<?php
	class ProdutosModel extends Conexao{

		public $pro_id;
		public $pro_idcliente;
		public $pro_nome;

        public $nome;
		public $pro_qrcode;
		public $pro_qtde;        
		public $pro_idsetor;
		public $pro_curvatura;
		public $pro_calibre;
		public $pro_comprimento;
		public $pro_diametrointerno;
		public $pro_fabricante;
		public $pro_numserie;
		public $pro_datafabricacao;
		public $pro_marca;
		public $pro_anvisa;
		public $pro_lotefabricacao;
		public $pro_referencias;
		public $pro_validacaofabricacao;
		public $pro_idgrupomateriais;
		public $pro_maxqtdprocessamento;
		public $pro_data;
		public $pro_foto;
		public $pro_descarte;
		public $pro_perdido;
		public $pro_composto;
		public $pro_status;
		public $pro_alerta;
		public $pro_alertamsg;
		public $pro_idusuario;
		public $pro_prontos;
		public $pro_detailproduct;
		public $pro_custo;
		// auxiliar
		public $pro_iso_id;
		public $pro_gma_nome;
		public $pro_set_nome;
		public $pro_reuso;
		public $pro_restante;

		public function __construct(){
			$this->conecta();
		}

		public function insert(){
		 	$sql = "INSERT INTO tmss_produto (
						pro_idcliente,
						pro_idsetor,
						pro_qrcode,
						pro_qtde,
						pro_nome,
						pro_calibre,
						pro_curvatura,
						pro_comprimento,
						pro_diametrointerno,
						pro_fabricante,
						pro_numserie,
						pro_datafabricacao,
						pro_marca,
						pro_anvisa,
						pro_lotefabricacao,
						pro_referencias,
						pro_validacaofabricacao,
						pro_idgrupomateriais,
						pro_maxqtdprocessamento,
						pro_custo,
						pro_data,
						pro_foto,
						pro_descarte,
						pro_composto,
						pro_status,
						pro_alerta,
						pro_alertamsg,
						pro_idusuario,
						pro_prontos,
						pro_detailproduct
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $this->getCme() . "',
						'" . DefaultHelper::acentos($this->pro_qrcode) . "',
						'" . $this->pro_qtde . "',
						'" . DefaultHelper::acentos($this->pro_nome) . "',
						'" . DefaultHelper::acentos($this->pro_calibre) . "',
						'" . DefaultHelper::acentos($this->pro_curvatura) . "',
						'" . DefaultHelper::acentos($this->pro_comprimento) . "',
						'" . DefaultHelper::acentos($this->pro_diametrointerno) . "',
						'" . DefaultHelper::acentos($this->pro_fabricante) . "',
						'" . $this->pro_numserie . "',
						'" . $this->pro_datafabricacao . "',
						'" . DefaultHelper::acentos($this->pro_marca) . "',
						'" . DefaultHelper::acentos($this->pro_anvisa) . "',
						'" . $this->pro_lotefabricacao . "',
						'" . DefaultHelper::acentos($this->pro_referencias) . "',
						'" . $this->pro_validacaofabricacao . "',
						'" . $this->pro_idgrupomateriais . "',
						'" . $this->pro_maxqtdprocessamento . "',
						'" . $this->pro_custo . "',
						'" .  date('Y-m-d H:i:s')."',
						'" . $this->pro_foto . "',
						'" . $this->pro_descarte . "',
						'" . $this->pro_composto . "',
						'" . $this->pro_status . "',
						" . $this->pro_alerta . ",
						'" . DefaultHelper::acentos($this->pro_alertamsg) . "',
						" . $this->pro_idusuario . ",
						'". $this->pro_prontos ."',
						'". trim($this->pro_detailproduct) ."'
					)";
			//error_log($sql);
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserido registro " . $id . " em tmss_produto.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function getCme(){
			$sql = "SELECT set_id FROM tmsd_setores where set_nome = 'CME' and set_del is null and set_masterclient = ".$_SESSION['usu_masterclient']."";
			error_log($sql);
			$res = mysql_query($sql);
			return mysql_result($res, 0);
		}

		public function update(){
			$sql = "UPDATE tmss_produto SET
						pro_idsetor = '" . $this->pro_idsetor . "',
						pro_qrcode = '" . DefaultHelper::acentos($this->pro_qrcode) . "',
						pro_qtde = '" . $this->pro_qtde . "',
						pro_nome = '" . DefaultHelper::acentos($this->pro_nome) . "',
						pro_curvatura = '" . DefaultHelper::acentos($this->pro_curvatura) . "',
						pro_calibre = '" . DefaultHelper::acentos($this->pro_calibre) . "',
						pro_comprimento = '" . DefaultHelper::acentos($this->pro_comprimento) . "',
						pro_diametrointerno = '" . DefaultHelper::acentos($this->pro_diametrointerno) . "',
						pro_fabricante = '" . DefaultHelper::acentos($this->pro_fabricante) . "',
						pro_numserie = '" . $this->pro_numserie . "',
						pro_datafabricacao = '" . $this->pro_datafabricacao . "',
						pro_marca = '" . DefaultHelper::acentos($this->pro_marca) . "',
						pro_anvisa = '" . DefaultHelper::acentos($this->pro_anvisa) . "',
						pro_lotefabricacao = '" . $this->pro_lotefabricacao . "',
						pro_referencias = '". DefaultHelper::acentos($this->pro_referencias) . "',
						pro_validacaofabricacao = '". $this->pro_validacaofabricacao ."',
						pro_idgrupomateriais = '" . $this->pro_idgrupomateriais . "',
						pro_maxqtdprocessamento = '" . $this->pro_maxqtdprocessamento . "',
						pro_custo = '" . $this->pro_custo . "',
						pro_foto = '" . $this->pro_foto . "',
						pro_descarte = '" . $this->pro_descarte . "',
						pro_perdido = '" . $this->pro_perdido . "',
						pro_composto = '" . $this->pro_composto . "',
						pro_status = '" . $this->pro_status . "',
						pro_alerta = " . $this->pro_alerta . ",
						pro_alertamsg = '" . DefaultHelper::acentos($this->pro_alertamsg) . "',
						pro_prontos = '". $this->pro_prontos ."',
						pro_detailproduct = '". trim($this->pro_detailproduct) ."'
					WHERE pro_id = " . $this->pro_id;
			$res = mysql_query($sql);
			//error_log("update prod(setStatus) = ".$sql);
			if($res) {
				// log
				$this->log_acao = "Atualização: registro " . $this->pro_id . " em tmss_produtos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

        public function updateReuso( $qtdAtual , $qtdAumentada ){

			$sql = "UPDATE tmss_produto SET
						pro_maxqtdprocessamento = pro_maxqtdprocessamento * 2
					WHERE pro_id = " . $this->pro_id;
					
			$res = mysql_query($sql);
			if ($res){
				// log
				$this->log_acao = "Atualização: registro {$this->pro_id} alterados em tmss_produtos -> Quantidade Maxima de Reuso aumentada de {$qtdAtual} para {$qtdAumentada}.";
				$this->gravaLog();
				//
			}
			return $res;			
		}

		public function updateNome( $dados ){
                        
			$sql = "UPDATE tmss_produto SET
						pro_nome = '{$dados['nome_novo']}'
					WHERE pro_nome = '{$dados['nome_velho']}' AND pro_idcliente = {$_SESSION['usu_masterclient']}";
					
			$res = mysql_query($sql);
			if ($res){
				// log
				$this->log_acao = "Atualização: registro nome Produto de {{$dados['nome_velho']}} para {$dados['nome_novo']} em tmss_produtos.";
				$this->gravaLog();
				//
			}
			return $res;			
		}

		public function updateQrcode(){

			$sql = "UPDATE tmss_produto SET
						pro_qrcode = '" . $this->pro_qrcode . "'
					WHERE pro_id = " . $this->pro_id;
					
			$res = mysql_query($sql);
			if ($res){
				// log
				$this->log_acao = "Atualização: registro " . $this->pro_id . " qrCode alterados ". $this->pro_qrcode ."  em tmss_produtos.";
				$this->gravaLog();
				//
			}
			return $res;			
		}

		public function updateSetor(){
			$sql = "UPDATE tmss_produto SET
						pro_idsetor = '" . $this->pro_idsetor . "'
					WHERE pro_id = " . $this->pro_id;
			//error_log($sql);
			$res = mysql_query($sql);
			if ($res){
				// log
				$this->log_acao = "Atualização: registro " . $this->pro_id . " setor alterados ". $this->pro_idsetor ."  em tmss_produtos.";
				$this->gravaLog();
				//
			}
			return $res;
		}
        

		public function delete($id){
			$sql = "UPDATE tmss_produto SET
						pro_del = '*'
					WHERE pro_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusão: registro " . $id . " em tmss_produtos.";
				$this->gravaLog();
				//
			}
			return $res;
		}

		public function selectProduto($pro_id){
			$sql = "SELECT * FROM tmss_produto WHERE pro_id = " . $pro_id . " AND pro_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			//error_log("prod oco = ".$sql);
			$obj = new ProdutosModel();
			$obj->pro_id = $row['pro_id'];
			$obj->pro_idcliente = $row['pro_idcliente'];
			$obj->pro_idsetor = $row['pro_idsetor'];
			$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
			$obj->pro_qtde = $row['pro_qtde'];
			$obj->pro_nome = $row['pro_nome'];
			$obj->pro_curvatura = $row['pro_curvatura'];
			$obj->pro_calibre = $row['pro_calibre'];
			$obj->pro_comprimento = $row['pro_comprimento'];
			$obj->pro_diametrointerno = $row['pro_diametrointerno'];
			$obj->pro_fabricante = $row['pro_fabricante'];
			$obj->pro_numserie = $row['pro_numserie'];
			$obj->pro_datafabricacao = $row['pro_datafabricacao'];
			$obj->pro_marca = $row['pro_marca'];
			$obj->pro_anvisa = $row['pro_anvisa'];
			$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
			$obj->pro_referencias = $row['pro_referencias'];
			$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
			$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
			$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
			$obj->pro_custo = $row['pro_custo'];
			$obj->pro_data = $row['pro_data'];
			$obj->pro_foto = $row['pro_foto'];
			$obj->pro_descarte = $row['pro_descarte'];
			$obj->pro_perdido = $row['pro_perdido'];
			$obj->pro_composto = $row['pro_composto'];
			$obj->pro_status = $row['pro_status'];
			$obj->pro_alerta = $row['pro_alerta'];
			$obj->pro_alertamsg = $row['pro_alertamsg'];
			$obj->pro_detailproduct = $row['pro_detailproduct'];
			$obj->pro_idusuario = $row['pro_idusuario'];
			return $obj;
		}
		
		public function selectProdutoByQrCode($qrcode){
		    $sql = "SELECT * FROM tmss_produto WHERE pro_qrcode = '" . $qrcode . "'";
		    $res = mysql_query($sql);
		    $row = mysql_fetch_array($res, MYSQL_ASSOC);
		    $obj = new ProdutosModel();
		    $obj->pro_id = $row['pro_id'];
		    $obj->pro_idcliente = $row['pro_idcliente'];
		    $obj->pro_qrcode = $row['pro_qrcode'];
			$obj->pro_qtde = $row['pro_qtde'];
		    $obj->pro_nome = $row['pro_nome'];
		    $obj->pro_curvatura = $row['pro_curvatura'];
		    $obj->pro_calibre = $row['pro_calibre'];
		    $obj->pro_comprimento = $row['pro_comprimento'];
		    $obj->pro_diametrointerno = $row['pro_diametrointerno'];
		    $obj->pro_fabricante = $row['pro_fabricante'];
		    $obj->pro_numserie = $row['pro_numserie'];
		    $obj->pro_datafabricacao = $row['pro_datafabricacao'];
		    $obj->pro_marca = $row['pro_marca'];
		    $obj->pro_modelo = $row['pro_modelo'];
		    $obj->pro_anvisa = $row['pro_anvisa'];
		    $obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
		    $obj->pro_referencias = $row['pro_referencias'];
		    $obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
		    $obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
		    $obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
		    $obj->pro_data = $row['pro_data'];
		    $obj->pro_foto = $row['pro_foto'];
		    $obj->pro_descarte = $row['pro_descarte'];
		    $obj->pro_composto = $row['pro_composto'];
		    $obj->pro_status = $row['pro_status'];
		    $obj->pro_alerta = $row['pro_alerta'];
			$obj->pro_detailproduct = $row['pro_detailproduct'];
		    $obj->pro_alertamsg = $row['pro_alertamsg'];
		    $obj->pro_idusuario = $row['pro_idusuario'];
		    return $obj;
		}

		public function selectAll($where, $order = null){
			if(isset($where))
				$where = "AND " . $where;
			if($order != "")
				$order = "ORDER BY " . $order;
                       
			$sql = "SELECT * 
                    FROM tmss_produto
					WHERE pro_del IS NULL AND (pro_idcliente = " . $_SESSION['usu_masterclient'] . " OR pro_idcliente = " . $_SESSION['usu_referencia'] .") ". $where . "
					" . $order;
            /*
			$sql = "SELECT *
					FROM tmss_produto AS pro
					INNER JOIN tmsd_gruposmateriais AS gma ON (gma_id = pro_idgrupomateriais)
					WHERE pro_idcliente = {$_SESSION['usu_masterclient']} AND pro_del IS NULL " . $where . $order; */
                                        
			$res = mysql_query($sql) or die('erro de banco de dados: ' . mysql_error());
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosModel();
				$obj->pro_id = $row['pro_id'];
				$obj->pro_idcliente = $row['pro_idcliente'];
				$obj->pro_idsetor = $row['pro_idsetor'];
				$obj->pro_qrcode = strtoupper($row['pro_qrcode']) ;
				$obj->pro_qtde = $row['pro_qtde'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_curvatura = $row['pro_curvatura'];
				$obj->pro_calibre = $row['pro_calibre'];
				$obj->pro_comprimento = $row['pro_comprimento'];
				$obj->pro_diametrointerno = $row['pro_diametrointerno'];
				$obj->pro_fabricante = $row['pro_fabricante'];
				$obj->pro_numserie = $row['pro_numserie'];
				$obj->pro_datafabricacao = $row['pro_datafabricacao'];
				$obj->pro_marca = $row['pro_marca'];
				$obj->pro_anvisa = $row['pro_anvisa'];
				$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
				$obj->pro_referencias = $row['pro_referencias'];
				$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
				$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
				$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
				$obj->pro_data = $row['pro_data'];
				$obj->pro_foto = $row['pro_foto'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_perdido = $row['pro_perdido'];
				$obj->pro_composto = $row['pro_composto'];
				$obj->pro_status = $row['pro_status'];
				$obj->pro_alerta = $row['pro_alerta'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
				$obj->pro_alertamsg = $row['pro_alertamsg'];
				$obj->pro_idusuario = $row['pro_idusuario'];
				$obj->pro_prontos = $row['pro_prontos'];
				$a[] = $obj;
			}
			return $a;
		}

		public function selectAllDel($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_produto
					WHERE pro_del = '*' AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
					ORDER BY pro_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosModel();
				$obj->pro_id = $row['pro_id'];
				$obj->pro_idcliente = $row['pro_idcliente'];
				$obj->pro_idsetor = $row['pro_idsetor'];
				$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
				$obj->pro_qtde = $row['pro_qtde'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_curvatura = $row['pro_curvatura'];
				$obj->pro_calibre = $row['pro_calibre'];
				$obj->pro_comprimento = $row['pro_comprimento'];
				$obj->pro_diametrointerno = $row['pro_diametrointerno'];
				$obj->pro_fabricante = $row['pro_fabricante'];
				$obj->pro_numserie = $row['pro_numserie'];
				$obj->pro_datafabricacao = $row['pro_datafabricacao'];
				$obj->pro_marca = $row['pro_marca'];
				$obj->pro_anvisa = $row['pro_anvisa'];
				$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
				$obj->pro_referencias = $row['pro_referencias'];
				$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
				$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
				$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
				$obj->pro_data = $row['pro_data'];
				$obj->pro_foto = $row['pro_foto'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_perdido = $row['pro_perdido'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
				$obj->pro_composto = $row['pro_composto'];
				$obj->pro_status = $row['pro_status'];
				$obj->pro_alerta = $row['pro_alerta'];
				$obj->pro_alertamsg = $row['pro_alertamsg'];
				$obj->pro_idusuario = $row['pro_idusuario'];
				$a[] = $obj;
			}
			return $a;
		}

		public function search($buscar, $limit, $where, $order){
			if($where != "")
				$where = "AND " . $where;
			if($order != "")
				$order = "ORDER BY " . $order;
			$sql = "SELECT * FROM tmss_produto
					WHERE
					(
						pro_qrcode = '" . $buscar . "'
						OR
						pro_nome LIKE '%" . $buscar . "%'
					)
					AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					" . $where . "
					AND pro_del IS NULL
					" . $order . " " . $limit;
			$res = mysql_query($sql);
			//error_log($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosModel();
				$obj->pro_id = $row['pro_id'];
				$obj->pro_idcliente = $row['pro_idcliente'];
				$obj->pro_idsetor = $row['pro_idsetor'];
				$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
				$obj->pro_qtde = $row['pro_qtde'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_custo = $row['pro_custo'];
				$obj->pro_curvatura = $row['pro_curvatura'];
				$obj->pro_calibre = $row['pro_calibre'];
				$obj->pro_comprimento = $row['pro_comprimento'];
				$obj->pro_diametrointerno = $row['pro_diametrointerno'];
				$obj->pro_fabricante = $row['pro_fabricante'];
				$obj->pro_numserie = $row['pro_numserie'];
				$obj->pro_datafabricacao = $row['pro_datafabricacao'];
				$obj->pro_marca = $row['pro_marca'];
				$obj->pro_anvisa = $row['pro_anvisa'];
				$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
				$obj->pro_referencias = $row['pro_referencias'];
				$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
				$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
				$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
				$obj->pro_data = $row['pro_data'];
				$obj->pro_foto = $row['pro_foto'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_perdido = $row['pro_perdido'];
				$obj->pro_composto = $row['pro_composto'];
				$obj->pro_status = $row['pro_status'];
				$obj->pro_alerta = $row['pro_alerta'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
				$obj->pro_alertamsg = $row['pro_alertamsg'];
				$obj->pro_idusuario = $row['pro_idusuario'];
				$a[] = $obj;
			}
			return $a;
		}

		public function searchCount($buscar, $limit, $where){
			if($where != "")
				$where = "AND " . $where;
			else
				$where = "";
			$sql = "SELECT COUNT(*) AS n FROM tmss_produto
					WHERE
					(
						pro_qrcode = '" . $buscar . "'
						OR
						pro_nome LIKE '%" . $buscar . "%'
					)
					AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					" . $where . "
					AND pro_del IS NULL
					ORDER BY pro_nome
					" . $limit;
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['n'];
		}

        public function selectProdutoParaSaida($qrcode){
            
            // $sql = "select * from tmss_produto where pro_qrcode='{$qrcode}' AND pro_idcliente = {$_SESSION['usu_masterclient']}";
			
            
            $sql = "SELECT *, pro_nome as nome
					FROM tmss_produto
					WHERE pro_qrcode = '" . $qrcode . "'
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
						AND pro_del IS NULL";
      
            
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
            
            $obj = null;
            
            if( $row AND is_array( $row ) AND array_key_exists('pro_id' , $row) ){

                $obj = new ProdutosModel();
                        
    			$obj->pro_id = $row['pro_id'];
    			$obj->pro_idcliente = $row['pro_idcliente'];
    			$obj->pro_idsetor = $row['pro_idsetor'];
    			$obj->pro_qrcode = strtoupper(  $row['pro_qrcode'] );
				$obj->pro_qtde = $row['pro_qtde'];    			
                $obj->nome = $row['nome'];
                $obj->pro_nome = $row['pro_nome'];
                
    			$obj->pro_curvatura = $row['pro_curvatura'];
    			$obj->pro_calibre = $row['pro_calibre'];
    			$obj->pro_comprimento = $row['pro_comprimento'];
    			$obj->pro_diametrointerno = $row['pro_diametrointerno'];
    			$obj->pro_fabricante = $row['pro_fabricante'];
    			$obj->pro_numserie = $row['pro_numserie'];
    			$obj->pro_datafabricacao = $row['pro_datafabricacao'];
    			$obj->pro_marca = $row['pro_marca'];
    			$obj->pro_anvisa = $row['pro_anvisa'];
    			$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
    			$obj->pro_referencias = $row['pro_referencias'];
    			$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
    			$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
    			$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
    			$obj->pro_data = $row['pro_data'];
    			$obj->pro_foto = $row['pro_foto'];
    			$obj->pro_descarte = $row['pro_descarte'];
    			$obj->pro_perdido = $row['pro_perdido'];
    			$obj->pro_prontos = $row['pro_prontos'];
    			$obj->pro_composto = $row['pro_composto'];
    			$obj->pro_status = $row['pro_status'];
    			$obj->pro_alerta = $row['pro_alerta'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
    			$obj->pro_alertamsg = $row['pro_alertamsg'];
    			$obj->pro_idusuario = $row['pro_idusuario'];
                
            }
			
			return $obj;
		}        

		public function selectProdutoParaSolicitacao($qrcode){
			$sql = "SELECT *
					FROM tmss_produto AS pro
					LEFT JOIN tmsd_setores AS setor ON (set_id = pro_idsetor)
					INNER JOIN tmsd_gruposmateriais AS gma ON (gma_id = pro_idgrupomateriais)
					WHERE pro_qrcode = '" . $qrcode . "'
						AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
						AND pro_del IS NULL";
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProdutosModel();
			$obj->pro_id = $row['pro_id'];
			$obj->pro_idcliente = $row['pro_idcliente'];
			$obj->pro_qtde = $row['pro_qtde'];
			$obj->pro_nome = $row['pro_nome'];
			$obj->pro_curvatura = $row['pro_curvatura'];
			$obj->pro_calibre = $row['pro_calibre'];
			$obj->pro_comprimento = $row['pro_comprimento'];
			$obj->pro_diametrointerno = $row['pro_diametrointerno'];
			$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
			$obj->pro_composto = $row['pro_composto'];
			$obj->pro_alerta = $row['pro_alerta'];
			$obj->pro_alertamsg = $row['pro_alertamsg'];
			$obj->pro_descarte = $row['pro_descarte'];
			$obj->pro_detailproduct = $row['pro_detailproduct'];
			$obj->pro_status = $row['pro_status'];
			$obj->pro_prontos = $row['pro_prontos']; 			
			// auxiliares
			$obj->pro_set_nome = $row['set_nome'];
			$obj->pro_gma_nome = $row['gma_nome'];

			$sql = "SELECT
						COUNT(sesd.ses_id) + COUNT(sess.ses_id) AS reuso
					FROM tmss_produto AS pro
					LEFT JOIN tmsd_itenssolicitacao AS isod ON (isod.iso_idproduto = pro_id)
						AND isod.iso_del IS NULL
					LEFT JOIN tmss_itenssolicitacao AS isos ON (isos.iso_idproduto = pro_id)
						AND isos.iso_del IS NULL
					LEFT JOIN tmsd_solicitacaoesterilizacao AS sesd ON (sesd.ses_id = isod.iso_idses)
						AND sesd.ses_del IS NULL
					LEFT JOIN tmss_solicitacaoesterilizacao AS sess ON (sess.ses_id = isos.iso_idses)
						AND sess.ses_del IS NULL
					WHERE pro_id = " . $obj->pro_id . "
						AND isod.iso_del IS NULL
						AND sesd.ses_del IS NULL
						AND isos.iso_del IS NULL
						AND sess.ses_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj->pro_reuso = $row['reuso'];
			return $obj;
		}

		public function selectSetorByProduto($idPro){
			$res = mysql_query("SELECT pro_idsetor FROM tmss_produto WHERE (pro_del IS NULL OR pro_del != '*') AND pro_id = ".$idPro );
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProdutosModel();
			$obj->pro_idsetor = $row['pro_idsetor'];
			return $obj;
		}

		public function selectGMaterialByProduto($idPro){
			$res = mysql_query("SELECT pro_idgrupomateriais FROM tmss_produto WHERE (pro_del IS NULL OR pro_del != '*') AND pro_id = ".$idPro );
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProdutosModel();
			$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
			return $obj;
		}

		public function selectProdutoInSolicitacao2($qrCode, $ses = 0, $stat = "x"){

			$sql = "SELECT * FROM tmss_produto AS pro
						INNER JOIN tmsd_itenssolicitacao AS iso ON (pro_id = iso_idproduto)
							AND iso_del IS NULL
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
							AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
					WHERE pro_qrcode = '" . $qrCode . "'
						AND pro_del IS NULL ORDER BY iso_id DESC
					LIMIT 1";			
	

			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProdutosModel();
			$obj->pro_id = $row['pro_id'];
			$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
			$obj->pro_qtde = $row['pro_qtde'];
			$obj->pro_nome = $row['pro_nome'];
			$obj->pro_curvatura = $row['pro_curvatura'];
			$obj->pro_calibre = $row['pro_calibre'];
			$obj->pro_comprimento = $row['pro_comprimento'];
			$obj->pro_diametrointerno = $row['pro_diametrointerno'];
			$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
			$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
			$obj->pro_descarte = $row['pro_descarte'];
			$obj->pro_perdido = $row['pro_perdido'];
			$obj->pro_composto = $row['pro_composto'];
			$obj->pro_detailproduct = $row['pro_detailproduct'];
			$obj->pro_iso_id = $row['iso_id'];
			return $obj;
		}
		

		public function selectProdutoInSolicitacao($qrCode, $ses = 0, $stat = "x"){
			//verifica se é impressão nova ou reeimpressão
			$sqlverify = "SELECT * FROM tmss_produto AS pro
						INNER JOIN tmsd_itenssolicitacao AS iso ON (pro_id = iso_idproduto)
							AND iso_del IS NULL
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
							AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
							" . $andSes . "
					WHERE pro_qrcode = '" . $qrCode . "'
						" . $andStat . "
						AND pro_del IS NULL ORDER BY iso_id DESC
					LIMIT 1";			
	
			$resverify = mysql_query($sqlverify);
			$rowverify = mysql_fetch_array($resverify, MYSQL_ASSOC);				
				
			if($ses != 0)
				$andSes = "AND iso_idses = " . $ses;
			else
				$andSes = "";
			if($stat != "x" && $stat != "")
				$andStat = "AND iso_status = '" . $stat . "'";
			else
				$andStat = "";
			
			
			if($rowverify['iso_status'] == 1){
				$sql = "SELECT * FROM tmss_produto AS pro
						INNER JOIN tmsd_itenssolicitacao AS iso ON (pro_id = iso_idproduto)
							AND iso_del IS NULL
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
							AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
							" . $andSes . "
					WHERE pro_qrcode = '" . $qrCode . "'
						" . $andStat . "
						AND pro_del IS NULL 
					ORDER BY iso_id DESC
					LIMIT 1";			
			}else{
				$sql = "SELECT * FROM tmss_produto AS pro
						INNER JOIN tmsd_itenssolicitacao AS iso ON (pro_id = iso_idproduto)
							AND iso_del IS NULL
						INNER JOIN tmsd_solicitacaoesterilizacao AS ses ON (iso_idses = ses_id)
							AND ses_masterclient = " . $_SESSION['usu_masterclient'] . "
							" . $andSes . "
					WHERE pro_qrcode = '" . $qrCode . "'
						" . $andStat . "
						AND pro_del IS NULL AND iso_datalimite = '0000-00-00'
					ORDER BY iso_id ASC
					LIMIT 1";	
			}	

			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ProdutosModel();
			$obj->pro_id = $row['pro_id'];
			$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
			$obj->pro_qtde = $row['pro_qtde'];
			$obj->pro_nome = $row['pro_nome'];
			$obj->pro_curvatura = $row['pro_curvatura'];
			$obj->pro_calibre = $row['pro_calibre'];
			$obj->pro_comprimento = $row['pro_comprimento'];
			$obj->pro_diametrointerno = $row['pro_diametrointerno'];
			$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
			$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
			$obj->pro_descarte = $row['pro_descarte'];
			$obj->pro_perdido = $row['pro_perdido'];
			$obj->pro_composto = $row['pro_composto'];
			$obj->pro_detailproduct = $row['pro_detailproduct'];
			$obj->pro_iso_id = $row['iso_id'];
			return $obj;

		}

		public function selectProdutosDescartados($dataInicio, $dataFinal){

		    if( $dataInicio != '//'){ $where .= " AND opr_data >= '$dataInicio' AND opr_data <= '$dataFinal' "; }

		    $sql = " SELECT pro_nome, pro_qrcode, opr_data, oco_nome, oco_descarte FROM tmss_produto
                    INNER JOIN tmss_clientes ON cli_id = pro_idcliente AND cli_ambiente = 'D'
                    LEFT JOIN tmsd_ocorrenciasprodutos ON opr_idproduto = pro_id
                    LEFT JOIN tmsd_ocorrencias ON oco_id = opr_idocorrencia
                    WHERE pro_descarte = '*'
                    AND pro_del IS NULL
		            AND opr_del IS NULL
		            AND oco_descarte = 'S'
                    AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
		            $where
                    ORDER BY opr_data DESC ";

            //error_log($sql);

            $res = mysql_query($sql);
            $a = array();
            while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
                $obj = new ProdutosModel();
                $obj->pro_id = $row['pro_id'];
                $obj->pro_idcliente = $row['pro_idcliente'];
                $obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
				$obj->pro_qtde = $row['pro_qtde'];
                $obj->pro_nome = $row['pro_nome'];
                $obj->pro_curvatura = $row['pro_curvatura'];
                $obj->pro_calibre = $row['pro_calibre'];
                $obj->pro_comprimento = $row['pro_comprimento'];
                $obj->pro_diametrointerno = $row['pro_diametrointerno'];
                $obj->pro_fabricante = $row['pro_fabricante'];
                $obj->pro_numserie = $row['pro_numserie'];
                $obj->pro_datafabricacao = $row['pro_datafabricacao'];
                $obj->pro_marca = $row['pro_marca'];
                $obj->pro_anvisa = $row['pro_anvisa'];
                $obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
                $obj->pro_referencias = $row['pro_referencias'];
                $obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
                $obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
                $obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
                $obj->pro_data = $row['pro_data'];
                $obj->pro_foto = $row['pro_foto'];
                $obj->pro_descarte = $row['pro_descarte'];
                $obj->pro_composto = $row['pro_composto'];
                $obj->pro_status = $row['pro_status'];
                $obj->pro_alerta = $row['pro_alerta'];
                $obj->pro_alertamsg = $row['pro_alertamsg'];
                $obj->pro_idusuario = $row['pro_idusuario'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
                $obj->pro_opr_data = $row['opr_data'];
                $obj->pro_oco_nome = $row['oco_nome'];
                $a[] = $obj;
            }
            return $a;
		}
		
		public function getquantidade($id){

			$sql = "SELECT pro_qtde FROM tmss_produto WHERE pro_id = '$id'";

			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['pro_qtde'];
		}
		
		//cleverson matias
		public function getqtdprontos($qr){
			$sql = "SELECT * FROM tmss_produto WHERE pro_qrcode = '$qr'";
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['pro_prontos'];
		}
		
		//cleverson
		public function getquantidadeByQr($qr){
			$sql = "SELECT * FROM tmss_produto WHERE pro_qrcode = '$qr'";
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['pro_qtde'];
		}
		public function getqtdprontosById($id){
			$sql = "SELECT * FROM tmss_produto WHERE pro_id = '$id'";
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row['pro_prontos'];
		}

		public function getProdutoDesc($idPro){
			$sql = "SELECT * FROM tmss_produto WHERE pro_id=".$idPro;
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row;
		}

		public function ReplicarItem($array,$qte,$qrcode,$user){
			$reprocessamento = 1;
			$sql = "INSERT INTO tmss_produto (
				pro_idcliente,
				pro_qrcode,
				pro_nome,
				pro_calibre,
				pro_curvatura,
				pro_comprimento,
				pro_diametrointerno,
				pro_fabricante,
				pro_numserie,
				pro_datafabricacao,
				pro_marca,
				pro_modelo,
				pro_anvisa,
				pro_lotefabricacao,
				pro_referencias,
				pro_validacaofabricacao,
				pro_idgrupomateriais,
				pro_maxqtdprocessamento,
				pro_data,
				pro_foto,
				pro_descarte,
				pro_composto,
				pro_status,
				pro_alerta,
				pro_alertamsg,
				pro_idusuario,
				pro_qtde
			) VALUES (
				'" . $array['pro_idcliente']  . "',
				'" . $qrcode."',
				'" . $array['pro_nome'] . "',
				'" . $array['pro_calibre'] . "',
				'" . $array['pro_curvatura'] . "',
				'" . $array['pro_comprimento'] . "',
				'" . $array['pro_diametrointerno'] . "',
				'" . $array['pro_fabricante'] . "',
				'" . $array['pro_numserie'] . "',
				'" . $array['pro_datafabricacao'] . "',
				'" . $array['pro_marca'] . "',
				'" . $array['pro_modelo'] . "',
				'" . $array['pro_anvisa'] . "',
				'" . $array['pro_lotefabricacao'] . "',
				'" . $array['pro_referencias'] . "',
				'" . $array['pro_validacaofabricacao,'] . "',
				'" . $array['pro_idgrupomateriais'] . "',
				'" . $reprocessamento. "',
				'" . $array['pro_data'] ."',
				'" . $array['pro_foto'] . "',
				'" . $array['pro_descarte,'] . "',
				'" . $array['pro_composto'] . "',
				'" . $array['pro_status'] . "',
				" .  $array['pro_alerta'] . ",
				'" . $array['pro_alertamsg'] . "',
				'" . $user. "',
				"  .$qte. "
			)";
			$res = mysql_query($sql);
			$sql = "SELECT MAX(s.PRO_ID) AS id, s.pro_qrcode, s.pro_qtde FROM tmss_produto s WHERE pro_nome='" . $array['pro_nome'] . "'";
			error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			return $row;
		}

		public function selectAllOrder($where, $order, $reuso){
			if(isset($where) && $where != "")
				$where = "AND " . $where;
			if ($reuso) {
					$sql = "SELECT *,
							COUNT(isod.iso_id) + COUNT(isos.iso_id) AS reuso,
							pro_maxqtdprocessamento - COUNT(isod.iso_id) - COUNT(isos.iso_id) AS restante
						FROM tmss_produto AS p
							INNER JOIN tmsd_gruposmateriais AS g ON (gma_id = pro_idgrupomateriais " . $where . ")
							LEFT JOIN tmsd_setores AS s ON (set_id = pro_idsetor)
							LEFT JOIN tmsd_itenssolicitacao AS isod ON (isod.iso_idproduto = pro_id)
							LEFT JOIN tmss_itenssolicitacao AS isos ON (isos.iso_idproduto = pro_id)
							LEFT JOIN tmsd_solicitacaoesterilizacao AS sesd ON (sesd.ses_id = isod.iso_idses)
							LEFT JOIN tmss_solicitacaoesterilizacao AS sess ON (sess.ses_id = isos.iso_idses)
						WHERE pro_del IS NULL
							AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
							AND isod.iso_del IS NULL
							AND sesd.ses_del IS NULL
							AND isos.iso_del IS NULL
							AND sess.ses_del IS NULL
						GROUP BY pro_id
						ORDER BY " . $order;
			} else {
				$sql = "SELECT * FROM tmss_produto AS p
						INNER JOIN tmsd_gruposmateriais AS g ON (gma_id = pro_idgrupomateriais)
						LEFT JOIN tmsd_setores AS s ON (set_id = pro_idsetor)
						WHERE pro_del IS NULL AND pro_idcliente = " . $_SESSION['usu_masterclient'] . " " . $where . "
						ORDER BY " . $order;
			}

			/*
			if ($reuso)
			error_log("ANALITICO - ".$sql);
				else
				error_log("SINTETICO - ".$sql);
				*/

			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ProdutosModel();
				$obj->pro_id = $row['pro_id'];
				$obj->pro_idsetor = $row['pro_idsetor'];
				$obj->pro_qrcode = strtoupper( $row['pro_qrcode'] );
				$obj->pro_qtde = $row['pro_qtde'];
				$obj->pro_nome = $row['pro_nome'];
				$obj->pro_curvatura = $row['pro_curvatura'];
				$obj->pro_calibre = $row['pro_calibre'];
				$obj->pro_comprimento = $row['pro_comprimento'];
				$obj->pro_diametrointerno = $row['pro_diametrointerno'];
				$obj->pro_fabricante = $row['pro_fabricante'];
				$obj->pro_numserie = $row['pro_numserie'];
				$obj->pro_datafabricacao = $row['pro_datafabricacao'];
				$obj->pro_marca = $row['pro_marca'];
				$obj->pro_anvisa = $row['pro_anvisa'];
				$obj->pro_lotefabricacao = $row['pro_lotefabricacao'];
				$obj->pro_referencias = $row['pro_referencias'];
				$obj->pro_validacaofabricacao = $row['pro_validacaofabricacao'];
				$obj->pro_idgrupomateriais = $row['pro_idgrupomateriais'];
				$obj->pro_maxqtdprocessamento = $row['pro_maxqtdprocessamento'];
				$obj->pro_data = $row['pro_data'];
				$obj->pro_foto = $row['pro_foto'];
				$obj->pro_descarte = $row['pro_descarte'];
				$obj->pro_perdido = $row['pro_perdido'];
				$obj->pro_composto = $row['pro_composto'];
				$obj->pro_status = $row['pro_status'];
				$obj->pro_alerta = $row['pro_alerta'];
				$obj->pro_alertamsg = $row['pro_alertamsg'];
				$obj->pro_idusuario = $row['pro_idusuario'];
				// auxiliar
				$obj->pro_gma_nome = $row['gma_nome'];
				$obj->pro_set_nome = $row['set_nome'];
				$obj->pro_reuso = $row['reuso'];
				$obj->pro_restante = $row['restante'];
				$obj->pro_detailproduct = $row['pro_detailproduct'];
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
 * Brothers Soluções em T.I. © 2015
*/
?>