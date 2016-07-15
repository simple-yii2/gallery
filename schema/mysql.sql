create table if not exists `Gallery`
(
	`id` int(10) not null auto_increment,
	`active` tinyint(1) default 1,
	`alias` varchar(100) default null,
	`image` varchar(200) default null,
	`thumb` varchar(200) default null,
	`title` varchar(100) default null,
	`description` varchar(200) default null,
	primary key (`id`),
	key `alias` (`alias`)
) engine InnoDB;

create table if not exists `GalleryImage`
(
	`id` int(10) not null auto_increment,
	`gallery_id` int(10) not null,
	`file` varchar(200) default null,
	`thumb` varchar(200) default null,
	`title` varchar(100) default null,
	`description` varchar(200) default null,
	primary key (`id`),
	key `gallery_id` (`gallery_id`)
) engine InnoDB;
