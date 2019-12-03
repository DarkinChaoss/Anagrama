$(document).ready(function() {
	var intervalo = 0;
	$("#txQrcode").keyup(function(e){
	
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);

		if($("#txQrcode").val().length > 3){

			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				 buscarProduto();
		      }, 100);
		}

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});
});


function abrirConferencia($material, $qrcode, $idproduto, $idsaida){

	$('#mdl-conferir').modal('show');
	$('#avisoProduto').html($material + ' - ' + $qrcode);
	
	$('#mdl-conferir').find('a').attr('href', 'produtosAConferir?idproduto='+$idproduto+'&idsaida='+$idsaida);

}