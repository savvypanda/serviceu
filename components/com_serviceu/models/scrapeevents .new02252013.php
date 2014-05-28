<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ServiceuModelScrapeevents extends JModel
{
	private $existing_events;
	private $event_categories;
	private $serviceu_config = array('orgKey' => '', 'format' => 'json');

	public function __construct($config)
	{
		parent::__construct($config);

		$this->_clearCategories();
		$this->_seedEventOccurrences();

		$params = JFactory::getApplication()->getParams();
		$this->serviceu_config['orgKey'] = $params->get('org_key');
	}

	/**
	 * TODO: Log errors
	 * TODO: Look into splitting up the event detail fetching into several
	 * subsequent calls
	 *
	 * @return void
	 * @author Joseph LeBlanc
	 */
	public function updateEvents()
	{
		require_once JPATH_COMPONENT . '/pest/PestJSON.php';

		try {
			$pest = new PestJSON('http://api.serviceu.com/rest/');
			$dates = $this->_getDates();
			$query_params = array_merge($dates, $this->serviceu_config);
			$events = $pest->get('/events/occurrences?' . http_build_query($query_params));

			if (is_array($events)) {
				foreach ($events as $event) {
					if ($event['StatusDescription'] == 'Approved') {
						$this->_storeEvent($event);
						$this->_fetchEventOccurrenceDetails($event['OccurrenceId']);
						$this->_categorizeEvent($event);

						if(isset($this->existing_events[$event['OccurrenceId']])) {
							$this->existing_events[$event['OccurrenceId']]->marked_for_delete = false;
						}
					}
				}
				foreach($this->existing_events as $rmevent) {
					if($rmevent->marked_for_delete) {
						$this->_removeEvent($rmevent);
					}
				}
			}
		} catch (Exception $e) {
			jimport('joomla.error.log');
			$log = JLog::getInstance('serviceu.php');

			$log->addEntry(array(
				'comment' => $e->getMessage(),
				'status' => get_class($e)
			));
		}

		$this->_markLastUpdated();
	}

	/*
	 * Function to delete an event given an object containing the event_details_id and event_id properties
	 */
	private function _removeEvent($event) {
		$query = "DELETE FROM #__serviceu_event_details WHERE event_details_id=".$this->_db->quote($event->event_details_id);
		$this->_db->setQuery($query);
		$this->_db->query();
		$query = "DELETE FROM #__serviceu_events WHERE events_id=".$this->_db->quote($event->events_id);
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	/**
	 * Clears the categories and category assignments. Written at the client's
	 * request.
	 *
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _clearCategories()
	{
		$query = "TRUNCATE TABLE #__serviceu_event_assigned_categories";
		$this->_db->setQuery($query);
		$this->_db->query();
		$query = "TRUNCATE TABLE #__serviceu_event_categories";
		$this->_db->setQuery($query);
		$this->_db->query();

		$this->event_categories = array();
	}

	/**
	 * Gets start and end dates, preformatted for ServiceU
	 *
	 * @return array
	 * @author Joseph LeBlanc
	 */
	protected function _getDates()
	{
		$dates = array('startDate' => date('m/d/Y'));

		$one_year_later = mktime(0, 0, 0, date('m'), date('d'), date('Y') + 1);
		//$one_year_later = mktime(0, 0, 0, date('m'), date('d')+1, date('Y')); //for debugging purposes

		$dates['endDate'] = date('m/d/Y', $one_year_later);

		return $dates;
	}

	/**
	 * TODO: Log errors
	 *
	 * @param string $EventId
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _fetchEventOccurrenceDetails($OccurrenceId)
	{
		$pest = new PestJSON('http://api.serviceu.com/rest/');
		$event_details = $pest->get('/events/occurrences/' . $OccurrenceId . '?' . http_build_query($this->serviceu_config));

		$row = JTable::getInstance('Serviceu_event_details', 'Table');
		$row->bind($event_details);

		if(isset($this->existing_events[$row->OccurrenceId])) {
			$row->event_details_id = $this->existing_events[$row->OccurrenceId]->event_details_id;
		}

		if (!$row->store()) {
			$error = $row->getError();
			throw new Exception($error);
		}
	}

	/**
	 * TODO: Log errors
	 *
	 * @param string $event
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _storeEvent($event)
	{
		$row = JTable::getInstance('Serviceu_events', 'Table');

		$row->bind($event);

		$row->DateModified = $this->_formatServiceuDate($row->DateModified);
		$row->OccurrenceEndTime = $this->_formatServiceuDate($row->OccurrenceEndTime);
		$row->OccurrenceStartTime = $this->_formatServiceuDate($row->OccurrenceStartTime);
		$row->ResourceEndTime = $this->_formatServiceuDate($row->ResourceEndTime);
		$row->ResourceStartTime = $this->_formatServiceuDate($row->ResourceStartTime);
		$row->last_sync_occurred = time();

		if(isset($this->existing_events[$row->OccurrenceId])) {
			$row->events_id = $this->existing_events[$row->OccurrenceId]->events_id;
		}

		if (!$row->store()) {
			$error = $row->getError();
			throw new Exception($error);
		}
	}

	protected function _categorizeEvent($event)
	{
		$categories = explode(' | ', $event['CategoryList']);

		$assigned = $this->_getEventCategoryIds($event['EventId']);

		foreach ($categories as $category) {
			if (isset($this->event_categories[$category])) {
				$category_id = $this->event_categories[$category];
			} else {
				$category_id = $this->_addCategory($category);
			}

			if (!in_array($category_id, $assigned)) {
				$this->_addEventToCategory($event['EventId'], $category_id);
			}
		}
	}

	protected function _addEventToCategory($EventId, $category_id)
	{
		$db = JFactory::getDBO();

		$query = "INSERT INTO #__serviceu_event_assigned_categories (EventId, category_id)"
				." VALUES ('" . $db->getEscaped($EventId) . "', '" . $db->getEscaped($category_id) . "')";

		$db->setQuery($query);
		$db->query();
	}

	/**
	 * TODO: Log errors
	 *
	 * @param string $event
	 * @return integer
	 * @author Joseph LeBlanc
	 */
	protected function _addCategory($category)
	{
		$row =& JTable::getInstance('Serviceu_event_categories', 'Table');
		$row->category_name = $category;

		if (!$row->store()) {
			$error = $row->getError();
			throw new Exception($error);
		}

		$this->event_categories[$category] = $row->category_id;

		return $row->category_id;
	}

	/**
	 * TODO: review the API and see if there's a way of limiting by date, then
	 * rework the query in this function to limit by that date.
	 *
	 * @return array
	 * @author Joseph LeBlanc
	 */
	protected function _seedEventOccurrences()
	{
		$query = "SELECT e.OccurrenceId, events_id, event_details_id, true as marked_for_delete FROM #__serviceu_events e JOIN #__serviceu_event_details d ON e.OccurrenceId=d.OccurrenceId";
		$this->_db->setQuery($query);
		$this->existing_events = $this->_db->loadObjectList('OccurrenceId');
	}

	protected function _getEventCategoryIds($EventId)
	{
		$query = "SELECT category_id FROM #__serviceu_event_assigned_categories"
				." WHERE EventId = '{$EventId}'";

		$this->_db->setQuery($query);
		return $this->_db->loadResultArray();
	}

	protected function _formatServiceuDate($time_string)
	{
		$time = strtotime($time_string);
		return date('Y-m-d H:i:s', $time);
	}

	protected function _markLastUpdated()
	{
		$query = "INSERT INTO #__serviceu_events_last_updated (`timestamp`) VALUES ('" . time() . "')";

		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}
}