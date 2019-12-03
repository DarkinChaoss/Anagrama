<?php
	class SaidaMateriaisController {
	
		public static function insert($dados){
			
            $saida = new SaidaMateriaisModel();
			$saida->sma_id = $dados['id'];
			$saida->sma_prontuario = utf8_decode($dados['prontuario']);
			$saida->sma_paciente = utf8_decode($dados['paciente']);
			//$saida->sma_sala = utf8_decode($dados['sala']);
			$saida->sma_idsetor = $dados['setor'];
			$saida->sma_idconvenio = $dados['convenio'];
			$saida->sma_data = $dados['data'];
			$saida->sma_tiposaida = 'S';
                        
			return $saida->insert();
            
		}
		
		public static function update($dados){
			$saida = new SaidaMateriaisModel();
			$saida->sma_id = $dados['id'];
			$saida->sma_prontuario = utf8_decode($dados['prontuario']);
			$saida->sma_paciente = utf8_decode($dados['paciente']);
			//$saida->sma_sala = utf8_decode($dados['sala']);
			$saida->sma_idsetor = $dados['setor'];
			$saida->sma_idconvenio = $dados['convenio'];
			$saida->sma_ultimolancamento = $dados['ultimoLancamento'];
			return $saida->update();
		}
		
		public static function getSaidaMateriais($id){
			$saida = new SaidaMateriaisModel();
			return $saida->selectSaidaMateriais($id);
		}
		
		public static function getSaidasMateriais($where){
			$saida = new SaidaMateriaisModel();
			return $saida->selectAll($where);
		}
		
        public static function getSaidaMateriaisByProntuario($prontuario){
		
        	$saida = new SaidaMateriaisModel();
			$ret = $saida->selectAll("sma_prontuario = '" . $prontuario . "'");
			return $ret[0];
		
        }
		
		public static function getMaiorSaidaProd($idPro){
			$saida = new SaidaMateriaisModel();
			return $saida->selectMaiorRegistro($idPro);
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