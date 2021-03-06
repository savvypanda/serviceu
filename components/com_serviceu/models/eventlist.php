<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ServiceuModelEventlist extends JModelLegacy {
	private $events;
	private $sorted_events;
	private $total;

	public function getEvents() {
		if (!isset($this->events)) {
			$limits = $this->getLimits();

			$query = $this->buildQuery();
			$this->events = $this->_getList($query, $limits['limitstart'], $limits['limit']);
			$this->_postFilterDates();
			$this->_postFilterDepartments();
			$this->_postCategorize();
			// Sort happens last
			$this->_postSortEvents();
		}

		return $this->sorted_events;
	}

	public function buildQuery() {
		$values = $this->getSearchValues();

		$query = "SELECT Name, OccurrenceStartTime, OccurrenceEndTime, DepartmentName, CategoryList, events_id, RegistrationEnabled, RegistrationUrl "
				."FROM #__serviceu_events AS e ";
		$where = array("e.OccurrenceStartTime >= '" . date('Y-m-d') . " 00:00:00'");
		if (count($values['categories'])) {
			$query .= "LEFT JOIN #__serviceu_event_assigned_categories AS ec USING (EventId) ";
			$where[] = "ec.category_id IN (" . implode(',', $values['categories']) . ")";
		}
		if (count($values['departments'])) {
			$sub_where = array();
			foreach ($values['departments'] as $department) {
				$sub_where[] = "e.DepartmentName = ".$this->_db->quote($department);
			}
			$where[] = '(' . implode(' OR ', $sub_where) . ')';
		}
		if (strlen($values['event_search'])) {
			$where[] = "(e.Description LIKE '%" . $this->_db->escape($values['event_search']) . "%' OR"
				. " e.Name LIKE '%" . $this->_db->escape($values['event_search']) . "%')";
		}
		if ($values['filter'] == 'month') {
			$where[] = "(e.OccurrenceStartTime >= NOW() AND e.OccurrenceStartTime <= DATE_ADD(NOW(), INTERVAL 30 DAY))";
		} else if ($values['filter'] == 'week') {
			$where[] = "(e.OccurrenceStartTime >= NOW() AND e.OccurrenceStartTime <= DATE_ADD(NOW(), INTERVAL 7 DAY))";
		}
		if ($values['month']) {
			$where[] = "(e.OccurrenceStartTime >= '" . date('Y') . "-" . $values['month'] . "-1 00:00:00' AND OccurrenceStartTime <= '" . date('Y') . "-" . $values['month'] . "-31 23:59:59')";
		}
		if ($values['date']) {
			$date = $values['date']['year'] . "-" . $values['date']['month'] . "-" . $values['date']['day'];
			$where[] = "(e.OccurrenceStartTime >= '{$date} 00:00:00' AND OccurrenceStartTime <= '{$date} 23:59:59')";
		}
		if (count($where)) {
			$query .= "WHERE " . implode(' AND ', $where);
		}
		$query .= " ORDER BY e.OccurrenceStartTime ASC ";
		return $query;
	}

	public function getPagination() {
		jimport('joomla.html.pagination');
		$total = $this->getTotal();
		$limits = $this->getLimits();
		$pagination = new JPagination($total, $limits['limitstart'], $limits['limit']);
		return $pagination;
	}

	public function getTotal() {
		if (empty($this->total)) {
			$query = $this->buildQuery();
			$this->total = $this->_getListCount($query);
		}
		return $this->total;
	}

	public function getLimits() {
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$input = $app->input;
		$limit = $params->get('event_list_limit', $input->get('limit', 0, 'int'));
		$limitstart = $input->get('limitstart', 0, 'int');
		return array('limit' => $limit, 'limitstart' => $limitstart);
	}

	public static function getEventCategories() {
		$db = JFactory::getDbo();
		$db->setQuery("SELECT category_id, category_name FROM #__serviceu_event_categories ORDER BY category_name");
		return $db->loadObjectList();
	}

	public static function getDepartments() {
		$db = JFactory::getDbo();
		$db->setQuery("SELECT DISTINCT DepartmentName FROM #__serviceu_events");
		return $db->loadColumn();
	}

	protected function _postFilterDates() {
		foreach ($this->events as &$event) {
			$time = strtotime($event->OccurrenceStartTime);
			$event->date = date('l, F jS', $time);
			$event->start_time = date('g:i A', $time);
			$event->end_time = date('g:i A', strtotime($event->OccurrenceEndTime));
		}
	}

	protected function _postFilterDepartments() {
		foreach ($this->events as &$event) {
			if ($event->DepartmentName == 'Riverside Park') {
				$event->DeptAbbr = 'RP';
			} else if ($event->DepartmentName == 'Washington Park') {
				$event->DeptAbbr = 'WP';
			} else if ($event->DepartmentName == 'Menomonee Valley') {
				$event->DeptAbbr = 'MV';
			}
		}
	}

	protected function _postCategorize() {
		foreach ($this->events as &$event) {
			$event->categories = explode(' | ', $event->CategoryList);
		}
	}

	protected function _postSortEvents() {
		$this->sorted_events = array();
		foreach ($this->events as $event) {
			$this->sorted_events[$event->date][] = $event;
		}
	}

	public static function getSearchValues() {
		$input = JFactory::getApplication()->input;
		$categories = $input->get('categories', array(), 'array');
		$departments = $input->get('departments', array(), 'array');
		$event_search = $input->get('event_search', '', 'string');
		$filter = $input->get('filter', '', 'string');
		$month = $input->get('month', 0, 'int');
		$date = $input->get('date', '', 'string');

		if ($filter != 'month' && $filter != 'week') {
			$filter = '';
		}

		if ($date) {
			$pieces = explode('/', $date);
			if (count($pieces) != 3) {
				$date = '';
			} else {
				$date = array(
					'month' => $pieces[0],
					'day' => $pieces[1],
					'year' => $pieces[2],
				);
			}
		}

		foreach ($departments as &$department) {
			$department = filter_var($department, FILTER_SANITIZE_STRING);
		}

		foreach ($categories as &$category) {
			$category = (int) $category;
		}

		return array(
			'categories' => $categories,
			'departments' => $departments,
			'event_search' => $event_search,
			'filter' => $filter,
			'month' => $month,
			'date' => $date,
		);
	}

	/**
	 * They haven't actually tagged anything with Accessible Program yet, so
	 * this is giving us a chance to at least test with another category in the
	 * meantime.
	 *
	 * @return string
	 * @author Joseph LeBlanc
	 */
	public static function getAccessibleCategory() {
		return 'Accessible Program';
	}
}
