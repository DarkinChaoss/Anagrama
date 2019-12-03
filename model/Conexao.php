<?php
	class Conexao {
		

		protected $host;
		protected $user;
		protected $password;
		protected $db;
		
		// protected $host = "localhost";
		// protected $user = "root";
		// protected $password = "";
		// protected $db = "tmsnewversion";
		
		/*protected $host = "mysql.skm.com.br";
		protected $user = "skmcombr8";
		protected $password = "03skmc12";
		protected $db = "skmcombr8";
		*/
		// log
		private $log_id;
		private $log_masterclient;
		private $log_idusuario;
		private $log_ip;
		private $log_data;
		public $log_acao;


		
		public function conecta(){
			
			$this->host = getenv('DB_HOST');
			$this->user = getenv('DB_USERNAME');
			$this->password = getenv('DB_PASSWORD');
			$this->db = getenv('DB_NAME');
			$link = mysql_connect($this->host, $this->user, $this->password) or die(mysql_error());
			mysql_select_db($this->db) or die(mysql_error());
			ini_set('mysql.connect_timeout','0'); 
			ini_set('max_execution_time', '0'); 
			mysql_set_charset('',$link); 
		}
		
		public function gravaLog(){
			$this->log_idusuario = ((isset($_SESSION['usu_id'])) ? $_SESSION['usu_id'] : 0);
			$this->log_ip = $_SERVER['REMOTE_ADDR'];
			$this->log_data = date('Y-m-d H:i:s');
			$sql = "INSERT INTO tmsd_log (log_masterclient, log_idusuario, log_ip, log_data, log_acao) 
					VALUES (" . $_SESSION['usu_masterclient'] . ", " . $this->log_idusuario . ", '" . $this->log_ip . "', '" . $this->log_data . "', '" . $this->log_acao . "')";
			return mysql_query($sql);
		}
		
	}
?>