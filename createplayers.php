<?php
session_start(); // Start the session

include 'functions.php';

//Grab the css styling by wrapping everything in a div class container
echo '<div class="container">';
echo "<h1>Let's play Yahtzee!</h1>";

css_styling();

if (!isset($_SESSION['player_names'])) {
	//Session player names created
    $_SESSION['player_names'] = [];
}

if (isset($_POST['addPlayer'])) {
    //Add an extra space in the session
    $_SESSION['player_names'][] = '';
}

if (isset($_POST['submit'])) {
    //Store the submitted player names in the session array
    $_SESSION['player_names'] = $_POST['player_names'];
	
	// Redirect to jahtzeeopdracht.php
    header("Location: jahtzeeopdracht.php");
    exit(); //Ensure that no further code is executed after the header redirect
}

//Generate buttons 

echo '<form method="POST">';

//Displays input fields for player names based on the session data
if (isset($_SESSION['player_names'])) {
    foreach ($_SESSION['player_names'] as $index => $playerName) {
        generatePlayerInput($index + 1, $playerName);
    }
} else {
    //Displays an initial input field for first player
    generatePlayerInput(1);
}

//controller
if(isset($_POST['reset']) ) {
  //opnieuw beginnen
  unset($_POST);
  session_destroy();
  header("Location: createplayers.php");
  exit();
}

echo '<button type="submit" name="addPlayer">Add Player</button>';
echo '<br><br>';
echo '<input type="submit" name="submit" value="Play">';
echo '</form>';

//Function that generates correct input fields for player names
function generatePlayerInput($index, $value = '') {
    echo "<label for='player_name_$index'>Player $index:</label>";
    echo "<input type='text' name='player_names[]' id='player_name_$index' value='$value' /*required*/>";
	echo "<br>";
}

display_reset();

//Debug statement
//var_dump($_SESSION['player_names']);

echo '</div>'; //Closes container, which derives its styling from css_styling();

//session_destroy();

?>