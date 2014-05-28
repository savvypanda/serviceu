<?php
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

require JPATH_BASE . '/modules/mod_serviceu_calendar/helper.php';

class ServiceuViewCalendar extends JView
{
	public function display($tpl = null)
	{
		$month = JRequest::getInt('month', date('n'));
		$year = JRequest::getInt('year', date('Y'));

		$cal = new ServiceuCalendarHelper($month, $year);
		$cal->renderMonthDays();
	}
}