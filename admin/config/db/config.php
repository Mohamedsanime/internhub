<?php

$host="localhost";
$dbname="internship";
$user="root";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user,"");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $e){
    echo $e->getMessage();
}
