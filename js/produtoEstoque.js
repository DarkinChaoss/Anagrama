/*
 * Weslen Augusto Marconcin
 *21/06/2017
 *Filtro do relatorio de controle de estoque
*/

function filtrarSituacao( $situacao ){
	
	$('.tableLinhas').find('tbody').hide();
	$('.tableLinhas').find('tbody').find("[situacao='"+$situacao+"']").show();			
	
	if( $situacao == 'T' ){
		$('.tableLinhas').find('tbody').show();				
	}
	
}