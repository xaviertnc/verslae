CREATE VIEW `Solidariteit2015` AS SELECT `r`.`volleNaam` AS `naam`,
     `r`.`epos`,
     `r`.`telefoon`,
     `r`.`selfoon`,
     `r`.`kantoorfoon`,
     `r`.`huisfoon`,
     `r`.`anderfoon`,
     `r`.`geboorteDatum` As `geboorte_datum`,
     `tblgeslag`.`beskrywing` AS `geslag`,
     `r`.`solidariteitlid` AS `solidariteit_lid`,
     `r`.`solidariteitkontak` AS `solidariteit_kan_kontak`
FROM `Registrasies2015` `r`
LEFT JOIN `tblgeslag` ON `r`.`geslag_id` = `tblgeslag`.`id`
WHERE `r`.`ekspo_id` = 5 AND (`r`.`solidariteitlid` = 1 OR `r`.`solidariteitkontak` = 1);
