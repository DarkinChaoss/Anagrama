<?php
	class OcorrenciasController {
	
		public static function insert($dados){
			$ocorrencia = new OcorrenciasModel();
			$ocorrencia->oco_id = $dados['id'];
			$ocorrencia->oco_sigla = $dados['sigla'];
			$ocorrencia->oco_nome = utf8_decode($dados['nome']);
			$ocorrencia->oco_descricao = utf8_decode($dados['descricao']);
			$ocorrencia->oco_descarte = $dados['descarte'];
			$ocorrencia->oco_efeitoespecial = $dados['efeitoespecial'];
			return $ocorrencia->insert();
		}
		
		public static function update($dados){
			$ocorrencia = new OcorrenciasModel();
			$ocorrencia->oco_id = $dados['id'];
			$ocorrencia->oco_sigla = $dados['sigla'];
			$ocorrencia->oco_nome = utf8_decode($dados['nome']);
			$ocorrencia->oco_descricao = utf8_decode($dados['descricao']);
			$ocorrencia->oco_descarte = $dados['descarte'];
			$ocorrencia->oco_efeitoespecial = $dados['efeitoespecial'];
			return $ocorrencia->update();
		}
		
		public static function delete($id){
			$ocorrencia = new OcorrenciasModel();
			return $ocorrencia->delete($id);
		}
		
		public static function getOcorrencia($id){
			$ocorrencia = new OcorrenciasModel();
			return $ocorrencia->selectocorrencia($id);
		}
		
		public static function getOcorrenciaSterilab($id){
			$ocorrencia = new OcorrenciasModel();
			return $ocorrencia->selectocorrenciaSterilab($id);
		}
		
		public static function getOcorrencias($where){
			$ocorrencia = new OcorrenciasModel();
			return $ocorrencia->selectAll($where);
		}
		
		public static function getOcorrenciasIndMasterclient($where){
			$ocorrencia = new OcorrenciasModel();
			return $ocorrencia->selectAllIndMasterclient($where);
		}
				
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2015
*/
?>