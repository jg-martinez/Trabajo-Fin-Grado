<?php

function evita_sql ($dato_entrada) {
	return preg_replace('/\'/i','\'\'',$dato_entrada);
	//return mysql_escape_string($dato_entrada);
}

function evita_xss ($dato_entrada) {
	return htmlspecialchars ($dato_entrada);
}

function evita_inyeccion ($dato_entrada) {
	return evita_xss(evita_sql($dato_entrada));
}

?>