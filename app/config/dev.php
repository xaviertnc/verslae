<?php

return array(
    'database' => array(
        'connections' => array(
            'mysql' => array(
                'DBHOST' => 'localhost',
                'DBNAME' => 'nhyrehup_toets',
                'DBUSER' => 'root',
                'DBPASS' => 'root'
            ),
        ),
    ),

	'email' => array(
		'name'       => 'Tuisskool',
		'smtp'		   => 'mail.homeschoolexpo.co.za',
		'from'		   => 'register@homeschoolexpo.co.za',
		'user'		   => 'register@homeschoolexpo.co.za',
		'replyto'	   => 'register@homeschoolexpo.co.za',
		'replyname'	 => 'Registrasies',
		'bcc1'		   => 'neels@tnc-it.co.za',
		'bcc1name'	 => 'Neels Moller',
		'pass'		   => 'pw4Register01',
		'encryption' => 'tls',
		'port'		   => '587',
	),
);
