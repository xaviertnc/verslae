CREATE VIEW `BelangeOpsom2015` AS SELECT count(`Belangstellings2015`.`bs_energie`) AS `bs_energie_totaal`,
        count(`Belangstellings2015`.`bs_nuweteg`) AS `bs_nuweteg_totaal`,
        count(`Belangstellings2015`.`bs_konstr`) AS `bs_konstr_totaal`,
        count(`Belangstellings2015`.`bs_opvoed`) AS `bs_opvoed_totaal`,
        count(`Belangstellings2015`.`bs_entrep`) AS `bs_entrep_totaal`,
        count(`Belangstellings2015`.`bs_veilig`) AS `bs_veilig_totaal`,
        count(`Belangstellings2015`.`bs_voedsel`) AS `bs_voedsel_totaal`,
        count(`Belangstellings2015`.`bs_buite`) AS `bs_buite_totaal`,
        count(`Belangstellings2015`.`bs_finans`) AS `bs_finans_totaal`,
        count(`Belangstellings2015`.`bs_fees`) AS `bs_fees_totaal`
FROM `Belangstellings2015`;
