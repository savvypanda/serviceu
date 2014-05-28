<?php defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class ServiceuViewPanel extends JViewLegacy {
	public function display($tpl = null) {
		$this->buildToolbar();
		$this->last_update = $this->get('LastUpdate');
		parent::display($tpl);
	}

	public function buildToolbar() {
		JToolBarHelper::title('ServiceU Events Control Panel');
		JToolBarHelper::preferences('com_serviceu');
	}
}