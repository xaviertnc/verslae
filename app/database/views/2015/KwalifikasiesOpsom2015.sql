CREATE VIEW `KwalifikasiesOpsom2015` AS SELECT
    `r`.`kwalifikasie_id` AS `kwalifikasie_id`,
    `tblkwalifikasies`.`beskrywing` AS `Kwalifikasie`,
    COUNT(`r`.`registrasie_id`) AS `Aantal`
FROM `Registrasies2015` `r`
LEFT JOIN `tblkwalifikasies` on `r`.`kwalifikasie_id` = `tblkwalifikasies`.`id`
GROUP BY  `r`.`kwalifikasie_id`;
