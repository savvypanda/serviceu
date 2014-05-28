<?php defined( '_JEXEC' ) or die; ?>
<h4>Date Selection</h4>

<script type="text/javascript" charset="utf-8">

jQuery(document).ready(function () {

	jQuery('#event_calendar_date_picker')[0].reset();

	var Calendar = {
		current_month: parseInt(jQuery('#event_calendar_month_input').val(), 10),
		current_year: parseInt(jQuery('#event_calendar_year_input').val(), 10),
		itemid: jQuery('#rsevents_calendar_module_itemid').val()
	};

	Calendar.increment_month = function (increment) {
		var to_set = Calendar.current_month + increment;

		Calendar.change_month(to_set);
	};

	Calendar.change_month = function (month_number) {
		month_number = parseInt(month_number);
		while(month_number < 1) {
			Calendar.current_year--;
			month_number+=12;
		}
		while(month_number > 12) {
			Calendar.current_year++;
			month_number-=12;
		}

		Calendar.current_month = month_number;

		var url = 'index.php?option=com_serviceu&view=calendar&format=raw&month=' + Calendar.current_month + '&year=' + Calendar.current_year + '&Itemid=' + Calendar.itemid;

		jQuery.get(url, function(content){
			jQuery('#month_day_container').html(content);
		});

		jQuery('#event_calendar_month_input').val(Calendar.current_month);
		jQuery('#event_calendar_year_input').val(Calendar.current_year);
	};

	jQuery('#event_calendar_month_input').change(function  () {
		Calendar.change_month(jQuery(this).val());
	});

	jQuery('a.calendar-left').click(function  (e) {
		e.stopPropagation();
		Calendar.increment_month(-1);
	});

	jQuery('a.calendar-right').click(function  (e) {
		e.stopPropagation();
		Calendar.increment_month(1);
	});

});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&Itemid=' . JSite::getMenu()->getActive()->id) ?>" method="get" id="event_calendar_date_picker">

	<div id="rsevents_calendar_module" class="rsevents_calendar_module">

		<div class="date-keys">

			<a class="calendar-left" rel="nofollow"></a>

			<div class="date">
				<?php echo $drop ?>
				<input type="hidden" name="year" id="event_calendar_year_input" value="<?php echo date('Y'); ?>" />
			</div>

			<a class="calendar-right" rel="nofollow"></a>

		</div>

		<div class="days">
			<div class="day">M</div>
			<div class="day">T</div>
			<div class="day">W</div>
			<div class="day">T</div>
			<div class="day">F</div>
			<div class="day">S</div>
			<div class="day">S</div>
		</div>

		<div id="month_day_container">
			<?php echo $cal->renderMonthDays(); ?>
		</div>
	</div>
	<input type="hidden" name="rsevents_calendar_module_itemid" value="<?php echo JSite::getMenu()->getActive()->id ?>" id="rsevents_calendar_module_itemid" />
</form>