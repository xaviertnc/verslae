<?php

define('__HUIDIGE_EKSPO_ID__', 9);
define('__APP_TITLE__', 'KragDag Verslae');

return array(
  'email' => array(
    'user'       => __SMTP_1_USER__,
    'pass'       => __SMTP_1_PASS__,
    'port'       => __SMTP_1_PORT__,
    'encryption' => __SMTP_1_ENCRYPT__,
    'smtp'       => __SMTP_1_SERVER__,
    'from'       => __SMTP_1_USER__,
    'fromname'   => 'KragDag Verslae',
    'replyto'    => __SMTP_1_USER__,
    'replyname'  => 'KragDag Verslae',
    'bcc1'       => 'hp@kragdag.co.za',
    'bcc1name'   => 'HP Steyn',
    'bcc2'       => 'neels@tnc-it.co.za',
    'bcc2name'   => 'Neels Moller',
    //'bcc3'     => 'freda@kragdag.co.za',
    //'bcc3name' => 'Freda',
  ),

  'database' => array(
    'connections' => array(
      'mysql' => array(
        'DBHOST' => __DBHOST_1__,
        'DBNAME' => __DBNAME_1__,
        'DBUSER' => __DBUSER_1__,
        'DBPASS' => __DBPASS_1__,
      ),
    ),
  )
);
