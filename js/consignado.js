$(document).ready(function(){
	$("#btSalvar").click(function(){
		// primeiro teste: valida��o do nome do produto
		$.post("nomesProdutos_new", {acao:"repetido", nome:$("#txNome").val()}, function(data){
			if($.trim(data) == "ERRO"){
				alert("Imposs�vel prosseguir!\n\nEscolha um nome v�lido para o produto.");
				$("#txNome").val("");
				$("#txNome").focus();
			} else {
				// demais testes
				if($("#txQrcode").val() == "") {
					alert("Informe o QRCode do produto!");
					$("#txQrcode").focus();
				} else if($("#txNome").val() == "") {
					alert("Informe o nome do produto consignado!");
					$("#txNome").focus();
				} /*else if($("#txSetor").val() == "0") {
					alert("Escolha o setor ao qual pertence o produto!");
					$("#txSetor").focus();
				}*/ else if($("#txQtdmaxima").val() == "") {
					alert("Informe a quantidade m�xima de reprocessamento do produto!");
					$("#txQtdmaxima").focus();
				} else if($("#txGrupomaterial").val() == "0") {
					alert("Escolha o grupo ao qual pertence o produto!");
					$("#txGrupomaterial").focus();
				} else {
					var valores = $("#formProdutos").serialize();
					$.post("consignados_new", valores, function(data){
						console.log(data)
						data = $.trim(data)
						if( data == "OK"){
							location.href = "consignados";
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
		location.href = "consignados";
	});

	$(".edit").live("click", function(){
		location.href = "consignados_new?populate=1&id=" + $(this).attr('id');
	});

	$(".delete").live("click", function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("consignados_new?delete=1&id=" + $(this).attr('id'), function(data){
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
		console.log('#txQrcode');
		$.post("consignados_new", {acao:"repetido", qrcode:$(this).val(), id:$("#txId").val()}, function(data){			
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
	
	
	$("#boxDevolvido").hide();
	$('#ckDevolvido').click(function(){
		if($(this).is(":checked")) {
			$("#boxDevolvido").show();
		} else {
			$("#boxDevolvido").hide();
		}
	});
});