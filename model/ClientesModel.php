<?php
	/*
	 * Classe utilizada para acessar e manipular dados na tabela de clientes em TMS Sterilab
	 */
	class ClientesModel extends Conexao{
		
		public $cli_id;
		public $cli_ambiente;
		public $cli_nome;
		public $cli_razaosocial;
		public $cli_cpfcnpj;
		public $cli_ie;
		public $cli_licencasanitaria;
		public $cli_cep;
		public $cli_logradouro;
		public $cli_numero;
		public $cli_complemento;
		public $cli_bairro;
		public $cli_cidade;
		public $cli_estado;
		public $cli_idregiao;
		public $cli_logo; // inserido apenas via banco de dados
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmss_clientes (
						cli_ambiente,
						cli_nome,
						cli_razaosocial,
						cli_cpfcnpj,
						cli_ie,
						cli_licencasanitaria,
						cli_cep,
						cli_logradouro,
						cli_numero,
						cli_complemento,
						cli_bairro,
						cli_cidade,
						cli_estado,
						cli_idregiao
					) VALUES (
						'" . $this->cli_ambiente . "',
						'" . DefaultHelper::acentos($this->cli_nome) . "',
						'" . DefaultHelper::acentos($this->cli_razaosocial) . "',
						'" . $this->cli_cpfcnpj . "',
						'" . $this->cli_ie . "',
						'" . $this->cli_licencasanitaria . "',
						'" . $this->cli_cep . "',
						'" . DefaultHelper::acentos($this->cli_logradouro) . "',
						'" . $this->cli_numero . "',
						'" . DefaultHelper::acentos($this->cli_complemento) . "',
						'" . DefaultHelper::acentos($this->cli_bairro) . "',
						'" . DefaultHelper::acentos($this->cli_cidade) . "',
						'" . $this->cli_estado . "',
						'" . $this->cli_idregiao . "'
					)";
			$res = mysql_query($sql);
			$id = mysql_insert_id();
			$con = mysql_query("SELECT LAST_INSERT_ID()") or die ("PROBLEMAS COM A CONSULTA: " . mysql_error());
			$res = mysql_fetch_row($con);
			if($res) {
				// log
				$this->log_acao = "Inserчуo: registro " . $id . " em tmss_clientes.";
				$this->gravaLog();
				//
			}
			return $res[0];
		}
		
		public function update(){
			$sql = "UPDATE tmss_clientes SET
						cli_ambiente = '" . $this->cli_ambiente . "',
						cli_nome = '" . DefaultHelper::acentos($this->cli_nome) . "',
						cli_razaosocial = '" . DefaultHelper::acentos($this->cli_razaosocial) . "',
						cli_cpfcnpj = '" . $this->cli_cpfcnpj . "',
						cli_ie = '" . $this->cli_ie . "',
						cli_licencasanitaria = '" . $this->cli_licencasanitaria . "',
						cli_cep = '" . $this->cli_cep . "',
						cli_logradouro = '" . DefaultHelper::acentos($this->cli_logradouro) . "',
						cli_numero = '" . $this->cli_numero . "',
						cli_complemento = '" . DefaultHelper::acentos($this->cli_complemento) . "',
						cli_bairro = '" . DefaultHelper::acentos($this->cli_bairro) . "',
						cli_cidade = '" . DefaultHelper::acentos($this->cli_cidade) . "',
						cli_estado = '" . $this->cli_estado . "',
						cli_idregiao = '" . $this->cli_idregiao . "'
					WHERE cli_id = " . $this->cli_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->cli_id . " em tmss_clientes.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmss_clientes SET
						cli_del = '*'
					WHERE cli_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmss_clientes.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectCliente($id){
			$sql = "SELECT * FROM tmss_clientes WHERE cli_id = " . $id . " AND cli_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new ClientesModel();
			$obj->cli_id = $row['cli_id'];
			$obj->cli_ambiente = $row['cli_ambiente'];
			$obj->cli_nome = $row['cli_nome'];
			$obj->cli_razaosocial = $row['cli_razaosocial'];
			$obj->cli_cpfcnpj = $row['cli_cpfcnpj'];
			$obj->cli_ie = $row['cli_ie'];
			$obj->cli_licencasanitaria = $row['cli_licencasanitaria'];
			$obj->cli_cep = $row['cli_cep'];
			$obj->cli_logradouro = $row['cli_logradouro'];
			$obj->cli_numero = $row['cli_numero'];
			$obj->cli_complemento = $row['cli_complemento'];
			$obj->cli_bairro = $row['cli_bairro'];
			$obj->cli_cidade = $row['cli_cidade'];
			$obj->cli_estado = $row['cli_estado'];
			$obj->cli_idregiao = $row['cli_idregiao'];
			$obj->cli_logo = $row['cli_logo'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_clientes 
					WHERE cli_del IS NULL AND (cli_ambiente = 'DS' OR cli_ambiente = 'D') " . $where . " 
					ORDER BY cli_nome";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ClientesModel();
				$obj->cli_id = $row['cli_id'];
				$obj->cli_ambiente = $row['cli_ambiente'];
				$obj->cli_nome = $row['cli_nome'];
				$obj->cli_razaosocial = $row['cli_razaosocial'];
				$obj->cli_cpfcnpj = $row['cli_cpfcnpj'];
				$obj->cli_ie = $row['cli_ie'];
				$obj->cli_licencasanitaria = $row['cli_licencasanitaria'];
				$obj->cli_cep = $row['cli_cep'];
				$obj->cli_logradouro = $row['cli_logradouro'];
				$obj->cli_numero = $row['cli_numero'];
				$obj->cli_complemento = $row['cli_complemento'];
				$obj->cli_bairro = $row['cli_bairro'];
				$obj->cli_cidade = $row['cli_cidade'];
				$obj->cli_estado = $row['cli_estado'];
				$obj->cli_idregiao = $row['cli_idregiao'];
				$obj->cli_logo = $row['cli_logo'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectMasterClient($id){
			//error_log("SELECT CLIENTE");
			$sql = "SELECT * FROM tmss_clientes 
					WHERE cli_id = " . $id . " AND cli_del IS NULL";
			//error_log($sql);
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			//error_log("CARREGA OBJETO CLIENTE");
			$obj = new ClientesModel();
			$obj->cli_id = $row['cli_id'];
			$obj->cli_ambiente = $row['cli_ambiente'];
			$obj->cli_nome = $row['cli_nome'];
			$obj->cli_razaosocial = $row['cli_razaosocial'];
			$obj->cli_cpfcnpj = $row['cli_cpfcnpj'];
			$obj->cli_ie = $row['cli_ie'];
			$obj->cli_licencasanitaria = $row['cli_licencasanitaria'];
			$obj->cli_cep = $row['cli_cep'];
			$obj->cli_logradouro = $row['cli_logradouro'];
			$obj->cli_numero = $row['cli_numero'];
			$obj->cli_complemento = $row['cli_complemento'];
			$obj->cli_bairro = $row['cli_bairro'];
			$obj->cli_cidade = $row['cli_cidade'];
			$obj->cli_estado = $row['cli_estado'];
			$obj->cli_idregiao = $row['cli_idregiao'];
			$obj->cli_logo = $row['cli_logo'];
			return $obj;
		}
		
		public function selectAllOrder($where, $order){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmss_clientes AS c 
					INNER JOIN tmss_regioes AS r ON (cli_idregiao = reg_id) 
					WHERE cli_del IS NULL AND (cli_ambiente = 'DS' OR cli_ambiente = 'D') " . $where . " 
					ORDER BY " . $order;
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new ClientesModel();
				$obj->cli_id = $row['cli_id'];
				$obj->cli_ambiente = $row['cli_ambiente'];
				$obj->cli_nome = $row['cli_nome'];
				$obj->cli_razaosocial = $row['cli_razaosocial'];
				$obj->cli_cpfcnpj = $row['cli_cpfcnpj'];
				$obj->cli_ie = $row['cli_ie'];
				$obj->cli_licencasanitaria = $row['cli_licencasanitaria'];
				$obj->cli_cep = $row['cli_cep'];
				$obj->cli_logradouro = $row['cli_logradouro'];
				$obj->cli_numero = $row['cli_numero'];
				$obj->cli_complemento = $row['cli_complemento'];
				$obj->cli_bairro = $row['cli_bairro'];
				$obj->cli_cidade = $row['cli_cidade'];
				$obj->cli_estado = $row['cli_estado'];
				$obj->cli_idregiao = $row['cli_idregiao'];
				$obj->cli_logo = $row['cli_logo'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>