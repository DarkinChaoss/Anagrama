$(document).ready(function(){

	$('.tipo_carga').click(function(ev){
		$('.tipo_carga').each(function(ev){
			this.checked = false
		})
		ev.target.checked = true
		$('#tipo_carga').val(ev.target.getAttribute('name'))
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

	$('#print').click(function(){

		var date = $('#data').val().split('/').reverse().join('-')
		var data = {
			lote: $('#lote').text(),
			equipment: $('#equipment').text(),
			tipo_carga: $('#tipo_carga').val(),
			date: date,
			contem_implantes: $('#contem_implantes').val(),
			liberado: $('#liberado').val(),
			resultado: $('#resultado').val(),
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
		data = JSON.stringify(data);
		$.post('esterilizacoesControle_new', {action: 'setInfoLote', data:data }, function(data){})
		window.print();
	})

	$('.back').click(function(){
		window.history.back()
	})

	function getInfoLoteData(){
		var info = {
			lote: $('#lote').text(),
			equipment: $('#equipment').text(),
			date: $('#data').val().split('/').reverse().join('-')
		}

		info = JSON.stringify(info);

		$.post('esterilizacoesControle_new', {action: 'getInfoLote', info:info}, function(data){
			data = $.trim(data);
			data = JSON.parse(data);
			console.log(data);
			$('#temperatura').val(data.infl_temperatura)
			$('#ini_ciclo').val(data.infl_ini_ciclo)
			$('#final_ciclo').val(data.infl_final_ciclo)
			$("input[name="+data.infl_tipo_carga.toLowerCase()+"]")[0].checked = true
			console.log($("#"+data.infl_contem_implantes.toLowerCase()+"")[0].checked = true)

		})
	}

	var local = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];

	if(local == 'esterilizacoesControle_esterilizacao' || local == 'esterilizacoesControle_expurgo'){
		getInfoLoteData()
	}
	
	
})