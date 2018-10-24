-- Create import result permission

INSERT IGNORE INTO permissions (name, display_name, description, created_at, updated_at)
VALUES 
("import-results", "Can import PT results from CSV file.", "Can import PT results from CSV file.", now(), now());

-- Update Admin permissions
INSERT IGNORE INTO permission_role (permission_id, role_id) SELECT id, 1 FROM permissions;

--Update permission to download results

--permissions table
INSERT IGNORE INTO permissions (name, display_name, description, created_at, updated_at)
VALUES 
("download-all-result", "Can download PT results to CSV file.", "Can download PT results to CSV file.", now(), now());

--permissions role table
INSERT IGNORE INTO permission_role (permission_id, role_id) SELECT id, 1 FROM permissions;
