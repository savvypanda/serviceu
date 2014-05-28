<?php
defined( '_JEXEC' ) or die;

require_once JPATH_BASE . '/components/com_serviceu/models/eventlist.php';
$accessible = ServiceuModelEventlist::getAccessibleCategory();

$event_ids = $params->get('display_events', '');

if (strlen($event_ids)) {
	$event_ids = explode(',', $event_ids);
	JArrayHelper::toInteger($event_ids);

	$query = "SELECT Name, OccurrenceStartTime, OccurrenceEndTime, LocationName, events_id, CategoryList "
			."FROM #__serviceu_events "
			."WHERE events_id IN (" . implode(',', $event_ids) . ") "
			."AND OccurrenceStartTime >= '" . date('Y-m-d') . " 00:00:00' "
			."ORDER BY OccurrenceStartTime ASC";
} else {
	$display_num = (int) $params->get('display_num', '3');

	$query = "SELECT Name, OccurrenceStartTime, OccurrenceEndTime, LocationName, events_id, CategoryList "
			."FROM #__serviceu_events "
			."WHERE OccurrenceStartTime >= '" . date('Y-m-d') . " 00:00:00' "
			."ORDER BY OccurrenceStartTime ASC LIMIT {$display_num}";
}

$db = JFactory::getDBO();
$db->setQuery($query);

$rows = $db->loadObjectList();

$filled = array();

foreach ($rows as $row) {
	$start = strtotime($row->OccurrenceStartTime);
	$end = strtotime($row->OccurrenceEndTime);

	$row->date = date('l, F jS', $start);

	$row->start_time = date('g:i A', $start);
	$row->end_time = date('g:i A', $end);

	$row->categories = explode(' | ', $row->CategoryList);

	$filled[] = $row;
}

require(JModuleHelper::getLayoutPath('mod_serviceu_eventlistings'));