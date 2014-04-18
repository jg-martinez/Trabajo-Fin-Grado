<?php
   $enlace = mysql_connect("localhost","tfc","caliope") or die("No pudo conectarse : " . mysql_error());
   $res= mysql_select_db("caliope", $enlace) or die("No pudo conectarse a la BD.");
   mysql_query("SET NAMES 'latin1' COLLATE 'latin1_spanish_ci'");
   $maxpage = 15;
?>