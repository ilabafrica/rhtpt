-- Create new column in notifications

ALTER TABLE `notifications` 
ADD COLUMN `description` VARCHAR(45) NULL AFTER `message`;

ALTER TABLE `pt` 
ADD COLUMN `date_approved` DATE NULL AFTER `approved_comment`;


INSERT INTO notifications (template, message, description) 
VALUES 
('7', 'Your Verification Code is: ', 'Phone Verification Code');
('8', 'Dear [user->name], your Sub-county Coordinator has approved your request to participate in PT. Your tester ID is {user->tester id}. Use the link sent to your email to get started. ', 'Enabled Account'),
('9', 'Dear [user->name], your PT system account has been created. Use the link sent to your email address to get started.', 'Account Created'),
('10', 'Dear County/Sub County Coordinator, NPHL has created Round [round->name]. You have until {round->enrollment_date} to enroll participants into this round.', 'Round Created'),


UPDATE notifications SET description = 'Enrollment Message' WHERE template = 5;
UPDATE notifications SET description = 'Panels Dispatch' WHERE template = 1;
UPDATE notifications SET description = 'Results Submitted' WHERE template = 2;
UPDATE notifications SET description = 'Results Feedback' WHERE template = 3;
