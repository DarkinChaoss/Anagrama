$(document).ready(function(){

	$("#btSalvar").click(function(){
		if($.trim($("#txCod").val()) == "" || $.trim($("#txNome").val()) == ""){
			alert("Preencha todos os campos para prosseguir!");
		} else {
			$.post("materiaisInternos_new", { acao:"repetido", id:$("#txId").val(), nome:$("#txNome").val() }, function(data){
				if($.trim(data) == "REPETIDO"){
					alert("Impossível prosseguir! Este nome de material interno já existe.");
					$("#txNome").focus();
					$("#txNome").select();
				} else {
					var valores = $("#formMaterialInterno").serialize();
					$.post("materiaisInternos_new", valores, function(data){
						if($.trim(data) == "OK"){
							location.href = "materiaisInternos";
						} else {
							alert("Erro ao efetuar cadastro!");
						}
					});
				}
			});
		}
	});
	
	$("#btCancelar").click(function(){
		location.href = "materiaisInternos";
	});
	
	$(".edit").click(function(){
		location.href = "materiaisInternos_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("materiaisInternos_new?delete=1&id=" + $(this).attr('id'), function(data){
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