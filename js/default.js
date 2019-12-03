$(document).ready(function(){
	
	$("#btEntrar").click(function(){
		var valores = $("#formLogin").serialize();
		$.post("login", valores, function(data){
			if($.trim(data) == "ERRO"){
				alert("Erro de autenticação!\n\nVerifique login e senha.");
			} else {
				location.href = "./";
			}
			
		});
	});
	
	$("#txLogin").keypress(function(e){
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			$("#btEntrar").click();
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});
	
	$("#txSenha").keypress(function(e){
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			$("#btEntrar").click();
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});
	
	$("#trocarUsuario").click(function(){
		$.post("login", { logout:1 }, function(){
			location.href = "home";
		});
	});
	
	$(".pula").keypress(function(e){
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			var campo =  $(".pula:visible");
			var indice = campo.index(this);
			if(campo[indice+1] != null){
				var proximo = campo[indice + 1];
				proximo.focus();
				proximo.select();
			}
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});
	
	$(".vaivolta").keypress(function(e){
		/*
		 * Verifica se o evento é Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			var campo =  $(".vaivolta:visible");
			var indice = campo.index(this);
			if(campo[indice+1] != null){
				var proximo = campo[indice + 1];
				proximo.focus();
				proximo.select();
			} else if(campo[indice-1] != null){
				var anterior = campo[indice - 1];
				anterior.focus();
				anterior.select();
			}
			// impede o sumbit caso esteja dentro de um form
			e.preventDefault(e);
			return false;
		}
	});
	
	
	$(".avoidEnter").keypress(function(e){
		/*
		 * Verifica se o evento ? Keycode (IE e outros)
		 * Se n?o for, pega o evento Which (Firefox)
		*/
		var tecla = (e.keyCode?e.keyCode:e.which);
		if(tecla == 13){
			// impede o pressionar da tecla
			e.preventDefault(e);
			return false;
		}
	});
	
});

function loadingShow(){
	$('#loading').removeClass('hide').show();
}

function loadingHide(){
	$('#loading').addClass('hide').hide();
}

function fecharJanela(){
	if(confirm("Deseja mesmo sair do sistema?")) {
		$.post("login", { logout:1 }, function(){
			ww = window.open(window.location, "_self");
			ww.close();
		});
	}
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/