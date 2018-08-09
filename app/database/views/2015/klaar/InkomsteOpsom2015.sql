CREATE VIEW `InkomseOpsom2015` AS SELECT `r`.`inkomsteband_id` AS `inkomsteband_id`,
        `b`.`band_beskrywing` AS `Inkomste Band`,
        count(`r`.`epos`) AS `Aantal`
FROM (`Registrasies2015` `r`
LEFT JOIN `tblinkomsteband` `b` on((`r`.`inkomsteband_id` = `b`.`id`)))
WHERE (`r`.`ekspo_id` = 5)
GROUP BY  `r`.`inkomsteband_id`;
