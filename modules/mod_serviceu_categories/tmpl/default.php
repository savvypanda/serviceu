<?php defined( '_JEXEC' ) or die; ?>

<form action="<?php echo JRoute::_('index.php?option=com_serviceu&view=eventlist&Itemid=' . JSite::getMenu()->getActive()->id) ?>" method="get">

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
	<input type="hidden" name="Itemid" value="<?php echo JSite::getMenu()->getActive()->id ?>" />


	<input type="submit" value="Click here to filter results" />

</form>

