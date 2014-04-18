<?php
$texto_init="El veloz murciélago hindú comía feliz cardillo y kiwi. La cigüeña toca el saxofón detrás del palenque de paja.";
$texto = explode(".",$texto_init);

$a=Array();
$index=0;
foreach ($texto as $frase){
	
	
	$frase = explode(" ",$frase);

	
	if (sizeof($frase)>15){
	echo ("***Frase grande!!: ");
	echo (sizeof($frase));
	echo("<br>");
	
		for ($i=0; $i <= 14; $i++){
			
			$fraseAux[$i] = $frase[$i];
		
		}
		
		$a[$index] = implode(" ",$fraseAux);
		$index++;
	}
	else{
		$a[$index] = implode(" ",$frase);
		$index++;

	}


	
	echo "<br><br>";
}


foreach ($a as $fr){
$param = array ("input_direct_data" => $fr,
		"language" => "es");
$_URL='http://localhost/freeling/freeling.wsdl';
$FreelingAPIClient = new SoapClient($_URL);
$job = $FreelingAPIClient->runAndWaitFor($param);

var_dump($job);}

echo "<br>Fin de query";
?>