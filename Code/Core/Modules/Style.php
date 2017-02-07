<?php

require_once "Code/Core/Tools/scssphp/scss.inc.php";
use Leafo\ScssPhp\Compiler;

class Style {
	private $files_css = array();
	private $files_js = array();

	function __construct() {
		if (Core::$settings->cache->enabled == 'false') {
			$this->init();
			//CSS
			$this->compileSCSS();
			$this->compileCSS();
			$this->finaliseCSS();
			//JS
			$this->compileJS();
			$this->finaliseJS();
		}
	}

	function finaliseJS(){
		$js = file_get_contents(Core::$theme->findResource('js/theme.js', 'style'));
		$replacements = simplexml_load_file(Core::$theme->findResource('js/overrides.xml', 'style'));
		foreach($replacements->override as $r){
			$css = str_replace($r->original, $r->replacement, $js);
		}
		$script = fopen(Core::$theme->findResource('js/theme.js', 'style'), 'w');
		fwrite($script, $css);
		fclose($script);
	}
	function finaliseCSS(){
		$css = file_get_contents(Core::$theme->findResource('css/theme.css', 'style'));
		$replacements = simplexml_load_file(Core::$theme->findResource('css/overrides.xml', 'style'));
		foreach($replacements->override as $r){
			$css = str_replace($r->original, $r->replacement, $css);
		}
		$style = fopen(Core::$theme->findResource('css/theme.css', 'style'), 'w');
		fwrite($style, $css);
		fclose($style);
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
		foreach ($this->files_css as $file) {
			if(Core::$settings->style->add_css_paths_to_css_comments == 'true'){
				$css = $css.'/* '.$file." */\n".file_get_contents($file)."\n";
			}else{
				$css = $css.file_get_contents($file)."\n";
			}
		}
		$style = fopen(Core::$theme->findResource('css/theme.css', 'style'), 'w');
		fwrite($style, $css);
		fclose($style);
	}
	function compileJS() {
		$js = '';
		foreach ($this->files_js as $file) {
			if(Core::$settings->style->add_css_paths_to_css_comments == 'true'){
				$js = $js.'/* '.$file." */\n".file_get_contents($file)."\n";
			}else{
				$js = $js.file_get_contents($file)."\n";
			}
		}
		$style = fopen(Core::$theme->findResource('js/theme.js', 'style'), 'w');
		fwrite($style, $js);
		fclose($style);
	}

	function init() {
		//CSS
		$loc = Core::$theme->findResource('css/active/', 'style');
		$dir = scandir($loc);
		foreach ($dir as $i) {
			if ($i != '.' && $i != '..') {
				if (is_dir($loc . $i)) {
					$this->searchDirectory($loc . $i, 'css');
				} else {
					array_push($this->files_css, $loc . $i);
				}
			}
		}
		//JS
		$loc = Core::$theme->findResource('js/active/', 'style');
		$dir = scandir($loc);
		foreach ($dir as $i) {
			if ($i != '.' && $i != '..') {
				if (is_dir($loc . $i)) {
					$this->searchDirectory($loc . $i, 'js');
				} else {
					array_push($this->files_js, $loc . $i);
				}
			}
		}
	}
	function searchDirectory($loc, $type) {
		$loc = $loc.'/';
		$dir = scandir($loc);
		foreach ($dir as $i) {
			if ($i != '.' && $i != '..') {
				if (is_dir($loc . $i)) {
					$this->searchDirectory($loc . $i, $type);
				} else {
					if($type == 'css'){
						array_push($this->files_css, $loc . $i);
					}else if($type == 'js'){
						array_push($this->files_js, $loc . $i);
					}else{
						return;
					}
				}
			}
		}
	}
}

?>