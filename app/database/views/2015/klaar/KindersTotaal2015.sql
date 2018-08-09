CREATE VIEW `KindersTotaal2015` AS SELECT sum(`o`.`kinders`) AS `kinders_totaal`
FROM `tblopgedaag` `o` LEFT JOIN `tblregistrasies` `r` on (`o`.`registrasie_id` = `r`.`id`)
WHERE `r`.`ekspo_id` = '5'
