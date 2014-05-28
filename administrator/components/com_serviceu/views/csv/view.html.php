<?php
defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class ServiceuViewCsv extends JView
{
	public function display($tpl = null)
	{
		JToolBarHelper::title('ServiceU Events CSV Download');

		parent::display($tpl);
	}
}