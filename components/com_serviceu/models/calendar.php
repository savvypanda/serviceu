<?php
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.model');

class ServiceuModelCalendar extends JModel
{
	private $data;

	public function getData()
	{
		return $this->data;
	}
}