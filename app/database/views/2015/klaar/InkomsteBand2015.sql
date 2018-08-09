CREATE VIEW `InkomsteBand2015` AS SELECT `r`.`epos` AS `epos`,
        `r`.`volleNaam` AS `volleNaam`,
        `b`.`band_beskrywing` AS `band_beskrywing`,
        `r`.`inkomsteband_id` AS `inkomsteband_id`
FROM (`Registrasies2015` `r`
LEFT JOIN `tblinkomsteband` `b` on((`r`.`inkomsteband_id` = `b`.`id`)))
WHERE (`r`.`ekspo_id` = 5);
