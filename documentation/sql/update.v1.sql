# Change of table coauditsession to define the default coaudit session, INOPIAE 2014-11-26
ALTER TABLE coauditsession ADD COLUMN `default` TINYINT DEFAULT 0 AFTER `to`;

UPDATE coauditsession SET `default` = 1 WHERE session_id = 2;

# Add missing views to table view_rights, INOPIAE 2014-11-26
INSERT INTO view_rights (view_name, read_permission, write_permission, active)
VALUES ( 'kpi', 28, 28, 1 ), ( 'kpilist', 28, 28, 1 );
