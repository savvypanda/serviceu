<?php defined('_JEXEC') or die('Restricted access');
$activemenu = JFactory::getApplication()->getMenu()->getActive();
?>

<link rel="stylesheet" href="<?php echo JURI::base() ?>media/com_serviceu/colorbox/colorbox.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" charset="utf-8" src="<?php echo JURI::base() ?>media/com_serviceu/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function (){
		jQuery('a.savvypandaremove').each(function() {jQuery(this).colorbox({width: 800, height: 650, iframe: true})});
	});
</script>

<span class="calendar-row">
<a class="calendar-link" href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&filter=week') ?>">Browse Current Week</a>&nbsp;|&nbsp;<a class="calendar-link" href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&filter=month') ?>">Browse Current Month</a>
</span>

<hr class="page-divider">
<?php foreach ($this->events as $date => $events): ?>
<div>
	<div class="calendar-date"><?php echo htmlentities($date) ?></div>
	<?php foreach ($events as $row): ?>
		<div class="calendar-row">
			<div class="left"><?php echo htmlentities($row->start_time) ?> - <?php echo htmlentities($row->end_time) ?></div>
			<div class="right">
				<div class="rights_1">
					<strong><a href="<?php echo JRoute::_('index.php?option=com_serviceu&amp;view=event&amp;id='. $row->events_id .($activemenu?('&Itemid='.$activemenu->id):'')) ?>"><?php echo htmlentities($row->Name) ?></a>
						<?php if (isset($row->DeptAbbr)): ?>
							<span class="<?php echo $row->DeptAbbr ?>">(<?php echo $row->DeptAbbr ?>)</span>
						<?php endif ?>
						<?php if (in_array($this->accessible, $row->categories)): ?>
							<span class="accessible-event">&#x267f;</span>
						<?php endif ?>
					</strong>
				</div>
				<div class="rights_2">
					<?php if ($row->RegistrationEnabled == 1): ?>
						<div id="event_register_button">
							<a href="<?php echo $row->RegistrationUrl ?>" class="savvypandaremove cboxElement">Click Here to Register</a>
						</div>
					<?php endif ?>
				</div>
				<div class="clearout"></div>
			</div>
		</div>
	<?php endforeach ?>
</div>
<?php endforeach ?>

<div id="navigation">
	<span><?php echo $this->pagination->getPagesLinks(); ?></span>
	<span><?php echo $this->pagination->getPagesCounter(); ?></span>
</div>








<?php /* defined('_JEXEC') or die('Restricted access');
$activemenu = JFactory::getApplication()->getMenu()->getActive();
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>media/com_serviceu/colorbox/colorbox.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" charset="utf-8" src="<?php echo JURI::base() ?>media/com_serviceu/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function (){
		jQuery('a.modal').each(function(){colorbox({width: 800, height: 650, iframe: true})});
	});
</script>

<span class="calendar-row">
	<a class="calendar-link" href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&filter=week'.($activemenu?('&Itemid='.$activemenu->id):'')) ?>">Browse Current Week</a>&nbsp;|&nbsp;<a class="calendar-link" href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&filter=month'.($activemenu?('&Itemid='.$activemenu->id):'')) ?>">Browse Current Month</a>
</span>

<hr class="page-divider">

<?php foreach ($this->events as $date => $events): ?>
<div>
  <div class="calendar-date"><?php echo htmlentities($date) ?></div>
  <?php foreach ($events as $row): ?>
  <div class="calendar-row">
    <div class="left"><?php echo htmlentities($row->start_time) ?> - <?php echo htmlentities($row->end_time) ?></div>
    <div class="right">
		<strong><a href="<?php echo JRoute::_('index.php?option=com_serviceu&amp;view=event&amp;id='. $row->events_id . ($activemenu?('&Itemid='.$activemenu->id):'')) ?>"><?php echo htmlentities($row->Name) ?></a>
			<?php if (in_array($this->accessible, $row->categories)): ?>
				<span class="accessible-event">&#x267f;</span>
			<?php endif ?>
			<?php if (isset($row->DeptAbbr)): ?>
				<span class="<?php echo $row->DeptAbbr ?>">(<?php echo $row->DeptAbbr ?>)</span>
			<?php endif ?>
		</strong>
	</div>
	<?php if ($row->RegistrationEnabled == 1): ?>
	 	<div id="event_register_button">
			<a href="<?php echo $row->RegistrationUrl ?>" class="modal">Click Here to Register</a>
	 	</div>
	 <?php endif ?>
  </div>
  <?php endforeach ?>
</div>
<?php endforeach ?>

<div id="navigation">
	<span><?php echo $this->pagination->getPagesLinks(); ?></span>
	<span><?php echo $this->pagination->getPagesCounter(); ?></span>
</div>

*/