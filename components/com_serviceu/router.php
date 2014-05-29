<?php defined( '_JEXEC' ) or die;

function ServiceuBuildRoute(&$query) {
	if (!isset($query['Itemid'])) {
		$item = JFactory::getApplication()->getMenu()->getActive();
		if($item) $query['Itemid'] = $item->id;
	}
	return array();
}

function ServiceuParseRoute($segments) {
	return array();
}