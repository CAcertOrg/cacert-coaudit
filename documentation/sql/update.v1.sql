
# Change of table coauditsession to define the default coaudit session, INOPIAE 2014-11-26
ALTER TABLE coauditdb.coauditsession add COLUMN `default` TINYINT DEFAULT 0 AFTER `to`;

UPDATE coauditdb.coauditsession SET `default` = 1 where session_id=2;

# Add missing views to table view_rights, INOPIAE 2014-11-26
INSERT INTO coauditdb.view_rights (view_name, read_permission, write_permission, active) VALUES ('kpi', 28, 28, 1);
INSERT INTO coauditdb.view_rights (view_name, read_permission, write_permission, active) VALUES ('kpilist', 28, 28, 1);