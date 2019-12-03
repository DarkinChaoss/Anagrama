<?php

	/* Classe criada para controlar o relatorio da conferencia de material
	20/06/2017
	Weslen Augusto Marconcin */

	class ConferenciaMaterialModel extends Conexao{

		public $idproduto;
		public $data_saida;
		public $qrcode;
		public $nome_produto;
		public $setor;
		public $ultimaEsterilizacao;
		public $dias_pendente;
		public $paciente;
		public $prontuario;
		public $idsaida;

		public function __construct(){
			$this->conecta();
		}		

		public function select( $prontuario = null , $qrcode = null){

	        $res = mysql_query( $this->sql( $prontuario , $qrcode ) );
	        $a = array();

	        while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
	            
	            $obj = new ConferenciaMaterialModel();

				$obj->idproduto = $row['idproduto'];
				$obj->data_saida = $row['data_saida_conv'];
				$obj->qrcode = $row['qrcode'];
				$obj->nome_produto = $row['nome_produto'];
				$obj->setor = $row['setor'];
				$obj->ultimaEsterilizacao = $row['ultimaEsterilizacao'];
				$obj->dias_pendente = $row['dias_pendente'];
				$obj->paciente = $row['paciente'];
				$obj->prontuario = $row['prontuario'];
				$obj->idsaida = $row['idsaida'];

	            $a[] = $obj;

	        }

	        return $a;			

		}

		private function sql( $prontuario = null , $qrcode = null ){

			$where = null;
			if( !empty( $prontuario ) )
				$where = " AND ( prontuario LIKE '%{$prontuario}%' OR 
								 paciente LIKE '%{$prontuario}%' )";

			if( !empty( $qrcode ) ){
				$where = " AND qrcode='{$qrcode}'";
			}

			return "	select * ,
								DATE_FORMAT(data_saida,'%d/%m/%Y') as data_saida_conv
						from (select isa_idproduto as idproduto,
										isa_data as data_saida,
										pro_qrcode as qrcode,
										pro_nome as nome_produto,
										set_nome as setor,
										coalesce( tmsd_ultimaEsterilizacao(isa_idproduto) , '0000-00-00' ) as ultimaEsterilizacao,
										DATEDIFF(CURDATE(), isa_data ) as dias_pendente,
										sma_paciente as paciente,
										sma_prontuario as prontuario,
										sma_id as idsaida
								from tmsd_itenssaida
								join tmsd_saidamateriais on sma_id = isa_idsaida AND sma_conferido is not null
								join tmsd_setores on set_id = sma_idsetor
								join tmss_produto on pro_id = isa_idproduto
								where (isa_conferente is null or isa_dataconferencia is null)) as t
						where data_saida > ultimaEsterilizacao {$where}";

		}
		
		public function selectControleEsterilizacao($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT pro_nome, COUNT(iso_id) AS n FROM tmsd_itenssolicitacao AS iso
					INNER JOIN tmss_produto AS pro ON (iso_idproduto = pro_id)
					WHERE iso_del IS NULL
					AND pro_id NOT IN (
						SELECT pco_idfilho FROM tmss_produtoscompostos AS pco
						INNER JOIN tmss_produto AS pro ON (pco_idfilho = pro_id)
						WHERE pco_del IS NULL AND pro_idcliente = " . $_SESSION['usu_masterclient'] . "
					)
					" . $where . "
					ORDER BY " . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ItensSolicitacaoModel();
				$obj->iso_pro_nome = $row['pro_nome'];
				$obj->n = $row['n'];
				$a[] = $obj;
			}
			return $a;
		}

	}