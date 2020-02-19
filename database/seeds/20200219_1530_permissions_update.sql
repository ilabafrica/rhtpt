-- Insert new permission
INSERT IGNORE INTO permissions (name, display_name, description, created_at) VALUES ('generate-participant-result-form','generate-participant-result-form','Allows user to generate/view participant results entry form in PDF', now());
-- Grant the admin role (ID=1) this new permission
INSERT IGNORE INTO permission_role (permission_id, role_id) SELECT id, 1 FROM permissions;

