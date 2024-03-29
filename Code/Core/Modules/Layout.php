<?php

class Layout {

	function build($page, $args = false) {
		echo '<html>';
		$layout = simplexml_load_file(Tifa::$theme->findResource($page, 'layout', $args));
		$overrides = $this->layoutHasOverride($page, $args);
		if (!$overrides) {
			foreach ($layout->layout->block as $block) {
				if ($block->attributes()->open_tag) {
					echo '<' . $block->attributes()->open_tag . '>';
				}
				if (Tifa::$settings->design->add_template_paths_to_html_comments == 'true') {
					echo '<!--' . Tifa::$theme->findResource($block->attributes()->template, 'template') . '-->';
				}
				if ($block->attributes()->wrapper) {
					echo '<' . $block->attributes()->wrapper . ($block->attributes()->wrapper_class ? ' class="' . $block->attributes()->wrapper_class . '" >' : '>');
				}
				include(Tifa::$theme->findResource($block->attributes()->template, 'template'));
				if ($block->attributes()->wrapper) {
					echo '</' . $block->attributes()->wrapper . '>';
				}
				if ($block->attributes()->close_tag) {
					echo '<' . $block->attributes()->close_tag . '>';
				}
			}
		} else {
			foreach ($layout->layout->block as $block) {
				$override = false;

				foreach ($overrides as $o) {
					if ((string)$o[0] == (string)$block->attributes()->name) {
						$override = true;
					}
				}
				if ($override) {
					if (Tifa::$settings->design->add_template_paths_to_html_comments == 'true') {
						echo '<!--' . Tifa::$theme->findResource($o[1], 'template') . '-->';
					}
					if ($block->attributes()->wrapper) {
						echo '<' . $block->attributes()->wrapper . ($block->attributes()->wrapper_class ? ' class="' . $block->attributes()->wrapper_class . '" >' : '>');
					}
					include(Tifa::$theme->findResource($o[1], 'template'));
					if ($block->attributes()->wrapper) {
						echo '</' . $block->attributes()->wrapper . '>';
					}
				} else {
					if (Tifa::$settings->design->add_template_paths_to_html_comments == 'true') {
						echo '<!--' . Tifa::$theme->findResource($block->attributes()->template, 'template') . '-->';
					}
					if ($block->attributes()->wrapper) {
						echo '<' . $block->attributes()->wrapper . ($block->attributes()->wrapper_class ? ' class="' . $block->attributes()->wrapper_class . '" >' : '>');
					}
					include(Tifa::$theme->findResource($block->attributes()->template, 'template'));
					if ($block->attributes()->wrapper) {
						echo '</' . $block->attributes()->wrapper . '>';
					}
				}
			}
		}
		echo '</html>';
	}

	function layoutHasOverride($page, $args = false) {
		$layout = simplexml_load_file(Tifa::$theme->findResource($page, 'layout', $args));
		if (Tifa::$theme->findResource('override/' . $page, 'layout', $args)) {
			$override = simplexml_load_file(Tifa::$theme->findResource('override/' . $page, 'layout', $args));
		} else {
			return false;
		}
		$overrides = array();
		if (file_exists(Tifa::$theme->findResource($page, 'layout', $args)) && file_exists(Tifa::$theme->findResource('override/' . $page, 'layout', $args))) {
			if (Tifa::$theme->getResourcePriority('override/' . $page, 'layout', $args) <= Tifa::$theme->getResourcePriority($page, 'layout', $args)) {
				foreach ($override->override->block as $block) {
					array_push($overrides, array($block->attributes()->name, $block->attributes()->template));
				}
			}
		}
		return count($overrides > 0) ? $overrides : false;
	}
}

?>