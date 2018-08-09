CREATE VIEW `SolidariteitTotaal2015` AS SELECT SUM(`solidariteit_lid`) AS `solidariteit_lid_totaal`,
    SUM(`solidariteit_kan_kontak`) AS `solidariteit_kan_kontak_totaal`,
    COUNT(*) AS `lid_of_kan_kontak_totaal`
FROM `Solidariteit2015`
