<?php
include ('freelingQuery.php');

class texto{
	var $body;
	var $lang;
	
	function texto () {
		$this->body = "An important settlement for more than two millennia, Paris had become, by the 12th century, one of Europe's foremost centres of learning and the arts and was the largest city in the Western world until the turn of the 18th century. Paris was the focal point for the French Revolution and the 1848 Revolution. Paris is today one of the world's leading business and cultural centres and its influences in politics, education, entertainment, media, science, and the arts all contribute to its status as one of the world's major global cities. Paris and the Paris region account for more than 30 per cent of the gross domestic product of France and have one of the largest city GDPs in the world, with €607 billion (US$845 billion) in 2011. Paris is one of the world's leading tourism destinations, hosting four UNESCO World Heritage Sites and many international organisations, including UNESCO and the European Space Agency.";
		$this->lang = 'en';
	}

}

$texto_init = new texto();


$freeling = new freelingQuery();

var_dump($freeling->Query($texto_init));




/*$texto = explode(".",$texto_init);

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
var_dump($a);
*/
/*
$param = array ("input_direct_data" => $texto,
		"language" => "en");
$_URL='http://localhost/freeling/freeling.wsdl';
$FreelingAPIClient = new SoapClient($_URL);
$job = $FreelingAPIClient->runAndWaitFor($param);

var_dump($job);
*/
echo "<br>Fin de query";
?>