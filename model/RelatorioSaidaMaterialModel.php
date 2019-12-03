<?php

	class RelatorioSaidaMaterialModel extends Conexao{

		private $set_nome;
		private $data;
		private $qtd_movimentada;
		private $sma_idsetor;
		
		/* auxiliar para montar o json do grafico*/
		private $json;

		public function __construct( $json = false ){

			$this->setJson( $json );
			$this->conecta();

		}

		public function get( $data_inicio , $data_fim ){

			$sql = $this->getSql($data_inicio , $data_fim );
			$result = mysql_query( $sql );

			$lista = null;
			while( $row = mysql_fetch_assoc( $result ) ){

				$item = new RelatorioSaidaMaterialModel();
				$item->setSet_nome( $row['set_nome'] );
				$item->setData( $row['data'] );
				$item->setQtd_movimentada( $row['qtd_movimentada'] );
				$item->setSma_idsetor( $row['sma_idsetor'] );

				$lista[] = $item;

			}

			if( !empty( $lista ) ){
				if( $this->getJson() ){
					return $this->preparaDados( $lista , $data_inicio , $data_fim );
				}else{
					return $lista;
				}
			}
			else{
				return false;
			}

		}

		public function preparaCategorias( $inicio , $fim ){

			$ret = mysql_query( $this->getDias( $inicio , $fim ) );
			$dias = null;
			while( $d = mysql_fetch_assoc( $ret ) ){
				$dias[] = $d['dia'];
			}
			return $dias;

		}

		public function preparaSetores( $inicio , $fim ){

			$ret = mysql_query( $this->getSetores( $inicio , $fim ) );
			$setor = null;
			while( $d = mysql_fetch_assoc( $ret ) ){
				$setor[] = $d['setor'];
			}
			return $setor;

		}

		public function preparaSeriePadrao( $qtdDias ){

			$dias = null;
			for ($i=0; $i < $qtdDias ; $i++) { 
				$dias[] = null;
			}
			return $dias;

		}

		public function preparaSerie( $setores , $array_padrao ){

			$ret = null;
			foreach ($setores  as $setor) {
				$ret[] = array( 'name' 	=> $setor,
								'data'	=> $array_padrao );
			}
			return $ret; 

		}

		public function preparaDados( $lista , $inicio , $fim){

			// aqui serao carregadas as datas -> refere-se a coluna do grafico
			$retorno['categorias'] = $this->preparaCategorias( $inicio , $fim );
			$retorno['setores'] = $this->preparaSetores(  $inicio , $fim );

			$array_series_padrao = $this->preparaSeriePadrao( count( $retorno['categorias'] ) );
			$retorno['series'] = $this->preparaSerie( $retorno['setores'] , $array_series_padrao );

			foreach ($retorno['series']  as $serie) {

				foreach ( $lista as $item ) {

					// pega a posicao de onde sera colocada a qtdmovimentada
					$p = array_search( $item->getData() , $retorno['categorias'] );

					// pega a posicao de onde sera colocada a qtdmovimentada do setor
					$s = array_search( $item->getSet_nome() , $retorno['setores'] );
					
					$retorno['series'][ $s ]['data'][ $p ] = (integer) $item->getQtd_movimentada();

				}					

			}

			return $retorno;

		}

		public function getSetores($data_inicio , $data_fim ){

			return "SELECT 	DISTINCT( set_nome ) as setor
					FROM tmsd_itenssaida 
					JOIN tmsd_saidamateriais ON sma_id = isa_idsaida AND (sma_del IS NULL OR sma_del != '*')  
					JOIN tmsd_setores ON set_id = sma_idsetor 
					JOIN tmss_produto ON pro_id = isa_idproduto AND pro_del IS NULL AND pro_idcliente = {$_SESSION['usu_masterclient']}
					WHERE (isa_del IS NULL OR isa_del != '*') AND  
					( DATE(isa_data) >=' {$data_inicio}' AND DATE(isa_data) <= '{$data_fim}') 
					group by cast(isa_data as date), sma_idsetor
					ORDER BY isa_data, set_nome ";

		}

		public function getDias($data_inicio , $data_fim ){

			return "SELECT 	DISTINCT( DATE_FORMAT(cast(isa_data as date),'%d-%m-%Y') ) as dia
					FROM tmsd_itenssaida 
					JOIN tmsd_saidamateriais ON sma_id = isa_idsaida AND (sma_del IS NULL OR sma_del != '*')  
					JOIN tmsd_setores ON set_id = sma_idsetor 
					JOIN tmss_produto ON pro_id = isa_idproduto AND pro_del IS NULL AND pro_idcliente = {$_SESSION['usu_masterclient']}
					WHERE (isa_del IS NULL OR isa_del != '*') AND  
					( DATE(isa_data) >=' {$data_inicio}' AND DATE(isa_data) <= '{$data_fim}') 
					group by cast(isa_data as date), sma_idsetor
					ORDER BY isa_data, set_nome ";

		}

		public function getSql($data_inicio , $data_fim ){

			return "SELECT 	set_nome,
							DATE_FORMAT(cast(isa_data as date),'%d-%m-%Y') as data,
							count(pro_id) as qtd_movimentada,
							sma_idsetor
					FROM tmsd_itenssaida 
					JOIN tmsd_saidamateriais ON sma_id = isa_idsaida AND (sma_del IS NULL OR sma_del != '*')  
					JOIN tmsd_setores ON set_id = sma_idsetor 
					JOIN tmss_produto ON pro_id = isa_idproduto AND pro_del IS NULL AND pro_idcliente = {$_SESSION['usu_masterclient']}
					WHERE (isa_del IS NULL OR isa_del != '*') AND  
					( DATE(isa_data) >=' {$data_inicio}' AND DATE(isa_data) <= '{$data_fim}') 
					group by cast(isa_data as date), sma_idsetor
					ORDER BY isa_data, set_nome ";

		}

		/**
		 * @return type
		 */
		public function getSma_idsetor()
		{
			return $this->sma_idsetor;
		}

		/**
		 * @return type
		 */
		public function getQtd_movimentada()
		{
		    return $this->qtd_movimentada;
		}
		/**
		 * @return type
		 */
		public function getData()
		{
		    return $this->data;
		}

		/**
		 * @return type
		 */
		public function getSet_nome()
		{
		    return $this->set_nome;
		}

		/**
		 * @param type $qtd_movimentada
		 */
		public function setQtd_movimentada($qtd_movimentada)
		{
		    $this->qtd_movimentada = $qtd_movimentada;
		    return $this;
		}

		/**
		 * @param type $data
		 */
		public function setData($data)
		{
		    $this->data = $data;
		    return $this;
		}

		/**
		 * @param type $set_nome
		 */
		public function setSet_nome($set_nome)
		{
		    $this->set_nome = $set_nome;
		    return $this;
		}

		/**
		 * @param type $sma_idsetor
		 */
		public function setSma_idsetor($sma_idsetor)
		{
		    $this->sma_idsetor = $sma_idsetor;
		    return $this;
		}

		/**
		 * @param type $json
		 */
		public function setJson($json){
		    $this->json = $json;
		    return $this;
		}
		/**
		 * @return type
		 */
		public function getJson(){
		    return $this->json;
		}

	}