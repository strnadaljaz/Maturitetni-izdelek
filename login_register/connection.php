<?php
// PB -> Podatkovna Baza
$dbhost = "localhost";
$dbuser = "taskfall_todo";
$dbpass = "pCG(VZU-6n5La_A]";
$dbname = "taskfall_todo";

// Vspostavim povezavo do PB
if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
    die("failed to connect");