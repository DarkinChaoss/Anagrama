<?php

	/* 
	Classe criada para controlar o estoque
	20/06/2017
	Weslen Augusto Marconcin
	*/

	class ControleEstoqueModel extends Conexao{

		public $setor;
		public $nome_produto;
		public $qrcode;
		public $ultima_saida;
		public $uso;
		public $status;
		public $situacao;
		public $validade;
		public $data1;
		public $data2;


		public function __construct(){
			$this->conecta();
		}		

		public function select( $setor = null , $situacao = null , $nome_produto = null, $validade = null, $data1 = null, $data2 = null ){
			mysql_query("SET wait_timeout=10");
			mysql_query("SET interactive_timeout=10");
	        $res = mysql_query( $this->sql( $setor , $situacao, strtoupper($nome_produto) , $validade, $data1, $data2 ) );
	        $a = array();

	        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
	            
	            $obj = new ControleEstoqueModel();

				$obj->setor = $row['set_nome'];
				$obj->nome_produto = $row['pro_nome'];
				$obj->qrcode = $row['pro_qrcode'];
				$obj->ultima_saida = $row['ultimaSaida'];
				$obj->uso = $row['isa_reuso'];
				$obj->status = $row['status'];
				$obj->situacao = $row['situacao'];
				$obj->validade = $row['validade'];
				$obj->data1 = $row['data1'];
				$obj->data2 = $row['data2'];

	            $a[] = $obj;

	        }

	        return $a;			

		}

		private function sql( $filtro_setor = null , $filtro_situacao = null, $nome_produto = null, $validade = null, $data1 = null, $data2 = null  ){

			$setor = null;
			if( !empty( $filtro_setor ) AND $filtro_setor > 0 ){
				$setor = "JOIN tmsd_setores ON set_id = SaiMat.sma_idsetor AND set_id = {$filtro_setor}";
			}
			else{
				$setor = 'LEFT JOIN tmsd_setores ON set_id = 
				case when SaiMat.sma_idsetor is null or SaiMat.sma_idsetor=0 then
				  soliEs.ses_idsetor
				else
				case when soli.iso_data > itenSai.isa_data then
				  soliEs.ses_idsetor
				else
				  SaiMat.sma_idsetor
				end
				end';
			}

			$where = null;
			if( !empty( $filtro_situacao ) ){

				if( $filtro_situacao == 'U' ){
					$where = "WHERE situacao = '{$filtro_situacao}'";
				}
				else{
					$where = "WHERE situacao in ( $filtro_situacao )";					
				}
			}

			if( !empty( $nome_produto ) ){

				if( !empty( $where ) ){
					$where .= " AND pro_nome LIKE '{$nome_produto}%'";
				}
				else{
					$where = " WHERE pro_nome LIKE '{$nome_produto}%'";	
				}

			}

			if( !empty( $data1 ) || !empty( $data2 )  ){

				if( !empty( $where ) ){
					$where .= " AND DATE(dataSai) BETWEEN '{$data1}' AND '{$data2}'";
				}
				else{
					$where = "WHERE DATE(dataSai) BETWEEN '{$data1}' AND '{$data2}' ";	
				}

			}


			if( !empty( $validade ) ){

				if( $validade == '<=5' )
					$validade = '(validade <= 5 AND validade > 0)';
				else
					$validade = "validade{$validade}";

				if( !empty( $where ) ){
					$where .= $validade;
				}
				else{
					$where = "WHERE {$validade}";
				}

			}

			

			return "SELECT 
			pro_id,
			pro_nome,
			pro_qrcode,
			pro_idcliente,
			pro_del,
			isa_reuso,
			ultimaSaida,
			dataSai,
			ultimaEsterilizacao,
			validade,
			status,
			situacao,
			idsetor,
			set_nome
				FROM(SELECT	
					prod.pro_id AS pro_id,
					prod.pro_nome AS pro_nome,
					prod.pro_qrcode AS pro_qrcode,
					prod.pro_idcliente AS pro_idcliente,
					prod.pro_del AS pro_del,
					itenSai.isa_reuso AS isa_reuso,  
					DATE_FORMAT(itenSai.isa_data,'%d/%m/%Y %H:%i:%s') AS ultimaSaida,
					itenSai.isa_data AS dataSai,
					DATE_FORMAT(CONCAT(soli.iso_data),'%d/%m/%Y %H:%i:%s') AS ultimaEsterilizacao,
					datediff((SELECT iso_datalimite 
						  FROM tmsd_itenssolicitacao
						  WHERE iso_idproduto = prod.pro_id AND (iso_del IS NULL OR iso_del !='*')
						  AND iso_datalimite != '0000-00-00'
						  ORDER BY iso_data DESC
						  LIMIT 0,1) , cast( now() as date )) AS validade,
					case when SaiMat.sma_tiposaida is not null and SaiMat.sma_tiposaida != 'T' then
														  case when itenSai.isa_data > soli.iso_data then
															  'Em uso'
														  else
														  	  CONCAT('Devolvido ',DATE_FORMAT(soli.iso_data,'%d/%m/%Y %H:%i:%s'))
														  END
													  else
														  case when itenSai.isa_data < soli.iso_data then
														      CONCAT('Devolvido ',DATE_FORMAT(soli.iso_data,'%d/%m/%Y %H:%i:%s'))
														  else
															  'Em estoque'
														  end
													  end as status,
													  case when SaiMat.sma_tiposaida is not null and SaiMat.sma_tiposaida != 'T' then
														  case when itenSai.isa_data > soli.iso_data then
															  'U'
														  else
															  'D'
														  END
													  else
														  case when itenSai.isa_data < soli.iso_data then
															  'D'
														  else
															  'E'
														  end
													  end AS situacao,
													  
					case when SaiMat.sma_idsetor is null or SaiMat.sma_idsetor=0 then
														  soliEs.ses_idsetor
													  else
														  case when soli.iso_data > itenSai.isa_data then
															  soliEs.ses_idsetor
														  else
															  SaiMat.sma_idsetor
														  end
													  end AS idsetor,
				  tmsd_setores.set_nome AS set_nome								  
				  FROM tmss_produto AS prod
				  LEFT JOIN tmsd_itenssolicitacao AS soli
					  ON prod.pro_id = soli.iso_idproduto
					  AND soli.iso_id = (SELECT MAX(S.iso_id) FROM tmsd_itenssolicitacao AS S WHERE S.iso_idproduto = prod.pro_id )
				  LEFT JOIN tmsd_itenssaida AS itenSai
					  ON itenSai.isa_idproduto = prod.pro_id
					  AND itenSai.isa_id = (SELECT MAX(S.isa_id) FROM tmsd_itenssaida AS S WHERE S.isa_idproduto = prod.pro_id )
				  LEFT JOIN tmsd_saidamateriais AS SaiMat ON SaiMat.sma_id = itenSai.isa_idsaida  
					  AND(sma_del IS NULL OR sma_del != '*')
				  lEFT JOIN tmsd_solicitacaoesterilizacao AS soliEs 
					  ON ses_id = (SELECT MAX(S.iso_idses) FROM tmsd_itenssolicitacao AS S WHERE S.iso_idproduto = prod.pro_id )
					  {$setor}) AS t											
				  {$where}
				  ORDER BY set_nome ASC, pro_nome ASC, pro_qrcode ASC, ultimaSaida DESC";

		}

		public function selectConsignados( $setor = null , $situacao = null , $nome_produto = null, $validade = null ){

	        $res = mysql_query( $this->sqlConsignados( $setor , $situacao, strtoupper($nome_produto) , $validade ) );
	        $a = array();

	        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
	            
	            $obj = new ControleEstoqueModel();

				$obj->setor = $row['set_nome'];
				$obj->nome_produto = $row['pro_nome'];
				$obj->qrcode = $row['pro_qrcode'];
				$obj->ultima_saida = $row['ultimaSaida'];
				$obj->uso = $row['isa_reuso'];
				$obj->status = $row['status'];
				$obj->situacao = $row['situacao'];
				$obj->validade = $row['validade'];

	            $a[] = $obj;

	        }

	        return $a;			

		}

		private function sqlConsignados( $filtro_setor = null , $filtro_situacao = null, $nome_produto = null, $validade = null  ){

			$setor = null;
			if( !empty( $filtro_setor ) AND $filtro_setor > 0 ){
				$setor = "JOIN tmsd_setores ON set_id = idsetor AND set_id = {$filtro_setor}";
			}
			else{
				$setor = 'LEFT JOIN tmsd_setores ON set_id = idsetor';
			}

			$where = null;
			if( !empty( $filtro_situacao ) ){

				if( $filtro_situacao == 'U' ){
					$where = "WHERE situacao = '{$filtro_situacao}'";
				}
				else{
					$where = "WHERE situacao in ( $filtro_situacao )";					
				}
			}

			if( !empty( $nome_produto ) ){

				if( !empty( $where ) ){
					$where .= " AND pro_nome LIKE '{$nome_produto}%'";
				}
				else{
					$where = " WHERE pro_nome LIKE '{$nome_produto}%'";	
				}

			}


			if( !empty( $validade ) ){

				if( $validade == '<=5' )
					$validade = '(validade <= 5 AND validade > 0)';
				else
					$validade = "validade{$validade}";

				if( !empty( $where ) ){
					$where .= $validade;
				}
				else{
					$where = "WHERE {$validade}";
				}

			}

			return "	SELECT pro_id,
								pro_nome,
								pro_qrcode,
								isa_reuso,
								DATE_FORMAT(ultimaSaida,'%d/%m/%Y %H:%i:%s') as ultimaSaida,
								DATE_FORMAT(ultimaSolicitacao,'%d/%m/%Y %H:%i:%s') as ultimaSolicitacao,
								DATE_FORMAT(ultimaEsterilizacao,'%d/%m/%Y %H:%i:%s') as ultimaEsterilizacao,
								status,
								situacao,
								set_nome,
								validade
						FROM (
							SELECT pro_id,
									pro_nome,
									pro_qrcode,
									validade,
									isa_reuso,
									ultimaSaida,
									ultimaSolicitacao,
									ultimaEsterilizacao,
									sma_tiposaida,
									case when sma_tiposaida is not null and sma_tiposaida != 'T' then
										case when ultimaSaida > ultimaEsterilizacao then
											'Em uso'
										else
											CONCAT('Devolvido ',DATE_FORMAT(ultimaEsterilizacao,'%d/%m/%Y %H:%i:%s'))
										END
									else
										case when ultimaSaida < ultimaEsterilizacao then
											CONCAT('Devolvido ', DATE_FORMAT(ultimaEsterilizacao,'%d/%m/%Y %H:%i:%s') )
										else
											'Em estoque'
										end
									end as status,
									case when sma_tiposaida is not null and sma_tiposaida != 'T' then
										case when ultimaSaida > ultimaEsterilizacao then
											'U'
										else
											'D'
										END
									else
										case when ultimaSaida < ultimaEsterilizacao then
											'D'
										else
											'E'
										end
									end as situacao,
									case when sma_idsetor is null or sma_idsetor=0 then
										ses_idsetor
									else
										case when ultimaEsterilizacao > ultimaSaida then
											ses_idsetor
										else
											sma_idsetor
										end
									end as idsetor
							FROM (SELECT pro_id,
											pro_nome,
											datediff( tmsd_validade( pro_id ) , cast( now() as date ) )as validade,
											pro_qrcode,
											sma_idsetor,
											ses_idsetor,
											COALESCE( tmsd_ultimoReuso(pro_id) , 0 ) as isa_reuso, 
											sma_tiposaida,
											tmsd_ultimaSaidaComId(pro_id) as ultimaSaida,
										    tmsd_ultimaSolicitacao(pro_id,isa_reuso) AS ultimaSolicitacao,
											tmsd_ultimaEsterilizacao(pro_id) as ultimaEsterilizacao
									FROM tmss_prodconsignado
									LEFT JOIN tmsd_itenssaida ON isa_id = tmsd_ultimaSaida( pro_id ) 
												AND (isa_del IS NULL OR isa_del != '*') 
									LEFT JOIN tmsd_saidamateriais ON sma_id = isa_idsaida  
												AND (sma_del IS NULL OR sma_del != '*')
									lEFT JOIN tmsd_solicitacaoesterilizacao on ses_id = tmsd_idUltimaSolicitacao(  pro_id )
									WHERE isa_consignado = 1 AND pro_idcliente = {$_SESSION['usu_masterclient']}  AND (pro_del IS NULL OR pro_del != '*') ) as t
							) as t		
							{$setor}								
						{$where}
						ORDER BY set_nome ASC, pro_nome ASC, pro_qrcode ASC, ultimaSaida DESC";

		}

		public function getSetDestinoFilho($id_filho){

			$sql = "SELECT isa_idsetordestino FROM tmsd_itenssaida WHERE isa_idproduto = $id_filho AND isa_del IS NULL ORDER BY isa_id desc LIMIT 1 ";
			$idsetor = mysql_result(mysql_query($sql), 0);

			$sql = "SELECT set_nome from tmsd_setores where set_id = $idsetor";
			$res = mysql_query($sql);
			return mysql_result($res, 0);

		}
		
	}