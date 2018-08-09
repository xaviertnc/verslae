CREATE VIEW `OnafhanklikOpsom2015` AS SELECT count(`Onafhanklikheid2015`.`oh_gladnie`) AS `Geheel Afhanklik van Eskomkrag`,
        count(`Onafhanklikheid2015`.`oh_geeneskom`) AS `Onafhanklik van Eskomkrag`,
        count(`Onafhanklikheid2015`.`oh_sonpanele`) AS `Het Sonpanele`,
        count(`Onafhanklikheid2015`.`oh_songeiser`) AS `Het Songeiser`,
        count(`Onafhanklikheid2015`.`oh_ledligte`) AS `Het LED ligte`,
        count(`Onafhanklikheid2015`.`oh_biogas`) AS `Gebruik Biogas`,
        count(`Onafhanklikheid2015`.`oh_altboumat`) AS `Gebruik Alternatiewe Boumateriale`,
        count(*) AS `Totaal`
FROM `Onafhanklikheid2015`;
