
if($("#mat_pendentes").val() != ''){
	$(".aviso").show()
}

if($("#mat_descarte").val() != ''){
	$(".tdmod").removeClass('tdnot')
	$(".tdmod").addClass('tddescart')
}

$('#voltarcaixa').live('click', function (){
	$('#telaPermissao').modal('hide');
});

$('#voltar').live('click', function (){
	$('#telaPermissao').modal('hide');
	$('#telaListaProdutos').modal('hide');
});

function atualizaQtds(){
	
	// quantidade original
	$qtdMaterial = getQtdMaterial();

	//$('#qtdTotalcomposto').html($qtdMaterial);


	if( $qtdMaterial > 0 ){

		// a quantidade pendente é a quantidade de itens que estao listados
		// no tbody da lista de materiais
		$qtdPendente = $('#tableConferencia > tbody').find('tr');
		

		$qtdPendente = $qtdPendente.length;

		
		// quantidade ja conferida
		$qtdConferido = $qtdMaterial - $qtdPendente;

		$('#qtdConferida').html( $qtdConferido + " item(ns) conferido(s)" );
		$('#qtdNaoConferida').html( $qtdPendente + " item(ns) n&atilde;o conferido(s)" );

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

	$("#txqrcode").val('');
	$('#txqrcode').focus();

}

function removeEsterilizacao( $idproduto ){
	
	$.post('conferindoComposto_new', {retirarEsterilizacao: $idproduto }, function(data, textStatus, xhr) {
		console.log( data );
	});
	
}


function lancar(){

	// removeEsterilizacao( $("#idProduto").val() );
	$.post("ocorrenciasprodutos", {idocorrencia:$("#slOcorrencia").val(), idproduto:$("#idProduto").val(), obs:$("#txObs").val(), obs:$("#txObs").val(), produtopai: $("#ProdutoPai").val(), produtopaiid: $("#ProdutoPaiId").val() }, function(data){		
		console.log(data)
		$("#tlaLancarOcorrencia").modal('hide');
		console.log($('#QrcodeProduto').val())
		removeMaterialLista( $('#QrcodeProduto').val() )
		removeEsterilizacao( $("#idProduto").val() );
		location.reload();
	});

}

function lancandoOcorrencia( $id , $qrcode ){
	$('#idProduto').val( $id );
	$('#QrcodeProduto').val( $qrcode );
	
	$('#divOcorrenciasProduto').hide();
	$("#tlaLancarOcorrencia").modal('show');

	$.post("ocorrenciasprodutos", {acao:'ocorrenciasProduto', produto:$('#QrcodeProduto').val() }, function(data){
		$("#produtoAlvo").text($qrcode);
		$("#produtoPai").text('Caixa: ' + $('#txProdutoPai').val());
		$("#ProdutoPai").val($('#txProdutoPai').val());
		$("#ProdutoPaiId").val($('#idProduto').val());
		$("#divOcorrenciasProduto").html(data);
		$("#slOcorrencia").val("0");
		$("#descricaoOcorrencia").text("");
		$("#txObs").val("");
		$("#lbObs").hide();
		$("#btConfirmarOcorrencia").hide();
		$("#tlaLancarOcorrencia").modal();
	});	
	
}

function getQtdMaterial(){
	return parseInt( $('input[name="qtdMaterial"]').val() );
}

function adicionaConferido( $el ){

	$('#frmMateriais').append( "<input type='hidden' name='materiais_conferidos[]' qrcode='"+ $($el).attr('pro_qrcode') +"' value='"+ $($el).attr('pro_id') +"'>" );

}

function verificarMaterial( $qrcode ){
	$.post('conferindoComposto_new', {qrcode_busca: $qrcode }, function(data) {
		$.trim( $("#txqrcodebusca").val( ) )
		console.log();
        if( data.msg != null ){
			alert( data.msg )
		}
	});	

}
	
//AQUI ELE RETIRA O PRODUTO DA LISTA
function removeMaterialLista( $qrcode, $idprod ){
	$qrcode = $qrcode.toUpperCase();
	$el = $('#tableConferencia > tbody').find( 'tr[pro_qrcode="'+$qrcode+'"]' );

	//verificarMaterial( $qrcode );   

	
	if( $el.length == 1 ){

		adicionaConferido( $el );
		$el.remove();
		atualizaQtds();								

	}
	else{
		$qrcode = $qrcode.toUpperCase();
        $el = $('#frmMateriais').find( 'input[qrcode="'+$qrcode+'"]' );
        if( $el.length == 1 ){
            alert('Material ja foi conferido.')
            setFocusBusca();
        }
        else{
            verificarMaterial( $qrcode );   
        }
        
	}
	
	$('#txqrcode').val('');
}

function buscaMaterial(){

	$.post('conferindoComposto_new', {buscar: $('#txqrcodebusca').val() }, function(data) {
		console.log(data)
		if( data.produto ){

			window.location.assign("conferindoComposto?id=" + data.produto.pro_id )

		}
		else{
			alert( data.erro )
			$('#txqrcodebusca').focus();
		}
	});

}

function enviarItensConferencia(){
	$.post('conferindoComposto_new', $('#frmMateriais').serialize() , function(data) {
console.log(data)		
		var msg = data.msg;
		var ms = msg.split(" ");

		if(ms[0] == 'Erro'){
			alert( data.msg );
		} else{
			alert( data.msg );
			/*var oJan = window.open("imprimeComposicao?id=" + $('#txProdutoPaiid').val() + '&qtdeFilhos=' + $('#qtdlist').text() + '&qdeConferido=' + $('#qtdConferida').text());
			
			setTimeout(function(){ 
				$('#mdl-aviso-pendencia').modal("hide");
			}, 300);*/
			//location.replace('conferindoComposto');
		}
	});
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
		if($('#mat_pendentes').val() != '' && $('#mat_pendentes').val() != undefined){
			$('#telaPermissao').modal()
		}else{

			enviarItensConferencia();
		}
		
	}

}

$(document).ready(function() {
		
		$.post("solicitacoes_new",{acao:'verautorizacao', id: $("#txProdutoPaiid").val()}, function(data){
			console.log(data)
			if($.trim(data) == 'nao'){
				$('#naoautorizado').text('Não autorizada na solicitação');
			}else if($.trim(data) == 'sim'){
				$('#autorizado').text('Autorizada na solicitação');
			}else{
				$('#autorizado').text('-');
			}
		});


	$('#naoautorizar').live('click', function (){
		if($('#responsavel').val() === ''){
			alert("Atenção! insira o nome do responsável");
		}else if($('#password').val() === ''){
			alert("Atenção! insira a senha.");
		}else{
			$.post("solicitacoes_new", {acao:"autorizacao", responsavel: $("#responsavel").val(), senha: $("#password").val(), nome_filho: $('#nome_filho').val(), composicao: $('#txProdutoPai').text(), qrcomposicao: $('#txQrcodePai').text(), pagina: 'C', idpai: $("#txProdutoPaiid").val(), modo: 'nao' }, function(data) {
				console.log(data)
				if($.trim(data) == "OK"){
					location.reload();
				}else{
					alert('Autorização invalida! Por favor, você precisa da autorização do responsável do turno.');
				}
			});	
		}	
	});	
	
	
	$('#autorizar').live('click', function (){
		if($('#responsavel').val() === ''){
			alert("Atenção! insira o nome do responsável");
		}else if($('#password').val() === ''){
			alert("Atenção! insira a senha.");
		}else{
			$.post("solicitacoes_new", {acao:"autorizacao", responsavel: $("#responsavel").val(), senha: $("#password").val(), nome_filho: $('#nome_filho').val(), composicao: $('#txProdutoPai').val(), qrcomposicao: $('#txQrcodePai').val(), pagina: 'C', idpai: $("#txProdutoPaiid").val(), modo: 'nao' }, function(data) {
				if($.trim(data) == "OK"){
					enviarItensConferencia();
					location.replace('conferindoComposto');
				}else{
					alert('Autorização invalida! Por favor, você precisa da autorização do responsável do turno.');
				}
			});	
		}	
	});


	$('#formBuscaProduto').submit(function(event) {
		event.preventDefault();
		$('#formBuscaProduto').trigger('reset');
	});	

	atualizaQtds();

	var intervalo2 = 0;
	$("#txqrcodebusca").keyup(function(e){
        //verificar quantidade caracteres qrcode
		if( $("#txqrcodebusca").val().length > 0){

			// intervalo2 até executar a função
			clearTimeout(intervalo2);
			intervalo2 = window.setTimeout(function() {
				 buscaMaterial( $.trim( $("#txqrcodebusca").val( ) )  );
		    }, 150);
		}

	});

	var intervalo = 0;
	$("#txqrcode").keyup(function(e){
      //verificar quantidade caracteres qrcode
		if( $("#txqrcode").val().length > 0){
			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				 removeMaterialLista( $.trim( $("#txqrcode").val( ) ) );
				 $("#txqrcode").val();
		    }, 150);
		}

	});

});