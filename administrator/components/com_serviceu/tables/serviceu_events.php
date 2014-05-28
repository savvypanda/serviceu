<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class TableServiceu_events extends JTable {
	public $events_id = null;
	public $CategoryList = null;
	public $ContactEmail = null;
	public $ContactName = null;
	public $ContactPhone = null;
	public $DateModified = null;
	public $DepartmentList = null;
	public $DepartmentName = null;
	public $Description = null;
	public $DisplayTimes = null;
	public $EventId = null;
	public $ExternalEventUrl = null;
	public $ExternalImageUrl = null;
	public $LocationAddress = null;
	public $LocationAddress2 = null;
	public $LocationCity = null;
	public $LocationName = null;
	public $LocationState = null;
	public $LocationZip = null;
	public $Name = null;
	public $OccurrenceEndTime = null;
	public $OccurrenceId = null;
	public $OccurrenceStartTime = null;
	public $PublicEventUrl = null;
	public $RegistrationEnabled = null;
	public $RegistrationUrl = null;
	public $ResourceEndTime = null;
	public $ResourceList = null;
	public $ResourceStartTime = null;
	public $StatusDescription = null;
	public $SubmittedBy = null;
	public $last_sync_occurred = null;

	function __construct(&$db) {
		parent::__construct('#__serviceu_events', 'events_id', $db);
	}
}