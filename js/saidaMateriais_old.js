$(document).ready(function(){
	// cleverson matias
	listaItens(($("#txId").val()));
	listaItensConsignados(($("#txId").val()));

	// scroll comum
	$(document).on('click', '#cs-comuns', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#comum')).offset().top
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


	$(".pagination li").live("click", function(){
		if($(this).attr('class') != "disabled" && $(this).attr('class') != "active"){
			if($(this).attr('pag') == "prev"){
				var pag = parseInt($("#pagAtiva").val()) - 1;
			} else if($(this).attr('pag') == "next"){
				var pag = parseInt($("#pagAtiva").val()) + 1;
			} else {
				var pag = $(this).attr('pag');
			}
			$.post("saidaMateriais_new", { pag:pag }, function(data){
				$("#paginacao").html(data);
				$.post("saidaMateriais_new", { acao:"lista", pag:pag }, function(dataLista){
					$("#lista").html(dataLista);
				});
			});
		}
	});

	$("#txProntuario").focus(function(){
		$("#slSetor").val("0");
	});

	$("#txPaciente").keyup(function(){
		if($("#txId").val() != ""){
			$("#btSalvar").show();
			$("#btDescartarAlteracao").show();
			$("#btFinalizar").hide();
			$("#btAdProd").hide();
		}
	});

	$("#btSalvar").click(function(){
		/*if($("#txPaciente").val() == ""){
			alert("Preencha todos os campos antes de salvar!");
			$("#txPaciente").focus();
		} else if($("#txSala").val() == ""){
			alert("Preencha todos os campos antes de salvar!");
			$("#txSala").focus();
		} else if($("#slSetor").val() == "0"){
			alert("Preencha todos os campos antes de salvar!");
			$("#slSetor").focus();
		} else if($("#slConvenio").val() == "0"){
			alert("Preencha todos os campos antes de salvar!");
			$("#slConvenio").focus();
		} else {*/
			var valores = $("#formSaida").serialize();
			/*
			if($("#txSala").is("[readonly]")){
				valores += "&ultimoLancamento=x"; // ultimoLancamento = x é usado para salvar alterações no prontuário sem alterar sma_ultimolancamento
			}
			*/
			$.post("saidaMateriais_new", valores, function(data){
				if($.trim(data) > 0){
					location.href = "saidaMateriais_new?prontuario=" + $("#txProntuario").val();
				} else {
					alert("Erro ao efetuar cadastro!");
				}
			});
		//}
	});

	$("#btDescartarAlteracao").click(function(){
		location.href = "saidaMateriais_new?prontuario=" + $("#txProntuario").val();
	});

	$("#telaAdicionarProduto").on("shown", function(){
		$("#divSala").show();
		$("#divItem").hide();
		$("#divRestoItem").hide();
		$("#txSalaItem").val("");
		$("#txQrcode").val("");
		$("#txQtdmaxima").text("");
		$("#qtdProc").text("");
		$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
		$("#btSalvarPro").hide();
		$("#lbProdutoDescartado").hide();
		$("#lbProdutoNaoCadastrado").hide();
		$("#lbProdutoNaoPronto").hide();
		$("#txSalaItem").focus();
	});

	$("#txSalaItem").keyup(function(e){
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			$("#btConfirmarSala").click();
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});

	$("#btConfirmarSala").click(function(){
		$("#divSala").hide();
		$("#divItem").show();
		$("#txQrcode").focus();
	});
	
	// cleverson matias busca produto
	function buscaProduto(buscarctx){
		var qr = $("#txQrcode").val();
		var set = $("#slSetor").val();
		
				$.post("saidaMateriais_new",{acao: buscarctx, qrcode:qr, setor:set, idSaida:$("#txId").val()}, function( data ){
					
                   //alert(data);
                                            
				/*	if (  data.idproduto != null ){

						$("#txIdproduto").val( data.idproduto );
						$("#txProduto").text( data.infoProduto );
						$("#txLote").text( data.lote );
						$("#txQtdmaxima").text( data.maxqtdprocessamento );
						$("#qtdProc").text( data.nreuso );
						$("#txValidade").text( data.validade );
						$("#divRestoItem").show();
						$("#txObsIsa").val("");
						$("#lbProdutoDescartado").hide();
						$("#lbProdutoNaoCadastrado").hide();
						$("#lbProdutoNaoPronto").hide();
						$("#lbValidadeEsterilizacao").hide();
						$("#divReuso").show();
						$("#divPrimeiroUso").hide();						

						if(data.nreuso == "" || data.nreuso == 0 || data.nreuso == null) {
							$("#divReuso").hide();
							$("#divPrimeiroUso").show();
							$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
							$("#btSalvarPro").show();
							$("#btSalvarPro").focus();
						} else if(parseInt($("#qtdProc").text()) > parseInt($("#txQtdmaxima").text())) {
							$("#lbReproc").attr("style", "color: red; font-weight: bold;");
							$("#btSalvarPro").hide();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						} else {
							$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
							$("#btSalvarPro").show();
							$("#btSalvarPro").focus();
						}

						if(data.descarte > 0) { // produto descartado
							$("#btSalvarPro").hide();
							$("#lbProdutoNaoCadastrado").hide();
							$("#lbProdutoNaoPronto").hide();
							$("#lbProdutoDescartado").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}
						if((data.status == 0 && ( data.nreuso != null || data.nreuso != "" ) ) 
							|| (data.status == 0 && data.qtdSaida != 0)) { // produto não pronto para uso
							$("#divPrimeiroUso").hide();
							$("#btSalvarPro").hide();
							$("#lbProdutoNaoPronto").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}
						else if(data.validade == "S") { // validade da esterilização excedida
							$("#btSalvarPro").hide();
							$("#lbValidadeEsterilizacao").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}

						
					} */
					if ($.trim(data) != "ERRO"){
						data = $.trim(data).split("*;*");
						$("#txIdproduto").val(data[0]); //id
						$("#txProduto").text(data[1]);// informações
						$("#txLote").text(data[2]); // lote
						$("#txQtdmaxima").text(data[3]); //max reprocessamento
						$("#qtdProc").text(data[4]); // reuso atual
						$("#txValidade").text(data[5]); // validade esterilização
						$("#divRestoItem").show();
						$("#txObsIsa").val("");
						$("#lbProdutoDescartado").hide();
						$("#lbProdutoNaoCadastrado").hide();
						$("#lbProdutoNaoPronto").hide();
						$("#lbValidadeEsterilizacao").hide();
						$("#divReuso").show();
						$("#divPrimeiroUso").hide();
						if(data[4] == "") { // reuso atual vazio
							$("#divReuso").hide();
							$("#divPrimeiroUso").show();
							$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
							$("#btSalvarPro").show();
							$("#btSalvarPro").focus();
						} else if(parseInt($("#qtdProc").text()) > parseInt($("#txQtdmaxima").text())) {// reprocessamento atual é maior que maximo de reprocessamento
							$("#lbReproc").attr("style", "color: red; font-weight: bold;");
							$("#btSalvarPro").hide();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						} else {
							$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
							$("#btSalvarPro").show();
							$("#btSalvarPro").focus();
						}
						if(data[6] > 0) { // produto descartado
							$("#btSalvarPro").hide();
							$("#lbProdutoNaoCadastrado").hide();
							$("#lbProdutoNaoPronto").hide();
							$("#lbProdutoDescartado").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}
						
						if((data[7] == 0 && data[4] != "") || (data[7] == 0 && data[9] != 0)) { // produto não pronto para uso 
							$("#divPrimeiroUso").hide();
							$("#btSalvarPro").hide();
							$("#lbProdutoNaoPronto").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}
						else if(data[8] == "S") { // validade da esterilização excedida
							$("#btSalvarPro").hide();
							$("#lbValidadeEsterilizacao").show();
							$("#txQrcode").focus();
							$("#txQrcode").select();
						}
					}
					 else {
						
						$("#divRestoItem").hide();
						$("#txIdproduto").val("");
						$("#txProduto").text("");
						$("#txQtdmaxima").text("0");
						$("#qtdProc").text("");
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").hide();
						$("#lbProdutoDescartado").hide();
						$("#lbProdutoNaoPronto").hide();
						$("#lbProdutoNaoCadastrado").show();

					}
					
				});
				
	}
	var intervalo = 0;
	
	$('#boxQtde').hide();
	$("#txQrcode").keyup(function(e){
		// oculta mensagens
		$("#lbProdutoDescartado").hide();
		$("#lbProdutoNaoCadastrado").hide();
		$("#lbProdutoNaoPronto").hide();
		$("#lbValidadeEsterilizacao").hide();
		$('#boxQtde').hide();

		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		if($("#txQrcode").val().length > 3){

			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				var context = $("input[name='chProduto']:checked"). val();
				
				
				if(context == 'pn'){
					buscaProduto('buscarpn');
					showQuantidade();
				}else{
					buscaProduto('buscarpc');
				}
				
				
			}, 700);
		}

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});
	
	$("#txQtde").change(function(){
		var qtdenew = Number($("#txQtde").val());
		var disponivel = Number($("#disponivel").val());

		if(qtdenew > disponivel){
			alert('Atenção: O limite de quantidade do produto é ' + $("#disponivel").val());
			$("#btSalvarPro").css("display","none");
		}else{
			$("#btSalvarPro").css("display","inline");			
		}
	});
	
	// cleverson matias
	function salvaProdComQtd(salvarctx){
		
		if($("#txQtde").val() == '' && $("#disponivel").val() > 0){
			alert('Este produto tem quantidade, adicione a quantide para prosseguir');
		}else if($("#disponivel").val() == 0){
			alert('a solicitação já contém a quantidade máxima deste produto.');
			$("#btSalvarPro").hide();
		};
		
		if(salvarctx == 'salvapnqtd'){consignado = 0}else{consignado = 0}
		$.post("saidaMateriais_new", {isconsignado: consignado, acao: salvarctx, idSaida:$("#txId").val(), sala:$("#txSalaItem").val(), idProduto:$("#txIdproduto").val(),
		 lote:$("#txLote").text(), validade:$("#txValidade").text(), reuso:$("#qtdProc").text(), obs:$("#txObsIsa").val()}, function(data){
			 
			 //alert(data);
			
			listaItens($("#txId").val());
			listaItensConsignados($("#txId").val());
            
			if ($.trim(data) == "OK"){
				// limpa a tela e deixa pronta para o próximo item ao invés de fechá-la
				$("#divRestoItem").hide();
				$("#txQrcode").val("");
				$("#txQtdmaxima").text("");
				$("#qtdProc").text("");
				$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
				$("#btSalvarPro").hide();
				$("#lbProdutoDescartado").hide();
				$("#lbProdutoNaoCadastrado").hide();
				$("#lbProdutoNaoPronto").hide();
				$("#txQrcode").focus();
				$("#boxQtde").hide();
			} else {
				alert("Erro ao efetuar cadastro!");
			}
			
		});
		
		
	};
	// cleverson matias
	function salvaProd(salvarctx){
		if(salvarctx == 'salvarpc'){consignado = 1}else{consignado = 0}
		$.post("saidaMateriais_new", {isconsignado: consignado, acao: salvarctx, idSaida:$("#txId").val(), sala:$("#txSalaItem").val(), idProduto:$("#txIdproduto").val(),
		 lote:$("#txLote").text(), validade:$("#txValidade").text(), reuso:$("#qtdProc").text(), obs:$("#txObsIsa").val()}, function(data){
			
			listaItens($("#txId").val());
			listaItensConsignados($("#txId").val());
            
			if ($.trim(data) == "OK"){
				// limpa a tela e deixa pronta para o próximo item ao invés de fechá-la
				$("#divRestoItem").hide();
				$("#txQrcode").val("");
				$("#txQtdmaxima").text("");
				$("#qtdProc").text("");
				$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
				$("#btSalvarPro").hide();
				$("#lbProdutoDescartado").hide();
				$("#lbProdutoNaoCadastrado").hide();
				$("#lbProdutoNaoPronto").hide();
				$("#txQrcode").focus();
			} else {
				alert("Erro ao efetuar cadastro!");
			}
			
		});
		
	}
	
	// cleverson matias
	$("#btSalvarPro").click(function(){
		if($("#boxQtde").is(":visible")){
			if($("#txQtde").val() == '' || $("#txQtde").val() == '0'){
				alert('Selecione uma quantidade para inserir na saída.');
			}else if($('#disponivel').val()  == '0'){
				alert('Quantidade máxima de produto atingida, aguardar próxima esterilização.');
			}else{
				var context = $("input[name='chProduto']:checked"). val();
				if(context == 'pn'){
					$.post('saidaMateriais_new', {qrcode:$('#txQrcode').val(), acao:'buscaQtdProntos', aux: 'aux'}, function(data){
						var teste = data.split(';');
							
						if(teste[1] > 1){
							var cont = $('#txQtde').val();
							while(cont > 0){
							  salvaProdComQtd('salvapnqtd');
							  cont--;
							}
							
						}else{
							salvaProd('salvarpn');
						}
					});
				}else{
				salvaProd('salvarpc');
				}
			};
		}else{
				var context = $("input[name='chProduto']:checked"). val();
				if(context == 'pn'){
					$.post('saidaMateriais_new', {qrcode:$('#txQrcode').val(), acao:'buscaQtdProntos', aux: 'aux'}, function(data){
						var teste = data.split(';');
							
						if(teste[1] > 1){
							var cont = $('#txQtde').val();
							while(cont > 0){
							  salvaProdComQtd('salvapnqtd');
							  cont--;
							}
							
						}else{
							salvaProd('salvarpn');
						}
					});
				}else{
				salvaProd('salvarpc');
				}
			};
		
		
		
	});

	$("#ckTodosItensSaida").click(function(){
		if($("#ckTodosItensSaida").is(":checked")){
			$(".ckItemSaida").attr("checked", true);
		} else {
			$(".ckItemSaida").attr("checked", false);
		}
	});

});

function listaItens(id) {
	$.post("saidaMateriais_new", {acao:"listaItens", id:id}, function(data) {
		$("#lista_itensSaida").html(data);
	});
}

// cleverson matias
function listaItensConsignados(id) {
	$.post("saidaMateriais_new", {acao:"listaItensConsignados", id:id}, function(data) {
		$("#lista_itensSaidaConsignados").html(data);
	});
}

function imprimirProntuario() {
	var elements = document.getElementsByClassName("ckItemSaida");
	var cont = 0;
	var valores = "acao=imprimir&id=" + $("#txId").val();
	for(var i = 0; i < elements.length; i++){
		if(elements[i].checked == true){
			valores += "&item[]=" + elements[i].getAttribute("alt");
			cont ++;
		}
	}
	if(cont == 0){
		alert("Para imprimir, selecione pelo menos um item da lista!");
	} else {
		$.post("saidaMateriais_new", valores, function(data) {
			//alert(data);
			$("#divPrint").html(data);
			$("#divPrint").printElement();
			/*setTimeout(function(){
				location.href = "./";
			}, 500);*/
		});
	}
}


function showQuantidade(){
	$.post('saidaMateriais_new', {qrcode:$('#txQrcode').val(), acao:'buscaQtdProntos', aux: 'aux'}, function(data){
		var teste = data.split(';');
		//console.log(teste[1]);
		if(teste[1] > 1){
		$('#boxQtde').show();
		$('#disponivel').val(teste[0]);
	}
	});
	
}

function noenter() {
	return !(window.event && window.event.keyCode == 13);
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2013
*/