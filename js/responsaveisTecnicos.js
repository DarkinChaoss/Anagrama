$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formRTecnico").serialize();
		$.post("responsaveisTecnicos_new", valores, function(data){
			if($.trim(data) == "OK"){
				location.href = "responsaveisTecnicos";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "responsaveisTecnicos";
	});
	
	$(".edit").click(function(){
		location.href = "responsaveisTecnicos_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("responsaveisTecnicos_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});
	
});


/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/