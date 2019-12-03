$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formCirculante").serialize();
		$.post("circulante_new", valores, function(data){
			if($.trim(data) == "OK"){
				location.href = "circulante";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "circulante";
	});
	
	$(".edit").click(function(){
		location.href = "circulante_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("circulante_new?delete=1&id=" + $(this).attr('id'), function(data){
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