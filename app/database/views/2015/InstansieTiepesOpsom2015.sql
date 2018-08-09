CREATE VIEW `InstansieTiepesOpsom2015` AS SELECT
    `r`.`instansietiepe_id` AS `instansietiepe_id`,
    `tbltiepeinstansies`.`beskrywing` AS `Tiepe Instansie`,
    COUNT(`r`.`registrasie_id`) AS `Aantal`
FROM `Registrasies2015` `r`
LEFT JOIN `tbltiepeinstansies` on `r`.`instansietiepe_id` = `tbltiepeinstansies`.`id`
GROUP BY  `r`.`instansietiepe_id`;
