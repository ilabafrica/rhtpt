-- Create new column in notifications


INSERT INTO notifications (template, message, description) 
VALUES 
('11', 'Dear [user->name],Your HIV PT System account has been created. Your username is [user->username]. Use the link sent to your email to get started. ', 'User Updated'),
('12', 'Dear [user->name],  NPHL has disabled your account.', 'User Disabled'),
('13', 'Dear [user->name], NPHL has enabled your account. Once
enrolled, you’ll receive a tester ID.', 'User Restored'),
('14', 'Your Password Reset Verification Code is:', 'Password Reset Verification Code');


