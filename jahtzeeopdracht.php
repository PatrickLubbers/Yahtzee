<?php 

ob_start();//Start output buffering

session_start();

include 'functions.php';

echo '<div class="container">';
echo "<h1>Let's play Yahtzee!</h1>";

if (!isset($_SESSION['stenen'])) {
echo "Yahtzee. Als je dobbels gooit zie je 5 dobbels met de ogen. <br> Je mag 3 keer gooien.
<br>Je kunt dobbels vastzetten als je ze wilt houden en niet nog een wilt gooien.
<br>Na 3 keer kies je een categorie waar je hand aan voldoet. Als het niet aan de voorwaarden voldoet, krijg je 0 punten voor de geselecteerde categorie.<br>
<br>Is de som van je scores in categorie 1 t/m 6 meer dan 63, dan krijg je 35 bonuspunten.<br><br>

1 t/m 6 getallen bij elkaar opgeteld:	<br><br>
three_of_a_kind: bijvoorbeeld 3-3-3-4-2 <br>
four_of_a_kind: bijvoorbeeld 6-6-6-6-4	<br>
full_house: bijvoorbeeld 2-2-3-3-3		<br>
small_straight: bijvoorbeeld 1-2-3-4-1	<br>
large_straight: bijvoorbeeld 1-2-3-4-5	<br>
yahtzee: bijvoorbeeld 1-1-1-1-1			<br>
chance: alle dobbels bij elkaar opgeteld<br>";
}

css_styling();

//init
$worp = 1;
$laatsteworp = 3;

// Initialize $numbersum if it's not already set
if (!isset($_SESSION['numbersum'])) {
    $_SESSION['numbersum'] = 0;
}

if (!isset($_SESSION['stenen'])) {
	$_SESSION['stenen']	= [];
}

if (!isset($_SESSION['current_player'])) {
	$_SESSION['current_player'] = 1;
}

if (isset($_SESSION['player_names'])) {
	$numberOfPlayers = count($_SESSION['player_names']);
} else {
	$numberOfPlayers = 4; //default when person starts game immediately
}

//I Initialize the scores array with default values
if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [];

    for ($player = 1; $player <= $numberOfPlayers; $player++) {
        $_SESSION['scores'][$player] = [
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
            'three of a kind' => null,
            'four of a kind' => null,
            'full house' => null,
            'small straight' => null,
            'large straight' => null,
            'yahtzee' => null,
            'chance' => null
        ];
    }
}
//Beter is denk ik om een session variabele bij te houden met de naam van de player en zijn nummer: 
//$_SESSION["player_".$number] = 'Patrick'; en dan number te laten oplopen met je score index.

//controller
if(isset($_POST['reset']) ) {
  //opnieuw beginnen
  unset($_POST);
  session_destroy();
  header("Location: createplayers.php");
  exit();
}

if(isset($_POST['dobbelen']) ) {
  //set array keys
  for($i = 0; $i <= 4; $i++) { //only generate up to 5 dice
	 $key = 'steen_' . $i;
     $stenen[$key] = isset($_POST['vast_' . $i]) ? (int)$_POST[$key] : mt_rand(1, 6);
    }
	
	$_SESSION['stenen'] = $stenen;
	
   //volgende worp
   $worp = $_POST['worp'] + 1;
}            

// Handle form submission. 
if (isset($_POST['score_submit'])) {
    $selectedCategory = $_POST['score_category'];
	//Takes the sum of the dice values before resetting the session array $stenen
    $sum = calculate_and_check_score($_SESSION['scores'][$_SESSION['current_player']], $_SESSION['stenen'], $selectedCategory, $_SESSION['numbersum']);
	//Updating the scores array based on the selected category
    $currentPlayer = 1; 
    $_SESSION['scores'][$_SESSION['current_player']][$selectedCategory] = $sum;

    //Updating the scores array based on the selected category
    $_SESSION['scores'][$_SESSION['current_player']][$selectedCategory] = $sum;

    //Resets the dice and prepares for the next round
    //$stenen = [];
    $worp = 1;
	
	//Move to next player
	$_SESSION['current_player']++;
	
	//Resets to player one once the end amount of players is reached
	if ($_SESSION['current_player'] > $numberOfPlayers) {
		$_SESSION['current_player'] = 1;
	}
	
	//Reset $_SESSION['stenen'] to make the application look cleaner during play (Start off each new round with a fresh session)
	$_SESSION['stenen'] = []; 
}

//Usage
render_form_dicethrow($_SESSION['stenen'], $worp, $laatsteworp);
render_scoresheet($_SESSION['scores'], $_SESSION['player_names']);

display_reset(); 

// Example usage
$result = calculate_final_result($_SESSION['scores'], $_SESSION['player_names']);

if ($result !== null) {
    echo "The winner is {$result['winnername']} with a 1/6 bonus of: {$result['bonusPoints']} a total score of {$result['totalScores'][$result['winner']]}!";
} else {
    echo "The game is not finished yet.";
}

//session_destroy(); testing

?>