<!-- Matthew Rife -->

<?PHP 

class searchableJSON {
	public $input;
	public $platform;
	public $searchable;
	public $JSON;
	public $indexToPrint = array();
	
	public function checkParameters() {
		global $argv;
		if(sizeof($argv) == 3) {
			echo "3 arguements";
			$this->input = "";
		}
		else if (sizeof($argv) == 4) {
			echo "Arguements worked!";
			$this->input = $argv[3];
		}
		else {
			echo "Invalid arguments";
			return;
		}
		$this->platform = $argv[1];
		$this->searchable = $argv[2];	
	}


	public function getJSON() {
		$data = file_get_contents('http://www.cs.uky.edu/~paul/public/Games.json');
		$decodedData = json_decode($data);
		$this->JSON = $decodedData;
		#var_dump($this->JSON);
	}

	public function findLabel() {
		print("Starting... ");
			foreach ($this->JSON->platforms as $key => $value) {
			#echo $value->label, PHP_EOL;
			if($value->label  == $this->platform) {
				print($key);
				array_push($this->indexToPrint,$key);
			}
			}
		$this->printResults();
	}

	public function printResults() {
		#var_dump($this->JSON->platforms[$this->indexToPrint]);
		for($i = 0; $i < sizeof($this->indexToPrint); $i++) {
			foreach($this->JSON->platforms[$i] as $key => $value) {
				if($key == "searchable") {
					#print array
					echo $key, " : ";
					for($arrayIndex = 0; $arrayIndex < sizeof($this->JSON->platforms[$i]->searchable);$arrayIndex++) {
						echo $value[$arrayIndex], " ";
					}
					echo PHP_EOL;
				}
				else {	
					echo $key, " : ", $value, PHP_EOL;
				}
			}
		}
		
	}
}
$JSONObject = new searchableJSON();
$JSONObject->checkParameters();
if(True) {	
	$JSONObject->getJSON();
	$JSONObject->findLabel();
}


?>

