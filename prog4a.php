<!-- Matthew Rife -->

<?PHP 

class searchableJSON {
	public $userInput;
	public $userPlatform;
	public $userSearchable;
	public $JSONDescriptors;
	public $JSONObjects;
	public $JSONSearchable;
	public $whichPlatformOptions = array();
	public $tempSearchFieldOptions = array();
	public $searchFieldOptions = array();
	public $JSON;
	public $indexToPrint;
	public $titles;
	public $objectsIndexToPrint = array();
	
	public function checkParameters() {
		global $argv;
		if(sizeof($argv) == 3) {
			$this->userInput = "";
		}
		else if (sizeof($argv) == 4) {	
			$this->userInput = $argv[3];
		}
		else {
			echo "Invalid arguments";
			return;
		}
		$this->userPlatform = $argv[1];
		$this->userSearchable = $argv[2];	
	}

	public function getParameters() {
		$this->userPlatform = $_GET["whichPlatform"];
		$this->userSearchable = $_GET["searchField"];
		$this->userInput = $_GET["criteria"];
	}

	public function echoForm() {
		echo "<form action=\"\" id=\"inputForm\">
			Criteria: <input type=\"text\" name=\"criteria\">
			<input type=\"submit\">
			</form>
			<br>
			<select name=\"whichPlatform\" form=\"inputForm\">";
		for($i = 0; $i <sizeof($this->whichPlatformOptions);$i++) {
			echo "<option value=\"";
			echo $this->whichPlatformOptions[$i];
			echo "\">";
			echo $this->whichPlatformOptions[$i];
			echo "</option>";
	
		}
			echo "</select>
			<select name=\"searchField\" form=\"inputForm\">";
		for($i = 0; $i <sizeof($this->searchFieldOptions);$i++) {
			echo "<option value=\"";
			echo $this->searchFieldOptions[$i];
			echo "\">";
			echo $this->searchFieldOptions[$i];
			echo "</option>";
	
		}	
			echo "</select>";	
	}
	public function findAllLabels() {
		foreach ($this->JSON->platforms as $key => $value) {
			array_push($this->whichPlatformOptions, $value->label);
		}
	}

	public function findAllSearchable() {
		foreach ($this->JSON->platforms as $key => $value) {
			for($i =0;$i <sizeof($value->searchable);$i++) {
				array_push($this->tempSearchFieldOptions, $value->searchable[$i]);
			}		
		}	
		$this->tempSearchFieldOptions = array_unique($this->tempSearchFieldOptions);	
		$this->searchFieldOptions = array_values($this->tempSearchFieldOptions);
	}

	public function getJSON() {
		$data = file_get_contents('http://www.cs.uky.edu/~paul/public/Games.json');
		$decodedData = json_decode($data);
		$this->JSON = $decodedData;
	}

	public function findLabel() {
		foreach ($this->JSON->platforms as $key => $value) {
			if($value->label  == $this->userPlatform) {
				$this->indexToPrint = $key;
			}
		}
		$this->findPlatform();
	}

	public function findPlatform() {
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
		$this->descriptionJSON = json_decode(file_get_contents($this->titlesURL));
		#var_dump($this->descriptionJSON);
		foreach($this->descriptionJSON as $key => $value) {
			if($key == $this->JSONDescriptors) {
				for($i = 0; $i < sizeof($value); $i++) {
					echo $value[$i], PHP_EOL;
				}
			}
			else if($key == $this->JSONObjects) {
				$this->TitlesObject = $value;		
				#var_dump($this->TitlesObject);
				for($i = 0; $i <sizeof($this->TitlesObject); $i++) {
					foreach($this->TitlesObject[$i] as $newKey => $newValue) {
						if($this->userSearchable == $newKey) {
							if($this->userInput == $newValue) {
								array_push($this->objectsIndexToPrint, $i);
							}
						}
						#echo $newKey, " : ", $newValue, PHP_EOL;
					}
				}

				for($i = 0; $i<sizeof($this->objectsIndexToPrint); $i++) {
					foreach($this->TitlesObject[$this->objectsIndexToPrint[$i]] as $newKey => $newValue) {
						echo $newKey, " : ". $newValue, PHP_EOL;
					}
				}
			}
		}
	}
}
function main() {
	$JSONObject = new searchableJSON();
	$JSONObject->getJSON();
	$JSONObject->findAllLabels();
	$JSONObject->findAllSearchable();
	$JSONObject->echoForm();
	$JSONObject->checkParameters();
	$JSONObject->findLabel();
	$JSONObject->findTitles();
}

main();


?>

