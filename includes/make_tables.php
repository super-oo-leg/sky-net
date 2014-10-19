<?php # Making DB tables for project - setup.php

// Only local PC can use this page
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') die('GO TO HELL!!!');

// Making a DB connection
require_once 'driver.php';
$driver = new Driver();

// Makes new table
$newTable = function($name, $query) use ($driver) {
	$driver->query("CREATE TABLE IF NOT EXISTS `$name` ($query) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	echo "Table $name created or already exists.<br />";
};

// HTML
echo '<html><head><title>Setting up database</title></head><body><h1>Setting up...</h1>';

// Creating 4 tables
$newTable('members',
	'`user` varchar(16) NOT NULL,
	`pass` char(40) NOT NULL,
	`ip` varchar(30) NOT NULL,
	`register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`user`),
	KEY `user` (`user`(6))'
);
$newTable('messages',
	'`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`auth` varchar(16) NOT NULL,
	`recip` varchar(16) NOT NULL,
	`pm` char(1) NOT NULL,
	`time` int(10) unsigned NOT NULL,
	`message` varchar(4096) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `auth` (`auth`(6)),
	KEY `recip` (`recip`(6))'
);
$newTable('friends',
	'`user` varchar(16) NOT NULL,
	`friend` varchar(16) NOT NULL,
	KEY `user` (`user`(6)),
	KEY `friend` (`friend`(6))'
);
$newTable('profiles',
	'`user` varchar(16) NOT NULL,
	`text` varchar(4096) DEFAULT NULL,
	`firstname` varchar(20) DEFAULT NULL,
	`lastname` varchar(20) DEFAULT NULL,
	PRIMARY KEY (`user`),
	KEY `user` (`user`(6))'
);

// Closing DB connection
$driver->shutDown();

// Finilizing HTML
?><br />...done.</body></html>
