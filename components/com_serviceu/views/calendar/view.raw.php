<?php defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');
require JPATH_BASE . '/modules/mod_serviceu_calendar/helper.php';

class ServiceuViewCalendar extends JViewLegacy {
	public function display($tpl = null) {
		$input = JFactory::getApplication()->input;
		$month = $input->get('month', date('n'), 'int');
		$year = $input->get('year', date('Y'), 'int');
		$cal = new ServiceuCalendarHelper($month, $year);
		$cal->renderMonthDays();
	}
}
