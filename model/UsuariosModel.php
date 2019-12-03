<?php
	class UsuariosModel extends Conexao{
		
		public $usu_id;
		public $usu_masterclient;
		public $usu_leituraqr;
		public $usu_login;
		public $usu_senha;
		public $usu_nivel;
		public $usu_referencia;
		public $usu_del;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function insert(){
			$sql = "INSERT INTO tmsd_usuarios (
						usu_masterclient,
						usu_leituraqr,
						usu_login,
						usu_senha,
						usu_nivel,
						usu_referencia
					) VALUES (
						'" . $_SESSION['usu_masterclient'] . "',
						'" . $this->usu_leituraqr . "',
						'" . DefaultHelper::acentos($this->usu_login) . "',
						'" . DefaultHelper::acentos($this->usu_senha) . "',
						'" . $this->usu_nivel . "',
						'" . $this->usu_referencia . "'
					)";
			$res = mysql_query($sql);
			if($res) {
				// log
				$id = mysql_insert_id();
				$this->log_acao = "Inserчуo: registro " . $id . " em tmsd_usuarios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function update(){
			$sql = "UPDATE tmsd_usuarios SET
						usu_leituraqr = '" . $this->usu_leituraqr . "',
						usu_login = '" . DefaultHelper::acentos($this->usu_login) . "',
						usu_senha = '" . DefaultHelper::acentos($this->usu_senha) . "',
						usu_nivel = '" . $this->usu_nivel . "',
						usu_referencia = '" . $this->usu_referencia . "'
					WHERE usu_id = " . $this->usu_id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Atualizaчуo: registro " . $this->usu_id . " em tmsd_usuarios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function delete($id){
			$sql = "UPDATE tmsd_usuarios SET
						usu_del = '*'
					WHERE usu_id = " . $id;
			$res = mysql_query($sql);
			if($res) {
				// log
				$this->log_acao = "Exclusуo: registro " . $id . " em tmsd_usuarios.";
				$this->gravaLog();
				//
			}
			return $res;
		}
		
		public function selectUsuario($id){
			$sql = "SELECT * FROM tmsd_usuarios WHERE usu_id = " . $id . " AND usu_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new UsuariosModel();
			$obj->usu_id = $row['usu_id'];
			$obj->usu_masterclient = $row['usu_masterclient'];
			$obj->usu_leituraqr = $row['usu_leituraqr'];
			$obj->usu_login = $row['usu_login'];
			$obj->usu_senha = $row['usu_senha'];
			$obj->usu_nivel = $row['usu_nivel'];
			$obj->usu_referencia = $row['usu_referencia'];
			return $obj;
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_usuarios WHERE usu_del IS NULL AND usu_masterclient = " . $_SESSION['usu_masterclient'] . " " . $where . " ORDER BY usu_login";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new UsuariosModel();
				$obj->usu_id = $row['usu_id'];
				$obj->usu_masterclient = $row['usu_masterclient'];
				$obj->usu_leituraqr = $row['usu_leituraqr'];
				$obj->usu_login = $row['usu_login'];
				$obj->usu_senha = $row['usu_senha'];
				$obj->usu_nivel = $row['usu_nivel'];
				$obj->usu_referencia = $row['usu_referencia'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function selectAllGlobal($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_usuarios WHERE usu_del IS NULL " . $where . " ORDER BY usu_login";
			//error_log($sql);
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new UsuariosModel();
				$obj->usu_id = $row['usu_id'];
				$obj->usu_masterclient = $row['usu_masterclient'];
				$obj->usu_leituraqr = $row['usu_leituraqr'];
				$obj->usu_login = $row['usu_login'];
				$obj->usu_senha = $row['usu_senha'];
				$obj->usu_nivel = $row['usu_nivel'];
				$obj->usu_referencia = $row['usu_referencia'];
				$a[] = $obj;
			}
			return $a;
		}
		
		public function validaUsuario($login, $senha){
			$sql = "SELECT * FROM tmsd_usuarios WHERE usu_login = '" . $login . "' AND usu_senha = '" . $senha . "' AND usu_del IS NULL";
			$res = mysql_query($sql);
			$row = mysql_fetch_array($res, MYSQL_ASSOC);
			$obj = new UsuariosModel();
			$obj->usu_id = $row['usu_id'];
			$obj->usu_masterclient = $row['usu_masterclient'];
			$obj->usu_leituraqr = $row['usu_leituraqr'];
			$obj->usu_login = $row['usu_login'];
			$obj->usu_senha = $row['usu_senha'];
			$obj->usu_nivel = $row['usu_nivel'];
			$obj->usu_referencia = $row['usu_referencia'];
			if(!empty($obj->usu_id)){
				// log
				$this->log_acao = "Login: usuсrio " . $obj->usu_id . " = " . $login . ".";
				$this->gravaLog();
				//
			} else {
				// log
				$this->log_acao = "Tentativa de login: " . $login . " com senha " . $senha . ".";
				$this->gravaLog();
				//
			}
			return $obj;
		}
		
		public function logout(){
			// log
			$this->log_acao = "Logout: usuсrio " . $_SESSION['usu_id'] . " = " . $_SESSION['usu_login'] . ".";
			$this->gravaLog();
			//
			return true;
		}
		
	}
?>