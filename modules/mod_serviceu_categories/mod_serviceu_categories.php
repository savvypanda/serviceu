<?php defined('_JEXEC') or die('Restricted Access');

require_once JPATH_BASE . '/components/com_serviceu/models/eventlist.php';

$search = ServiceuModelEventlist::getSearchValues();
$categories = ServiceuModelEventlist::getEventCategories();
$departments = ServiceuModelEventlist::getDepartments();
$accessible = ServiceuModelEventlist::getAccessibleCategory();

require(JModuleHelper::getLayoutPath('mod_serviceu_categories'));
