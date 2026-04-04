<?php

$conn = mysqli_connect("localhost", "root", "", "tienda_buhonero");
if (!$conn) die("Connection failed: " . mysqli_connect_error());
if (session_status() === PHP_SESSION_NONE) session_start();

?>