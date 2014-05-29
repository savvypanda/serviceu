<?php defined('_JEXEC') or die('Restricted Access');

require JPATH_BASE . '/modules/mod_serviceu_calendar/helper.php';

$month = JFactory::getApplication()->input->get('month', date('n'), 'int');
$cal = new ServiceuCalendarHelper($month);
$weeks = $cal->getWeeksArray();
$drop = $cal->getMonthDrop($month);

require(JModuleHelper::getLayoutPath('mod_serviceu_calendar'));
