<?php

class Style {

	private $files = array();

	function __construct() {
		if (Core::$settings->cache->enabled == 'false') {
			$this->init();
			$this->compileCSS();
		}
	}

	function compileCSS() {
		$css = '';
		foreach ($this->files as $file) {
			$css = $css.file_get_contents($file)."\n";
		}
		$loc = Core::$theme->findResource('css/', 'style');
		$style = fopen($loc.'/style.css', 'w');
		fwrite($style, $css);
		fclose($style);
	}


	function init() {
		$loc = Core::$theme->findResource('css/active/', 'style');
		$dir = scandir($loc);
		foreach ($dir as $i) {
			if ($i != '.' && $i != '..') {
				if (is_dir($loc . $i)) {
					$this->searchDirectory($loc . $i);
				} else {
					array_push($this->files, $loc . $i);
				}
			}
		}
	}
	function searchDirectory($loc) {
		$loc = $loc.'/';
		$dir = scandir($loc);
		foreach ($dir as $i) {
			if ($i != '.' && $i != '..') {
				if (is_dir($loc . $i)) {
					$this->searchDirectory($loc . $i);
				} else {
					array_push($this->files, $loc . $i);
				}
			}
		}
	}
}

?>