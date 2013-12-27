<?php
defined('_JEXEC') or die;

function modChrome_border($module, &$params, &$attribs) {
	if($module->content) {
		echo "<div class='well " . htmlspecialchars($params->get('moduleclass_sfx')) . "'>";
		if ($module->showtitle) {
			echo "<h3 class='page-header'>{$module->title}</h3>";
		}
		echo "{$module->content}</div>";
	}
}
?>
