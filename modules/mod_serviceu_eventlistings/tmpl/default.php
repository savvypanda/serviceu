<?php defined('_JEXEC') or die('Restricted Access');
$activemenu = JFactory::getApplication()->getMenu()->getActive();
?>

<h4>Event Listings</h4>
<?php foreach ($filled as $row): ?>
	<h5><?php echo htmlentities($row->Name) ?><?php if (in_array($accessible, $row->categories)): ?>
		<span class="accessible-event-listing">&#x267f;</span>
	<?php endif ?></h5>
	<p><?php echo htmlentities($row->LocationName) ?></p>
	<p><?php echo $row->date ?>
	<p><?php echo $row->start_time ?>
	<?php if ($row->end_time): ?>
		- <?php echo $row->end_time ?>
	<?php endif ?></p>
	<p><a href="<?php echo JRoute::_('index.php?option=com_serviceu&view=event&id=' . $row->events_id . ($activemenu?('&Itemid='.$activemenu->id):'')) ?>">Details &gt;</a></p>
<?php endforeach ?>