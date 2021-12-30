<?php

$host = 'mysql.itn.liu.se';
$user = 'lego';
$pass = '';
$db = 'lego';

$dsn = "mysql:dbname=$db;host=$host;charset=utf8";

$settings = array(
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

try {
    $dbm = new PDO($dsn, $user, $pass, $settings);
} catch (PDOException $e) {
    echo 'Kunde inte koppla mot db.<br>'.$e->getMessage();
    exit;
}
/*
 * Sends a request to database and returns an answer if $fetch = true.
 *
 * @param, string, $query, the request sent to database
 * @param, boolean, $fetch, indicates if the function should return data or not
 * @param, array, $data, data sent into the query
 * @return, array, returns the answer from database - only returns if $fetch = true
 */

    function runQuery($query, array $data)
    {
        global $dbm;
        
        try {
            $stmt = $dbm->prepare($query);
			//var_dump($query);
            $stmt->execute($data);
			return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            echo 'Frågan gick åt skogen. Förklaring:<br>'
       .$e->getMessage();
            exit;
        }
    }

