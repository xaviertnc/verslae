CREATE VIEW `VoorheenBygewoon2015` AS SELECT
    `vorigebesoek`,
    COUNT(*) AS `VoorheenBygewoonTotaal`
FROM `tblregistrasies`
WHERE `ekspo_id` = 5
GROUP BY `vorigebesoek`;
