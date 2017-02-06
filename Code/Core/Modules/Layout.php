<?php

class Layout {

	function __construct() {
	}

	function build($page, $args = false) {
		$layout = simplexml_load_file(Core::$theme->findResource($page, 'layout', $args));
		$overrides = $this->layoutHasOverride($page, $args);
		if(!$overrides){
			foreach ($layout->layout->block as $block) {
				include(Core::$theme->findResource($block->attributes()->template, 'template'));
			}
		}else{
			foreach ($layout->layout->block as $block) {
				$override = false;

				foreach($overrides as $o){
					if((string)$o[0] == (string)$block->attributes()->name){
						$override = true;
					}
				}
				if($override){
					include(Core::$theme->findResource($o[1], 'template'));
				}else{
					include(Core::$theme->findResource($block->attributes()->template, 'template'));
				}
			}
		}
	}

	function layoutHasOverride($page, $args = false) {
		$layout = simplexml_load_file(Core::$theme->findResource($page, 'layout', $args));
		if(Core::$theme->findResource('override/' . $page, 'layout', $args)){
			$override = simplexml_load_file(Core::$theme->findResource('override/' . $page, 'layout', $args));
		}else{
			return false;
		}
		$overrides = array();
		if (file_exists(Core::$theme->findResource($page, 'layout', $args)) && file_exists(Core::$theme->findResource('override/' . $page, 'layout', $args))) {
			if(Core::$theme->getResourcePriority('override/' . $page, 'layout', $args) <= Core::$theme->getResourcePriority($page, 'layout', $args)){
				foreach($override->override->block as $block){
					array_push($overrides, array($block->attributes()->name, $block->attributes()->template));
				}
			}
		}
		return count($overrides > 0) ? $overrides : false;
	}

}

?>