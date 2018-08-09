CREATE VIEW `Belangstellings2015` AS SELECT `b`.`volleNaam` AS `volleNaam`,
        `b`.`epos` AS `epos`,
        `r`.`bs_energie` AS `bs_energie`,
        `r`.`bs_nuweteg` AS `bs_nuweteg`,
        `r`.`bs_konstr` AS `bs_konstr`,
        `r`.`bs_opvoed` AS `bs_opvoed`,
        `r`.`bs_entrep` AS `bs_entrep`,
        `r`.`bs_veilig` AS `bs_veilig`,
        `r`.`bs_voedsel` AS `bs_voedsel`,
        `r`.`bs_buite` AS `bs_buite`,
        `r`.`bs_finans` AS `bs_finans`,
        `r`.`bs_fees` AS `bs_fees`
FROM (`tblregistrasies` `r`
LEFT JOIN `tblbesoekers` `b` on((`r`.`besoeker_id` = `b`.`id`)))
WHERE (`r`.`ekspo_id` = 5);
