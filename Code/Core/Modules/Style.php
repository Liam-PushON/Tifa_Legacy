<?php

require_once "Code/Core/Tools/scssphp/scss.inc.php";
use Leafo\ScssPhp\Compiler;

class Style {

	private $files = array();

	function __construct() {
		if (Core::$settings->cache->enabled == 'false') {
			$this->init();
			$this->compileSCSS();
			$this->compileCSS();
		}
	}

	function compileSCSS(){
		$scss = new Compiler();
		$css =  $scss->compile(file_get_contents(Core::$theme->findResource('scss/theme.scss', 'style')));
		$scss = fopen(Core::$theme->findResource('css/active/', 'style').'/scss_import.css', 'w');
		fwrite($scss, $css);
		fclose($scss);
	}

	function compileCSS() {
		$css = '';
		foreach ($this->files as $file) {
			if(Core::$settings->style->add_css_paths_to_css_comments == 'true'){
				$css = $css.'/* '.$file." */\n".file_get_contents($file)."\n";
			}else{
				$css = $css.file_get_contents($file)."\n";
			}
		}
		$loc = Core::$theme->findResource('css/', 'style');
		$style = fopen($loc.'/theme.css', 'w');
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