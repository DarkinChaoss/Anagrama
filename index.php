<?php
	require "view/helper/config.php";
	require "env.php";
	
	function __autoload($classname){
		if(file_exists("view/" . $classname . ".php")){
			require_once "view/" . $classname . ".php";
		}
		elseif(file_exists("controller/" . $classname . ".php")){
			require_once "controller/" . $classname . ".php";
		}
		elseif(file_exists("model/" . $classname . ".php")){
			require_once "model/" . $classname . ".php";
		}
		elseif(file_exists("classes/" . $classname . ".php")){
			require_once "classes/" . $classname . ".php";
		}
		elseif(file_exists("view/helper/" . $classname . ".php")){
			require_once "view/helper/" . $classname . ".php";
		}
		elseif(file_exists("view/relatorios/" . $classname . ".php")){
			require_once "view/relatorios/" . $classname . ".php";
		}
	}
	//print_r($_GET);
	
	$_GET['module'] = (isset($_GET['module'])) ? $_GET['module'] : 'home';
	
	if(file_exists("view/" . $_GET['module'] . ".php")) {
		require "view/" . $_GET['module'] . ".php";
	} elseif(file_exists("view/helper/" . $_GET['module'] . ".php")) {
		require "view/helper/" . $_GET['module'] . ".php";
	} elseif(file_exists("view/relatorios/" . $_GET['module'] . ".php")) {
		require "view/relatorios/" . $_GET['module'] . ".php";

	} elseif(file_exists("view/partials/" . $_GET['module'] . ".php")) {
		require "view/partials/" . $_GET['module'] . ".php";
	}else {
		require "view/404.php";
	}
?>