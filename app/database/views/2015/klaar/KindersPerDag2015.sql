CREATE VIEW `KindersPerDag2015` AS SELECT `d`.`dagNaam` AS `dag_naam`, `d`.`jaar` AS `jaar`, sum(`o`.`kinders`) AS `kinders_totaal_per_dag`
FROM `tblopgedaag` `o` LEFT JOIN `tbldagname` `d` on ((`d`.`ekspo_id` = 5) AND (`o`.`dag` = `d`.`dag`))
LEFT JOIN `tblregistrasies` `r` on (`o`.`registrasie_id` = `r`.`id`)
WHERE `r`.`ekspo_id` = '5'
GROUP BY  `o`.`dag`;
