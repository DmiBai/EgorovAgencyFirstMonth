<?php

$dbName = 'mod4';
$dbPassword = 'root';
$dbUsername = 'root';
$dbHost = '127.0.0.1';
$dialect = 'mysql';

$dbConnection = new PDO($dialect . ':host=' . $dbHost .
    ';dbname=' . $dbName, $dbUsername, $dbPassword);

$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $dbConnection->prepare('SELECT * FROM emails');
$data->execute();
$row = $data->fetchAll(PDO::FETCH_ASSOC);

$now = '' . date("Y-m-d H:i:s");
$now = str_replace(' ','_',$now);
$now = str_replace('-', '', $now);
$now = str_replace(':','', $now);

$content = '';
foreach($row as $item){
   $content .= json_encode($item);
   $content .= PHP_EOL;
}

file_put_contents('D:/OpenServerBackup/domains/phpprojects/email/php/cron/backups/' . $now.'.txt', $content);