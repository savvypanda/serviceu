<?php defined( '_JEXEC' ) or die;

jimport('joomla.application.component.model');

class ServiceuModelCsv extends JModelLegacy {
	private $data;

	public function getData() {
		if (!isset($this->data)) {
			$query = "SELECT * FROM #__serviceu_events LEFT JOIN #__serviceu_event_details USING(OccurrenceId)";
			$this->data = $this->_getList($query);
		}
		return $this->data;
	}
}