<?php
defined( '_JEXEC' ) or die;

$path = JPATH_ROOT . '/logs/serviceu.php';

if (file_exists($path)) {
	$content = file_get_contents($path);
	$content = str_replace("#<?php die('Direct Access To Log Files Not Permitted'); ?>", '', $content);
	$content = str_replace("\n", '<br />', $content);
} else {
	$content = "No errors logged.";
}

echo $content;