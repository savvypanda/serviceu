<?php defined('_JEXEC') or die('Restricted Access');

class ServiceuCalendarHelper {
	public $month;
	public $last_month;
	public $year;

	protected $weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
	protected $timestamp;
	protected $last_month_timestamp;
	protected $month_days;
	protected $last_month_days;
	protected $start;

	function __construct($month = null, $year = null) {
		if ($month) {
			$this->month = $month;
		} else {
			$this->month = date('n');
		}
		$this->last_month = $this->month - 1;

		if ($year) {
			$this->year = $year;
		} else {
			$this->year = date('Y');
		}

		$this->timestamp = mktime(0, 0, 0, $this->month, 1, $this->year);
		$this->month_days = date('t', $this->timestamp);

		$this->last_month_timestamp = mktime(0, 0, 0, $this->last_month, 1, $this->year);
		$this->last_month_days = date('t', $this->last_month_timestamp);
	}

	public function getWeeksArray() {
		static $weeks = array();

		if (count($weeks)) {
			return $weeks;
		}

		$first_week = array();

		// Find the day of the week where the month starts
		$month_starts = date('D', $this->timestamp);
		$blank_days = array_search($month_starts, $this->weekdays);

		if ($blank_days != 0) {
			// get the start date for the backfill
			$start_date = $this->last_month_days - $blank_days + 1;

			while ($start_date <= $this->last_month_days) {
				$first_week[] = $this->_makeDate($this->last_month, $start_date, 'prev-month');
				$start_date++;
			}
		}

		$current_date = 1;

		while (count($first_week) < 7) {
			$first_week[] = $this->_makeDate($this->month, $current_date);
			$current_date++;
		}

		$weeks[] = $first_week;

		$week = array();

		while ($current_date <= $this->month_days) {
			if (count($week) == 7) {
				$weeks[] = $week;
				$week = array();
			}

			$week[] = $this->_makeDate($this->month, $current_date);

			$current_date++;
		}

		$current_date = 1;

		while (count($week) < 7) {
			$week[] = $this->_makeDate($this->month + 1, $current_date, 'next-month');
			$current_date++;
		}

		$weeks[] = $week;

		return $weeks;
	}

	protected function _makeDate($month, $number, $class = 'noevents', $link = '') {
		$event_days = $this->getDaysWithEvents();

		if ($class == 'noevents' && in_array($number, $event_days)) {
			$class = 'event_present';
		}

		$date = array('day' => $number);

		if ($link) {
			$date['link'] = $link;
		} else {
			$search = date('m/d/Y', mktime(0,0,0, $month, $number, $this->year));
			$item = JSite::getMenu()->getActive();

			if ($item) {
				$date['link'] = JRoute::_('index.php?option=com_serviceu&view=eventlist&date=' . $search . '&Itemid=' . $item->id);
			} else {
				$date['link'] = JRoute::_('index.php?option=com_serviceu&view=eventlist&date=' . $search);
			}

		}

		$date['class'] = $class;

		return $date;
	}

	public function getDaysWithEvents() {
		static $days = null;

		if ($days != null) {
			return $days;
		}

		$days = array();

		$database = JFactory::getDBO();

		$query = "SELECT OccurrenceStartTime FROM #__serviceu_events "
				. "WHERE OccurrenceStartTime >= '{$this->year}-{$this->month}-01 00:00:00' "
				. "AND OccurrenceStartTime <= '{$this->year}-{$this->month}-31 23:59:59' "
				. "AND OccurrenceStartTime >= '" . date('Y-m-d') . " 00:00:00' "
				. "ORDER BY OccurrenceStartTime";

		$database->setQuery($query);

		$rows = $database->loadObjectList();

		foreach ($rows as $row) {
			preg_match('/(\d+)-(\d+)-(\d+)/', $row->OccurrenceStartTime, $matches);
			$days[] = $matches[3];
		}

		return array_unique($days);
	}

	public function getMonthDrop($selected) {
		$months = array(
			'1' => 'January',
			'2' => 'February',
			'3' => 'March',
			'4' => 'April',
			'5' => 'May',
			'6' => 'June',
			'7' => 'July',
			'8' => 'August',
			'9' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December',
		);

		$select = '<select name="month" id="event_calendar_month_input">';

		foreach ($months as $val => $month) {

			$sel = '';

			if ($val == $selected) {
				$sel = 'selected';
			}

			$select .= '<option value="' . $val . '" ' . $sel . '>' . $month . '</option>';
		}

		$select .= '</select>';

		return $select;
	}

	public function renderMonthDays() {
		$weeks = $this->getWeeksArray();
		require 'month_days.php';
	}
}
