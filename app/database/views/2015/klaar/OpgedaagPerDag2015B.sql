CREATE VIEW `OpgedaagPerDag2015B` AS SELECT
    `tbldagname`.`dagNaam` AS `dag`,
    COUNT(`tblopgedaag`.`dag`) AS `hoveelhed_opgedaag`,
    SUM(`tblopgedaag`.`kinders`) AS `hoveelheid_kinders`,
    COUNT(*) AS `hoeveeld_registrsies`
FROM `tblregistrasies` `r`
LEFT JOIN `tblopgedaag` ON `r`.`id` = `tblopgedaag`.`registrasie_id`
LEFT JOIN `tbldagname` ON ((`tblopgedaag`.`dag` = `tbldagname`.`id`) AND (`tbldagname`.`ekspo_id` = 5))
WHERE (`r`.`ekspo_id` = 5) GROUP BY `tblopgedaag`.`dag`;
