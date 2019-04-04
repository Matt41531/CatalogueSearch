<!-- Matthew Rife -->

<?PHP 

function checkParameters() {
	global $argv;
	if(sizeof($argv) == 3) {
		echo "3 arguements";
		$input = "";
	}
	else if (sizeof($argv) == 4) {
		echo "Arguements worked!";
		$input = $argv[3];
	}
	else {
		echo "Invalid arguments";
		return;
	}
	$platform = $argv[1];
	$searchable = $argv[2];
	return [$platform, $searchable, $input];
}

$parameters = checkParameters();
if($parameters) {
	echo "Cool";
}

?>

