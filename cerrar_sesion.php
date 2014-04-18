<?php 
    // se cierra la conexion con la base de datos
	session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
	session_destroy();

	include "index.php";
?>