$(document).ready(function(){

	$("#btSalvar").click(function(){
		var valores = $("#formGrupoMateriais").serialize();
		$.post("gruposMateriais_new", valores, function(data){
			if($.trim(data) == "OK"){
				location.href = "gruposMateriais";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "gruposMateriais";
	});
	
	$(".edit").click(function(){
		location.href = "gruposMateriais_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("gruposMateriais_new?delete=1&id=" + $(this).attr('id'), function(data){
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