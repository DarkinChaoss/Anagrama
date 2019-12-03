$(document).ready(function(){
	
	
	$("#btPrintPQ").click(function(){
		$("#divPrintP").printElement();
	});
	
	$("#btPrint").click(function(){
		/*var url = window.location.href;
		var url_limpia = url.replace("#","");
		$( location ).attr("href", url_limpia);*/
		$("#divPrint").printElement();
		
		
		/*if(c1 == -1){
			
		alert("Por favor selecione o tipo de relatório, Reduzido o Completo");
		}else{
			
			$("#divPrint").printElement();
		}	*/
	  
	   
		
	});
	
	$("#btPrintcomposto").click(function(){
		$("#containerdivPrintcomposto").printElement();
	});

});


