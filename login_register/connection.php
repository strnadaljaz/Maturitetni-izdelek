<?php

$dbhost = "localhost";
$dbuser = "aljaz";
$dbpass = "pCG(VZU-6n5La_A]";
$dbname = "todo";

/*$dbhost = "195.201.179.80";
$dbuser = "taskfall_todo";
$dbpass = "pCG(VZU-6n5La_A]";
$dbname = "taskfall_todo";*/

if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
    die("failed to connect");