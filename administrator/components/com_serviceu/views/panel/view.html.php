<?php
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class ServiceuViewPanel extends JView
{
	public function display($tpl = null)
	{
		$this->buildToolbar();

		$this->assign('last_update', $this->get('LastUpdate'));

		parent::display($tpl);
	}

	public function buildToolbar()
	{
		JToolBarHelper::title('ServiceU Events Control Panel');
		JToolBarHelper::preferences('com_serviceu');
	}
}