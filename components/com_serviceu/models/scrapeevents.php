<?php defined( '_JEXEC' ) or die('Restricted access');

jimport('joomla.application.component.model');

class ServiceuModelScrapeevents extends JModel {
	private $existing_events;
	private $event_categories;
	private $serviceu_config = array('orgKey' => '', 'format' => 'json');
	private $cur_memory_limit = 0;
	private $debugging = false;
	private $eventcounter = 0;
	private $errorlog = null;
	private $errorTolerance = 0;
	private $errors = 0;

	public function __construct($config) {
		parent::__construct($config);
	}

	/**
	 * TODO: Look into splitting up the event detail fetching into several subsequent calls
	 *
	 * @return void
	 * @author Joseph LeBlanc
	 */
	public function updateEvents() {
		require_once JPATH_COMPONENT . '/pest/PestJSON.php';

		//$this->_clearCategories();
		$this->_seedCategories();
		$this->_seedEventOccurrences();
		$this->_getMemoryLimit();
		$this->debugging = (JRequest::getString('loglevel')=='extrainfo');

		$params = JFactory::getApplication()->getParams();
		$this->serviceu_config['orgKey'] = $params->get('org_key');
		$this->errorTolerance = $params->get('error_tolerance',5);
		if(!$this->debugging && $params->get('debugging')==1) $this->debugging = true;

		jimport('joomla.error.log');
		$this->errorlog = JLog::getInstance('serviceu.php');

		try {
			$pest = new PestJSON('http://api.serviceu.com/rest/');
			$dates = $this->_getDates();
			$query_params = array_merge($dates, $this->serviceu_config);
			$events = $pest->get('/events/occurrences?' . http_build_query($query_params));

			if (is_array($events)) {
				$this->_logNotification('Beginning Event Sync with Verbose Logging. The last processed event will be saved in /logs/serviceu_event.txt');

				foreach ($events as &$event) {
					if($this->debugging) $this->_save_last_event($event);

					if ($event['StatusDescription'] == 'Approved') {
						$success = $this->_storeEvent($event);
						if($success) {
							$success = $this->_fetchEventOccurrenceDetails($event);
							if($success) {
								$success = $this->_categorizeEvent($event);
							}
						}

						if($success && isset($this->existing_events[$event['OccurrenceId']])) {
							$this->existing_events[$event['OccurrenceId']]->marked_for_delete = false;
						}

						$this->eventcounter++;
						if($this->eventcounter%100==0) {
							$this->_logNotification($this->eventcounter.' Events Added');
						}
					}
					//$this->_resetExecutionTime();
				}
				$this->_logNotification('Finished adding events. Removing non-updated events');
				$events_removed = 0;
				$cats_removed = 0;
				foreach($this->existing_events as $rmevent) {
					if($rmevent->marked_for_delete) {
						$this->_removeEvent($rmevent);
						$events_removed++;
					}
				}
				foreach($this->event_categories as $rmcat) {
					if($rmcat->marked_for_delete) {
						$this->_removeCat($rmcat);
						$cats_removed++;
					}
				}
				$this->_logNotification("Finished removing events. $events_removed events and $cats_removed categories were deleted.");
				$this->_logNotification('Sync complete');
			} else {
				$this->errorlog->addEntry(array(
					'comment' => 'No events returned by ServiceU API. Skipping update.',
					'status' => 'Notification'
				));
			}
		} catch (Exception $e) {
			$this->errorlog->addEntry(array(
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
		if($event->event_details_id) {
			$query = "DELETE FROM #__serviceu_event_details WHERE event_details_id=".$this->_db->quote($event->event_details_id);
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		if($event->events_id) {
			$query = "DELETE FROM #__serviceu_events WHERE events_id=".$this->_db->quote($event->events_id);
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}
	private function _removeCat($cat) {
		if($cat->category_id) {
			$query = 'DELETE FROM #__serviceu_event_categories WHERE category_id='.$cat->category_id;
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}

	/**
	 * Clears the categories and category assignments. Written at the client's
	 * request.
	 *
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _clearCategories() {
		$query = "TRUNCATE TABLE #__serviceu_event_assigned_categories";
		$this->_db->setQuery($query);
		$this->_db->query();
		$query = "TRUNCATE TABLE #__serviceu_event_categories";
		$this->_db->setQuery($query);
		$this->_db->query();

		$this->event_categories = array();
	}

	protected function _seedCategories() {
		$query = 'SELECT category_id, category_name, true AS marked_for_delete FROM #__serviceu_event_categories';
		$this->_db->setQuery($query);
		$this->event_categories = $this->_db->loadObjectList('category_name');
	}

	/**
	 * Gets start and end dates, preformatted for ServiceU
	 *
	 * @return array
	 * @author Joseph LeBlanc
	 */
	protected function _getDates() {
		$params = JFactory::getApplication()->getParams();
		$sync_days = $params->get('sync_days',365)*1;
		$today = new DateTime();
		$enddate = new DateTime();
		$enddate->add(new DateInterval('P'.$sync_days.'D'));

		$dates = array('startDate' => $today->format('m/d/Y'),
					   'endDate' => $enddate->format('m/d/Y'));

		$this->_logNotification('Fetching events between '.$dates['startDate'].' and '.$dates['endDate']);

		return $dates;
	}

	/**
	 *
	 * @param string $EventId
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _fetchEventOccurrenceDetails($event) {
		$occurrenceId = $event['OccurrenceId'];

		$pest = new PestJSON('http://api.serviceu.com/rest/');
		$event_details = $pest->get('/events/occurrences/' . $occurrenceId . '?' . http_build_query($this->serviceu_config));

		if($this->debugging) $this->_save_last_event($event_details, true);

		$row = JTable::getInstance('Serviceu_event_details', 'Table');
		$row->bind($event_details);

		$updating = array_key_exists($occurrenceId, $this->existing_events);

		if($updating) {
			if($this->existing_events[$occurrenceId]->event_details_id) {
				$row->event_details_id = $this->existing_events[$occurrenceId]->event_details_id;
			} else {
				$updating = false;
			}
		}

		if (!$row->store()) {
			$this->_logError($row->getError());
			return false;
		}

		if(!$updating) {
			$new_event = new stdClass();
			$new_event->OccurrenceId = $occurrenceId;
			$new_event->events_id = $event['events_id'];
			$new_event->event_details_id = $row->getDBO()->insertid();
			$new_event->marked_for_delete = false;
			$this->existing_events[$occurrenceId] = $new_event;
		}
		return true;
	}

	/**
	 *
	 * @param string $event
	 * @return void
	 * @author Joseph LeBlanc
	 */
	protected function _storeEvent(&$event)
	{
		$row = JTable::getInstance('Serviceu_events', 'Table');

		$row->bind($event);

		$row->DateModified = $this->_formatServiceuDate($row->DateModified);
		$row->OccurrenceEndTime = $this->_formatServiceuDate($row->OccurrenceEndTime);
		$row->OccurrenceStartTime = $this->_formatServiceuDate($row->OccurrenceStartTime);
		$row->ResourceEndTime = $this->_formatServiceuDate($row->ResourceEndTime);
		$row->ResourceStartTime = $this->_formatServiceuDate($row->ResourceStartTime);
		$row->last_sync_occurred = time();

		$updating = array_key_exists($event['OccurrenceId'], $this->existing_events);

		if($updating) {
			$row->events_id = $this->existing_events[$event['OccurrenceId']]->events_id;
		}

		if (!$row->store()) {
			$this->_logError($row->getError());
			return false;
		}

		if(!$updating) {
			$event['events_id'] = $row->getDBO()->insertid();
		}
		return true;
	}

	protected function _categorizeEvent($event) {
		$categories = explode(' | ', $event['CategoryList']);

		$assigned = $this->_getEventCategoryIds($event['EventId']);
		$new_assigned = array();

		foreach ($categories as $category) {
			if (array_key_exists($category, $this->event_categories)) {
				$this->event_categories[$category]->marked_for_delete = false;
				$category_id = $this->event_categories[$category]->category_id;
			} else {
				$category_id = $this->_addCategory($category);
			}
			if(!$category_id) return false;

			if (!in_array($category_id, $assigned)) {
				$this->_addEventToCategory($event['EventId'], $category_id);
			}
			$new_assigned[] = $category_id;
		}
		$to_remove = array_diff($assigned, $new_assigned);
		foreach($to_remove as $category_id) {
			$this->_removeEventFromCategory($event['Eventid'], $category_id);
		}
		return true;
	}

	protected function _addEventToCategory($EventId, $category_id) {
		$db = JFactory::getDBO();
		$query = "INSERT INTO #__serviceu_event_assigned_categories (EventId, category_id)"
				." VALUES ('" . $db->getEscaped($EventId) . "', '" . $db->getEscaped($category_id) . "')";
		$db->setQuery($query);
		$db->query();
	}

	protected function _removeEventFromCategory($EventId, $category_id) {
		$db = JFactory::getDBO();
		$query = 'DELETE #__serviceu_event_assigned_categories WHERE EventId = '.$db->getEscaped($EventId).' AND category_id = '.$db->getEscaped($category_id);
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
	protected function _addCategory($category) {
		$row =& JTable::getInstance('Serviceu_event_categories', 'Table');
		$row->category_name = $category;

		if (!$row->store()) {
			$this->_logError($row->getError());
			return false;
		}

		$newcategory = new stdClass();
		$newcategory->category_id = $row->category_id;
		$newcategory->category_name = $category;
		$newcategory->marked_for_delete = false;
		$this->event_categories[$category] = $newcategory;

		return $row->category_id;
	}

	/**
	 * TODO: review the API and see if there's a way of limiting by date, then
	 * rework the query in this function to limit by that date.
	 *
	 * @return array
	 * @author Joseph LeBlanc
	 */
	protected function _seedEventOccurrences() {
		$query = "SELECT e.OccurrenceId, events_id, event_details_id, true as marked_for_delete FROM #__serviceu_events e LEFT JOIN #__serviceu_event_details d ON e.OccurrenceId=d.OccurrenceId";
		$this->_db->setQuery($query);
		$this->existing_events = $this->_db->loadObjectList('OccurrenceId');
	}

	protected function _getEventCategoryIds($EventId) {
		$query = "SELECT category_id FROM #__serviceu_event_assigned_categories"
				." WHERE EventId = '{$EventId}'";

		$this->_db->setQuery($query);
		return $this->_db->loadResultArray();
	}

	protected function _formatServiceuDate($time_string) {
		$time = strtotime($time_string);
		return date('Y-m-d H:i:s', $time);
	}

	protected function _getMemoryLimit(){
		return $this->_getBytes(ini_get('memory_limit'));
	}
	protected function _resetExecutionTime() {
		set_time_limit(30);
		$tolerance = 5242880; //5MB
		$cur_memory_usage = memory_get_usage();
		if($cur_memory_usage > $this->cur_memory_limit - $tolerance) {
			$this->cur_memory_limit += $tolerance;
			ini_set('memory_limit',$this->_getMBytes($this->cur_memory_limit));
		}
	}

	protected function _getBytes($num) {
		$num = trim($num);
		$abbr = strtoupper($num[strlen($num)-1]);
		switch($abbr) {
			case 'T': $num *= 1024;
			case 'G': $num *= 1024;
			case 'M': $num *= 1024;
			case 'K': $num *= 1024;
		}
		return $num * 1;
	}
	protected function _getMBytes($num) {
		return round($num/1048576,0).'M';
	}

	protected function _logError($message) {
		if($this->debugging) {
			$message = 'Record #'.$this->eventcounter.': '.$message;
		}
		if($this->errors < $this->errorTolerance) {
			$this->errors++;
			$this->errorlog->addEntry(array(
				'comment' => $message,
				'status' => 'Error'
			));
		} else {
			$this->errorlog->addEntry(array(
				'comment' => 'Error tolerance reached. Aborting.',
				'status' => 'Error'
			));
			throw new Exception($message);
		}
	}
	protected function _logNotification($message) {
		if($this->debugging) {
			$this->errorlog->addEntry(array(
				'comment' => $message,
				'status' => 'Notification'
			));
		}
	}

	private function _save_last_event($event, $append = false) {
		$file = JPATH_BASE.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'serviceu_event.txt';
		$mode = $append?'a':'w';
		$logstring = ($append?"\n\n":'').var_export($event, true);
		$handle = fopen($file,$mode);
		fwrite($handle, $logstring);
		fclose($handle);
	}

	protected function _markLastUpdated() {
		$query = "INSERT INTO #__serviceu_events_last_updated (`timestamp`) VALUES ('" . time() . "')";

		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}
}