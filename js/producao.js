$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formProducao").serialize();
		$.post("producao_new", valores, function(data){
			console.log(data);
			if($.trim(data) == "OK"){
				location.href = "producao";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "producao";
	});
	
	$(".edit").click(function(){
		location.href = "producao_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("producao_new?delete=1&id=" + $(this).attr('id'), function(data){
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
 * Brothers Solu��es em T.I. � 2013
*/