<?php
	class EquipamentoController {

		public static function insert($dados){
			$equipamento = new EquipamentoModel();
			$equipamento->eq_id = $dados['id'];
			$equipamento->eq_descricao = $dados['descricao'];
			$equipamento->eq_enzimatico = $dados['enzimatico'];
			$equipamento->eq_neutro = $dados['neutro'];
			$equipamento->eq_equitipo = $dados['equipamento'];
			$equipamento->eq_formatoimp = $dados['imprime'];
			$equipamento->eq_ultimo = '0';
			return $equipamento->insert();
		}

		public static function update($dados){
			$equipamento = new EquipamentoModel();
			$equipamento->eq_id = $dados['id'];
			$equipamento->eq_descricao = $dados['descricao'];
			$equipamento->eq_enzimatico = $dados['enzimatico'];
			$equipamento->eq_neutro = $dados['neutro'];
			$equipamento->eq_equitipo = $dados['equipamento'];
			$equipamento->eq_formatoimp = $dados['imprime'];
			$equipamento->eq_ultimo = $dados['ultimo'];
			return $equipamento->update();
		}

		public static function delete($id){
			$equipamento = new EquipamentoModel();
			return $equipamento->delete($id);
		}

		public static function getEqupipamento($id){
			$equipamento = new EquipamentoModel();
			return $equipamento->selectEquipamento($id);
		}

		public static function getEquipamento($where){
			$equipamento = new EquipamentoModel();
			return $equipamento->selectAll($where);
		}

		public static function getUltimoEquipamento(){
		    $equipamento = new EquipamentoModel();
		    return $equipamento->selectUltimoEquipamento();
		}
		
		//Nuevos metodos para enzimatico y neutro
		
			public static function getEqupipamento_standar($id){
			$equipamento = new EquipamentoModel();
			return $equipamento->selectEquipamento_standar($id);
		}
		
		
		

	}
?>