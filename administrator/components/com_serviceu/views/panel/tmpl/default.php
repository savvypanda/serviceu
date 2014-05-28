<?php
defined( '_JEXEC' ) or die;

$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_serviceu/views/panel/tmpl/jquery-1.7.2.min.js');
$document->addScript(JURI::base() . 'components/com_serviceu/views/panel/tmpl/default.js');
$document->addStyleSheet(JURI::base() . 'components/com_serviceu/views/panel/tmpl/default.css');

?>

<p><input type="button" value="Force API Update" id="update_serviceu" /></p>
<p id="update_serviceu_status">&nbsp;</p>

<p>Time last updated: <span><?php echo JHTML::_('date', $this->last_update, JText::_('DATE_FORMAT_LC2')) ?></span></p>