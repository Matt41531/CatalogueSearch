<!-- Matthew Rife -->

<?PHP 

class searchableJSON {
	public $input;
	public $platform;
	public $searchable;
	public $JSON;
	
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
			echo $value->label, PHP_EOL;
			if($value-> label  == $this->platform) {
				print("Found one");
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

