<!-- Matthew Rife -->

<?PHP 

class searchableJSON {
	public $userInput;
	public $userPlatform;
	public $userSearchable;
	public $JSONDescriptors;
	public $JSONObjects;
	public $JSONSearchable;
	public $JSON;
	public $indexToPrint;
	public $titles;
	public $objectsToPrint = array();
	
	public function checkParameters() {
		global $argv;
		if(sizeof($argv) == 3) {
			echo "3 arguements";
			$this->userInput = "";
		}
		else if (sizeof($argv) == 4) {
			echo "Arguements worked!";
			$this->userInput = $argv[3];
		}
		else {
			echo "Invalid arguments";
			return;
		}
		$this->userPlatform = $argv[1];
		$this->userSearchable = $argv[2];	
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
				if($value->label  == $this->userPlatform) {
					$this->indexToPrint = $key;
					echo "The key printing... ", $key;
				}
			}
		$this->findPlatform();
	}

	public function findPlatform() {
		#var_dump($this->JSON->platforms[$this->indexToPrint]);
			foreach($this->JSON->platforms[$this->indexToPrint] as $key => $value) {
				if($key == "searchable") {
					$this->JSONSearchable = $value;
				}
				else if($key == "label") {
					#skip
				}
				else if($key == "url") {
					$this->titlesURL = $value;					
				}
				else if($key == "descriptors") {
					$this->JSONDescriptors = $value;
				}
				else if($key == "objects") {
					$this->JSONObjects = $value;
				}
				else {
					echo "INVALID JSON FORMAT: Must have url,label,descriptors,objects,and searchable properties";
				}
			}
		
		
	}

	public function findTitles() {
		echo $this->titlesURL;
		$this->descriptionJSON = json_decode(file_get_contents($this->titlesURL));
		#var_dump($this->descriptionJSON);
		foreach($this->descriptionJSON as $key => $value) {
			echo $key;
			if($key == $this->JSONDescriptors) {
				for($i = 0; $i < sizeof($value); $i++) {
					echo $value[$i], PHP_EOL;
				}
			}
			else if($key == $this->JSONObjects) {
				echo "Object";
				$this->TitlesObject = $value;		
				#var_dump($this->TitlesObject);
				for($i = 0; $i <sizeof($this->TitlesObject); $i++) {
					foreach($this->TitlesObject[$i] as $newKey => $newValue) {
						echo $newKey, " : ", $newValue, PHP_EOL;
					}
				}
			}
		}
	}
}
$JSONObject = new searchableJSON();
$JSONObject->checkParameters();
$JSONObject->getJSON();
$JSONObject->findLabel();
$JSONObject->findTitles();



?>

