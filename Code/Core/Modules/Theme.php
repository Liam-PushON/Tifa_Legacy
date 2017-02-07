<?php

class Theme{

	public $theme, $package, $fallback_theme, $fallback_package;

	function __construct() {
		$this->init();
	}

	function init() {
		if(Core::$settings->cache->enabled == 'false'){
			Core::$settings->design->theme = $this->theme = Core::$database->query('SELECT * FROM config WHERE `key`="theme"')[0]['value'];
			Core::$settings->design->package = $this->package = Core::$database->query('SELECT * FROM config WHERE `key`="package"')[0]['value'];
			Core::$settings->design->fallback->theme = $this->fallback_theme = Core::$database->query('SELECT * FROM config WHERE `key`="fallback_theme"')[0]['value'];
			Core::$settings->design->fallback->package = $this->fallback_package = Core::$database->query('SELECT * FROM config WHERE `key`="fallback_package"')[0]['value'];
			Core::$settings->saveXML('config/settings.xml');
		}else{
			$this->theme = Core::$settings->design->theme;
			$this->package = Core::$settings->design->package;
			$this->fallback_theme = Core::$settings->design->fallback->theme;
			$this->fallback_package = Core::$settings->design->fallback->package;
		}
	}

	function findResource($resource, $type, $args = false) {
		$themes = array($this->theme, $this->fallback_theme, 'base');
		$packages = array($this->package, $this->fallback_package, 'default');
		if($args && Core::$layout->design->enable_internal_override == 'true'){
			if($args['override_theme']){
				$themes = array($args['override_theme'], $this->theme, $this->fallback_theme, 'base');
			}
			if($args['override_theme']){
				$packages = array($args['override_package'], $this->package, $this->fallback_package, 'default');
			}
		}
		foreach($themes as $theme){
			foreach($packages as $package){
				$path = $type.'/'.$theme.'/'.$package.'/'.$resource;
				if(file_exists($path)){
					return $path;
				}
			}
		}
		return false;
	}

	function getResourcePriority($resource, $type, $args = false){
		$themes = array($this->theme, $this->fallback_theme, 'base');
		$packages = array($this->package, $this->fallback_package, 'default');
		if($args && Core::$layout->design->enable_internal_override == 'true'){
			if($args['override_theme']){
				$themes = array($args['override_theme'], $this->theme, $this->fallback_theme, 'base');
			}
			if($args['override_theme']){
				$packages = array($args['override_package'], $this->package, $this->fallback_package, 'default');
			}
		}
		$t = 0; $p = 0;
		foreach($themes as $theme){
			$p = 0;
			foreach($packages as $package){
				$path = $type.'/'.$theme.'/'.$package.'/'.$resource;
				if(file_exists($path)){
					return $t.$p;
				}
				$p++;
			}
			$t++;
		}
		return 99;
	}

}

?>