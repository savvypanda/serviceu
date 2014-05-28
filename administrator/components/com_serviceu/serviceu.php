<?php
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controller');

class ServiceuController extends JController
{
	public function display()
	{
		$view = JRequest::getCmd('view', '');

		if ($view == '') {
			JRequest::setVar('view', 'panel');
		}

		parent::display();
	}
}

$controller = new ServiceuController();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();