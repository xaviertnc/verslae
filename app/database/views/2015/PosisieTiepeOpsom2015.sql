CREATE VIEW `PosisieTiepeOpsom2015` AS SELECT
    `r`.`posisie_id` AS `posisie_id`,
    `tblondernemingposisies`.`beskrywing` AS `Tiepe Onderneming Posisie`,
    COUNT(`r`.`registrasie_id`) AS `Aantal`
FROM `Registrasies2015` `r`
LEFT JOIN `tblondernemingposisies` on `r`.`posisie_id` = `tblondernemingposisies`.`id`
GROUP BY  `r`.`posisie_id`;
