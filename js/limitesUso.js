$(document).ready(function(){

	$('#txQtde').keyup(function(evt) {
		var letters = /^[0-9 ]+$/;
	   if(this.value.match(letters))
	   	return;   
	   else
	     {
	     	console.log("letters");
	     	var val = this.value
	     	val = val.replace(/[^\d.-]/g, '');
	     	$('#txQtde').val(val)
	     return;
	     }
	});
	
	$(".mask_num").keyup(function(){
		var is = $(this).val();
		while(isNaN(is)) {
			is = is.substring(0, is.length-1);
			$(this).val(is);
		}
	});

	$("#btSalvar").click(function(){
		var valores = $("#formLimiteUso").serialize();
		$.post("limitesUso_new", valores, function(data){
			if($.trim(data) == "OK"){
				location.href = "limitesUso";
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$("#btCancelar").click(function(){
		location.href = "limitesUso";
	});
	
	$(".edit").click(function(){
		location.href = "limitesUso_new?populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("limitesUso_new?delete=1&id=" + $(this).attr('id'), function(data){
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