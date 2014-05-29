<?php defined('_JEXEC') or die('Restricted access'); ?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>media/com_serviceu/colorbox/colorbox.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" charset="utf-8" src="<?php echo JURI::base() ?>media/com_serviceu/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function (){
		jQuery('a.savvypandaremove').colorbox({width: 800, height: 650, iframe: true});
	});
</script>
<div id="event">
	<div class="event_titles">
		<h2><?php echo htmlentities($this->row->Name) ?></h2>
	</div>
	<div class="wrap_event">
		<div class="left_side_event">
			<div class="event_desc">
				<div id="event_description">
					<?php echo $this->row->Description ?>
				</div>
			</div>
			<?php if ($this->row->accessible): ?>
				<div class="access_this_event">
					<span class="wheel_chair">&#x267f;</span> <a type="text/html" rel="width[500];height[140]" class="jcepopup noicon" target="_blank" href="index.php?option=com_content&view=article&id=4&Itemid=251">Our Accessibility Policy</a>
				</div>
			<?php endif ?>
			<div id="event_social_sharing">
				<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					<a class="addthis_button_compact"></a>
					<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f7e15084c6e0df5"></script>
			</div>
			<div id="event_contact">
				<p class="event_cont">Do you have questions about this event?</p>
				<p><strong>Contact:</strong> <?php echo htmlentities($this->row->ContactName) ?> <a href="mailto:<?php echo htmlentities($this->row->ContactEmail) ?>"><?php echo htmlentities($this->row->ContactEmail) ?></a></p>
			</div>
		</div>
		<div class="right_side_event">
			<div class="right_side_box">
				<h3>Date and Time</h3>
				<div id="event_date_and_time">
					<?php echo htmlentities($this->row->date) ?><br />
					<?php echo htmlentities($this->row->start_time) ?><?php if (strlen($this->row->end_time)): ?>
						to <?php echo htmlentities($this->row->end_time) ?>
					<?php endif ?>
				</div>
			</div>
			<div class="right_side_box">
				<h3>Location</h3>
				<div id="event_location">
					<a href="http://maps.google.com/maps?q=<?php echo urlencode($this->row->LocationAddress . ' ' . $this->city_state_zip) ?>" target="_blank"><?php echo htmlentities($this->row->LocationName) ?><br />
						<?php echo htmlentities($this->row->LocationAddress) ?><br />
						<?php echo htmlentities($this->city_state_zip) ?><br />
						<?php if (strlen($this->row->ContactPhone)): ?>
							<?php echo htmlentities($this->row->ContactPhone) ?>
						<?php endif ?></a>
				</div>
			</div>
			<div class="right_side_box">
				<h3>Price</h3>
				<div id="event_price">
					<?php if (strlen($this->row->TicketingDescription)): ?>
						<?php echo htmlentities($this->row->TicketingDescription) ?>
					<?php else: ?>
						No price information.
					<?php endif ?>

				</div>
			</div>
			<?php if ($this->row->RegistrationEnabled == 1): ?>
				<div id="event_register_button">
					<a href="<?php echo $this->row->RegistrationUrl ?>" class="savvypandaremove">Click Here to Register</a>
				</div>
			<?php endif ?>
			<div id="event_add_to_calendar">
				<a href="http://public.serviceu.com/calendar/VCalendarFormat.asp?EventID=<?php echo htmlentities($this->row->EventId) ?>&OccID=<?php echo htmlentities($this->row->OccurrenceId) ?>&orgKey=d6e74e07-2a49-4172-b759-0ec0a1068800">Add to My Calendar</a>
			</div>
		</div>
		<div class="clearout"></div>
	</div>
	<div class="event_cal_bottom">
		<div id="event_calendar_link">
			<a href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&Itemid=191') ?>">Back to Calendar</a>
		</div>
	</div>
</div>


<?php /* defined( '_JEXEC' ) or die('Restricted access'); ?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>media/com_serviceu/colorbox/colorbox.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" charset="utf-8" src="<?php echo JURI::base() ?>media/com_serviceu/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function (){
	jQuery('a.modal').colorbox({width: 800, height: 650, iframe: true});
});
</script>
<div id="event">
	<h2><?php echo htmlentities($this->row->Name) ?></h2> <?php if ($this->row->accessible): ?>
		<span class="accessible-event">&#x267f;</span>
	<?php endif ?>

	<div id="event_description">
		<?php echo $this->row->Description ?>
	</div>

	<div id="event_social_sharing">
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f7e15084c6e0df5"></script>
	</div>

	<div id="event_contact">
		<p>Do you have questions about this event?</p>
		<p><strong>Contact:</strong> <?php echo htmlentities($this->row->ContactName) ?> <a href="mailto:<?php echo htmlentities($this->row->ContactEmail) ?>"><?php echo htmlentities($this->row->ContactEmail) ?></a></p>
	</div>

	<div id="event_calendar_link">
		<a href="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist') ?>">Back to Calendar</a>
	</div>

	<h3>Date and Time</h3>
	<div id="event_date_and_time">
		<?php echo htmlentities($this->row->date) ?><br />
		<?php echo htmlentities($this->row->start_time) ?><?php if (strlen($this->row->end_time)): ?>
			to <?php echo htmlentities($this->row->end_time) ?>
		<?php endif ?>
	</div>

	<h3>Location</h3>
	<div id="event_location">
		<a href="http://maps.google.com/maps?q=<?php echo urlencode($this->row->LocationAddress . ' ' . $this->city_state_zip) ?>" target="_blank"><?php echo htmlentities($this->row->LocationName) ?><br />
		<?php echo htmlentities($this->row->LocationAddress) ?><br />
		<?php echo htmlentities($this->city_state_zip) ?><br />
		<?php if (strlen($this->row->ContactPhone)): ?>
		<?php echo htmlentities($this->row->ContactPhone) ?>
		<?php endif ?></a>
	</div>

	<h3>Price</h3>

	<div id="event_price">
		<?php if (strlen($this->row->TicketingDescription)): ?>
		<?php echo htmlentities($this->row->TicketingDescription) ?>
		<?php else: ?>
		No price information.
		<?php endif ?>

	</div>

	<?php if ($this->row->RegistrationEnabled == 1): ?>
	<div id="event_register_button">
		<a href="<?php echo $this->row->RegistrationUrl ?>" class="modal">Click Here to Register</a>
	</div>
	<?php endif ?>

	<div id="event_add_to_calendar">
		<a href="http://public.serviceu.com/calendar/VCalendarFormat.asp?EventID=<?php echo htmlentities($this->row->EventId) ?>&amp;OccID=<?php echo htmlentities($this->row->OccurrenceId) ?>&amp;orgKey=d6e74e07-2a49-4172-b759-0ec0a1068800">Add to My Calendar</a>
	</div>
</div>

 */