CREATE TABLE tx_monthlyschedule_domain_model_mymonth (
	month int(11) NOT NULL DEFAULT '0',
	year int(11) NOT NULL DEFAULT '0',
	days int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_monthlyschedule_domain_model_myday (
	mymonth int(11) unsigned DEFAULT '0' NOT NULL,
	dayname varchar(255) NOT NULL DEFAULT '',
	timeslot varchar(255) NOT NULL DEFAULT '',
	confirm smallint(1) unsigned NOT NULL DEFAULT '0',
	person varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	topic varchar(255) NOT NULL DEFAULT ''
);
