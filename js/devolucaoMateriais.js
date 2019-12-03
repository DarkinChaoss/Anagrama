$(document).ready(function(){

	$("#btDevolver").click(function(){
		if(confirm("Tem certeza que deseja realizar a devolução deste material?")){
			$.post("devolucaoMateriais_new", {acao:'devolver', idsaida:$("#txIdsaida").val(), idproduto:$("#txIdproduto").val()}, function(data){
				if($.trim(data) != "ERRO"){
					location.reload();
				} else {
					alert("Erro ao efetuar operação! Tente novamente.");
				}
			});
		}
	});
	
	$("#btCancelar").click(function(){
		//$("#divDescMaterial").hide();
		$("#txQrcode").val("");
		$("#txQrcode").focus();
	});
	
	var intervalo = 0;
	//verifica tamanho do qrcode para executar
	$("#txQrcode").keyup(function(){
		$(".divDescMaterial").remove();
		$("#boxQtde").hide();
		if($("#txQrcode").val().length > 0){
			// intervalo até executar a função
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				
				var qr = $("#txQrcode").val();		
				$.post("devolucaoMateriais_new",{acao:'buscar', qrcode:qr}, function(data){
					
					//console.log(data);
					
					if ($.trim(data) == "NAORETIRADO"){
						//$("#divDescMaterial").hide();
						$("#txQrcode").val("");
						$("#txQrcode").focus();
						alert("Este material não foi retirado para uso!\n\nNão há necessidade de devolução.");
					} else if ($.trim(data) == "SEMSAIDA"){
						//$("#divDescMaterial").hide();
						$("#txQrcode").val("");
						$("#txQrcode").focus();
						alert("Impossível realizar a devolução!\n\nNão existe lançamento de retirada desse material.");
					} else if ($.trim(data) != "ERRO"){
						
						data = JSON.parse(data);
						
						
						//$("#divDescMaterial").show();
						//data = $.trim(data);
						//console.log(data[0]);
						if(data[0].total > 1){
							$("#boxQtde").show();
						}
						
						var arr = [];
						
						//console.log(arr);
						
						data.forEach(function(item, index){
							
									
									
									
									$("#wrapper").append(`
									<div class="divDescMaterial">
										<input type="hidden" class="txIdproduto" value="${item.produto}">
										<input type="hidden" class="txIdsaida" value="${item.isa_idsaida}">
										<h4 class="txProduto">${item.infoProduto}</h4>
										<label>Lote: <span class="txLote">${item.lote}</span></label>
										<br>
										<h4 style="font-weight: normal;">Dados da última saída</h4>
										<label>Data: <span class="txDatasaida">${item.isa_data}</span></label>
										<label>Prontuário: <span class="txProntuario">${item.prontuario}</span></label>
										<label>Paciente: <span class="txPaciente">${item.paciente}</span></label>
										<div class="pull-right">
											<a href="#" class="btn btn-primary" class="btDevolver"><i class="icon-arrow-left icon-white"></i> Devolver material</a>
											<a href="#" class="btn btn-danger" class="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
										</div>
										<br>
										<hr>
									</div>
							`	);
							var contar = 0; 

									for(y = 0; y < arr.length; y++){ 
										if(arr[y] == item.isa_idsaida){ 
											contar++; 
										} 

									} 
							arr.push(item.isa_idsaida);
							console.log(contar);
						});
						/*
						$("#disponivel").val(data[7]);
						$("#txIdproduto").val(data[0]);
						$("#txProduto").text(data[1]);
						$("#txLote").text(data[2]);
						$("#txIdsaida").val(data[3]);
						$("#txDatasaida").text(data[4]);
						$("#txProntuario").text(data[5]);
						$("#txPaciente").text(data[6]);
						$("#btDevolver").focus();
						*/
					} else {
						//$("#divDescMaterial").hide();
					}
				});
			}, 150);
		}
	});
	
});

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/