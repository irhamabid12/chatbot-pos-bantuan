<?php
$host = "localhost";
$user = "rgtastyc_adminposindo";
$pass = "c.GGd;MAk;o6";
$db = "rgtastyc_customer_service_pos_indonesia";

$conn = new mysqli($host, $user, $pass, $db);

if(mysqli_connect_error()){
    error_log("Error : coult not connect to database");
    exit;
}
?>