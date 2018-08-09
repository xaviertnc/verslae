CREATE VIEW `InkomsteOpsom2015B` AS SELECT `r`.`inkomsteband_id` AS `inkomsteband_id`,
        `b`.`band_beskrywing` AS `Inkomste Band`,
        `k`.`beskrywing` AS `Sal Koop`,
        count(`r`.`epos`) AS `Inkomste Aantal`
FROM ((`Registrasies2015` `r`
LEFT JOIN `tblinkomsteband` `b` on((`r`.`inkomsteband_id` = `b`.`id`)))
LEFT JOIN `tblsalkoop` `k` on((`r`.`beplaninvesteer_id` = `k`.`id`)))
WHERE (`r`.`ekspo_id` = 5)
GROUP BY  `r`.`inkomsteband_id`,`r`.`beplaninvesteer_id`;
