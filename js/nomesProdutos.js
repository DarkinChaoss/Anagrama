$(document).ready(function(){
	var pathname = window.location.pathname.split('/')[2];

	window.onbeforeunload = function(){
		if(pathname == 'nomesProdutos_new'){
			$.post('nomesProdutos_new', {acao:'cleanDataBase'})
		}
	}

	var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
	        }
   	 	}
	};

	$('.pro_img').click(function(){
		$('.pro_img_large').show();
	})

	$('#cropImageBtn').click(function(){
		var attrb = document.querySelector('#thumb').getAttribute('src')
		var largeimgsrc = document.querySelector('.pro_img_large img').getAttribute('src');
		
		document.querySelector('#thumb').setAttribute('src', '');
		document.querySelector('.pro_img_large img').setAttribute('src', '');

		setTimeout(function(){
			document.querySelector('#thumb').setAttribute('src', attrb);
			document.querySelector('.pro_img_large img').setAttribute('src', largeimgsrc);
		}, 2000)
	})

	$("#new_name").click(function(ev){
		ev.preventDefault
		$.post("nomesProdutos_new", {acao:'insertEmptyRecord'}, function(data){
			data = $.trim(data);
			location.href = 'nomesProdutos_new?new=1&populate=1&id='+ data
		})
	})

	$("#btSalvar").click(function(){

		$.post("nomesProdutos_new", { acao:"repetido", nome:$("#txNome").val(), saving:getUrlParameter('new') }, function(data){

			if($.trim(data) == "REPETIDO"){
				alert("Impossível prosseguir! Este nome de produto já existe.");
			} else {
				var valores = $("#formNomeProduto").serialize();
				$.post("nomesProdutos_new", valores, function(data){
					console.log(data);

					if($.trim(data) == "OK"){
						location.href = "nomesProdutos";
					} else {
						alert("Erro ao efetuar cadastro!");
					}
				});
			}
		});
	});
	
	$("#btCancelar").click(function(){
		$.post("nomesProdutos_new", {acao:'delNotUsed'}, function(data){
			location.href = "nomesProdutos";
		})
	});
	
	$(".edit").click(function(){
		location.href = "nomesProdutos_new?new=0&populate=1&id=" + $(this).attr('id');
	});
	
	$(".delete").click(function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("nomesProdutos_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});

	$('#cancel').click(function(){
		$('.pro_img_large').fadeOut(50);
	})

});


/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/