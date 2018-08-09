CREATE VIEW `OpgedaagPerDag2015` AS SELECT `tbldagname`.`dagNaam` AS `dag`,
    `tbldagname`.`jaar` AS `jaar`,
    COUNT(`tblopgedaag`.`registrasie_id`) AS `opgedaag_totaal_per_dag`
FROM `tblopgedaag`
LEFT JOIN `tblregistrasies` ON (`tblopgedaag`.`registrasie_id` = `tblregistrasies`.`id`)
RIGHT JOIN `tbldagname` ON ((`tbldagname`.`ekspo_id` = 5) AND (`tblopgedaag`.`dag` = `tbldagname`.`dag`))
WHERE `tblregistrasies`.`ekspo_id` = '5'
GROUP BY  `tblopgedaag`.`dag`;
