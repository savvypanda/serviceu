<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ServiceuViewEvent extends JView
{
	protected $row;

	function display($tpl = null)
	{
		$row = $this->get('Event');
		$this->assign('row', $row);
		$this->assign('city_state_zip', $row->LocationCity . ', ' . $row->LocationState . ' ' . $row->LocationZip);

		$document = JFactory::getDocument();
		$document->setTitle($row->Name . ' at the Urban Ecology Center ' . $row->date);

		parent::display($tpl);
	}
}