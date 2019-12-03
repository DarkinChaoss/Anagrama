<?php
	class MenuModel extends Conexao{
		
		public $men_id;
		public $men_pai;
		public $men_ordem;
		public $men_nome;
		public $men_url;
		public $men_sub;
		public $men_divider;
		public $men_visualizacao;
		
		public function __construct(){
			$this->conecta();
		}
		
		public function selectAll($where){
			if(isset($where))
				$where = "AND " . $where;
			$sql = "SELECT * FROM tmsd_menu 
					WHERE men_del IS NULL " . $where . " 
					ORDER BY men_pai, men_ordem";
			$res = mysql_query($sql);
			$a = array();
			while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				$obj = new MenuModel();
				$obj->men_id = $row['men_id'];
				$obj->men_pai = $row['men_pai'];
				$obj->men_ordem = $row['men_ordem'];
				$obj->men_nome = $row['men_nome'];
				$obj->men_url = $row['men_url'];
				$obj->men_sub = $row['men_sub'];
				$obj->men_divider = $row['men_divider'];
				$obj->men_visualizacao = $row['men_visualizacao'];
				$a[] = $obj;
			}
			return $a;
		}
		
	}
?>