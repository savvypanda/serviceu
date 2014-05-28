<?php
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controller');

class ServiceuController extends JController
{
	public function display()
	{
		$view = JRequest::getCmd('view', '');

		if ($view == '') {
			JRequest::setVar('view', 'calendar');
		}

		parent::display();
	}

	public function updateEvents()
	{
		$model = $this->getModel('scrapeevents');

		$start = microtime(true);
		$model->updateEvents();
		$end = microtime(true);

		echo json_encode(array('process' => 'complete', 'time' => $end - $start));
	}
}

$controller = new ServiceuController();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();