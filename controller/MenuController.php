<?php
	class MenuController {
	
		public static function getMenu($where){
			$menu = new MenuModel();
			return $menu->selectAll($where);
		}
		
	}
?>