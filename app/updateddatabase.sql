update projectsource set id = id+10;
insert into leadsources 
(`id`,`source`,`type`,`description`,`reference`,`datefrom`,`dateto`,`user_id`,`leadstatus`,`created_at`,`updated_at`)

SELECT `id`,`source`,'project',`description`,`reference`,`datefrom`,`dateto` ,'1','1',`created_at`,`updated_at` FROM `projectsource`;

UPDATE locations SET created_at = '2014-04-01 00:00:00' WHERE CAST(created_at AS CHAR(20)) = '0000-00-00 00:00:00';
UPDATE locations SET updated_at = NULL WHERE CAST(updated_at AS CHAR(20)) = '0000-00-00 00:00:00';

ALTER TABLE `locations` CHANGE `created_at` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` DATETIME NULL DEFAULT NULL;

INSERT INTO `leadsources` (`id`, `source`, `type`, `description`, `reference`, `datefrom`, `dateto`, `filename`, `user_id`, `leadstatus`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'National Account Locations', 'locations', 'National account locations - do not delete', NULL, '2014-04-02', '2028-12-31', NULL, '1', '1', '2014-04-01 00:00:00', NULL, NULL);

insert into addresses (`addressable_id`,`company_id`,`lat`,`lng`,`businessname`,`street`,`address2`,`city`,`state`,`zip`,`phone`,`contact`,`segment`,`businesstype`,`geostatus`,`addressable_type`,`lead_source_id`,`vertical`,`created_at`,`updated_at`)
SELECT locations.id,company_id,lat,lng,businessname,street,address2,city,state,zip,phone,contact,segment,businesstype,geostatus,type,'18',vertical,locations.created_at,locations.updated_at 
FROM `locations`,companies where locations.company_id = companies.id ;

update leads set type = 'lead';

insert into addresses (`addressable_id`,`lat`,`lng`,`businessname`,`street`,`city`,`state`,`zip`,`phone`,`addressable_type`,`lead_source_id`,`created_at`,`updated_at`)
SELECT leads.id,lat,lng,businessname,address,city,state,zip,phone,type,lead_source_id,leads.created_at,leads.updated_at 
FROM `leads`;


insert into addresses (`addressable_id`,`lat`,`lng`,`businessname`,`street`,`address2`,`city`,`state`,`zip`,`addressable_type`,`lead_source_id`,`vertical`,`created_at`,`updated_at`)
SELECT projects.id,lat,lng,`project_title`,street,`addr2`,city,state,`zipcode`,type,project_source_id,'3',projects.created_at,projects.updated_at 
FROM `projects`;

ALTER TABLE `projects` DROP FOREIGN KEY `projects_project_source_foreign_key`; ALTER TABLE `projects` ADD CONSTRAINT `projects_project_source_foreign_key` FOREIGN KEY (`project_source_id`) REFERENCES `leadsources`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES ('14', 'EVP', CURRENT_TIMESTAMP, NULL);