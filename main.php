<?php

class CSVParser {

	private string $csv;
	private string $comma;
	public  array  $lines;

	public function __construct(string $csv, string $comma){

		$this->csv 		= $csv;
		$this->comma 	= $comma;
		$this->lines	= $this->readFile($csv);
	}

	private function readFile(string $csv) : array	{

			if(!file_exists($csv)) {
				exit("ERROR: The file $csv does not exists\n");
			}

			$source = file_get_contents($csv);

			$line 	= "";
			$lines 	= [];
			$i 		= 0;

			for($i; $i < strlen($source); $i++) {

				if($source[$i] != "\n"){
					$line .= $source[$i];
				}else {
					array_push($lines, explode($this->comma,$line));
					$line = '';
				}
			}

			for($i = 0; $i < sizeof($lines[0]); $i++) {
				$lines[0][$i] = trim($lines[0][$i]);
			}

			return $lines;

		}

	public function makeArray(array $arrayOfLines = null) : array {

		$i = 0;
		$j = 0;
		$lines = $this->lines;
		$data = [];

		for($i = 1; $i < sizeof($lines); $i++) {

			$element = $this->generateIndexes($lines[0]);

			for($j = 0; $j < sizeof($lines[$i]); $j++){
				$aux = $lines[$i][$j];
				$element[$lines[0][$j]] = $aux;
			}

			array_push($data ,$element);
		}

		return $data;
	}

	public function makeJson(){
		return json_encode($this->makeArray($this->lines), JSON_PRETTY_PRINT);
	}

	private function generateIndexes(array $indexes) : array {

		$vector = [];

		for($i = 0; $i < sizeof($indexes); $i++){
			$vector[trim(strval($indexes[$i]),"\xEF\xBB\xBF")] = [];
		}

		return $vector;
	}

}
