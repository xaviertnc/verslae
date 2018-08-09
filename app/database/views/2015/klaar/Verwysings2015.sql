CREATE VIEW `Verwysings2015` AS SELECT `b`.`volleNaam` AS `volleNaam`,
        `b`.`epos` AS `epos`,
        `r`.`vw_naam1` AS `vw_naam1`,
        `r`.`vw_van1` AS `vw_van1`,
        `r`.`vw_epos1` AS `vw_epos1`,
        `r`.`vw_naam2` AS `vw_naam2`,
        `r`.`vw_van2` AS `vw_van2`,
        `r`.`vw_epos2` AS `vw_epos2`,
        `r`.`vw_naam3` AS `vw_naam3`,
        `r`.`vw_van3` AS `vw_van3`,
        `r`.`vw_epos3` AS `vw_epos3`
FROM (`tblregistrasies` `r`
LEFT JOIN `tblbesoekers` `b` on((`r`.`besoeker_id` = `b`.`id`)))
WHERE (`r`.`ekspo_id` = 5);
