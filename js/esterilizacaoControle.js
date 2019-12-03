$(document).ready(function(){

	$('.tipo_carga').click(function(ev){
		$('.tipo_carga').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#tipo_carga').val(ev.target.getAttribute('name'))
	})

	$('.tipo_carga_expurgo').click(function(ev){
		$('.tipo_carga_expurgo').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#tipo_carga_expurgo').val(ev.target.getAttribute('name'))
	})

	$('.contem_implantes').click(function(ev){
		$('.contem_implantes').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#contem_implantes').val(ev.target.getAttribute('id'))
	})

	$('.liberado').click(function(ev){
		$('.liberado').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#liberado').val(ev.target.getAttribute('id'))
	})

	$('.resultado').click(function(ev){
		$('.resultado').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#resultado').val(ev.target.getAttribute('id'))
	})
	
	$('#slEEsterilizacao').val($('#equipamento_id').val());
	$('.btn-procurar').click(function(){
		
		var search_data     = $('#search_data').val().split('/').reverse().join('-');
		var combo_equip_val = $('#slEEsterilizacao').val();

		if(search_data){
			window.location = "esterilizacoesControle?search_data="+search_data+"&combo_equip_val="+combo_equip_val;
		}
		
	})

	$('.js_abrir_esterilizacao').click(function(ev){
		ev.preventDefault
		var equipment = this.getAttribute('data-equip');
		var equipment_id = this.getAttribute('data-equipid');
		var lote = this.getAttribute('data-lote');
		var data = this.getAttribute('data-date');

		if(equipment && lote && data){
			window.location = "esterilizacoesControle_esterilizacao?data="+data+"&equipment_id="+equipment_id+"&lote="+lote+"&equipment="+equipment;
		}
	})

	$('.js_abrir_expurgo').click(function(ev){
		ev.preventDefault
		var equipment = this.getAttribute('data-equip');
		var equipment_id = this.getAttribute('data-equipid');
		var lote = this.getAttribute('data-lote');
		var data = this.getAttribute('data-date');


		if(equipment && lote && data){
			window.location = "esterilizacoesControle_expurgo?data="+data+"&equipment_id="+equipment_id+"&lote="+lote+"&equipment="+equipment;
		}
	})

	$('#save').click(function(){

		var tipo_carga, contem_implantes, liberado, resultado, tipo_carga_expurgo;

		$.each($('.tipo_carga'), function (k,v){
			if (v.checked == true)
			{
				tipo_carga = v.getAttribute('name')
			}
			
		})

		$.each($('.tipo_carga_expurgo'), function (k,v){
			if (v.checked == true)
			{
				tipo_carga_expurgo = v.getAttribute('name')
			}
			
		})

		$.each($('.contem_implantes'), function (k,v){
			if (v.checked == true)
			{
				contem_implantes = v.getAttribute('id')
			}
			
		})
		
		$.each($('.liberado'), function (k,v){
			if (v.checked == true)
			{
				liberado = v.getAttribute('id')
			}
			
		})

		$.each($('.resultado'), function (k,v){
			if (v.checked == true)
			{
				resultado = v.getAttribute('id')
			}
			
		})
	
		var date = $('#data').val().split('/').reverse().join('-')
		var data = {
			lote: $('#lote').text(),
			equipment: $('#equipment').text(),
			tipo_carga: tipo_carga,
			tipo_carga_expurgo: tipo_carga_expurgo,
			date: date,
			contem_implantes: contem_implantes,
			liberado: liberado,
			resultado: resultado,
			temperatura: $('#temperatura').val(),
			ini_ciclo: $('#ini_ciclo').val(),
			final_ciclo: $('#final_ciclo').val(),
			resp_leitura_final: $('#resp_leitura_final').val(),
			horario_retirada: $('#horario_retirada').val(),
			leitura_resultado: $('#leitura_resultado').val(),
			resp_retirada: $('#resp_retirada').val(),
			resp_incubacao: $('#resp_incubacao').val(),
			incub_data: $('#incub_data').val(),
			incub_horario: $('#incub_horario').val(),
			resp_leitura_final_last: $('#resp_leitura_final_last').val()
		}

	
		$.post('esterilizacoesControle_new', {action: 'setInfoLote', data:data }, function(data){
			//console.log(data);
		})

		$('#save_msg').fadeIn();

		setTimeout(function(){
			$('#save_msg').fadeOut(800);
		}, 1500)
		//window.print();
	})

	$('#print').click(function(){

		var tipo_carga, contem_implantes, liberado, resultado, tipo_carga_expurgo;

		$.each($('.tipo_carga'), function (k,v){
			if (v.checked == true)
			{
				tipo_carga = v.getAttribute('name')
			}
			
		})

		$.each($('.tipo_carga_expurgo'), function (k,v){
			if (v.checked == true)
			{
				tipo_carga_expurgo = v.getAttribute('name')
			}
			
		})

		$.each($('.contem_implantes'), function (k,v){
			if (v.checked == true)
			{
				contem_implantes = v.getAttribute('id')
			}
			
		})
		
		$.each($('.liberado'), function (k,v){
			if (v.checked == true)
			{
				liberado = v.getAttribute('id')
			}
			
		})

		$.each($('.resultado'), function (k,v){
			if (v.checked == true)
			{
				resultado = v.getAttribute('id')
			}
			
		})
	
		var date = $('#data').val().split('/').reverse().join('-')
		var data = {
			lote: $('#lote').text(),
			equipment: $('#equipment').text(),
			tipo_carga: tipo_carga,
			tipo_carga_expurgo: tipo_carga_expurgo,
			date: date,
			contem_implantes: contem_implantes,
			liberado: liberado,
			resultado: resultado,
			temperatura: $('#temperatura').val(),
			ini_ciclo: $('#ini_ciclo').val(),
			final_ciclo: $('#final_ciclo').val(),
			resp_leitura_final: $('#resp_leitura_final').val(),
			horario_retirada: $('#horario_retirada').val(),
			leitura_resultado: $('#leitura_resultado').val(),
			resp_retirada: $('#resp_retirada').val(),
			resp_incubacao: $('#resp_incubacao').val(),
			incub_data: $('#incub_data').val(),
			incub_horario: $('#incub_horario').val(),
			resp_leitura_final_last: $('#resp_leitura_final_last').val()
		}

	
		$.post('esterilizacoesControle_new', {action: 'setInfoLote', data:data }, function(data){
			//console.log(data);
		})

		window.print();
	})

	$('.back').click(function(){
		window.history.back()
	})

	function getInfoLoteData(){
		var info = {
			lote: $('#lote').text(),
			date: $('#data').val().split('/').reverse().join('-'),
			equipment:$('#equipment').text()
		}

		$.post('esterilizacoesControle_new', {action: 'getInfoLote', info:info}, function(data){
			
			data = $.trim(data);
			data = JSON.parse(data);
			console.log(data.tipo_carga_expurgo);
			$('#temperatura').val(data.temperatura)
			$('#ini_ciclo').val(data.ini_ciclo)
			$('#final_ciclo').val(data.final_ciclo)
			$('#resp_leitura_final').val(data.resp_leitura_final)
			$('#horario_retirada').val(data.horario_retirada)
			$('#leitura_resultado').val(data.leitura_resultado)
			$('#resp_retirada').val(data.resp_retirada)
			$('#resp_incubacao').val(data.resp_incubacao)
			$('#incub_data').val(data.incub_data)
			$('#incub_horario').val(data.incub_horario)
			$('#resp_leitura_final_last').val(data.resp_leitura_final_last)
			data.tipo_carga == '' ? '' : $("input[name="+data.tipo_carga.toLowerCase()+"]")[0].checked = true
			data.contem_implantes == '' ? '' : $("#"+data.contem_implantes.toLowerCase()+"")[0].checked = true
			data.liberado == '' ? '' : $("#"+data.liberado.toLowerCase()+"")[0].checked = true
			data.resultado == '' ? '' : $("#"+data.resultado.toLowerCase()+"")[0].checked = true
			data.tipo_carga_expurgo == '' ? '' : $("input[name="+data.tipo_carga_expurgo.toLowerCase()+"]")[0].checked = true
			
		})
	}

	var local = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];

	if(local == 'esterilizacoesControle_esterilizacao' || local == 'esterilizacoesControle_expurgo'){
		getInfoLoteData()
	}
	
	
})