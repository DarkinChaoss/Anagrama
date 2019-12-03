<?php
	class LimitesUsoController {

		public static function insert($dados){
			$limiteUso = new LimitesUsoModel();
			$limiteUso->liu_id = $dados['id'];
			//$limiteUso->liu_descricao = $dados['descricao'] . " " . $dados['medida'];
			$limiteUso->liu_qtde = $dados['quantidade'];
			$limiteUso->liu_description = $dados['descricao'];
			$limiteUso->liu_periodo = $dados['periodo'];
			$limiteUso->liu_ultimo = '0';
			return $limiteUso->insert();
		}

		public static function update($dados){
			$limiteUso = new LimitesUsoModel();
			$limiteUso->liu_id = $dados['id'];
			//$limiteUso->liu_descricao = $dados['descricao'] . " " . $dados['medida'];
			$limiteUso->liu_qtde = $dados['quantidade'];
			$limiteUso->liu_description = $dados['descricao'];
			$limiteUso->liu_periodo = $dados['periodo'];
			$limiteUso->liu_ultimo = $dados['ultimo'];
			return $limiteUso->update();
		}

		public static function delete($id){
			$limiteUso = new LimitesUsoModel();
			return $limiteUso->delete($id);
		}

		public static function getLimiteUso($id){
			$limiteUso = new LimitesUsoModel();
			return $limiteUso->selectLimiteUso($id);
		}

		public static function getLimitesUso($where){
			$limiteUso = new LimitesUsoModel();
			return $limiteUso->selectAll($where);
		}

		public static function getUltimoLimitesUso(){
		    $limiteUso = new LimitesUsoModel();
		    return $limiteUso->selectUltimoLimiteUso();
		}

	}
?>