<?php defined('_JEXEC') or die('Restricted Access'); ?>
<div class="month-days">
<?php foreach ($weeks as $week): ?>
<div class="month-days-line">
	<?php foreach ($week as $day): ?>
	<div class="month-day-cell <?php echo $day['class'] ?>">
		<a href="<?php echo $day['link'] ?>"><?php echo $day['day'] ?></a>
	</div>
	<?php endforeach ?>
</div>
<?php endforeach ?>
</div>