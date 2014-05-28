<?php defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controller');

class ServiceuController extends JControllerLegacy {
	public function display() {
		$input = JFactory::getApplication()->input;
		$view = $input->get('view', '');

		if ($view == '') {
			$input->set('view', 'calendar');
		}

		parent::display();
	}

	public function updateEvents() {
		$model = $this->getModel('scrapeevents');

		$start = microtime(true);
		$model->updateEvents();
		$end = microtime(true);

		echo json_encode(array('process' => 'complete', 'time' => $end - $start));
	}
}

$controller = new ServiceuController();
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
