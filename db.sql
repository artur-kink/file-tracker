drop schema if exists file_tracker;
create schema file_tracker;

use file_tracker;

create table computers(
	id int primary key AUTO_INCREMENT,
	name nvarchar(25) not null unique,
	ip nvarchar(16),
	host nvarchar(100),
	auth_key nvarchar(255),
	last_register_date TIMESTAMP not null,
	last_activity_date TIMESTAMP not null
);

create table files(
	id int primary key AUTO_INCREMENT,
	computer int not null,
	name nvarchar(255) not null,
	extension nvarchar(10),
	path nvarchar(255),
	size long,
	create_date datetime,
	modified_date datetime,
	added_date timestamp DEFAULT CURRENT_TIMESTAMP,
	foreign key (computer) references computers(id) on delete cascade
);

create view detailed_files as(
	select f.*, c.name as computer_name
	from files f
	inner join computers c on c.id = f.computer
);


delimiter $$
create procedure register_computer(IN in_name nvarchar(25), IN in_ip nvarchar(16),
	IN in_host nvarchar(100))
begin
	if (select count(*) from computers c where c.ip = in_ip and c.name = in_name) > 0 then
		select id, auth_key from computers c where c.ip = in_ip and c.name = in_name;
	else
		insert into computers(name, ip, host, auth_key) values(in_name, in_ip, in_host, 'temp');
		
		select id, auth_key from computers
		where id = last_insert_id();
	end if;
end$$

delimiter $$
create procedure check_authentication(IN id int, IN auth_key nvarchar(255))
begin
	if (select count(*) from computers c where c.id = id and c.auth_key = auth_key) > 0 then
		select 1 as result;
	else
		select 0 as result;
	end if;
end$$