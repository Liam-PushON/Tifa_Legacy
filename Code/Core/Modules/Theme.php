<?php

class Theme{
	public $theme, $package, $fallback_theme, $fallback_package;

	function __construct() {
		$this->init();
	}
	function init() {
		if(Tifa::$settings->cache->enabled == 'false'){
			Tifa::$settings->design->theme = $this->theme = Tifa::$database->query('SELECT * FROM config WHERE `key`="theme"')[0]['value'];
			Tifa::$settings->design->package = $this->package = Tifa::$database->query('SELECT * FROM config WHERE `key`="package"')[0]['value'];
			Tifa::$settings->design->fallback->theme = $this->fallback_theme = Tifa::$database->query('SELECT * FROM config WHERE `key`="fallback_theme"')[0]['value'];
			Tifa::$settings->design->fallback->package = $this->fallback_package = Tifa::$database->query('SELECT * FROM config WHERE `key`="fallback_package"')[0]['value'];
			Tifa::$settings->saveXML('config/settings.xml');
		}else{
			$this->theme = Tifa::$settings->design->theme;
			$this->package = Tifa::$settings->design->package;
			$this->fallback_theme = Tifa::$settings->design->fallback->theme;
			$this->fallback_package = Tifa::$settings->design->fallback->package;
		}
	}

	function findResource($resource, $type, $args = false) {
		$themes = array($this->theme, $this->fallback_theme, 'tifa');
		$packages = array($this->package, $this->fallback_package, 'default');
		if($args && Tifa::$config->design->enable_internal_override == 'true'){
			if($args['override_theme']){
				$themes = array($args['override_theme'], $this->theme, $this->fallback_theme, 'tifa');
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
		$themes = array($this->theme, $this->fallback_theme, 'tifa');
		$packages = array($this->package, $this->fallback_package, 'default');
		if($args && Tifa::$layout->design->enable_internal_override == 'true'){
			if($args['override_theme']){
				$themes = array($args['override_theme'], $this->theme, $this->fallback_theme, 'tifa');
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