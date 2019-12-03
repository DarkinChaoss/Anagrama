$(document).ready(function(){

	$(window).unload(function() { 
    	$.post('transferenciaEstoque_new', {cleanUnfinished: $('#tes_id').val()}, function(data){});    
    })

   var idSetorOrigen;
   var modalpassou = false;
   var modalqrcode;
   var paraprocesso;
   var teste;
   var continua ;

	$('#txQrcode').focus();


   $("#btnEscolher").live("click", function(){

		modalpassou= true;
		var select = document.getElementById("slSetorQte");
		var itemSelecionado = select.options[select.selectedIndex].value;
		idSetorOrigen = itemSelecionado;
		$('#modalSetor').modal('hide');
		buscaProduto(modalqrcode);
   });


	$("#btCancelar").click(function(){
		$("#divDescMaterial").hide();
		$("#txQrcode").val("");
		$("#txQrcode").focus();
	});

	var intervalo = 0;
	
	$(".qrcodebusca").on('keyup', function(e){
		$("#slSetorQte option").remove();
		$("#lbProdutoNaoCadastrado").hide();
		$("#lbItemDescartado").hide();
		$("#lbItemNaoPronto").hide();
		$("#lbItemValidadeExp").hide();
		$("#lbItemNaoAutorizado").hide();
		$("#lbTransferenciaNaoCadastrada").hide();
		$("#lbItenNaoCadastrado").hide();
		$("#lbItemFNaoCadastrado").hide();

		//verificar tamanho do qrcode
		if($("#txQrcode").val().length > 0){

			//alert('maior q 6');
			// intervalo atE executar a funcao
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {

				var qr = $("#txQrcode").val().toUpperCase();
				var qrold = $("#txQrcode").val().toUpperCase();

				var qrcodenew = $("#txQrcode").val().toUpperCase().split('.');


				if (qrcodenew.length == 1 ){

					$('table tr').each(function(){
						var name = $(this).find('.qrcode').html();
						if(name == qr){	

							alert('Produto já se encontra nesta solicitaçðo');
							document.getElementById('txQrcode').value=''; 
							$("#txQrcode").focus();
							throw new Error("");
						}
						
					});
				}
				

				if (qrcodenew.length >= 3 ){
					
					var qr = qrcodenew[0]+'.'+qrcodenew[1];
					var loteref = qrcodenew[2];
					$.post("transferenciaEstoque_new",{acao:'Verificatrans', qrcode:qr, reflote:loteref}, function(data){
						data = $.trim(data).split("*;*");
						if(data[0] == '0'){
							alert('produto esgotado para este lote');
							continua = false;
							document.getElementById('txQrcode').value=''; 
						}
						else{
							if(modalpassou == false){
								modalqrcode = qrold;
								document.getElementById('txQrcode').value='';
								
								$.post("transferenciaEstoque_new",{acao:'getQte', qrcode:qr, reflote:loteref,setor:idSetorOrigen, tesID: $('#tes_id').val()}, function(data){

									if(newqte == '404'){
										alert('produto esgotado para este lote');

										throw new Error("");
									}
									
								});

								$.post("transferenciaEstoque_new",{acao:'getcombo', qrcode:qr, reflote:loteref}, function(data){
									console.log(data);
									option = JSON.parse(data);
									for( var k in option) {
										$('#slSetorQte').append('<option value="'+ option[k]['idsetor']+'">' + option[k]['nome'] + '</option>');
									}
									
								});
								$('#modalSetor').modal('show');
								throw new Error("");
								
							}
							else{
								buscaProduto($("#txQrcode").val());
								throw new Error("");
								
							}

							var idsetor = idSetorOrigen;
						

							$('table tr').each(function(){
								var name = $(this).find('.qrcode').html();
								if(name == qrold){	
									$.post("transferenciaEstoque_new",{acao:'getQte', qrcode:qr, reflote:loteref,setor:idsetor, tesID: $('#tes_id').val()}, function(data){
										newqte = JSON.parse(data);

										id = "#"+newqte['id']+loteref;
										qte = $(''+id+'').html();

										if(parseInt(newqte['qte']) > qte ){
											$(''+id+'').html('');
											$(''+id+'').append(parseInt(qte)+1);
											
										}
										else{
											alert('Quantidade disponivel nesse lote está esgotada');
											
										}
									
									});
									document.getElementById('txQrcode').value=''; 
									$("#txQrcode").focus();
									throw new Error("");
								}
								
							});
						
						}
								
					});	
				}
				else{

					if (qrcodenew.length == 2 ){
						alert('Este produto nðo foi esterelizado');
						document.getElementById('txQrcode').value=''; 
						$("#txQrcode").focus();
						throw new Error("");
					}
						
						
						$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:qr, reflote:loteref,setor:idSetorOrigen, tesID: $('#tes_id').val()}, function(data){
							if ($.trim(data) != "ERRO" && $.trim(data) != "ERROII" && $.trim(data) != "ERROIII" && $.trim(data) != "ERROIV" && $.trim(data) != "ERROV"
								&& $.trim(data) != "ERROVI" && $.trim(data) != "ERROVII" && $.trim(data) != "ERROVIII" && $.trim(data) != "ERROIX" && $.trim(data) != "PRODNOT"){
								data = $.trim(data);
								//alert(data);
								//console.log('info: ' + data);
								//alert("correu tudo bem até aqui!!! \n tes_id="+data);
								$('#tes_id').val(data);
								$("#txQrcode").val('');
								$("#txQrcode").focus();
								//chamar popula itens...
								$.post('transferenciaEstoque_new',{acao:'listarItens', reflote:loteref, qrcode:qr,tesID: $('#tes_id').val()}, function(data){
									//alert(data);
									//console.log('lista: ' + data);

								
								  var input, filter, table, tr, td, i, txtValue;
								  input = qr;
								  filter = input.toUpperCase();
								  table = document.getElementsByClassName("table");
								  tr = table[0].getElementsByTagName("tr");

								  var x = true;
									for (i = 0; i < tr.length; i++) {
									    td = tr[i].getElementsByTagName("td")[0];
									    if (td) {
									      txtValue = td.innerHTML || td.innerText;
									      if (txtValue.toUpperCase().indexOf(filter) > -1) {
									        	var x = false;
									      } else {

									        
									      }
									    }
									  }
									
									if(x){
											console.log(data);
											//$('#lista_itensSE').empty();
											$('#lista_itensSE').append(data);
											$('.filhocomposto').hide();
									}

								});
							} else if ($.trim(data) == "ERRO"){
								$("#lbProdutoNaoCadastrado").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
								//$("#divDescMaterial").hide();
							} else if ($.trim(data) == "ERROII"){
								$("#lbItemDescartado").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
								//$("#divDescMaterial").hide();
							}else if ($.trim(data) == "ERROIII"){
								$("#lbItemNaoPronto").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
								//$("#divDescMaterial").hide();
							}else if ($.trim(data) == "ERROIV"){
								$("#lbItemValidadeExp").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
								//$("#divDescMaterial").hide();
							}else if ($.trim(data) == "ERROV"){
								$("#lbItemNaoAutorizado").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
							}else if ($.trim(data) == "ERROVI"){
								alert('ERRO INESRPERADO! \n CONTACTE O SUPORTE.');
							}else if ($.trim(data) == "ERROVII"){
								$("#lbTransferenciaNaoCadastrada").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
							}else if ($.trim(data) == "ERROVIII"){
								$("#lbItenNaoCadastrado").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
							}else if ($.trim(data) == "ERROIX"){
								$("#lbItemFNaoCadastrado").show();
								$("#txQrcode").val('');
								$("#txQrcode").focus();
							
						}else if ($.trim(data) == "PRODNOT"){

							alert('Produto não pode ser inserido');
							$("#txQrcode").val('');
							$("#txQrcode").focus();
						}
						});	
				}
			}, 700);
		}
	});
	
	
	$("#btAdicionar").live("click", function(){
		var qr = $("#txQrcode").val();

		$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:qr, tesID: $('#tes_id').val()}, function(data){
			if ($.trim(data) != "ERRO" && $.trim(data) != "ERROII" && $.trim(data) != "ERROIII" && $.trim(data) != "ERROIV" && $.trim(data) != "ERROV"
				&& $.trim(data) != "ERROVI" && $.trim(data) != "ERROVII" && $.trim(data) != "ERROVIII" && $.trim(data) != "ERROIX"){

				console.log(data)
				data = $.trim(data);
				//alert("correu tudo bem até aqui!!! \n tes_id="+data);
				$('#tes_id').val(data);
				$("#txQrcode").val('');
				$("#txQrcode").focus();
				//chamar popula itens...
				$.post('transferenciaEstoque_new',{acao:'listarItens',tesID: $('#tes_id').val()}, function(data){
					//alert(data);
					$('#lista_itensSE').html(data);
					$("#telaInsereProdQtde").modal('hide');
				});
			} else if ($.trim(data) == "ERRO"){
				$("#lbProdutoNaoCadastrado").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
				//$("#divDescMaterial").hide();
			} else if ($.trim(data) == "ERROII"){
				$("#lbItemDescartado").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
				//$("#divDescMaterial").hide();
			}else if ($.trim(data) == "ERROIII"){
				$("#lbItemNaoPronto").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
				//$("#divDescMaterial").hide();
			}else if ($.trim(data) == "ERROIV"){
				$("#lbItemValidadeExp").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
				//$("#divDescMaterial").hide();
			}else if ($.trim(data) == "ERROV"){
				$("#lbItemNaoAutorizado").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
			}else if ($.trim(data) == "ERROVI"){
				alert('ERRO INESRPERADO! \n CONTACTE O SUPORTE.');
			}else if ($.trim(data) == "ERROVII"){
				$("#lbTransferenciaNaoCadastrada").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
			}else if ($.trim(data) == "ERROVIII"){
				$("#lbItenNaoCadastrado").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
			}else if ($.trim(data) == "ERROIX"){
				$("#lbItemFNaoCadastrado").show();
				$("#txQrcode").val('');
				$("#txQrcode").focus();
			}
		});
		
	});

	function buscaProduto(qrcode){
		$("#lbProdutoNaoCadastrado").hide();
		$("#lbItemDescartado").hide();
		$("#lbItemNaoPronto").hide();
		$("#lbItemValidadeExp").hide();
		$("#lbItemNaoAutorizado").hide();
		$("#lbTransferenciaNaoCadastrada").hide();
		$("#lbItenNaoCadastrado").hide();
		$("#lbItemFNaoCadastrado").hide();
		//verificar tamanho do qrcode
		if(qrcode.length > 0){
			
			//alert('maior q 6');
			// intervalo atE executar a funcao
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {

				var qr = qrcode.toUpperCase()
				var qrold = qrcode.toUpperCase()

				var qrcodenew = qrcode.split('.');

				if (qrcodenew.length >= 3 ){

					var qr = qrcodenew[0]+'.'+qrcodenew[1];
					var loteref = qrcodenew[2];				
				

					$('table tr').each(function(){
						var name = $(this).find('.qrcode').html();
						if(name == qrold){	
							$.post("transferenciaEstoque_new",{acao:'getQte', qrcode:qr, reflote:loteref,setor:idSetorOrigen, tesID: $('#tes_id').val()}, function(data){
								newqte = JSON.parse(data);

								id = "#"+newqte['id']+loteref;
								qte = $(''+id+'').html();

								if(parseInt(newqte['qte']) > qte ){
									$(''+id+'').html('');
									$(''+id+'').append(parseInt(qte)+1);
									
								}
								else{
									alert('Quantidade disponivel nesse lote está esgotada');
									
								}
							
							});
							document.getElementById('txQrcode').value=''; 
							$("#txQrcode").focus();
							throw new Error("");
						}
						
					});
				}
				
				$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:qr, reflote:loteref,setor:idSetorOrigen, tesID: $('#tes_id').val()}, function(data){
					if ($.trim(data) != "ERRO" && $.trim(data) != "ERROII" && $.trim(data) != "ERROIII" && $.trim(data) != "ERROIV" && $.trim(data) != "ERROV"
						&& $.trim(data) != "ERROVI" && $.trim(data) != "ERROVII" && $.trim(data) != "ERROVIII" && $.trim(data) != "ERROIX"){
						data = $.trim(data);
						//alert("correu tudo bem até aqui!!! \n tes_id="+data);
						$('#tes_id').val(data);
						$("#txQrcode").val('');
						$("#txQrcode").focus();
						//chamar popula itens...
						$.post('transferenciaEstoque_new',{acao:'listarItens', reflote:loteref, qrcode:qr,tesID: $('#tes_id').val()}, function(data){
							//alert(data);
							
							console.log('lista2: ' + data);

							//$('#lista_itensSE').empty()  //Nailson verificar pois segundo o cleverson alumas vezes pra produto composto da erro
							$('#lista_itensSE').append(data);
							$('.filhocomposto').hide();
						});
					} else if ($.trim(data) == "ERRO"){
						$("#lbProdutoNaoCadastrado").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
						//$("#divDescMaterial").hide();
					} else if ($.trim(data) == "ERROII"){
						$("#lbItemDescartado").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
						//$("#divDescMaterial").hide();
					}else if ($.trim(data) == "ERROIII"){
						$("#lbItemNaoPronto").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
						//$("#divDescMaterial").hide();
					}else if ($.trim(data) == "ERROIV"){
						$("#lbItemValidadeExp").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
						//$("#divDescMaterial").hide();
					}else if ($.trim(data) == "ERROV"){
						$("#lbItemNaoAutorizado").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
					}else if ($.trim(data) == "ERROVI"){
						alert('ERRO INESRPERADO! \n CONTACTE O SUPORTE.');
					}else if ($.trim(data) == "ERROVII"){
						$("#lbTransferenciaNaoCadastrada").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
					}else if ($.trim(data) == "ERROVIII"){
						$("#lbItenNaoCadastrado").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
					}else if ($.trim(data) == "ERROIX"){
						$("#lbItemFNaoCadastrado").show();
						$("#txQrcode").val('');
						$("#txQrcode").focus();
					}
				});				
			}, 700);
		}
	}
	
	$(".delete").live("click",function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("transferenciaEstoque_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});

	$(".edit").live("click",function(){

		  qrcode = $(this).attr('value');
		  qrold = $(this).attr('value');//qrcode com ponto
		  idprod  = $(this).attr('id');//id do produto

		  
		  var qrcodenew = qrcode.split('.');
		  var qr = qrcodenew[0]+'.'+qrcodenew[1];
		  var loteref = qr[2];	

		  $('table tr').each(function(){
				var name = $(this).find('.qrcode').html();
				if(name == qrold){	

					id = "#"+idprod;
					qte = $(''+id+'').html();
					if (qte > 1){
						$(''+id+'').html('');
						$(''+id+'').append(parseInt(qte)-1);
					}
					else{
						alert('Produto já está na quantidade minima');
					}
				}
			});
	
	});
	
	$('#transferirestoque').click(function(){
		
		if($('#tes_id').val() > 0){
			if ($('#slSetor').val() > 0){
				
			
				
				if($('#txtRetiradoPor').val().length > 2){
					if(confirm('Todos os Itens da lista serão transferidos de setor, deseja continuar?')){

						$('table tr').each(function(){
							var qrcode = $(this).find('.qrcode').html();
							if (qrcode != null){
								if(qrcode.split('.').length >= 2){
									dados = $(this).find('.qte').attr('value');
									qte = $(this).find('.qte').html();
									if (dados != null){
										if (parseInt (qte) >= 1){
											pro_id = dados.split('.');
											$.post('transferenciaEstoque_new',{acao: 'updateqte', tesID: $('#tes_id').val(),pro_id:pro_id[0], loteref:pro_id[1], qte:qte , setor:idSetorOrigen, saida:$('#tes_id').val() },function(data){
												if (data >0)
												{}
											});
										}
										
									}
								}
							}							
								
						});

						$.post('transferenciaEstoque_new',{acao: 'transferir', tesID: $('#tes_id').val(),setID:$('#slSetor').val(), retiradoPor: $('#txtRetiradoPor').val()},function(data){
							data = $.trim(data);
							
							if(data == 'OK'){
								alert('Transferência realizada com sucesso!');
								location.reload();
							}else{
								alert('Não foi possível concluir tranferencia, contacte o suporte!');
							} 
						});
					}
				 	}else{
						alert('Por favor informe quem está retirando os produtos!');
						$('#txtRetiradoPor').focus();
				 	}

			}else{
				alert('É necessário escolher um setor!');
			}
		}else{
			alert('Primeiro encontre os itens a serem transferidos, após escolha um setor e os transfeira.');
			$('#txQrcode').focus();
		}
	});

});
