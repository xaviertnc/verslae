CREATE VIEW `TiepeOnderwysOpsom2015` AS SELECT
    `r`.`onderwys_id` AS `onderwys_id`,
    `tbltiepesonderwys`.`beskrywing` AS `Tiepe Onderwys`,
    COUNT(`r`.`registrasie_id`) AS `Aantal`
FROM `Registrasies2015` `r`
LEFT JOIN `tbltiepesonderwys` on `r`.`onderwys_id` = `tbltiepesonderwys`.`id`
GROUP BY  `r`.`onderwys_id`;
