<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_BASE . '/components/com_serviceu/models/eventlist.php';

jimport('joomla.application.component.model');

class ServiceuModelEvent extends JModel
{
	private $event;

	function getEvent()
	{
		if (!isset($this->event))
		{
			$id = JRequest::getInt('id', 0);

			$query = "SELECT e.*, d.TicketingDescription "
					."FROM #__serviceu_events AS e "
					."LEFT JOIN #__serviceu_event_details AS d USING(OccurrenceId) "
					."WHERE events_id = '{$id}'";

			$db = JFactory::getDBO();
			$db->setQuery($query);

			$row = $db->loadObject();

			$time = strtotime($row->OccurrenceStartTime);
			$row->date = date('l, F jS', $time);
			$row->start_time = date('g:i A', $time);
			$row->end_time = date('g:i A', strtotime($row->OccurrenceEndTime));

			$categories = explode(' | ', $row->CategoryList);

			if (in_array(ServiceuModelEventlist::getAccessibleCategory(), $categories)) {
				$row->accessible = true;
			} else {
				$row->accessible = false;
			}

			$this->event = $row;
		}

		return $this->event;
	}

}