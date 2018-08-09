CREATE VIEW `RegistrasiesTotaal2015` AS SELECT
    COUNT(*) AS `RegistrasiesTotaal2015`
FROM `tblregistrasies`
WHERE `tblregistrasies`.`ekspo_id` = 5;
