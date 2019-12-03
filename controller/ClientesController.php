<?php
	class ClientesController {
	
		public static function insert($dados){
			$cliente = new ClientesModel();
			$cliente->cli_ambiente = "D";
			$cliente->cli_nome = utf8_decode($dados['nome']);
			$cliente->cli_razaosocial = utf8_decode($dados['razao_social']);
			$cliente->cli_cpfcnpj = $dados['cpf_cnpj'];
			$cliente->cli_ie = $dados['ie'];
			$cliente->cli_licencasanitaria = $dados['licenca_sanitaria'];
			$cliente->cli_cep = $dados['cep'];
			$cliente->cli_logradouro = utf8_decode($dados['logradouro']);
			$cliente->cli_numero = $dados['numero'];
			$cliente->cli_complemento = utf8_decode($dados['complemento']);
			$cliente->cli_bairro = utf8_decode($dados['bairro']);
			$cliente->cli_cidade = utf8_decode($dados['cidade']);
			$cliente->cli_estado = utf8_decode($dados['estado']);
			$cliente->cli_idregiao = $dados['regiao'];
			return $cliente->insert();
		}
		
		public static function update($dados){
			$cliente = new ClientesModel();
			$cliente->cli_id = $dados['id'];
			$cliente->cli_ambiente = $dados['ambiente'];
			$cliente->cli_nome = utf8_decode($dados['nome']);
			$cliente->cli_razaosocial = utf8_decode($dados['razao_social']);
			$cliente->cli_cpfcnpj = $dados['cpf_cnpj'];
			$cliente->cli_ie = $dados['ie'];
			$cliente->cli_licencasanitaria = $dados['licenca_sanitaria'];
			$cliente->cli_cep = $dados['cep'];
			$cliente->cli_logradouro = utf8_decode($dados['logradouro']);
			$cliente->cli_numero = $dados['numero'];
			$cliente->cli_complemento = utf8_decode($dados['complemento']);
			$cliente->cli_bairro = utf8_decode($dados['bairro']);
			$cliente->cli_cidade = utf8_decode($dados['cidade']);
			$cliente->cli_estado = utf8_decode($dados['estado']);
			$cliente->cli_idregiao = $dados['regiao'];
			return $cliente->update();
		}
		
		public static function delete($id){
			$cliente = new ClientesModel();
			return $cliente->delete($id);
		}
		
		public static function getCliente($id){
			$cliente = new ClientesModel();
			return $cliente->selectCliente($id);
		}
		
		public static function getClientes($where){
			$cliente = new ClientesModel();
			return $cliente->selectAll($where);
		}
		
		public static function getMasterClient($id){
			$cliente = new ClientesModel();
			return $cliente->selectMasterClient($id);
		}
		
		public static function relClientes($where, $order){
			$cliente = new ClientesModel();
			return $cliente->selectAllOrder($where, $order);
		}
		
	}
?>