$(document).ready(function(){

	//INICIO ISSUE_PRODUTO_COMPOSTO JÁ NO CADASTRO DO PRODUTO FILHO
	$('#inputComposto').hide();	
	$('.labelfatherProduct').hide();
	$('.boxinfofather').hide();
	
	$('.checkprod').click(function(ev){
		$('.checkprod').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		//var check = $('#checkprod').val(ev.target.getAttribute('name'))
	
		var namefield = ev.target.getAttribute('name');
		
		if (namefield === 'itemcomposicao') {
			$('#boxQtde').hide();
			$('.labelfatherProduct').show();
			$('#inputComposto').show();
			
			if ($('#namecomposto').text() != '') {
				$('.boxinfofather').show();
			}
		
		}else if (namefield === 'ckqtde') {
			$('#boxQtde').show();
			$('#inputComposto').hide();
			$('.labelfatherProduct').hide();
			$('.boxinfofather').hide();
		}else{
			$('#boxQtde').hide();
			$('#inputComposto').hide();
			$('.labelfatherProduct').hide();
			$('.boxinfofather').hide();
		}
	})
	
	
	$('#inputComposto').keyup(function(){
		var qrcode = $('#inputComposto').val();
		
		$.post("produtos_new", {acao:"getInfoProduto", qrcode:qrcode, new_action: 's'}, function(data){
			var data = $.trim(data);
			if (data === 'N') {
				alert('Esta composição não existe. Verifique o qrcode digitado.');
			}else{
				var aux = data.split(',');
				$('#idcomposto').val(aux[3]);
				$('#namecomposto').text(aux[0]);
				$('#qrcomposto').text(aux[1]);
				$('#childrencomposto').text(aux[2]);
				$('.boxinfofather').show();
			}
		});
	});
	//FIM ISSUE_PRODUTO_COMPOSTO JÁ NO CADASTRO DO PRODUTO FILHO
		
	

	$('#frm-substituir').submit(function(event) {
		event.preventDefault();
		inserir(this);
	});

	$('.btnSubstituir').live('click', function (){
		// SUBSTITUIR QRCODE - PRODUTO COMPOSTO
		if( $('#formComposicao').is(':visible') ){

			var id = $(this).attr('id');

			$.post("produtos_new", {acao:"getPro", id:this.id}, function(data){
				data = JSON.parse(data);

				if(data.pro_id > 0){
					$('input[name="idproduto"]').val( data.pro_id );
					$('input[name="qrcode_atual"]').val( data.pro_qrcode );
				}else{
					alert('Não foi possível Localizar o produto !');
					return false;
				}
			});
		}

	});

	$(".pagination li").live("click", function(){
		if($(this).attr('class') != "disabled" && $(this).attr('class') != "active"){
			if($(this).attr('pag') == "prev"){
				var pag = parseInt($("#pagAtiva").val()) - 1;
			} else if($(this).attr('pag') == "next"){
				var pag = parseInt($("#pagAtiva").val()) + 1;
			} else {
				var pag = $(this).attr('pag');
			}
			if($("#ckDescartados").is(":checked"))
				var descart = $("#ckDescartados").val();
			else
				var descart = "";
			$.post("produtos_new", { buscar:$("#txBuscar").val(), pag:pag, descart:descart }, function(data){
				$("#paginacao").html(data);
				$.post("produtos_new", { acao:"lista", buscar:$("#txBuscar").val(), pag:pag, descart:descart }, function(dataLista){
					$("#lista").html(dataLista);
				});
			});
		}
	});

	$("#ckDescartados").click(function(){
		$("#formBusca").submit();
	});

	//INICIO ISSUE_PRODUTO_COMPOSTO JÁ NO CADASTRO DO PRODUTO FILHO

	$("#btSalvarContinuar").click(function(){
		// primeiro teste: valida��o do nome do produto
		$.post("nomesProdutos_new", {acao:"repetido", nome:$("#txNome").val()}, function(data){
			if($.trim(data) == "ERRO"){
				alert("Impossível prosseguir!\n\nEscolha um nome válido para o produto.");
				$("#txNome").val("");
				$("#txNome").focus();
			} else {
				// demais testes
				if($("#txQrcode").val() == "") {
					alert("Informe o QRCode do produto!");
					$("#txQrcode").focus();
				} else if($("#txNome").val() == "") {
					alert("Informe o nome do produto!");
					$("#txNome").focus();
				} /*else if($("#txSetor").val() == "0") {
					alert("Escolha o setor ao qual pertence o produto!");
					$("#txSetor").focus();
				}*/ else if($("#txQtdmaxima").val() == "") {
					alert("Informe a quantidade máxima de reprocessamento do produto!");
					$("#txQtdmaxima").focus();
				} else if($("#txGrupomaterial").val() == "0") {
					alert("Escolha o grupo ao qual pertence o produto!");
					$("#txGrupomaterial").focus();
				} else {
					var valores = $("#formProdutos").serialize();
					//console.log('valoressss:' + valores);
					$.post("produtos_new", valores, function(data){
						console.log(data)
						data = $.trim(data)
						if( data == "OK"){
							var qrcode = $('#inputComposto').val();
							
							$.post("produtos_new", {acao:"getInfoProduto", qrcode:qrcode, new_action: 's'}, function(data){
								var data = $.trim(data);
								if (data === 'N') {
									alert('Esta composição não existe. Verifique o qrcode digitado.');
								}else{
									
									$('#txQrcode').val('');
									$('#txNome').val('');
									$('#txCalibre').val('');
									$('#txCurvatura').val('');
									$('#txDiametrointerno').val('');
									$('#txComprimento').val('');
									$('#txFabricante').val('');
									$('#txMarca').val('');
									$('#txAnvisa').val('');
									$('#txNumSerie').val('');
									$('#txLotefabricacao').val('');
									$('#txValidacaofabricacao').val('');
									$('#txAlertaMsg').val('');
									$('#textareapro').val('');

									$.post("produtos_new", {acao:"getInfoProduto", qrcode:qrcode, new_action: 's'}, function(data){
										var data = $.trim(data);
										if (data === 'N') {
											alert('Esta composição não existe. Verifique o qrcode digitado.');
										}else{
											var aux = data.split(',');

											$('#idcomposto').val(aux[3]);
											$('#namecomposto').text(aux[0]);
											$('#qrcomposto').text(aux[1]);
											$('#childrencomposto').text(aux[2]);
											$('.boxinfofather').show();
										}
									});									


									
									alert("Produto cadastrado");
									
								}
							});
						} else {
							if( data == "ERRO2" )
								alert("Nenhum nome padr�o foi encontrado, verifique em NOMES de ");
							else
								if( data == "ERRO3" )
									alert("O nome informado n�o � um nome padr�o, verifique.");
								else
									alert("Erro ao efetuar cadastro!");
						}
					});
				}
			}
		});
	});

	//FIM ISSUE_PRODUTO_COMPOSTO JÁ NO CADASTRO DO PRODUTO FILHO


	$("#btSalvar").click(function(){
		// primeiro teste: valida��o do nome do produto
		$.post("nomesProdutos_new", {acao:"repetido", nome:$("#txNome").val()}, function(data){
			if($.trim(data) == "ERRO"){
				alert("Impossível prosseguir!\n\nEscolha um nome válido para o produto.");
				$("#txNome").val("");
				$("#txNome").focus();
			} else {
				// demais testes
				if($("#txQrcode").val() == "") {
					alert("Informe o QRCode do produto!");
					$("#txQrcode").focus();
				} else if($("#txNome").val() == "") {
					alert("Informe o nome do produto!");
					$("#txNome").focus();
				} /*else if($("#txSetor").val() == "0") {
					alert("Escolha o setor ao qual pertence o produto!");
					$("#txSetor").focus();
				}*/ else if($("#txQtdmaxima").val() == "") {
					alert("Informe a quantidade máxima de reprocessamento do produto!");
					$("#txQtdmaxima").focus();
				} else if($("#txGrupomaterial").val() == "0") {
					alert("Escolha o grupo ao qual pertence o produto!");
					$("#txGrupomaterial").focus();
				} else {
					var valores = $("#formProdutos").serialize();
					//console.log('valoressss:' + valores);
					$.post("produtos_new", valores, function(data){
						console.log(data)
						data = $.trim(data)
						if( data == "OK"){
							location.href = "produtos";
						} else {
							if( data == "ERRO2" )
								alert("Nenhum nome padr�o foi encontrado, verifique em NOMES de ");
							else
								if( data == "ERRO3" )
									alert("O nome informado n�o � um nome padr�o, verifique.");
								else
									alert("Erro ao efetuar cadastro!");
						}
					});
				}
			}
		});
	});

	$("#btCancelar").click(function(){
		location.href = "produtos";
	});

	$(".edit").live("click", function(){
		location.href = "produtos_new?populate=1&id=" + $(this).attr('id');
	});

	$(".delete").live("click", function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("produtos_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});

	$("#txQrcode").blur(function(){
		$.post("produtos_new", {acao:"repetido", qrcode:$(this).val(), id:$("#txId").val()}, function(data){
			if($.trim(data) == "ERRO"){
				alert("Imposs�vel prosseguir!\n\nEste QRCode j� est� sendo utilizado em outro produto.");
				$("#txQrcode").val("");
				$("#txQrcode").focus();
			} else if($.trim(data) == "APAGADO"){
				alert("Imposs�vel prosseguir!\n\nOutro produto com este mesmo QRCode j� foi cadastrado e apagado!");
				$("#txQrcode").val("");
				$("#txQrcode").focus();
			}
		});
	});

	// Ao digitar o QrCode - Replicar Produto (produto base)
	var time_exec;
	$("#txQrcodeReplicar_origem").keyup(function(e){
		
		var qrcode = $.trim( $(this).val() );
 
		clearTimeout(time_exec);
		time_exec = setTimeout(function(){
			
			if( qrcode.length >= 0 ){
				loadingShow();
				$.post("produtos_new", {acao:'getInfoProduto', qrcode:qrcode}, function(data){
					// data = JSON.parse(data);
					loadingHide(); 
					
					if( $.trim(data) == 'N' ){
						$('.txProdutoBase_nome').css({"color":"red","font-weight":"bold"});
						$('.txProdutoBase_nome').html('Produto n? encontrado !');
						$("#txQrcodeReplicar_origem").val('').focus();
					}else{
						$('.txProdutoBase_nome').css({"color":"blue","font-weight":"bold"});
						$('.txProdutoBase_nome').html(data);
						$('input[name="txQrcodeReplicar_novo[]"]').eq(0).focus();
					} 
				});
			}

		}, 300);
	});

	$("#telaReplicar").on('shown', function(){
		$('#txQrcodeReplicar_origem').focus();
	});
	
	// Ao digitar o QrCode - Replicar Produto (novo)
	var time_exec;
	$('input[name="txQrcodeReplicar_novo[]"]').live('keyup', function(e){
		
		var qrcode = $.trim( $(this).val() );
		var el = $(this);
 
		clearTimeout(time_exec);
		time_exec = setTimeout(function(){
			
			if( qrcode.length != "") {
				loadingShow();
				$.post("produtos_new", {acao:"repetido", qrcode:qrcode}, function(data){
					loadingHide();
					//alert(data);
					if($.trim(data) == "ERRO"){
						alert("C?igo de produto j?cadastrado!");
						el.val('').focus();
					}else{
						clona_replicarProduto();
					}
				});			
			}

		}, 300);
	});
	
	// Gera C?igo do novo produto
	$(".btnGeraQrCodeReplicar_novo").live("click", function(event){
		event.preventDefault();
		
		var el = $(this);
		
		$.post("produtos_new", {acao:"geraCodigo"}, function(data){			
			el.parent().find('input[name="txQrcodeReplicar_novo[]').val( $.trim(data) );
			clona_replicarProduto();
		});
	});	
	
	// Novo Campo QrCode - Replicar
	$(".btnAddQrCodeReplicar_novo").live("click", function(event){
		event.preventDefault();
		clona_replicarProduto();	
	});
	
	// Remover Campo QrCode - Replicar
	$(".btnRemoverQrCodeReplicar_novo").live("click", function(event){
		event.preventDefault();
		$(this).parent().remove();
	});

	// Salva  Replicar Produto	
	$("#btSalvarReplicar").live("click", function(event){
		event.preventDefault();		
		var QrCode_origem = $.trim( $('#txQrcodeReplicar_origem').val() );		
		if( QrCode_origem == '' ){ $('#txQrcodeReplicar_origem').focus(); }
		loadingShow();
		$('#formReplicar').submit();
	});

	$(".bt_composto").live("click", function(){
		var id = $(this).attr("id");
		populaProdutoComposto(id);
		populaProdutoCompostoprint(id);
	});

	$(".removeFilho").live("click", function(){
		if(confirm("Tem certeza que deseja remover este produto da lista?")){
			var aux = $(this).attr("id").split("_");
			$.get("produtosCompostos?id=" + aux[1], function(data){
				if($.trim(data) == 1){
					$.post("produtosCompostos", {idpai:$("#txIdPai").val(), modo:"1"}, function(data){
						var aux = $.trim(data).split("*;*");
						$("#txIdPai").val(aux[0]);
						$("#txQrcode").text(aux[1]);
						$("#txProduto").text(aux[2]);
						//$("#txIdSetor").val(aux[2]);
						$("#divQtdeItens").text(aux[3]);
						$("#divProdutosFilhos").html(aux[4]);
					});
				} else {
					alert("Erro de conex�o! Por favor, tente novamente.");
				}
			});
		}
	});

	$(".filhoPerdido").live("click", function(){
		var aux = $(this).attr("id").split("_");
		var tag = $(this);
		$.get("ocorrenciasprodutos?perdido=" + aux[1], function(data){
			if($.trim(data) == "OCO"){
				alert("N�o foi encontrada nenhuma ocorr�ncia de perda de material.");
			} else if($.trim(data) == "ERRO"){
				alert("Erro de conex�o! Por favor, tente novamente.");
			} else {
				tag.parent().parent().toggleClass("perdido");
				tag.fadeOut(500);
			}
		});
	});

	$("#telaComposicao").on('hide', function(){
		location.reload();
	});

	$("#btAdicionarProduto").click(function(){
		$("#txQrcodeAdicionar").val("");
		$("#divRestoItem").hide();
		$("#btConfirmarAdicionar").hide();
		$("#lbProdutoNaoCadastrado").hide();
		$("#txQrcodeAdicionar").focus();
		$("#telaAdicionarProduto").modal();
	});

	$("#telaAdicionarProduto").on('shown', function(){
		$("#txQrcodeAdicionar").val("");
		$("#divRestoItem").hide();
		$("#btConfirmarAdicionar").hide();
		$("#lbProdutoNaoCadastrado").hide();
		$("#txQrcodeAdicionar").focus();
	});
	

	var intervalo = 0;
	$("#txQrcodeAdicionar").keyup(function(e){
      //verificar quantidade caracteres qrcode
		if( $("#txQrcodeAdicionar").val().length > 0){
			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				buscarProdutoAdicionar()
		    }, 150);
		}

	});

	$("#txQrcodeAdicionar").keypress(function(e){
		/*
		 * Verifica se o evento � Keycode (IE e outros)
		 * Se n�o for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			buscarProdutoAdicionar();
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});

	$("#btConfirmarAdicionar").click(function(){
		$.post("produtosCompostos",{idpai:$("#txIdPai").val(), idfilho:$("#txIdProdutoAdicionar").val()}, function(data){
			if($.trim(data) == "OK"){
				$.post("produtosCompostos", {idpai:$("#txIdPai").val(), modo:"1"}, function(data){
					var aux = $.trim(data).split("*;*");
					$("#txIdPai").val(aux[0]);
					$("#txQrcode").text(aux[1]);
					$("#txProduto").text(aux[2]);
					//$("#txIdSetor").val(aux[2]);
					$("#divQtdeItens").text(aux[3]);
					$("#divProdutosFilhos").html(aux[4]);
					// fecha e abre novamente a tela para continuar adicionando (s� assim ele obedece o focus)
					$("#telaAdicionarProduto").modal("hide");
					setTimeout(function(){
						$("#telaAdicionarProduto").modal();
					}, 600);
				});
			} else if($.trim(data) == "ERRO") {
				alert("Erro de conexão! Por favor, tente novamente.");
			} else {
				$('#btAdicionarProduto').trigger('click');
				//alert("Impossível adicionar!\n\nEsse produto já faz parte da composição de um produto composto:\n\n" + data);
			}
		});
	});

	$('#mdl-substituir').on('shown.bs.modal', function () {
	  $('#txMotivoSubs').focus()
	})

	
	
	
	//atualizações para cliente novo

	//verificar se o checkbox da quantidade foi clicado ou não
	$("#boxQtde").hide();
	$('#ckQtde').click(function(){
		if($(this).is(":checked")) {
			$("#boxQtde").show();
		} else {
			$("#boxQtde").hide();
		}
	});
});

function clona_replicarProduto(){
	
	if( $('.camposReplicar_novo input').last().val() != '' ){
		var clone = $('.camposReplicar_novo').eq(0).clone();
		clone.find('input').val('');		
		$(clone).insertAfter('.camposReplicar_novo:last');	
		$('.btnRemoverQrCodeReplicar_novo').removeClass('hide');
		$('.btnRemoverQrCodeReplicar_novo').eq(0).addClass('hide');
		$('.camposReplicar_novo input').last().focus();	
	}
	
}

function inserir($frm){	
	$( $frm ).find('input[name=inserir]').val(1);
	$.post('qrcode', $( $frm ).serialize() , function(data) {
		if( data.suc ){
			alert( data.suc );

			// SE FOR SUBSTITUIÇÃO DE PRODUTO COMPOSTO
			if( $('#formComposicao').is(':visible') ){
				// Atualiza já com o novo QrCode
				var idPai = $('#txIdPai').val();
				populaProdutoComposto(idPai);
				$('#mdl-substituir').css( "display", "none" );
				$('#mdl-substituir').find('button[type=reset]').click();
			}else{
				window.location.assign("produtos_new?populate=1&id="+$( $frm ).find('input[name=idproduto]').val())
			}

		}
		else{
			alert( data.err );
		}

	});

}

function populaProdutoComposto(id){
	$.post("produtosCompostos", {idpai:id, modo:"1"}, function(data){
		var aux = $.trim(data).split("*;*");
		$("#txIdPai").val(aux[0]);
		$("#txQrcode").text(aux[1]);
		$("#txProduto").text(aux[2]);
		//$("#txIdSetor").val(aux[2]);
		$("#divQtdeItens").text(aux[3]);
		$("#divProdutosFilhos").html(aux[4]);
		//div para imprimir a caixa do composto
		$("#divProdutosFilhos").html(aux[4]);
	});
}

function populaProdutoCompostoprint(id){
	$.post("produtosCompostos", {idpai:id, modo:"1", method:"print"}, function(data){
		var aux = $.trim(data).split("*;*");
		$("#txIdPaicomposto").val(aux[0]);
		$("#txQrcodecomposto").text(aux[1]);
		$("#txProdutocomposto").text(aux[2]);
		$("#divQtdeItenscomposto").text(aux[3]);
		$("#divPrintcomposto").html(aux[4]);
	});
}

function buscarProdutoAdicionar() {
	var qr = $("#txQrcodeAdicionar").val();

	var set = $("#txIdSetor").val();
	$.post("produtosCompostos",{acao:"buscar", qrcode:qr, setor:set, idpai:$("#txIdPai").val()}, function(data){
		if ($.trim(data) == "SETOR") {
			alert("Este produto não pertence ao mesmo setor que o produto composto ao qual deseja adicioná-lo!");
			$("#txQrcodeAdicionar").val("");
			$("#lbProdutoNaoCadastrado").hide();
			$("#divRestoItem").hide();
			$("#btConfirmarAdicionar").hide();
		} else if ($.trim(data) == "REPETIDO") {
			alert("Este produto já está incluso nesta composição!");
			$("#txQrcodeAdicionar").val("");
			$("#lbProdutoNaoCadastrado").hide();
			$("#divRestoItem").hide();
			$("#btConfirmarAdicionar").hide();
		} else if ($.trim(data) != "ERRO"){
			$("#divRestoItem").html(data);
			$("#divRestoItem").show();
			$("#btConfirmarAdicionar").show();
			$("#lbProdutoNaoCadastrado").hide();
			$("#btConfirmarAdicionar").focus();
		} else if ($("#btConfirmarAdicionar").is(":visible")) {

			$("#divRestoItem").hide();
			$("#btConfirmarAdicionar").hide();
			$("#lbProdutoNaoCadastrado").show();
			$("#txQrcodeAdicionar").focus();		
			$("#txQrcodeAdicionar").select();
		}
			$( "#btConfirmarAdicionar" ).trigger( "click" );
		

		
	});
}

function removeRotulo(id) {
	alert(id);
	if(confirm("Tem certeza que deseja remover a imagem desse rótulo?")){
		$.post("produtos_new",{removerotulo:id}, function(data){
			if($.trim(data) == "OK"){
				location.reload();
			} else {
				alert("Falha de conexão! Por favor, tente novamente.")
			}
		});
	}
}

function noenter() {
	return !(window.event && window.event.keyCode == 13);
}

function modalSubs(){
    $('#mdl-substituir').modal('show');
	$('#mdl-substituir').css( "display", "block" )
}

function modalConsultaSubs(){
    $('#mdl-consultar').modal('show')
}

function validaData(strData) {
	dia = strData.substring(0,2);
	mes = strData.substring(3,5);
	ano = strData.substring(6,10);
	situacao = true;
	// verifica o dia valido para cada mes
	if ((dia < 01)||(dia < 01 || dia > 30) && (  mes == 04 || mes == 06 || mes == 09 || mes == 11 ) || dia > 31) {
	   situacao = false;
	}
	// verifica se o mes � valido
	if (mes < 01 || mes > 12 ) {
	   situacao = false;
	}
	// verifica se � ano bissexto
	if (mes == 2 && ( dia < 01 || dia > 29 || ( dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
	   situacao = false;
	}
	if (strData == '') {
	   situacao = false;
	}
	return situacao;
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Solu��es em T.I. � 2013
*/