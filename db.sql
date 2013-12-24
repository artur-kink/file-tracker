drop schema if exists file_tracker;
create schema file_tracker;

use file_tracker;

create table files(
	id int primary key AUTO_INCREMENT,
	computer nvarchar(20),
	name nvarchar(255) not null,
	extension nvarchar(10)
);