$(document).ready(function(){
	
	$("#tlaCriarUsuario").on('shown', function(){
		$("#txLoginX").focus();
	});

	$("#btSalvarUsuario").click(function(){
		// primeiras verificações de formulário ------ função copiada de usuarios.js
		//alert($("#txLoginX").val() + " - " + $("#txSenhaX").val());
		if($("#txLoginX").val() == "" || $("#txSenhaX").val() == "") {
			alert("Preencha todos os campos para prosseguir!");
		} else if($("#txSenhaX").val().length < 5) {
			alert("A senha deve conter de 5 a 20 caracteres!");
		} else if($("#txSenhaX").val() == $("#txLoginX").val()) {
			alert("A senha não pode ser igual ao login!");
		} else if($("#txSenhaX").val() != $("#txSenhaX2").val()) {
			alert("A confirmação da senha está incorreta!");
			$("#txSenhaX").val("");
			$("#txSenhaX2").val("");
			$("#txSenhaX").focus();
		} else {
			var valores = $("#formCriarUsuario").serialize();
			$.post("usuarios_new", valores, function(data){
				if($.trim(data) == "ERRO1"){
					alert("Este login já está sendo utilizado!\n\nPor favor, escolha outro.");
				} else if($.trim(data) == "ERRO"){
					alert("Erro ao efetuar cadastro!");
				} else {
					location.reload();
				}
			});
		}
	});
	
	$("#btCancelarUsuario").click(function(){
		$("#txIdPessoa").val("");
		$("#txNivel").val("");
		$("#txLoginX").val("");
		$("#txSenhaX").val("");
	});
	
	$(".new_user").click(function(){
		var aux = $(this).attr("id").split("_");
		$("#txIdPessoa").val(aux[0]);
		$("#txNivel").val(aux[1]);
		$("#tlaCriarUsuario").modal();
	});
	
});

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/