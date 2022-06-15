<?php
date_default_timezone_set('asia/jakarta');
ob_start();
session_start();
$con=new PDO("mysql:host=localhost;dbname=kontrakan","root","");
?>