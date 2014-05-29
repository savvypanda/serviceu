CREATE TABLE IF NOT EXISTS `#__serviceu_events` (
	`events_id` SERIAL,
	`CategoryList` VARCHAR(511),
	`ContactEmail` VARCHAR(255),
	`ContactName` VARCHAR(255),
	`ContactPhone` VARCHAR(255),
	`DateModified` DATETIME,
	`DepartmentList` VARCHAR(255),
	`DepartmentName` VARCHAR(255),
	`Description` TEXT,
	`DisplayTimes` VARCHAR(255),
	`EventId` VARCHAR(255),
	`ExternalEventUrl` VARCHAR(255),
	`ExternalImageUrl` VARCHAR(255),
	`LocationAddress` VARCHAR(255),
	`LocationAddress2` VARCHAR(255),
	`LocationCity` VARCHAR(255),
	`LocationName` VARCHAR(255),
	`LocationState` VARCHAR(255),
	`LocationZip` VARCHAR(255),
	`Name` VARCHAR(255),
	`OccurrenceEndTime` DATETIME,
	`OccurrenceId` VARCHAR(255),
	`OccurrenceStartTime` DATETIME,
	`PublicEventUrl` VARCHAR(255),
	`RegistrationEnabled` VARCHAR(255),
	`RegistrationUrl` VARCHAR(255),
	`ResourceEndTime` DATETIME,
	`ResourceList` VARCHAR(255),
	`ResourceStartTime` DATETIME,
	`StatusDescription` VARCHAR(255),
	`SubmittedBy` VARCHAR(255),
	`last_sync_occurred` INT(11),
	PRIMARY KEY(`events_id`),
	UNIQUE(`OccurrenceId`)
);

CREATE TABLE IF NOT EXISTS `#__serviceu_event_details` (
	`event_details_id` SERIAL,
	`ContactEmail` VARCHAR (255),
	`ContactId` VARCHAR (255),
	`ContactName` VARCHAR (255),
	`ContactPhone` VARCHAR (255),
	`Description` TEXT,
	`DisplayTimes` VARCHAR (15),
	`ExternalEventUrl` VARCHAR (2083),
	`LocationAddress` VARCHAR (255),
	`LocationAddress2` VARCHAR (255),
	`LocationCity` VARCHAR (255),
	`LocationName` VARCHAR (255),
	`LocationState` VARCHAR (255),
	`LocationZip` VARCHAR (255),
	`Name` VARCHAR (255),
	`PublicEventUrl` VARCHAR (2083),
	`SubmitterUserId` VARCHAR (255),
	`TicketingDescription` VARCHAR (255),
	`DateModified` VARCHAR (255),
	`DepartmentName` VARCHAR (255),
	`EventId` VARCHAR (255),
	`MaxDate` VARCHAR (255),
	`MinDate` VARCHAR (255),
	`Notes` VARCHAR (255),
	`OccurrenceEndTime` VARCHAR (255),
	`OccurrenceId` VARCHAR (255),
	`OccurrenceName` VARCHAR (255),
	`OccurrenceStartTime` VARCHAR (255),
	`RegistrationType` VARCHAR (255),
	`RegistrationUrl` VARCHAR (255),
	`ResourceList` VARCHAR (255),
	PRIMARY KEY(`event_details_id`),
	UNIQUE(`OccurrenceId`)
);

CREATE TABLE IF NOT EXISTS `#__serviceu_event_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(255) NOT NULL DEFAULT '' COLLATE latin1_bin,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
);


CREATE TABLE IF NOT EXISTS `#__serviceu_event_assigned_categories` (
  `EventId` varchar(255) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL,
  UNIQUE KEY `EventId` (`EventId`,`category_id`),
  KEY `EventId_2` (`EventId`)
);

CREATE TABLE IF NOT EXISTS `#__serviceu_events_last_updated` (
	`timestamp` varchar(255)
);