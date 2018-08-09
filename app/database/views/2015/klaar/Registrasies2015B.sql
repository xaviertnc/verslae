CREATE VIEW `Registrasies2015B` AS SELECT `r`.`volleNaam` AS `volleNaam`,
    `r`.`epos` AS `epos`,
    `r`.`selfoon` AS `selfoon`,
    `tblgeslag`.`beskrywing` AS `geslag`,
    `r`.`vorigebesoek` AS `vorige_besoek`,
    `tblkwalifikasies`.`beskrywing` AS `kwalifikasie`,
    `tbltiepeinstansies`.`beskrywing` AS `tiepe_instansie`,
    `tblinkomsteband`.`band_beskrywing` AS `inkomsteband`,
    `tbltiepesonderwys`.`beskrywing` AS `tiepe_onderwys`,
    `tblondernemingposisies`.`beskrywing` AS `posisie_in_onderneming`,
    `tblwaargehoor`.`beskrywing` AS `waargehoor`,
    `tblsalkoop`.`beskrywing` AS `beplan_om_te_koop`,
    `r`.`solidariteitlid` AS `solidariteit_lid`,
    `r`.`solidariteitkontak` AS `solidariteit_kan_kontak`
FROM `Registrasies2015` `r`
LEFT JOIN `tblgeslag` ON `r`.`geslag_id` = `tblgeslag`.`id`
LEFT JOIN `tblkwalifikasies` ON `r`.`kwalifikasie_id` = `tblkwalifikasies`.`id`
LEFT JOIN `tbltiepeinstansies` ON `r`.`instansietiepe_id` = `tbltiepeinstansies`.`id`
LEFT JOIN `tblinkomsteband` ON `r`.`inkomsteband_id` = `tblinkomsteband`.`id`
LEFT JOIN `tbltiepesonderwys` ON `r`.`onderwys_id` = `tbltiepesonderwys`.`id`
LEFT JOIN `tblondernemingposisies` ON `r`.`posisie_id` = `tblondernemingposisies`.`id`
LEFT JOIN `tblwaargehoor` ON `r`.`waargehoor_id` = `tblwaargehoor`.`id`
LEFT JOIN `tblsalkoop` ON `r`.`beplaninvesteer_id` = `tblsalkoop`.`id`
WHERE (`r`.`ekspo_id` = 5);
