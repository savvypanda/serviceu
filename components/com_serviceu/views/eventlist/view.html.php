<?php defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class ServiceuViewEventlist extends JViewLegacy {
	function display($tpl = null) {
		$events = $this->get('events');
		$pagination = $this->get('pagination');
		$accessible = $this->get('accessibleCategory');

		$this->events = $events;
		$this->pagination = $pagination;
		$this->accessible = $accessible;

		parent::display($tpl);
	}
}