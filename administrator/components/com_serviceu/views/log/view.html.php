<?php defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class ServiceuViewLog extends JViewLegacy {
	public function display($tpl = null) {
		JToolBarHelper::title('ServiceU Events Error Log');
		parent::display($tpl);
	}
}