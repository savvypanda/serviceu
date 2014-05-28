<?php
defined( '_JEXEC' ) or die;

class TableServiceu_event_categories extends JTable
{
	public $category_id = null;
	public $category_name = null;

	public function __construct(&$db)
	{
		parent::__construct('#__serviceu_event_categories', 'category_id', $db);
	}
}
