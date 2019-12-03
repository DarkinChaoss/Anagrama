$(document).ready(function(){
	
	$('.correct-pn').html('<i class="fas fa-check"></i>')
			
	$("input[name='optradio']").live("click", function(){
		var optradio = $("input[name='optradio']:checked").val()
		if(optradio == 'pn'){
			$('.correct-pn').html('<i class="fas fa-check"></i>')
			$('.correct-pc').html('')

		}else if(optradio == 'pc'){
			$('.correct-pc').html('<i class="fas fa-check"></i>')
			$('.correct-pn').html('')			
		}
	});


	
	// scroll comum
	$(document).on('click', '#cs-comuns', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#comuns')).offset().top
	    }, 500);
	});

	// scroll consignados
	$(document).on('click', '#cs-consignados', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#consignados')).offset().top
	    }, 500);
	});

	// scroll hide over 100
	$('#cs-linksInternos').hide();
	$(document).on('scroll', function () {
	    var scrollActual = $(window).scrollTop();

	    if(scrollActual > 100){
	    	$('#cs-linksInternos').fadeIn();
	    }else{
	    	$('#cs-linksInternos').fadeOut();
	    }
	});
});
	
function atualizaQtds(){
	
	// quantidade original
	$qtdMaterial = getQtdMaterial();

	if( $qtdMaterial > 0 ){

		// a quantidade pendente é a quantidade de itens que estao listados
		// no tbody da lista de materiais
		$qtdPendente = $('#tableConferencia > tbody').find('.qtdprods');
		
		$qtdPendenteConsignado = $('#tableConferencia > tbody').find('.qtdprodsConsignado');
		
		$qtdPendenteConsignado = $qtdPendenteConsignado.length;
		
		$qtdPendente = $qtdPendente.length;

		var qtdP = parseInt($qtdPendente) + $qtdPendenteConsignado;

		// quantidade ja conferida
		$qtdConferido = $qtdMaterial - $qtdPendente;

		$('#qtdConferida').html( $qtdConferido + " item(ns) conferido(s)" );
		$('#qtdNaoConferida').html( qtdP + " item(ns) n&atilde;o conferido(s)" );

		if( $qtdPendente == 0 ){

			$('#qtdNaoConferida').css('color', 'green');
			$('#qtdNaoConferida').html('Todos os materiais foram conferidos');
			$('#txqrcode').attr('disabled', 'disabled');

		}
		else{
			setFocusBusca();
		}
		
		return $qtdPendente;

	}
	else{
		$('#btnSalvar').hide();
	}

}

function setFocusBusca(){
	$('#txqrcode').focus();
}

function getQtdMaterial(){
	return parseInt( $('input[name="qtdMaterial"]').val() );
}

function removeMaterialLista( $id ){
	$('#tableConferencia > tbody').find('[pro_id="'+$id+'"]').remove();
	$('#txqrcode').val('');
	$('#qtde1').val('1');
}

function removeMaterialListaConsignado( $id ){
	$('#tableConferencia > tbody').find('[pro_id_consig="'+$id+'"]').remove();
	$('#txqrcode').val('');
	atualizaQtds();	
}

function removeMaterialListaComQuantidade(){
	if($('#qtde').val() > $('#qtdeDisponivel').val()){
		alert('A quantide excede a quantidade disponível do produto');
	}else{
		var qt = $('#qtde').val();
		$("#qtde2").val(qt)
		console.log(qt);
		var id = $('#idprod').val() + '_';
		var find = $('#tableConferencia > tbody').find('[pro_idqtde="'+id+'"]:lt('+qt+')');
		console.log(find)
		find.remove();

		$('#txqrcode').val('');
		
		$('#telaProdQtde').modal('hide');

		atualizaQtds();		
	}
}

function buscaMaterial(){
	$.post('conferindoMaterial_new', {buscar: $('#txqrcode').val(), productcheck:$("input[name='optradio']:checked").val() }, function(data) {

		if(data.erro){
			if($("input[name='optradio']:checked").val() == 'pn'){
				$('#err').text(data.erro + ' como produto comum');
			}else{
				$('#err').text(data.erro + ' como produto consignado');
			}
		}
		
		console.log(data)
			
		if(data.produto){
			
			if($("input[name='optradio']:checked").val() == 'pn'){
				var count = $('#tableConferencia > tbody').find('[pro_id="'+data.produto+'"]');
				
				var dtpro = data.produto + '_';
				var countq = $('#tableConferencia > tbody').find('[pro_idqtde="'+dtpro+'"]');
				
				var ct = countq.length

				
				if(count.length == 1){
					removeMaterialLista(data.produto);				
				}else{
					if(data.qtde){
						$('#telaProdQtde').modal('show');	
					}
					$('#qtdeDisponivel').val(ct);
					
					$('#idprod').val(data.produto);
					
					//removeMaterialListaComQuantidade(data.produto);
				}
				atualizaQtds();
			}else{
				var dtpro = data.produto + '_c';
				var countConsignado = $('#tableConferencia > tbody').find('[pro_id_consig="'+data.produto+'"]');
				
				var id = data.produto + '_c';
				removeMaterialListaConsignado(id)
				
			}
		}
	});
}

function enviarItensConferencia(){
	console.log($('#frmMateriais').serialize())
	$.post('conferindoMaterial_new', $('#frmMateriais').serialize() , function(data) {
		console.log(data)
		alert( data.msg );
	});

	location.replace('ConferenciaMaterial');
}

function salvarConferencia(){
	$qtdPendente = atualizaQtds();
	if( $qtdPendente > 0 ){

		$('#mdl-aviso-pendencia').modal('show');

		$html = '<ul>';
		$('#tableConferencia > tbody > tr > td[nome_produto="1"]').each(function(index, el) {
			$html += "<li>"+$( el ).html()+'</li>';
		});
		$html += '</ul>';
		$('#avisoQtdNaoConferida').html( $html ).addClass('avisoQtdNaoConferida');
	}
	else{
		enviarItensConferencia();
	}

}

$(document).ready(function() {

	atualizaQtds();

	$('#formBuscaProduto').submit(function(event) {
		event.preventDefault();
		buscaMaterial();
		$('#formBuscaProduto').trigger('reset');
	});

	var intervalo = 0;
	$("#txqrcode").keyup(function(e){
		// oculta mensagens
		$("#lbProdutoDescartado").hide();
		$("#lbProdutoNaoCadastrado").hide();

		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		if($("#txqrcode").val().length > 0){

			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				 buscaMaterial();
		      }, 100);
		}

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});

});