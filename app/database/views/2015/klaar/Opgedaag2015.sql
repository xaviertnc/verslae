CREATE VIEW `Opgedaag2015` AS SELECT `r`.`volleNaam` AS `volleNaam`,
    `r`.`epos` AS `epos`,
    `tblgeslag`.`beskrywing` AS `geslag`,
    `r`.`vorigebesoek` AS `vorige_besoek`,
    `r`.`solidariteitlid` AS `solidariteit_lid`,
    `r`.`solidariteitkontak` AS `solidariteit_kan_kontak`,
    `tblsalkoop`.`beskrywing` AS `beplan_om_te_koop`,
    `tbldagname`.`dagNaam` AS `opgedaag_dag`,
    `tblopgedaag`.`kinders`,
    `tblopgedaag`.`afgemerk_deur`
FROM `Registrasies2015` `r`
LEFT JOIN `tblgeslag` ON `r`.`geslag_id` = `tblgeslag`.`id`
LEFT JOIN `tblsalkoop` ON `r`.`beplaninvesteer_id` = `tblsalkoop`.`id`
LEFT JOIN `tblopgedaag` ON `r`.`registrasie_id` = `tblopgedaag`.`registrasie_id`
LEFT JOIN `tbldagname` ON ((`tblopgedaag`.`dag` = `tbldagname`.`id`) AND (`tbldagname`.`ekspo_id` = 5))
WHERE (`r`.`ekspo_id` = 5) ORDER BY `tblopgedaag`.`dag`;
