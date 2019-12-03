$(document).ready(function(){

	$("#btSalvar").click(function(){
		if($.trim($("#txNome").val()) == ""){
			alert("Preencha todos os campos para prosseguir!");
		} else {
			$.post("turnosTrabalho_new", { acao:"repetido", nome:$("#txNome").val() }, function(data){
				if($.trim(data) == "REPETIDO"){
					alert("Impossível prosseguir! Este nome já está relacionado a outro turno de trabalho.");
				} else {
					var valores = $("#formTurnoTrabalho").serialize();
					$.post("turnosTrabalho_new", valores, function(data){
						if($.trim(data) == "OK"){
							location.href = "turnosTrabalho";
						} else {
							alert("Erro ao efetuar cadastro!");
						}
					});
				}
			});
		}
	});
	
	$("#btCancelar").click(function(){
		location.href = "turnosTrabalho";
	});
	
	$(".edit").click(function(){
		location.href = "turnosTrabalho_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("turnosTrabalho_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});
	
	$("#txInicio").blur(function(){
		var inicio = $("#txInicio").val();
		if(inicio.length < 8){
			alert("Informe horas, minutos e segundos, utilizando 2 dígitos para cada.\n\nExemplo: 08:00:00.");
			$(this).focus();
		}
	});
	
	$("#txFim").blur(function(){
		var inicio = $("#txFim").val();
		if(inicio.length < 8){
			alert("Informe horas, minutos e segundos, utilizando 2 dígitos para cada.\n\nExemplo: 05:59:59.");
			$(this).focus();
		}
	});
	
});


/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/