<?php
defined( '_JEXEC' ) or die;

require JPATH_BASE . '/modules/mod_serviceu_calendar/helper.php';

$month = JRequest::getInt('month', date('n'));

$cal = new ServiceuCalendarHelper($month);
$weeks = $cal->getWeeksArray();
$drop = $cal->getMonthDrop($month);

require(JModuleHelper::getLayoutPath('mod_serviceu_calendar'));
