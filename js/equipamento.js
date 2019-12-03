$(document).ready(function(){
	
	$(".mask_num").keyup(function(){
		var is = $(this).val();
		while(isNaN(is)) {
			is = is.substring(0, is.length-1);
			$(this).val(is);
		}
	});

	$("#btSalvar").click(function(){
		var valores = $("#formEquipamento").serialize();
		console.log(valores);
		$.post("equipamento_new", valores, function(data){
			if($.trim(data) == "OK"){
				location.href = "equipamento";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "equipamento";
	});
	
	$(".edit").click(function(){
		location.href = "equipamento_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("equipamento_new?delete=1&id=" + $(this).attr('id'), function(data){
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
 * Desenvolvido por Nailson Israel
 *
 * 
 * Brothers Soluções em T.I. © 2013
*/