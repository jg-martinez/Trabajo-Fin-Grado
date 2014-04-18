<?php
class Texto_anotado{
	
	var $titulo;
	var $anotado = TRUE;
	var $cuerpo = array();
	
}


$m = new Mongo();
$d = $m ->selectDB("textos");
$t = $d->selectCollection("t");
$texto = @fopen('texto.txt','rb',true);
var $palabras = 0;

$anotado = new Texto_Anotado;

while (!feof($texto)){
	$linea=fgets($texto);

	$partida = explode (" ",$linea);
	if (count($partida)==4){
		
		
		$partida[0]=utf8_encode($partida[0]);
		$partida[1]=utf8_encode($partida[1]);
		$partida[2]=utf8_encode($partida[2]);
		$partida[3]=utf8_encode($partida[3]);
		
		$anotado->cuerpo[$palabras]=$partida;
		$palabras=$palabras +1 ;
		
	}
}
$t->save($anotado);



?>