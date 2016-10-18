<?php
require_once __DIR__ . '/quikstart.php';
include_once('functions.php');

$client = getClient();
$service = new Google_Service_Gmail($client);
$user = 'me';

//---Creamos una label donde se guardaran los mensajes procesados. 
//---La llamaremos procesados y guardaremos el identificador de la Label

$result=createLabel($service,'me','procesados');
$r=explode("//",$result);
echo $r[0]."\n";
$labelId=$r[1];


//---Añadimos el identificador del nuevo label, a la base de datos.
$dsn='mysql:dbname=sistemaPIT;host=127.0.0.1';
$DBuser='root';
$DBpassword='root';
try {
    $dbh=new PDO($dsn,$DBuser,$DBpassword);
} 
catch (PDOException $e) {
}  
$sth = $dbh->prepare('INSERT INTO labels (label_name,label_id) VALUES (?,?);');
$sth->execute(array("procesados",$labelId));
$user = $sth->fetch();
echo "Nuevo Label añadido a la base de datos\n";


















