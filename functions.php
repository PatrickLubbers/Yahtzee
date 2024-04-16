<?php

//Done: Add only dice from certain number //Done
//Done: Correctly add sums to score sheets //Done
//Done: put scores of multiple scoresheets in comparison function and declare winner //done
//Done: Check on lower row scoring conditions (full house, straight.... etc). //done
//Done: Build in additional check whether category has already been selected
//DONE: build in result calculations.
//DONE (even multiple!): build in second player
 
//TODO: build in comparison function. (kind of still needed, if you want to show all scores

//CSS DESIGN. Should be in separate css folder, not in a function.

function css_styling() {
    echo '<style>';
    echo 'body { 
        font-family: Arial, sans-serif; 
        background-color: #ecf0f5; 
        margin: 0; 
    }';
	echo 'button {
		background-color: #4CAF50;
		color: white;
		padding: 20px 15px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
	}';	
    echo '.container { 
        background-color: #b3cde0; 
        border-radius: 10px; 
        margin: 20px; 
        padding: 20px; 
    }';
    echo 'h1 { 
        color: #3498db; 
    }';
    echo 'form { 
        margin-bottom: 20px; 
    }';
    echo 'input[type="checkbox"] { 
        margin-right: 3px;
		transform: scale(1.65); 
		//Normal width length dont work on the button
		//found this on mdn web docs https://developer.mozilla.org/en-US/docs/Web/CSS/transform-function/scale
    }';
	echo 'input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
    }';		
    echo 'img { 
        margin-right: 5px; 
		width: 100px; 
		border-radius: 12px;
		
    }';
    echo 'ul { 
        padding: 2; 
        margin: 2; 
        list-style: none; 
    }';
    echo '.scoresheet { 
        background-color: #d5e8f9; 
        padding: 10px; 
        border: 1px solid #bcdff1; 
        border-radius: 5px; 
        margin: 10px 0; 
    }';
    echo '.scoresheet ul { 
        display: flex; 
        flex-wrap: wrap; 
    }';
    echo '.scoresheet li { 
        width: 120px; 
        margin-right: 10px; 
        margin-bottom: 10px; 
        padding: 10px; 
        background-color: #a8c9e3; 
        border-radius: 5px; 
    }';
    echo '</style>';
}
//This styling is being called in all php script files, especially the container class

//VIEW
function display_echo_message($playerName) {
	echo "<br><h4>It is $playerName's turn<h4>";
	echo '<img src="https://e7.pngegg.com/pngimages/269/271/png-clipart-computer-icons-arrow-arrow-hand-arrow.png" alt="Arrow Image">';
}


function render_form_dicethrow($stenen, $worp, $laatsteworp) {
    echo '<form name="stenen" method="POST">';

    foreach ($stenen as $key => $value) {
        echo '<input type="hidden" name="' . $key . '" ' .'value="' . $value . '"/>';
		
		$imageSrc = get_die_image_src($value);
		echo '<img src="' . $imageSrc . '" alt="Die Face ' . $value . '" />';

        if ($worp > 1) {
            $checkboxKey = substr($key, 6);
            echo
                '&nbsp;&nbsp;&nbsp' .
                '<input type="checkbox" name="vast_' . $checkboxKey . '"';

            if (isset($_POST['vast_' . $checkboxKey])) {
                echo ' checked ';
            }
			echo '<br />';
            echo '<small>vastzetten</small>';
        }
    }
    echo '<br />';

    // knop voor nogmaals dobbelen
    if ($worp <= $laatsteworp) {
        echo
            '<br><input type="submit" name="dobbelen" value="Throw dice attempt: ' . $worp . '" />';
    } 

    // formulieren afsluiten
    echo
        '<br/><br/>' .
        '<input type="hidden" name="worp"  value="' . $worp . '" />' .
        '</form>';
		
	//Calls show_result function if $worp is greater than 3
    if ($worp > 3) {
        show_result($_SESSION['stenen']);
		render_score_form($_SESSION['scores'][$_SESSION['current_player']]);
    }
}

function render_scoresheet($scores, $playerNames) {
    echo '<div class="scoresheet">';
    echo '<h2>Scoresheet:</h2>';

    foreach ($scores as $playerIndex => $categories) {
		$playerIndex = $playerIndex - 1;
		$playerName = isset($playerNames[$playerIndex]) ? $playerNames[$playerIndex] : "Player $playerIndex";
		
		if ($_SESSION['current_player'] == $playerIndex + 1) {
            display_echo_message($playerName);
        }
		
        echo "<h3>$playerName's Scoresheet:</h3>";
        echo '<ul>';
        foreach ($categories as $category => $value) {
            echo '<li><strong>' . $category . ':</strong><br> ' . $value . '</li>';
        }
        echo '</ul>';
    }

    echo '</div>';
}

function display_reset() {
	echo "<form method=POST>";
	echo '<input type="submit" name="reset" value="Reset" />';
	echo "</form>";
}

function get_die_image_src($value) {
	switch ($value) {
		case 1:
			return 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Dice-1.png';
		case 2: 
			return 'https://upload.wikimedia.org/wikipedia/commons/1/18/Dice-2.png';
		case 3:
			return 'https://upload.wikimedia.org/wikipedia/commons/7/70/Dice-3.png';
		case 4:
			return 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Dice-4.png';
		case 5:
			return 'https://upload.wikimedia.org/wikipedia/commons/6/6c/Dice-5.png';
		case 6:
			return 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Dice-6.png';
		default:
			return '';
	}
}

function show_result($stenen) {
	echo '<h2>Results after 3 throws:</h2>';
	foreach ($stenen as $key => $value) {
        // To display images based on thrown dice value
        $imageUrls = [
            1 => 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Dice-1.png',
            2 => 'https://upload.wikimedia.org/wikipedia/commons/1/18/Dice-2.png',
            3 => 'https://upload.wikimedia.org/wikipedia/commons/7/70/Dice-3.png',
            4 => 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Dice-4.png',
            5 => 'https://upload.wikimedia.org/wikipedia/commons/6/6c/Dice-5.png',
            6 => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Dice-6.png',
        ];

        if (isset($imageUrls[$value])) {
            echo '<img src="' . $imageUrls[$value] . '" alt="Die Face ' . $value . '" />';
        }
	}
	
	//This shows the sum of the thrown dice
    $sum = calculate_sum($stenen);
    echo '<p>Sum of the thrown dice: ' . $sum . '</p>';
}

//######################
//This is most likely unnecessary. Will refactor
//######################

function render_score_form($scores) {
    echo '<h2>Choose a scoring category:</h2>';
    echo '<form name="score_form" method="POST">';

    foreach ($scores as $category => $value) {
        echo '<input type="radio" name="score_category" value="' . $category . '" required>';
        echo '<label for="' . $category . '">' . $category . '</label>';
		echo '&nbsp;&nbsp;&nbsp;';
        echo '<span>' . $value . '</span>';
        echo '<br>';
    }
	
	echo '<br>';

    echo '<input type="submit" name="score_submit" value="Score">';
    echo '</form>';
}

function calculate_and_check_score($scoresheet, $stenen, $selectedCategory, &$numbersum) {
	$sum = 0;
	
	// Check if the category has already been selected
    if ($scoresheet[$selectedCategory] !== null) {
        echo "Category '$selectedCategory' has already been selected. Please choose a different category.";
		return $scoresheet[$selectedCategory]; //Return the same value thats already in there
    }
	
	//Score for specific number
	foreach ($stenen as $value) {
		if ($selectedCategory == $value && $scoresheet[$value] === null) {
			$sum += $value;
            $numbersum += $value; //updating numbersum
		}
	}
	
	//Score for full house 
	if ($selectedCategory == 'full house' && check_full_house($stenen) == true) {
		$sum += 30;
	}
	
	//Score for three of a kind
	if ($selectedCategory == 'three of a kind' && check_three_of_a_kind($stenen) == true) {
		$sum += calculate_sum($stenen);
	}
	
	//Score for four of a kind
	if($selectedCategory == 'four of a kind' && check_four_of_a_kind($stenen) == true) {
		$sum += calculate_sum($stenen);
	}
	
	//Score for small_straight
	if ($selectedCategory == 'small straight' && check_small_straight($stenen) == true) {
		$sum += 30;
	}
	
	//Score for large_straight
	if ($selectedCategory == 'large straight' && check_large_straight($stenen) == true) {
		$sum += 40;
	}
	
	//Score for yahtzee
    if ($selectedCategory == 'yahtzee' && check_yahtzee($stenen) == true && $scoresheet[$selectedCategory] == null) { //fix this or conditional, is hideouss
        $sum += 50;
    }
	
	//Score for chance
	if ($selectedCategory == 'chance') {
		$sum += calculate_sum($stenen);
	}	

    //Score for +35 point bonus
	
    return $sum;
}

function check_yahtzee($stenen) {
    $counter = array_count_values($stenen);

    foreach ($counter as $value => $count) {
        if ($count >= 5) {
            return true;
        }
    }
	return false;
} 

function check_full_house($stenen) {
    $counter = array_count_values($stenen);
    
    $three_of_a_kind = false;
    $pair = false;

    foreach ($counter as $value => $count) {
        if ($count >= 3) {
            $three_of_a_kind = true; 
        } elseif ($count >= 2) {
            $pair = true;
        }
    }
	
	if ($three_of_a_kind == true && $pair == true) {
		return true;
	} else {
		return false;
	} 	
} 
//It seems fine to keep it like this and not merge two functions together 

function check_three_of_a_kind($stenen) {
	$counter = array_count_values($stenen);
	
	foreach ($counter as $value => $count) {
		if ($count >= 3) {
			return true;
		}
	}
	return false;
}
//the return false statement was called to early, it wouldnt work for 1,4,4,4,1

function check_four_of_a_kind($stenen) {
    $counter = array_count_values($stenen);

    foreach ($counter as $value => $count) {
        if ($count >= 4) {
            return true;
        } 
    }
	return false;
} 
//the return false statement was called too early, it wouldnt work for 1,4,4,4,4

function check_small_straight($stenen) {
    // Sorting dice
    sort($stenen);

    $consecutiveCount = 1;

    for ($i = 0; $i < count($stenen) - 1; $i++) {
        //This way it skips duplicates
        if ($stenen[$i] == $stenen[$i + 1]) {
            continue;
        }

        //Checking for consecutive numbers
        if ($stenen[$i] + 1 == $stenen[$i + 1]) {
            $consecutiveCount++;
        } else {
            $consecutiveCount = 1; // Reset count for non-consecutive numbers
        }

        //Check for a sequence of 4 consecutive numbers
        if ($consecutiveCount >= 4) {
            return true;
        }
    }
    return false;
}

function check_large_straight($stenen) {	
	//sorting dice
	sort($stenen);
		
	for ($i = 0; $i < 4; $i++) {
		if ($stenen[$i] + 1 !== $stenen[$i + 1]) { //checks if dice are really sequential, if not..
			return false; 
		}
	}
	return true;
}

function calculate_sum($stenen) {
    //sum all the values of the dice
    $sum = array_sum($stenen);
    
    return $sum;
}


function calculate_final_result($scores, $playerNames) {
    // Check if any value in any subarray is still null
    foreach ($scores as $categories) {
        if (in_array(null, $categories, true)) {
            // If any value is still null in any array within array, the game is not finished
            return null;
        }
    }

    // If all values are filled (not null), the scores are calculated.
    $totalScores = [];
    
    foreach ($scores as $player => $categories) {
        // Initialize the bonus points variable
        $bonusPoints = 0;

        // Calculate the sum of categories 1 to 6
        $totalSum = array_sum(array_filter($categories, 'is_numeric'));

        // Check if the sum of categories 1 to 6 exceeds 10
        if ($totalSum > 62) {
            // Add 35 bonus points
            $bonusPoints += 35;
        }

        // Add the bonus points to the total sum
        $totalScores[$player] = $totalSum + $bonusPoints;
    }

    // Finding the player with the highest total score based on its index in session scores
    $winnerIndex = array_search(max($totalScores), $totalScores);

    // Return an array with the winner's name, index, and total scores
    return [
        'winnername' => $playerNames[$winnerIndex - 1], 
        'winner' => $winnerIndex,
        'totalScores' => $totalScores,
        'bonusPoints' => $bonusPoints
    ];
}
//Via de index benaderen we de naam van de speler wiens index in session player_names hetzelfde is -1
//En de scoresheet die in session scores onder dezelfde index opgeslagen staat

//Dat de indexen niet helemaal overeenkomen is wel een beetje ongemakkelijk. TODO: FIX
