$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formSetor").serialize();
		$.post("setores_new", valores, function(data){
			if($.trim(data) != "ERRO"){
				location.href = "setores";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "setores";
	});
	
	$(".edit").click(function(){
		location.href = "setores_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("setores_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().slideUp();
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