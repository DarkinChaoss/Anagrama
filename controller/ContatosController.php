<?php
	class ContatosController {
	
		public static function insert($dados){
			$contato = new ContatosModel();
			$contato->con_id = $dados['id'];
			$contato->con_idsetor = $dados['idsetor'];
			$contato->con_nome = utf8_decode($dados['nome']);
			$contato->con_email = utf8_decode($dados['email']);
			$contato->con_telefone = utf8_decode($dados['telefone']);
			// verifica se é o primeiro contato a ser adicionado... se for, marca como principal
			$arr = ContatosController::getContatos("con_idsetor = " . $contato->con_idsetor);
			if(empty($arr)){
				$contato->con_principal = '1';
			}
			//
			return $contato->insert();
		}
		
		public static function update($dados){
			$contato = new ContatosModel();
			$contato->con_id = $dados['id'];
			$contato->con_idsetor = $dados['idsetor'];
			$contato->con_nome = utf8_decode($dados['nome']);
			$contato->con_email = utf8_decode($dados['email']);
			$contato->con_telefone = utf8_decode($dados['telefone']);
			$contato->con_principal = $dados['principal'];
			return $contato->update();
		}
		
		public static function delete($id){
			$contato = new ContatosModel();
			return $contato->delete($id);
		}
		
		public static function getContato($id){
			$contato = new ContatosModel();
			return $contato->selectContato($id);
		}
		
		public static function getContatos($where){
			$contato = new ContatosModel();
			return $contato->selectAll($where);
		}
		
		public static function marcarContato($id){
			$contato = new ContatosModel();
			$contato = ContatosController::getContato($id);
			$contato->con_principal = '1';
			$contato->update();
			// desmarca contato marcado antes desse
			$arr = ContatosController::getContatos("con_idsetor = " . $contato->con_idsetor . " AND con_principal = '1' AND con_id <> " . $contato->con_id);
			if(!empty($arr)){
				$arr[0]->con_principal = "";
				$arr[0]->update();
			}
		}
		
	}
?>