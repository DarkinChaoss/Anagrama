$(document).ready(function(){

	$("#btSalvar").click(function(){
		// primeiras verificações de formulário
		if($("#txLoginU").val() == "" || $("#txSenhaU").val() == "" || $("#slNivel").val() == "-" || $("#slReferencia").val() == "0") {
			alert("Preencha todos os campos para prosseguir!");
		} else if($("#txSenhaU").val().length < 5) {
			alert("A senha deve conter de 5 a 20 caracteres!");
		} else if($("#txSenhaU").val() == $("#txLoginU").val()) {
			alert("A senha não pode ser igual ao login!");
		} else {
			var valores = $("#formUsuario").serialize();
			$.post("usuarios_new", valores, function(data){
				if($.trim(data) == "ERRO1"){
					alert("Este login já está sendo utilizado!\n\nPor favor, escolha outro.");
				} else if($.trim(data) == "ERRO"){
					alert("Erro ao efetuar cadastro!");
				} else {
					location.href = "usuarios";
				}
			});
		}
	});
	
	$("#btCancelar").click(function(){
		location.href = "usuarios";
	});
	
	$(".edit").click(function(){
		location.href = "usuarios_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("usuarios_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});
	
	$("#slNivel").change(function(){
		if($(this).val() != "-") {
			$.get("usuarios_new?comboRef=" + $(this).val(), function(data){
				$("#slReferencia").html(data);
				document.getElementById("slReferencia").disabled = false;
			});
		} else {
			$("#slReferencia").html("");
			document.getElementById("slReferencia").disabled = true;
		}
	});
	
	$("#btAlterar").click(function(){
		if($("#txSenhaAtual").val() == "" || $("#txNovaSenha").val() == "" || $("#txNovaSenha2").val() == "") {
			alert("Preencha todos os campos para prosseguir!")
		} else {
			if($("#txNovaSenha").val() != $("#txNovaSenha2").val()) {
				alert("Confirmação de nova senha está incorreta!\n\nTente novamente.")
				$("#txSenhaAtual").val("");
				$("#txNovaSenha").val("");
				$("#txNovaSenha2").val("");
				$("#txSenhaAtual").focus();
			} else {
				var valores = $("#formAlterarSenha").serialize();
				$.post("alterarSenha", valores, function(data){
					if($.trim(data) == "OK"){
						alert("Senha alterada com sucesso!");
						location.href = "./";
					} else {
						alert("Senha atual não confere!\n\nTente novamente.");
						$("#txSenhaAtual").val("");
						$("#txNovaSenha").val("");
						$("#txNovaSenha2").val("");
						$("#txSenhaAtual").focus();
					}
				});
			}
		}
	});
	
	$("#btCancelarAlterar").click(function(){
		location.href = "./";
	});
	
});

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/