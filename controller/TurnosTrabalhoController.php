<?php
	class TurnosTrabalhoController {
	
		public static function insert($dados){
			$turnoTrabalho = new TurnosTrabalhoModel();
			$turnoTrabalho->tur_nome = utf8_decode($dados['nome']);
			$turnoTrabalho->tur_inicio = $dados['inicio'];
			$turnoTrabalho->tur_fim = $dados['fim'];
			return $turnoTrabalho->insert();
		}
		
		public static function update($dados){
			$turnoTrabalho = new TurnosTrabalhoModel();
			$turnoTrabalho->tur_id = $dados['id'];
			$turnoTrabalho->tur_nome = utf8_decode($dados['nome']);
			$turnoTrabalho->tur_inicio = $dados['inicio'];
			$turnoTrabalho->tur_fim = $dados['fim'];
			return $turnoTrabalho->update();
		}
		
		public static function delete($id){
			$turnoTrabalho = new TurnosTrabalhoModel();
			return $turnoTrabalho->delete($id);
		}
		
		public static function getTurnoTrabalho($id){
			$turnoTrabalho = new TurnosTrabalhoModel();
			return $turnoTrabalho->selectTurnoTrabalho($id);
		}
		
		public static function getTurnosTrabalhoBuscar($buscar){
			$turnoTrabalho = new TurnosTrabalhoModel();
			return $turnoTrabalho->search($buscar);
		}
		
		public static function getTurnosTrabalho($where){
			$turnoTrabalho = new TurnosTrabalhoModel();
			return $turnoTrabalho->selectAll($where);
		}
		
	}
?>