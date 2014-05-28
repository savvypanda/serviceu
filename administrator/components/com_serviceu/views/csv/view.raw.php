<?php defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class ServiceuViewCsv extends JViewLegacy {
	public function display($tpl = null) {
		$data = $this->get('Data');

		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/csv');

		header('Content-Disposition: attachment;filename="events.csv"');
		header('Cache-Control: max-age=0');

		$headers = array(
			'Event Name',
			'Description',
			'Confirmation #',
			'Event Start Time',
			'Event End Time',
			'Resource Start Time',
			'Resource End Time',
			'Resource List',
			'Location',
			'Status Description',
			'Department List',
			'Category List',
			'Submitted By',
			'Ticket Information'
		);

		$fp = fopen('php://output', 'w');
		fputcsv($fp, $headers);

		foreach ($data as $row) {
			$csv = array(
				$row->Name,
				$row->Description,
				$row->EventId,
				$row->OccurrenceStartTime,
				$row->OccurrenceEndTime,
				$row->ResourceStartTime,
				$row->ResourceEndTime,
				$row->ResourceList,
				$row->LocationName,
				$row->StatusDescription,
				$row->DepartmentList,
				$row->CategoryList,
				$row->SubmittedBy,
				$row->TicketingDescription
			);

			fputcsv($fp, $csv);
		}

		fclose($fp);
	}
}