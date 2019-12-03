<?php 

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'setInfoLote'){

	$data = (object) $_REQUEST['data'];
	print_r(InfoLoteController::insert($data));
	exit;	
}


if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getInfoLote'){

	$data = $_REQUEST['info'];
	$response = InfoLoteController::getInfoLote($data);

	print_r(json_encode($response));
}
