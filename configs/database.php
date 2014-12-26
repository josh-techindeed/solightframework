<?php
return array(

    // required
    'database_type' => 'mysql',
    'database_name' => 'database_name',
    'server' => '127.0.0.1',
    'port' => '3306',
    'username' => 'root',
    'password' => 'root',
 
    // optional
    'charset' => 'utf8',
    
    // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => array(
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ),
);
