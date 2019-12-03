<?php
	class EtiquetagemHelper{

		public static function populaComboLimiteUso($id = 0){
			$select = "	<select name='limiteUso' id='slLimiteUso' class='input-large'>
							<option value='0'>** Escolha **</option>";

			foreach (LimitesUsoController::getLimitesUso() as $liu){
				$ultimo = (($liu->liu_ultimo == '1') ? "selected" : "");
				$select .= "<option value='".$liu->liu_id."'".($liu->liu_id == $id ? "selected='selected'" : "")." " . $ultimo . ">".$liu->liu_descricao."</option>";
			}

			$select .= "</select>
						<script>
							calculaLimite();
						</script>";
			return $select;
		}

	}
?>