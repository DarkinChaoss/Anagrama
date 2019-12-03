$(document).ready(function(){
	$('#itensdescartados').hide();
	$('.prodesc').hide();

	$("input[name=descart]").change(function(){
	  // verifica se foi selecionado
	  if($(this).is(':checked')){
		$('#itensdescartados').show();
		$('.prodesc').show();		
	  } else {
		$('#itensdescartados').hide();
		$('.prodesc').hide();
	  }
	});

	$("#btSalvar").click(function(){
		var valores = $("#formOcorrencia").serialize();
		$.post("ocorrencias_new", valores, function(data){
			//alert(data);
			if($.trim(data) == "OK"){
				location.href = "ocorrencias";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});

	$("#btCancelar").click(function(){
		location.href = "ocorrencias";
	});

	$(".edit").click(function(){
		location.href = "ocorrencias_new?populate=1&id=" + $(this).attr('id');
	});

	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("ocorrencias_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});

	$("#ckDescarte").click(function(){
		if(document.getElementById("ckDescarte").checked == true) {
			$("#ckDescarte").val("S");
		} else {
			$("#ckDescarte").val("N");
		}
	});


	// Funções copiadas e adaptadas de etiquetagem.js
	var intervalo = 0;
	$("#txQrcode").keyup(function(e){
		$("#lbProdutoNaoCadastrado").hide();
		$("#lbProdutoDescartado").hide();
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);

		if($("#txQrcode").val().length > 0){

			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {

				$.post("ocorrenciasprodutos", {acao:'buscar', qrcode:$("#txQrcode").val()}, function(data){
					console.log(data)
					if ($.trim(data) != "ERRO" && $.trim(data) != ""){
						var aux = $.trim(data).split("*;*");
						console.log(aux)
						$("#idProduto").val(aux[0]);
						if (aux[2] == "*") {
							$("#lbProdutoDescartado").show();
							$("#txQrcode").select();
						} else {
							$.post("ocorrenciasprodutos", {acao:'ocorrenciasProduto', produto:aux[0]}, function(data){
								$("#produtoAlvo").text(aux[1]);
								$("#produtoPai").text('Caixa: ' + aux[4]);
								$("#ProdutoPai").val(aux[4]);
								$("#ProdutoPaiId").val(aux[5]);
								$("#divOcorrenciasProduto").html(data);
								$("#slOcorrencia").val("0");
								$("#descricaoOcorrencia").text("");
								$("#txObs").val("");
								$("#lbObs").hide();
								$("#btConfirmarOcorrencia").hide();
								$("#tlaLancarOcorrencia").modal();
							});
						}
					} else {
						$("#lbProdutoNaoCadastrado").show();
					}
				});
			}, 150);
		}
	});

	$("#slOcorrencia").change(function(){
		$.post("ocorrenciasprodutos", {ocorrencia:$("#slOcorrencia").val()}, function(data){
			$("#descricaoOcorrencia").text(data);
			if($("#slOcorrencia").val() == 0){
				$("#txObs").val("");
				$("#lbObs").hide();
				$("#txProd").val("");
				$("#lbProduct").hide();
				$("#btConfirmarOcorrencia").hide();
			} else {
				$("#lbObs").show();
				$("#lbProduct").show();
				$("#btConfirmarOcorrencia").show();
			}
		});
	});

	$("#btConfirmarOcorrencia").click(function(){
		$.post("ocorrenciasprodutos", {idocorrencia:$("#slOcorrencia").val(), idproduto:$("#idProduto").val(), obs:$("#txObs").val(), obs:$("#txObs").val(), produtopai: $("#ProdutoPai").val(), produtopaiid: $("#ProdutoPaiId").val() }, function(data){
			console.log(data)
			$("#tlaLancarOcorrencia").modal('hide');
		});
	});

	$("#tlaLancarOcorrencia").on('shown', function(){
		$("#txQrcode").val("");
		$("#slOcorrencia").focus();
	});

	$("#tlaLancarOcorrencia").on('hide', function(){
		$("#txQrcode").focus();
	});

	//

});


/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2015
*/