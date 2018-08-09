<?php

return array(
    'database' => array(
        'connections' => array(
            'mysql' => array(
                'DBHOST' => 'localhost',
                'DBNAME' => 'kragdag_2018',
                'DBUSER' => 'kragdag_user',
                'DBPASS' => 'pw4Eksp02015'
            ),
        ),
    ),

	'email' => array(
		'name'       => 'KragDag',
		'smtp'		 => 'mail.kragdag.co.za',
		'from'		 => 'stelsel@kragdag.co.za',
		'user'		 => 'stelsel@kragdag.co.za',
		'replyto'	 => 'stelsel@kragdag.co.za',
		'replyname'	 => 'Registrasies',
		'bcc1'		 => 'neels@tnc-it.co.za',
		'bcc1name'	 => 'Neels Moller',
		'pass'		 => 'pw4KDSt31s31',
		'encryption' => 'tls',
		'port'		 => '587',
	),
);
