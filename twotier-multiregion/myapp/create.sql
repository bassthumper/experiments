
use ebdb;

drop table myrecords;

CREATE TABLE `myrecords` 
(
  id MEDIUMINT NOT NULL AUTO_INCREMENT,
  ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `regionaz` varchar(20) default NULL,
  `value` varchar(20) default NULL,
  primary key (id)
) 
DEFAULT CHARSET=latin1 
;

insert into myrecords (regionaz, value) values ("test","myvalue");

select * from myrecords;


show tables;