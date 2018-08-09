-- create the evaluated results table used in audit trail
CREATE TABLE `clean`.`evaluated_results` ( 
	`id` INT NOT NULL , 
	`pt_id` INT NOT NULL , 
	`participant_id` INT NOT NULL , 
	`reason_for_change` VARCHAR(300) NOT NULL , 
	`results` TEXT NOT NULL , 
	`user_id` INT NOT NULL , 
	`deleted_at` TIMESTAMP NOT NULL , 
	`created_at` TIMESTAMP NOT NULL , 
	`updated_at` TIMESTAMP NOT NULL );