$(document).ready(function(){
	
	$("#slSetor").change(function(){
		if($(this).val() != 0){
			$("#btNovoEmprestimo").show();
		} else {
			$("#btNovoEmprestimo").hide();
		}
		$.post("emprestimo_new", {acao:"buscar", setor:$(this).val()}, function(data){
			$("#listaItens").html(data);
			$("#txBuscar").val("");
			$("#btLimparBusca").hide();
		});
	});
	
	$("#btLimparBusca").click(function(){
		location.href = "emprestimo?setor=" + $("#slSetor").val();
	});
	
	$("#btNovoEmprestimo").click(function(){
		$("#telaNovoEmprestimo").modal({
				keyboard: false
		});
	});
	
	// MODAL/DIV NOVO EMPRÉSTIMO
	
		$("#telaNovoEmprestimo").on('shown', function(){
			$("#idEmprestimo").val("0");
			$("#nomeSetor").text($("#slSetor option:selected").text());
			$("#dataEmprestimo").text("");
			$("#txNomeSolicitante").val("");
			$("#divItensEmprestimo").html("");
			$("#txNomeSolicitante").focus();
		});
		
		$("#telaNovoEmprestimo").on('hide', function(){
			$.post("emprestimo_new", {acao:"buscar", filtro:$("#txBuscar").val(), setor:$("#slSetor").val()}, function(data){
				$("#listaItens").html(data);
			});
		});
		
		$("#txNomeSolicitante").keypress(function(e){
			/*
			 * Verifica se o evento é Keycode (IE e outros)
			 * Se não for, pega o evento Which (Firefox)
			*/
			var tecla = (e.keyCode?e.keyCode:e.which);
			if(tecla == 13){
				$("#btAdicionarMaterial").click();
				// impede o sumbit caso esteja dentro de um form
				e.preventDefault(e);
				return false;
			}
		});

		$("#btAdicionarMaterial").click(function(){
			if($.trim($("#txNomeSolicitante").val()) == ""){
				alert("Informe o nome do solicitante do empréstimo para prosseguir!");
				$("#txNomeSolicitante").focus();
			} else {
				// pega o nome do turno atual, caso esteja inserindo o empréstimo
				if($("#idEmprestimo").val() == 0){
					$.post("emprestimo_new", {acao:"nomeTurno"}, function(data){
						$("#nomeTurno").text(data);
					});
				}
				//
				$.post("emprestimo_new", {acao:"salvar", id:$("#idEmprestimo").val(), setor:$("#slSetor").val(), nomesolicitante:$("#txNomeSolicitante").val()}, function(data){
					var aux = $.trim(data).split("*;*");
					$("#idEmprestimo").val(aux[0]);
					$("#dataEmprestimo").text(aux[1]);
					$("#txNomeMaterial").val("");
					$("#txCodMaterial").val("");
					$("#txQtde").val("1");
					$("#idMaterialLido").val("");
					$("#nomeMaterialLido").html("&nbsp;");
					$("#divNovoEmprestimo").fadeOut(function(){
						$("#divAdicionarMateriais").fadeIn();
						$("#txNomeMaterial").focus();
					});
				});
			}
		});
		
		$("#btCancelarNovo").click(function(){
			if(confirm("Tem certeza que deseja cancelar o cadastro desse empréstimo?")){
				if($("#idEmprestimo").val() != "0"){
					$.post("emprestimo_new", {acao:"cancelar", id:$("#idEmprestimo").val()}, function(){
						$("#telaNovoEmprestimo").modal('hide');
					});
				} else {
					$("#telaNovoEmprestimo").modal('hide');
				}
			}
		});
		
		$("#btSalvarNovo").click(function(){
			if($("#idEmprestimo").val() == "0"){
				alert("Nenhum material foi adicionado à solicitação!");
			} else {
				var oJan = window.open("comprovanteEmprestimo?id=" + $("#idEmprestimo").val());
				setTimeout(function(){
					$("#telaNovoEmprestimo").modal('hide');
				}, 300);
			}
		});
	
	//
		
	// MODAL/DIV ADICIONAR MATERIAL
		
		$("#txNomeMaterial").change(function(){
			buscaMaterial();
		});
		
		$("#txNomeMaterial").keyup(function(){
			buscaMaterial();
		});
		
		$("#txQtde").focus(function(){
			if($("#idMaterialLido").val() == "" && $("#txNomeMaterial").val() != ""){
				alert("Material inválido!");
				$("#txNomeMaterial").focus();
			}
		});
		
		$("#txQtde").keypress(function(e){
			/*
			 * Verifica se o evento é Keycode (IE e outros)
			 * Se não for, pega o evento Which (Firefox)
			*/
			var tecla = (e.keyCode?e.keyCode:e.which);
			if(tecla == 13){
				$("#btSalvarMaterial").click();
				// impede o sumbit caso esteja dentro de um form
				e.preventDefault(e);
				return false;
			}
		});
		
		$(".remove").live("click", function(){
			$.post("emprestimo_new", {acao:"removerItem", id:$(this).attr("id")}, function(data){
				if($.trim(data) != "ERRO")
					listaItensNovoEmprestimo();
				else
					alert("Falha ao remover item! Tente novamente.");
			});
		});
		
		$("#btSalvarMaterial").click(function(){
			if($("#idMaterialLido").val() != ""){
				$("#txNomeMaterial").val("");
				$.post("emprestimo_new", {acao:"salvarItem", idEmprestimo:$("#idEmprestimo").val(), idMaterial:$("#idMaterialLido").val(),
				 turno:$("#nomeTurno").text(), entregue:$("#txQtde").val()}, function(data){
					if($.trim(data) != "ERRO"){
						$("#divMsg").fadeIn();
						setTimeout(function(){
							$("#divMsg").fadeOut();
						}, 2000);
						$("#idMaterialLido").val("");
						$("#nomeMaterialLido").html("&nbsp;");
						$("#txCodMaterial").val("");
						$("#txQtde").val("1");
					} else {
						alert("Falha ao salvar item! Tente novamente.");
					}
				});
			} else {
				alert("Escolha o material a ser adicionado!");
			}
		});
		
		$("#btCancelarMaterial").click(function(){
			$("#divAdicionarMateriais").fadeOut(function(){
				$("#divNovoEmprestimo").fadeIn();
				listaItensNovoEmprestimo();
			});
		});
		
		$("#btFinalizarMaterial").click(function(){
			if($("#idMaterialLido").val() != ""){
				$.post("emprestimo_new", {acao:"salvarItem", idEmprestimo:$("#idEmprestimo").val(), idMaterial:$("#idMaterialLido").val(),
				 turno:$("#nomeTurno").text(), entregue:$("#txQtde").val()}, function(data){
					if($.trim(data) != "ERRO"){
						$("#divAdicionarMateriais").fadeOut(function(){
							$("#divNovoEmprestimo").fadeIn();
							listaItensNovoEmprestimo();
						});
					} else {
						alert("Falha ao salvar item! Tente novamente.");
					}
				});
			} else {
				alert("Escolha o material a ser adicionado!");
				$("#txNomeMaterial").focus();
			}
		});
		
	//
	
	// MODAL DEVOLUÇÃO DE MATERIAIS
		
		$("#telaDevolucaoMateriais").on('shown', function(){
			$("#txDevSujo").focus();
			$("#txDevSujo").select();
		});
		
		$("#txDevSujo").keyup(function(){
			somaDev();
		});
		
		$("#txDevSemUso").keyup(function(){
			somaDev();
		});
		
		$("#btSalvarDevolucao").click(function(){
			if($("#totalDebito").text() < 0){
				alert("Impossível prosseguir! A quantidade de devolução está maior que a entregue.");
			} else {
				if($.trim($("#txDevSujo").val()) == "")
					$("#txDevSujo").val("0");
				if($.trim($("#txDevSemUso").val()) == "")
					$("#txDevSemUso").val("0");
				var totalSujo = parseInt($("#txDevSujo").val()) + parseInt($("#devSujo").val());
				var totalSemUso = parseInt($("#txDevSemUso").val()) + parseInt($("#devSemUso").val());
				$.post("emprestimo_new", {acao:"devolverItem", id:$("#idItemEmprestimo").val(), sujo:totalSujo, semUso:totalSemUso}, function(data){
					if($.trim(data) != "ERRO"){
						$("#telaDevolucaoMateriais").modal('hide');
						$.post("emprestimo_new", {acao:"buscar", setor:$("#slSetor").val()}, function(data){
							$("#listaItens").html(data);
						});
					} else {
						alert("Falha ao salvar devolução! Tente novamente.");
					}
				});
			}
		});
		
	//
	
});

function listaItensNovoEmprestimo() {
	$.post("emprestimo_new", {acao:"listaItens", id:$("#idEmprestimo").val()}, function(data){
		var aux = $.trim(data).split("*;*");
		$("#divItensEmprestimo").html(aux[0]);
		$("#totalItensNovoEmprestimo").text("Total: " + aux[1] + " itens");
	});
}

function buscaMaterial() {
	if($("#txNomeMaterial").val() != ""){
		$.post("emprestimo_new", {acao:"checkMaterial", nome:$("#txNomeMaterial").val()}, function(data){
			if($.trim(data) == "ERRO"){
				$("#idMaterialLido").val("");
				$("#nomeMaterialLido").text("Nome de material inválido!");
				$("#txNomeMaterial").focus();
			} else {
				var aux = $.trim(data).split("*;*");
				$("#idMaterialLido").val(aux[0]);
				$("#nomeMaterialLido").text(aux[1]);
			}
		});
	}
}

function devolucaoItem(nome, id, entregue, sujo, semuso) {
	$("#idItemEmprestimo").val(id);
	$("#nomeMaterialDevolucao").text(nome);
	$("#totalEntregue").text(entregue);
	$("#txDevSujo").val("0");
	$("#txDevSemUso").val("0");
	$("#devSujo").val(sujo);
	$("#devSemUso").val(semuso);
	$("#totalDebito").text(entregue - (sujo + semuso));
	$("#devDebito").val(entregue - (sujo + semuso));
	$("#telaDevolucaoMateriais").modal();
}

function somaDev() {
	$("#totalDebito").text($("#devDebito").val() - $("#txDevSujo").val() - $("#txDevSemUso").val());
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/