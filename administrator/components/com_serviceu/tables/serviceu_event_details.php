<?php defined( '_JEXEC' ) or die;

class TableServiceu_event_details extends JTable {
	public $event_details_id = null;
	public $ContactEmail = null;
	public $ContactId = null;
	public $ContactName = null;
	public $ContactPhone = null;
	public $Description = null;
	public $DisplayTimes = null;
	public $ExternalEventUrl = null;
	public $LocationAddress = null;
	public $LocationAddress2 = null;
	public $LocationCity = null;
	public $LocationName = null;
	public $LocationState = null;
	public $LocationZip = null;
	public $Name = null;
	public $PublicEventUrl = null;
	public $SubmitterUserId = null;
	public $TicketingDescription = null;
	public $DateModified = null;
	public $DepartmentName = null;
	public $EventId = null;
	public $MaxDate = null;
	public $MinDate = null;
	public $Notes = null;
	public $OccurrenceEndTime = null;
	public $OccurrenceId = null;
	public $OccurrenceName = null;
	public $OccurrenceStartTime = null;
	public $RegistrationType = null;
	public $RegistrationUrl = null;
	public $ResourceList = null;

	public function __construct(&$db) {
		parent::__construct('#__serviceu_event_details', 'event_details_id', $db);
	}
}
