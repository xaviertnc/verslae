CREATE VIEW `GeslagOpsom2015` AS SELECT
    `r`.`geslag_id` AS `geslag_id`,
    `tblgeslag`.`beskrywing` AS `Geslag`,
    COUNT(`r`.`registrasie_id`) AS `Aantal`
FROM `Registrasies2015` `r`
LEFT JOIN `tblgeslag` on `r`.`geslag_id` = `tblgeslag`.`id`
GROUP BY  `r`.`geslag_id`;
