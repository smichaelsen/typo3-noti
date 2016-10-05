CREATE TABLE tx_noti_subscription (
    uid int(11) NOT NULL auto_increment,
    pid int(11) NOT NULL default '0',

    addresses text,
    event varchar(255) NOT NULL default '',
    `text` text,

    crdate int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY (uid)
);
