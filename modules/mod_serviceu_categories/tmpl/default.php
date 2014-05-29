<?php defined('_JEXEC') or die('Restricted Access');
$activemenu = JFactory::getApplication()->getMenu()->getActive();
?>

<div class="event_wrp">
	<form action="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist'.($activemenu?('&Itemid='.$activemenu->id):'')) ?>" method="get">
		<div class="event_middle">
			<input type="text" name="event_search" value="<?php echo htmlentities($search['event_search']) ?>" id="event_search" />
		</div>

		<p class="event_wrp_p"><em>To filter calendar results, check one or more of the categories below.</em></p>
		<?php foreach ($categories as $category): ?>
			<div class="event_foreach">
				<?php if (in_array($category->category_id, $search['categories'])): ?>
					<input type="checkbox" name="categories[]" value="<?php echo $category->category_id ?>" checked />
				<?php else: ?>
					<input type="checkbox" name="categories[]" value="<?php echo $category->category_id ?>" />
				<?php endif ?>
				&nbsp;<?php echo htmlentities($category->category_name) ?>
				<?php if ($category->category_name == $accessible): ?>
					<span class="accessible-event">&#x267f;</span>
				<?php endif ?>
				<div class="clearout"></div>
			</div>
		<?php endforeach ?>

		<p>&nbsp;</p>
		<p class="fac_filt"><em>Filter by Facility</em></p>
		<?php foreach ($departments as $department): ?>
			<div class="event_foreach">
				<?php if (in_array($department, $search['departments'])): ?>
					<input type="checkbox" name="departments[]" value="<?php echo htmlentities($department) ?>" checked />
				<?php else: ?>
					<input type="checkbox" name="departments[]" value="<?php echo htmlentities($department) ?>" />
				<?php endif ?>
				&nbsp;<?php echo htmlentities($department) ?>
				<div class="clearout"></div>
			</div>
		<?php endforeach ?>

		<div class="wrp_submit">
			<input type="submit" value="Click here to filter results" />
		</div>
		<input type="hidden" name="option" value="com_serviceu" />
		<input type="hidden" name="view" value="eventlist" />
		<?php if($activemenu): ?><input type="hidden" name="Itemid" value="<?php echo $activemenu->id; ?>" /><?php endif; ?>
	</form>
</div>




<?php /* defined('_JEXEC') or die('Restricted Access');
$activemenu = JFactory::getApplication()->getMenu()->getActive();
?>

<form action="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist'.($activemenu?('&Itemid='.$activemenu->id):'')) ?>" method="get">
	<input type="text" name="event_search" value="<?php echo htmlentities($search['event_search']) ?>" id="event_search" />

	<p><em>To filter calendar results, check one or more of the categories below.</em></p>
	<?php foreach ($categories as $category): ?>
		<?php if (in_array($category->category_id, $search['categories'])): ?>
			<input type="checkbox" name="categories[]" value="<?php echo $category->category_id ?>" checked />
		<?php else: ?>
			<input type="checkbox" name="categories[]" value="<?php echo $category->category_id ?>" />
		<?php endif ?>
		&nbsp;<?php echo htmlentities($category->category_name) ?>
		<?php if ($category->category_name == $accessible): ?>
			<span class="accessible-event">&#x267f;</span>
		<?php endif ?>
		<p>&nbsp;</p>
	<?php endforeach ?>

	<p>&nbsp;</p>
	<p><em>Filter by Facility</em></p>
	<?php foreach ($departments as $department): ?>
		<?php if (in_array($department, $search['departments'])): ?>
			<input type="checkbox" name="departments[]" value="<?php echo htmlentities($department) ?>" checked />
		<?php else: ?>
			<input type="checkbox" name="departments[]" value="<?php echo htmlentities($department) ?>" />
		<?php endif ?>
		&nbsp;<?php echo htmlentities($department) ?>
		<p>&nbsp;</p>
	<?php endforeach ?>

	<input type="hidden" name="option" value="com_serviceu" />
	<input type="hidden" name="view" value="eventlist" />
	<?php if($activemenu): ?><input type="hidden" name="Itemid" value="<?php echo $activemenu->id ?>" /><?php endif; ?>
	<input type="submit" value="Click here to filter results" />
</form>
*/