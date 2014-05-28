<?php defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controller');

class ServiceuController extends JControllerLegacy {
	public function display() {
		$input = JFactory::getApplication()->input;
		$view = $input->get('view', '');

		if ($view == '') {
			$input->set('view', 'panel');
		}

		parent::display();
	}
}

$controller = new ServiceuController();
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();