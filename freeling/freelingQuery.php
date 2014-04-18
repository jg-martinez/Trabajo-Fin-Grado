<?php 

	class freelingQuery {
	

	
		private $_text;
		private $_lang;

		function _parteTexto ($text){
			//Índice para recorrer cada una de las frases del texto
			$_index = 0;
			//Array que contendrá las frases cortadas
			$_frases = Array();
			$fraseAux = Array();
			
			
			$texto = explode(".",$text);
			foreach ($texto as $frase){
	
				$frase = explode(" ",$frase);

/*				if (sizeof($frase)>15){

				
					for ($i=0; $i <= 14; $i++){
						
						$fraseAux[$i] = $frase[$i];
					
					}
					
					$_frases[$_index] = implode(" ",$fraseAux);
					$_index++;
				}
				else{
					$_frases[$_index] = implode(" ",$fraseAux);
					$_index++;

				}*/
				
				if (sizeof($frase)>15){
					$_particion = array_chunk($frase, 15);
					foreach ($_particion as $pequefrase){
						$_frases[$_index] = implode(" ",$pequefrase);
						$_index++;
					}
				}
				else{
					$_frases[$_index] = implode(" ",$frase);
					$_index++;
				}
		
		}
		return $_frases;
	}
	

		
		
		public function Query ($text){
		
			$this->_text = utf8_encode($text["body"]);
			$this->_lang = "es";
			
			$_URL= "freeling.wsdl";							
			$FreelingAPIClient = new SoapClient($_URL);
			$_frases = $this->_parteTexto($this->_text);
			$_job = Array();
			$_index = 0;
			foreach ($_frases as $frase){
			
				$param = array ("input_direct_data" => $frase, "language" => $this->_lang);
				
				$_job[$_index] = $FreelingAPIClient->runAndWaitFor($param);
				$_index++;

			}
			return $_job;
			}
			
		function _load ($param){
			$t=_conectarMDB();
			$f=$t->find(array('h_title'=> $param));
			return $f;
			}
			
		function _store ($id,$text){
			$t=_conectarMDB();
			$t->update($id,array('body'=>$text));
			}
			
		function _conectarMDB(){
			$m = new Mongo();
			$d = $m ->selectDB("textos");
			$t = $d->selectCollection("t");
			
			return $t;
			}
			
	} 
?>