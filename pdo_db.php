<?php
$dbms = 'mysql';
$host = 'localhost';
$dbName = 'tieba';
$user = 'root';
$pass = 'root';
$dsn = "$dbms:host=$host;dbname=$dbName";
$dbh = new PDO($dsn, $user, $pass);
$res=$dbh->query('set names utf8');







?>
