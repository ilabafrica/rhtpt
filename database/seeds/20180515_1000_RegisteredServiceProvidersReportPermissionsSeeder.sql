-- Create new report permissions

INSERT IGNORE INTO permissions (name, display_name, description)
VALUES 
("reports-catalog", "Can view reports", "View Reports"),
("read-general-report", "Can view general report", "Can view general report"),
("read-participant-registration-counts-report", "Can view participant registration count report", "Can view participant registration count reports");

UPDATE permissions SET created_at = now() WHERE created_at = NULL;
UPDATE permissions SET updated_at = now() WHERE updated_at = NULL;

-- Update Admin permissions
INSERT IGNORE INTO permission_role (permission_id, role_id) SELECT id, 1 FROM permissions;