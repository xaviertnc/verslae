CREATE VIEW `RegistrasieTiepesOpgedaag2015` AS SELECT `r`.`registrasietiepe_id` AS `registrasietiepe_id`,
`t`.`beskrywing` AS `beskrywing`, count(`r`.`id`) AS `aantal`
FROM (`tblregistrasies` `r` LEFT JOIN `tblregistrasietiepes` `t` on((`r`.`registrasietiepe_id` = `t`.`id`)))
WHERE ((`r`.`ekspo_id` = 5) AND ((`r`.`registrasietiepe_id` <> 1) OR ((`r`.`verander_op` >= '2015-06-26') AND (`r`.`registrasietiepe_id` = 1))))
GROUP BY  `r`.`registrasietiepe_id`;
