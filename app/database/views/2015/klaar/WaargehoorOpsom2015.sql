CREATE VIEW `WaargehoorOpsom2015` AS SELECT
    `r`.`waargehoor_id` AS `waargehoor_id`,
    `tblwaargehoor`.`beskrywing` AS `Waar gehoor`,
    COUNT(`r`.`id`) AS `Aantal`
FROM `tblregistrasies` `r`
LEFT JOIN `tblwaargehoor` on `r`.`waargehoor_id` = `tblwaargehoor`.`id`
WHERE `r`.`ekspo_id` = 5
GROUP BY  `r`.`waargehoor_id`;
