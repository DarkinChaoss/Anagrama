$(document).ready(function(){
	
	$("#tlaAdicionarContato").on('shown', function(){
		if($("#txId").val() == "") { // salva setor caso ainda não esteja salvo
			var valores = $("#formSetor").serialize();
			$.post("setores_new", valores, function(data){
				if($.trim(data) != "ERRO"){
					$("#txId").val(data);
				} else {
					alert("Erro na conexão! Tente novamente.");
				}
			});
		}
	});
	
	$("#btAdicionarContato").click(function(){
		$("#txIdContato").val("");
		$("#txNomeContato").val("");
		$("#txEmailContato").val("");
		$("#txTelefoneContato").val("");
	});
	
	$("#btSalvarContato").click(function(){
		var valores = $("#formAdicionarContato").serialize() + "&idsetor=" + $("#txId").val();
		$.post("contatos_new", valores, function(data){
			if($.trim(data) == "OK"){
				listaContatos();
				$("#tlaAdicionarContato").modal('hide');
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});
	
	$(".marcarContato").live("click", function(){
		$.get("contatos_new?marcar=1&id=" + $(this).val(), function(){
			//ok
		});
	});
	
});

function listaContatos() {
	$.get("contatos_new?setor=" + $("#txId").val(), function(data){
		$("#contatosSetor").html(data);
	});
}

function editarContato(id) {
	$.get("contatos_new?populate=1&id=" + id, function(data){
		$("#tlaAdicionarContato").modal('show');
		var aux = $.trim(data).split(";");
		$("#txIdContato").val(aux[0]);
		$("#txNomeContato").val(aux[1]);
		$("#txEmailContato").val(aux[2]);
		$("#txTelefoneContato").val(aux[3]);
		
	});
}

function apagarContato(id) {
	if(confirm("Deseja mesmo apagar este contato?")) {
		$.get("contatos_new?delete=1&id=" + id, function(data){
			if($.trim(data) == "OK") {
				$("#contatoPai" + id).slideUp();
				//$("#principal" + id).slideUp();
			} else {
				alert("Erro ao apagar contato!");
			}
		});
	}
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/