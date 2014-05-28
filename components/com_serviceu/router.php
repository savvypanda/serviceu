<?php
defined( '_JEXEC' ) or die;

function ServiceuBuildRoute(&$query)
{
	if (!isset($query['Itemid'])) {
		$item = JSite::getMenu()->getActive();

		$query['Itemid'] = $item->id;
	}

	return array();
}

function ServiceuParseRoute($segments)
{
	return array();
}