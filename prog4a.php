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
		if(!$this->userPlatform) {
			echo "<b> Invalid platform </b><br>";
		}
		else if(!$this->userSearchable) {
			echo "<b> Invalid search field </b><br>";
		}
		else {
			//Worked
		}
	}

	public function getParameters() {
		$this->userPlatform = $_GET["whichPlatform"];
		$this->userSearchable = $_GET["searchField"];
		$this->userInput = $_GET["criteria"];
		$this->submit = $_GET["Report"];
	}

	public function echoForm() {
		echo "<select name=\"whichPlatform\" form=\"inputForm\">";
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
		echo "<form action=\"#\" id=\"inputForm\">
			Criteria: <input type=\"text\" name=\"criteria\">
			<input type=\"submit\" name=\"Report\" value=\"Report\">
			</form>
			<br>";
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
					echo $value[$i], "<br>";
				}
			}
			else if($key == $this->JSONObjects) {
				$this->TitlesObject = $value;		
				for($i = 0; $i <sizeof($this->TitlesObject); $i++) {
					foreach($this->TitlesObject[$i] as $newKey => $newValue) {
						if($this->userSearchable == $newKey) {
							if($this->userInput == $newValue) {
								array_push($this->objectsIndexToPrint, $i);
							}
							else if($this->userInput == "") {
								array_push($this->objectsIndexToPrint, $i);
							}
						}	
					}
				}

				for($i = 0; $i<sizeof($this->objectsIndexToPrint); $i++) {
					foreach($this->TitlesObject[$this->objectsIndexToPrint[$i]] as $newKey => $newValue) {
						if($this->userSearchable == $newKey) {
							echo "<b>", $newKey, " : ", $newValue, "</b>", "<br>";
						}
						else {
							echo $newKey, " : ". $newValue, "<br>";
						}
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
	$JSONObject->getParameters();
	if($JSONObject->submit) {
		$JSONObject->checkParameters();
		$JSONObject->findLabel();
		$JSONObject->findTitles();
	}
}

main();


?>

