<?php
	foreach (SaidaMateriaisController::getSaidasMateriais("sma_sala IS NOT NULL AND sma_sala != ''") as $sma) {
		error_log($sma->sma_id);
		ItensSaidaController::updateSala($sma->sma_id, $sma->sma_sala);
	}
?>