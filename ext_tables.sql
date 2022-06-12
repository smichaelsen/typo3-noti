CREATE TABLE tx_noti_notification (
	uid int(11) NOT NULL auto_increment,
	pid int(11) NOT NULL default '0',

	user int(11) NOT NULL default '0',
	title tinytext,
	message text,
	is_message_html tinyint(4) DEFAULT '0' NOT NULL,
	icon_identifier tinytext,
	read tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid)
);

CREATE TABLE tx_noti_subscription (
	uid int(11) NOT NULL auto_increment,
	pid int(11) NOT NULL default '0',

	user int(11) NOT NULL default '0',
	event_key tinytext,
	notifier_key tinytext,
	PRIMARY KEY (uid)
);
