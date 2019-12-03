
$(document).ready(function(){
	// cleverson matias
	// carrega dinamicamente os totais de itens comuns e consignados
	contaComunEtiquetagem();
	contaConsigEtiquetagem();
	
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
	
	// cleverson controle tipo de produto a ser buscado -> comuns
	$("#txQrcode").attr("placeholder", "Buscar produto comun...").placeholder();
	
	$('#cs-co').on('change', function(){ // on change of state
		checkedContext('pn');
	   $("#txQrcode").attr("placeholder", "Buscar produto comun...").placeholder();
	})
	//cleverson controle tipo de produto a ser buscado -> consignados
	$('#cs-consig').on('change', function(){ // on change of state
		checkedContext('pc');
	   $("#txQrcode").attr("placeholder", "Buscar produto consignado...").placeholder();
	})
	
	

	$("#tlaEtiquetarProduto").on('shown', function(){
		$("#txQrcode").hide();
		$("#txQrcode").val("");
		$("#btImprimir").focus();
	});

	$("#tlaEtiquetarProduto").on('hide', function(){
		$("#txQrcode").show();
		$("#txQrcode").focus();
	});

	$("#btVoltar").click(function(){
		location.href = "etiquetagem";
	});

	// Reimpressão de etiqueta
	$("#btReimpressao").click(function(){
		$("#tlaReimpressaoEtiqueta").modal();
	});

	// Gerar etiqueta ACS 05/09/2017
	$("#btGeraEtiqueta").click(function(){
		clearTimeout(time_exec);
		time_exec = setTimeout(function(){
			// Monta C�digo de Barras e Envia para tela de impress�o
			$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeEtiqueta").val(), function(){
				var parametros = "qrCode=" + $("#txQrcodeEtiqueta").val();
				window.open("etiquetaAvulsa?" + parametros);
				$("#txQrcodeEtiqueta").val('');
				$("#txQrcodeEtiqueta").focus();
			});
		}, 150);
	});

	// Ao digitar o QrCode Para impress�o ACS 05/09/2017
	var time_exec;
	$("#btGeraEtiqueta").keyup(function(e){
		alert(1);
		clearTimeout(time_exec);
		time_exec = setTimeout(function(){

			var tecla = (e.keyCode?e.keyCode:e.which);
			if( $("#btGeraEtiqueta").val().length > 3 /* && tecla == 13 */ ){
				$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeEtiqueta").val(), function(){
					var parametros = "qrCode=" + $("#txQrcodeEtiqueta").val();
					window.open("etiquetaAvulsa?" + parametros);
					$("#txQrcodeEtiqueta").val('');
					$("#txQrcodeEtiqueta").focus();
				});
			}

		}, 150);
	});
    
	// Ao abrir modal - Foca no campo do QrCode
	$("#tlaReimpressaoEtiqueta").on('shown', function(){
		$("#txQrcodeReimpressao").focus();
	});

	// Ao digitar o QrCode Para impressão
	var time_exec;
	$("#txQrcodeReimpressao").keyup(function(e){
		$('#itensSolicitacaoReimpressao').html(''); // Limpa lista

		clearTimeout(time_exec);
		time_exec = setTimeout(function(){

			var tecla = (e.keyCode?e.keyCode:e.which);
			if( $("#txQrcodeReimpressao").val().length > 3 /* && tecla == 13 */ ){
				$.post("etiquetagem_new", {acao:'reimpressaoEtiqueta', qrcode:$.trim( $("#txQrcodeReimpressao").val() )}, function(data){
					console.log(data);
					$('#solicitacoesReimpressao').removeClass('hide').show();
					$('#itensSolicitacaoReimpressao').html(data);
					$('input[name="dataLimite[]"]').mask("99/99/9999"); // Mascara de Data
				});
			}

		}, 150);
	});

	// Botão para reimpressão do item
	$(".btReimprimir").live('click', function(){
		var id = $(this).attr('id');

		// Verifica se exixte input dataLimite no item clicado
		if( $(this).parent().parent().find('input[name="dataLimite[]"]').length ){

			// Verifica se o input está vazio ou é inválido
			var dataLimite = $(this).parent().parent().find('input[name="dataLimite[]"]').val();
			if( dataLimite == '' || dataLimite.length < 10 ){ // dd/mm/aaaa
				$(this).parent().parent().find('input[name="dataLimite[]"]').focus();
				return false;
			} else{
				$.post("etiquetagem_new", {acao:'alteraValidade', id:id, dataLimite:dataLimite}, function(data){
					$(this).parent().parent().find('input[name="dataLimite[]"]').attr('disabled','disabled'); // Desativa o Campo Data
				}); // Altera a data no Banco
			}
		}

		// Monta Código de Barras e Envia para tela de impressão
		$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeReimpressao").val(), function(){
			var parametros = "acao=reimpressao&item=" + id + "&qrCode=" + $("#txQrcodeReimpressao").val();
			window.open("etiqueta?" + parametros);
		});
	});
	
	$("#boxQtde").hide();
	$("#labelQtd").hide();
	var intervalo = 0;
	//$("#txQrcode").keypress(function(e){
	$("#txQrcode").keyup(function(e){
		/*
		 * Verifica se o evento ï¿½ Keycode (IE e outros)
		 * Se nï¿½o for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		if($("#txQrcode").val().length > 3){

			// intervalo atï¿½ executar a funï¿½ï¿½o
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {

				$("#imgLoading").show();
				//cleverson matias add extra data to send with post (kind of product) 
				$.post("etiquetagem_new", {acao:'buscar', qrcode:$("#txQrcode").val(), product:$("input[name='optradio']:checked").val()/*, ses:$("#txId").val()*/}, function(data){
					data = $.trim(data);
					//console.log(data);
					var context = $("input[name='optradio']:checked"). val();
					if (data != "NULO"){
						if (data != "ERRO"){
							adata = data.split("*;*");
							//console.log(adata);
							//console.log(adata[16]);
							if( adata[0] != "" ){ $("#tlaEtiquetarProduto").modal(); }
							$("#idItem").val(adata[0]);
							$("#idProduto").val(adata[1]);
							$("#qrcode").text(adata[2]);
							$("#nomeProduto").text(adata[3]);
							$("#dadosProduto1").text(adata[4]);
							$("#dadosProduto2").text(adata[5]);
							$("#setor").text(adata[6]);
							$("#metodo").text(adata[8]);
							
							if(context == 'pn' && adata[16] > 0){
								$("#labelQtd").hide();
								$('#reuso').hide();
						
							}else{
								$("#labelQtd").hide();
								$('#reuso').show();
								$("#qtdProcessada").text(adata[9]);
								
							}
							
							if(context == 'pc' && adata[16] > 0){
								$("#reuso").hide();
								$("#qtdPro").text(adata[16]);
								$("#labelQtd").show();
							}
							
							$("#qtdMaxima").text(adata[10]);
							$("#txLote").val(adata[11]);
							$("#dataEsterilizacao").text(adata[12]);
							$("#composto").val(adata[15]);
							//etiquetagem
							//console.log(adata[14]);
							if (adata[14] == '1') { // se item jï¿½ tiver sido finalizado
								$("#imgOkModal").show();
								$(".modal-header").attr("style", "background: #adfe9d;");
								$(".modal-footer").attr("style", "background: #adfe9d;");
								// $("#txLote").attr("readonly", true);
								$("#slLimiteUso").hide();
								$("#dataLimitedataLimite").show();
								$("#dataLimite").text(adata[13]);
								$("#boxQtde").hide();
								$("#qtdename").hide();
								$("#qtdename2").hide();
								$("#qtdeItem2").hide();
								$("#qtdeItem").hide();								
							} else {
								$("#imgOkModal").hide();
								$(".modal-header").attr("style", "background: white;");
								$(".modal-footer").attr("style", "background: white;");
								// $("#txLote").attr("readonly", false);
								$("#slLimiteUso").show();
								$("#spanLimiteUso").html(adata[13]);
							}

							if(adata[16] > 0){
								$("#boxQtde").show();		
							}else{
								$("#boxQtde").hide();
							}

							if(adata[17] > 0){
								$("#qtdeItem").change(function(e){
									if($("#qtdeItem").val() == ''){
										alert("Insira uma quantidade.");
										$("#btImprimir").hide();
									}
								});									

								$("#qtdeItem").keyup(function(e){
									
									if($("#qtdeItem").val() > parseInt(adata[17])){
										alert('A quantide inserida excede a quantidade atual em etiquetagem');
										$("#btImprimir").hide();
									}else{
										$("#btImprimir").show();
									}
								});
								$("#qtdeItem2").val(adata[17]);
								//este campo valida a qeust�o de que o produto tem quantidade e precisa ser inserido quantidade para validar 
								$("#qtdeItem3").val(adata[16]);
							}
							
							if($("input[name='optradio']:checked").val() == 'pc'){
								
								$("#boxQtde").hide();
							}

						} /*else {
							alert("Esse produto nï¿½o se encontra na lista para etiquetagem.");
						}*/
					}
					$("#imgLoading").hide();
				});

			 }, 150);
		}

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});

	$("#slLimiteUso").live('change', function(){
		calculaLimite();
	});

	var qtdgeral = null;	
	
	function enviaImpressao(ctx, qtdeGeral, data){
		var qtdProcessada = '';			
		var qtdeConsignado = '';
		if (ctx == 'pn' && qtdeGeral > 0){
			 qtdProcessada = '';
		}else{
			qtdProcessada = $("#qtdProcessada").text();
		}
		
		if(ctx == 'pc' && qtdeGeral > 0){
			qtdProcessada = '';	
			qtdeConsignado = qtdeGeral;		
			
		}
		
		console.log(qtdProcessada);
				
		 var parametros =  "qrCode=" + $("#qrcode").text()
			+ "&nomeProduto=" + $("#nomeProduto").text()
			+ "&dadosProduto1=" + $("#dadosProduto1").text()
			+ "&dadosProduto2=" + $("#dadosProduto2").text()
			+ "&nomeSetor=" + $("#setor").text()
			+ "&metodo=" + $("#metodo").text()
			+ "&qtdeProc=" + qtdProcessada
			+ "&qtdeConsignado=" + qtdeConsignado
			+ "&lote=" + $("#txLote").val()
			+ "&item=" + $("#idItem").val()
			+ "&composicao=" + $.trim(data);
		var oJan = window.open("etiqueta?" + parametros);
		setTimeout(function(){ location.reload(); }, 300);
	}	
	
	function save(){
		$.get("view/Barcode2/gerabarras.php?code=" + $("#qrcode").text(), function(){
		var ctx = $("input[name='optradio']:checked"). val();

			//console.log(data);
			$.post("etiquetagem_new",{ctx:ctx, iditem:$("#idItem").val(), dataEsterilizacao:$("#dataEsterilizacao").text(), dataLimite:$("#dataLimite").text(),
			 lote:$("#txLote").val(), idSolicitacao:$("#txNumero").text(), limiteUso:$("#slLimiteUso").val(), composto:$("#composto").val(), qtde:$("#qtdeItem").val(), idproduto:$("#idProduto").val()},
				 function(data){
				 var ctx = $("input[name='optradio']:checked"). val();
				 var qtdeGeral = null;

				 if(ctx == 'pn'){
					 $.post("etiquetagem_new", {idpn:$("#idProduto").val(), acao:'getquantidadepn'}, function(data){
						  qtdeGeral = data; 
						  enviaImpressao(ctx, qtdeGeral, data);
					 });
				 }else{
					 $.post("etiquetagem_new", {idpc:$("#idProduto").val(), acao:'getquantidadepc'}, function(data){
						  qtdeGeral = data; 					
						  enviaImpressao(ctx, qtdeGeral, data);
					 });
				 }			 
				});
		});	
	}
	
	$("#btImprimir").click(function(){
		if($("#slLimiteUso").val() == 0){
            if( $('#txLote').val() == "" )
			    alert('Escolha a data limite de uso!');
		} else if ($("#txLote").val() == ""){
			alert("Informe o lote do item!");
			$("#txLote").focus();
		} else {
			var ctx = $("input[name='optradio']:checked").val();
			if(parseInt($("#qtdeItem3").val()) > 0 && ctx == 'pn'){
				if(!parseInt($("#qtdeItem").val()) > 0){
					alert('Por favor insira uma quantidade');
				}else{
					// cleverson matias
							$.post('etiquetagem_new', {acao:'setProntos', value:$("#qtdeItem").val(), idproduto: $("#idProduto").val()},function(data){
								//alert(data);
							});
							
					save();
				}
			}else{
				save();					
			}
			/*
*/
		}
	});

	$("#btOcorrencia").click(function(){ // funï¿½ï¿½o adaptada em solicitacoes.js como lancarOcorrencia()
		$.post("ocorrenciasprodutos",{acao:'ocorrenciasProduto', produto:$("#idProduto").val()}, function(data){
			$("#produtoAlvo").text($("#nomeProduto").text());
			$("#divOcorrenciasProduto").html(data);
			$("#slOcorrencia").val("0");
			$("#descricaoOcorrencia").text("");
			$("#txObs").val("");
			$("#lbObs").hide();
			$("#btConfirmarOcorrencia").hide();
			$("#tlaLancarOcorrencia").modal();
		});
	});

	$("#slOcorrencia").change(function(){
		$.post("ocorrenciasprodutos",{ocorrencia:$("#slOcorrencia").val()}, function(data){
			$("#descricaoOcorrencia").text(data);
			if($("#slOcorrencia").val() == 0){
				$("#txObs").val("");
				$("#lbObs").hide();
				$("#btConfirmarOcorrencia").hide();
			} else {
				$("#lbObs").show();
				$("#btConfirmarOcorrencia").show();
			}
		});
	});

	$("#btConfirmarOcorrencia").click(function(){
		$.post("ocorrenciasprodutos",{idocorrencia:$("#slOcorrencia").val(), idproduto:$("#idProduto").val(), obs:$("#txObs").val()}, function(data){
			$("#tlaLancarOcorrencia").modal('hide');
			if($.trim(data) == "R") { // anula 1 reuso
				$("#qtdProcessada").text(parseInt($("#qtdProcessada").text()) - 1);
			}
		});
	});

	// Etiquetagem em Massa
	$("#btEtiquetagemMassa").click(function(){
		event.preventDefault();

		//if( confirm('Confirmar etiquetagem em massa ?' ) ){

			$.post("etiquetagem_new", {acao:'etiquetagemMassa'}, function(data){
				if( $.trim( data ) == 'OK' ){
					location.reload();
				}else{
					alert('Não foi possível concluir a solicitação');
				}
			});

		//}

	});

});

function calculaLimite(){
	if( $("#slLimiteUso").val() == '0' ){
		$("#dataLimite").hide();
	}else{
		$.post("etiquetagem_new",{limiteUso:$("#slLimiteUso").find("option").filter(":selected").text()}, function(data){
			$("#dataLimite").show();
			$("#dataLimite").text($.trim(data));
		});
	}

}

function buscaSolicitacao() {
	$.post("etiquetagem_new",{acao:'buscar', numero:$("#txNpedido").val()}, function(data){
		if ($.trim(data) != "ERRO"){
			data = $.trim(data).split(";");
			$("#txId").val(data[0]);
			$("#txNumero").text(data[1]);
			$("#txSetor").text(data[2]);
			$("#txDataEntrada").text(data[3]);
			$("#txDataEsterilizacao").text(data[4]);
			$("#formPedido").hide();
			$("#btVoltar").show();
			$("#divPedidoEtiquetagem").show();
			$.post("etiquetagem_new",{acao:'listar', id:data[0]}, function(data){
				$("#listaItens").html(data);
			});
		} else {
			alert("Solicitaï¿½ï¿½o nï¿½o encontrada! Verifique o nï¿½ da solicitaï¿½ï¿½o.");
			$("#txNpedido").val("");
			$("#txNpedido").focus();
		}
	});
}

function noenter() {
	return !(window.event && window.event.keyCode == 13);
}



function contaComunEtiquetagem(){
	$.post("etiquetagem_new", {acao:"contaComum"}, function(data) {
		$("#cs-qtd-comuns").text("Comuns: " + data);
		$("#TotComuns").val(data);
	});
}

function contaConsigEtiquetagem(){
	$.post("etiquetagem_new", {acao:"contaConsig"}, function(data) {
		
		var total = parseInt($("#TotComuns").val()) + parseInt(data);

		$("#cs-qtd-consignados").text("Consignados: " + data);
		$("#cs-totalItens").text("Total de Itens: " + total);

		// timeout para esperar os dados do banco
		if(isNaN(total)){
			$("#cs-totalItens").html("Total de Itens" + '<i class="fas fa-spinner cs-spinner">');
			setTimeout(function(){
				contaConsigEtiquetagem();
			}, 500);
			
		}
		
	});
}

function checkedContext(context){
	$.post("etiquetagem_new", {ctx: context});
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluï¿½ï¿½es em T.I. ï¿½ 2013
*/