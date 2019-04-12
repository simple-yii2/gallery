create table if not exists `gallery`
(
    `id` int(10) not null auto_increment,
    `tree` int(10),
    `lft` int(10) not null,
    `rgt` int(10) not null,
    `depth` int(10) not null,
    `type` int(10) not null default 0,
    `active` tinyint(1) default 1,
    `alias` varchar(100) default null,
    `thumbWidth` int(10) default 360,
    `thumbHeight` int(10) default 270,
    `image` varchar(200) default null,
    `thumb` varchar(200) default null,
    `title` varchar(100) default null,
    `description` varchar(200) default null,
    `imageCount` int(10) default null,
    primary key (`id`),
    key `alias` (`alias`)
) engine InnoDB;

create table if not exists `gallery_image`
(
    `id` int(10) not null auto_increment,
    `gallery_id` int(10) not null,
    `file` varchar(200) default null,
    `thumb` varchar(200) default null,
    `title` varchar(100) default null,
    `description` varchar(200) default null,
    primary key (`id`),
    foreign key (`gallery_id`) references `gallery` (`id`) on delete cascade on update cascade,
    key `gallery_id` (`gallery_id`)
) engine InnoDB;
