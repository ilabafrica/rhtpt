-- Insert new permission
INSERT IGNORE INTO permissions (name, display_name, description, created_at) VALUES ('generate-pt-receipt-record','generate-pt-receipt-record','Allows user to generate/view PT receipt record in PDF', now());
-- Grant the admin role (ID=1) this new permission
INSERT IGNORE INTO permission_role (permission_id, role_id) SELECT id, 1 FROM permissions;

