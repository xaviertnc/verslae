CREATE VIEW `Onafhanklikheid2015` AS SELECT `b`.`volleNaam` AS `volleNaam`,
        `b`.`epos` AS `epos`,
        `r`.`oh_gladnie` AS `oh_gladnie`,
        `r`.`oh_geeneskom` AS `oh_geeneskom`,
        `r`.`oh_sonpanele` AS `oh_sonpanele`,
        `r`.`oh_songeiser` AS `oh_songeiser`,
        `r`.`oh_ledligte` AS `oh_ledligte`,
        `r`.`oh_biogas` AS `oh_biogas`,
        `r`.`oh_altboumat` AS `oh_altboumat`
FROM (`tblregistrasies` `r`
LEFT JOIN `tblbesoekers` `b` on((`r`.`besoeker_id` = `b`.`id`)))
WHERE (`r`.`ekspo_id` = 5);
