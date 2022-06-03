CREATE TABLE tx_noti_subscription (
    uid int(11) NOT NULL auto_increment,
    pid int(11) NOT NULL default '0',

    user int(11) NOT NULL default '0',
    event_key tinytext,
    notifier_key tinytext,
    PRIMARY KEY (uid)
);
