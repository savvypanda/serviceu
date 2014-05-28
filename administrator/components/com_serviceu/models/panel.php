<?php
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.model');

class ServiceuModelPanel extends JModel
{
	public function getLastUpdate()
	{
		$query = "SELECT max(`timestamp`) FROM #__serviceu_events_last_updated";

		$db = JFactory::getDBO();
		$db->setQuery($query);

		return date('m/d/Y g:i:s A', $db->loadResult());
	}
}