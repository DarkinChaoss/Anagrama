$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formAdministracao").serialize();
		$.post("administracao_new", valores, function(data){
			console.log(data);
			if($.trim(data) == "OK"){
				location.href = "administracao";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "administracao";
	});
	
	$(".edit").click(function(){
		location.href = "administracao_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("administracao_new?delete=1&id=" + $(this).attr('id'), function(data){
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