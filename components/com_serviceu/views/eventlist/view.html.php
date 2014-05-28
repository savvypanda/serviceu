<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class ServiceuViewEventlist extends JView
{
	function display($tpl = null)
	{
		$events = $this->get('events');
		$pagination = $this->get('pagination');
		$accessible = $this->get('accessibleCategory');

		$this->assign('events', $events);
		$this->assign('pagination', $pagination);
		$this->assign('accessible', $accessible);

		parent::display($tpl);
	}
}