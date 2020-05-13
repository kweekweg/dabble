<?php
	require("Dabble.php");
	require("word_processor.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];

		// If variables are not set redirect to index page with empty error message
		if(isset($_POST["wordInput"]) && isset($_POST["puzzletype"])){
			$puzzleType = $_POST["puzzletype"];
			$wordInput = $_POST["wordInput"];

			// If input is blank redirect to Index with empty error message
			if(trim($wordInput) === ''){
				redirect("emptyinput");
			}
		}
		else{
			redirect("emptyinput");
		}



		// Parse through input and generate a word list
		$wordList = generateWordList($wordInput);

		// If only one word was passed in redirect with count error message
		if(count($wordList) == 1){
			redirect("count");
		}

		// Create dabble puzzle
		$dabble = new Dabble($wordList);

		// If there was an error with input redirect with invalid input message
		if($dabble->getErrorStatus() == true){
			print_r("asdfa:");
			redirect("invalidinput");
		}
		else{
			// Get lists and puzzles
			$letterList = $dabble->getLetterList();
			$wordList = $dabble->getWordList();

			$pyramidPuzzle = $dabble->getPyramidPuzzle();
			$stepUpPuzzle = $dabble->getStepUpPuzzle();
			$stepDownPuzzle = $dabble->getStepDownPuzzle();

			$pyramidLetterPuzzle = $dabble->getPyramidLetterPuzzle();
			$stepUpLetterPuzzle = $dabble->getStepUpLetterPuzzle();
			$stepDownLetterPuzzle = $dabble->getStepDownLetterPuzzle();

			$characterList = $dabble->getCharacterList();
		}

	}
	else{
		redirect(" ");
	}

	/*
	 * Redirects user to index page with Get error code if there is an issue with input
	 */
	function redirect($error){
		if($error != " "){
			$url = "index.php?error=".$error;
		}
		else{
			$url = "index.php";
		}

		header("Location: ".$url);
		exit;
	}

	/*
	 * Generates a word list based on input
	 * Splits input by line breaks, trims each line, and then puts each line into an array
	 */
	function generateWordList($wordInput){
		$wordList = [];

		$lines = explode("\n", $wordInput);

		foreach($lines as $line){

			$word = trim($line);

			if(!(empty($word))){
				array_push($wordList, $word);
			}
		}

		return $wordList;
	}

	/*** Word Processor Functions ***/
	function getWordLength($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLength();
	}

	function splitWord($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLogicalChars();
	}
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- Spectrum -->
    <link rel="stylesheet" type="text/css" href="spectrum.css">
    <script type="text/javascript" src="spectrum.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="dabblestyle.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">

    <title>Dabble Puzzle</title>
</head>
<body>
    <div class="container-fluid">
        <div class="jumbotron" id="jumbos">
        </div>
		<br>
        <div class="panel">
            <div class="panel-group">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Dabble Puzzle</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div align="center">
                            <h3><?php echo($title);?></h3>
                        </div>
                        <div align="center">
                            <h4><?php echo($subtitle);?></h4>
                        </div>
                        <div align="center">
                            <div class="col-sm-6">
                                <div class="row letters rectangleLettersPuzzle"> <h3>Letters</h3>
								<table class="puzzle">
                                <?php
									// Prints a grid with 5 square width with puzzle letters
									foreach($letterList as $row){
										echo'<tr>';
										foreach($row as $letter){
											if($letter != "0"){
												echo'<td class="filled">'.$letter.'</td>
												';
											}
											else{
												echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
												';
											}
										}
										echo'</tr>';
									}
								?>
								</table>
							</div>
								<div class="letters stepupLettersPuzzle" style="display: none;">
									<div class="row"> <h3>Step Up Letters</h3></div>
									<div class="row">
										<table class="puzzle">
											<?php
												// Prints blank step up puzzle
												foreach($stepUpLetterPuzzle as $row){
													echo'<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">'.$letter.'</td>';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
															';
														}
													}
													echo'</tr>';
												}
											?>
										</table>
									</div>
								</div>
								<div class="letters stepdownLettersPuzzle" style="display: none;">
									<div class="row"> <h3>Step Down Letters</h3> </div>
									<div class="row">
										<table class="puzzle">
											<?php
												// Prints blank step down puzzle

												foreach($stepDownLetterPuzzle as $row){
													echo'<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">'.$letter.'</td>
															';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
															';
														}
													}
													echo'</tr>';
												}
											?>
										</table>
									</div>
								</div>
								<div class="letters pyramidLettersPuzzle" style="display: none;">
									<div class="row"> <h3>Pyramid Letters</h3> </div>
									<div class="pyramid">
										<?php
											// Prints blank pyramid puzzle
											// Cells must be printed with correct styling
											// Top cell, then final top right cell, left cells, inside cells, right cells,
											// bottom cells, then final right cell
											$wordCount = count($wordList);
											$count = 0;

											for($i = 0; $i < $wordCount; $i++){
												$word = $wordList[$i];
												$charList = splitWord($word);
												$length = getWordLength($word);

												echo'<div class="row">';

												for($j = 0; $j < $length; $j++){
													if($i == 0){
														if($j < $length - 1){
															echo'<div class="top">'.$characterList[$count++].'</div>';
														}
														else{
															echo'<div class="topRight">'.$characterList[$count++].'</div>';
														}
													}
													else if($i < $wordCount - 1){
														if($j == 0){
															echo'<div class="left">'.$characterList[$count++].'</div>';
														}
														else if($j < ($length - 1)){
															echo'<div class="inside">'.$characterList[$count++].'</div>';

														}
														else{
															echo'<div class="right">'.$characterList[$count++].'</div>';

														}
													}
													else{
														if($j < ($length - 1)){
															echo'<div class="bottom">'.$characterList[$count++].'</div>';

														}
														else{
															echo'<div class="bottomRight">'.$characterList[$count++].'</div>';

														}
													}
												}
												echo'</div>';
											}
										?>
									</div>
								</div>
              </div>

							<?php //START OF WORDS PUZZLE *******************?>
              <div class="col-sm-6">
								<div class="stepupPuzzle word">
									<div class="row"> <h3>Step Up</h3></div>
									<div class="row">
										<table class="puzzle">
											<?php
												// Prints blank step up puzzle
												foreach($stepUpPuzzle as $row){
													echo'<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">&nbsp;&nbsp;&nbsp;&nbsp;</td>
															';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
															';
														}
													}
													echo'</tr>';
												}
											?>
										</table>
									</div>
								</div>
								<div class="stepdownPuzzle word">
									<div class="row"> <h3> Step Down </h3> </div>
									<div class="row">
										<table class="puzzle">
											<?php
												// Prints blank step down puzzle

												foreach($stepDownPuzzle as $row){
													echo'<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">&nbsp;&nbsp;&nbsp;&nbsp;</td>
															';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
															';
														}
													}
													echo'</tr>';
												}
											?>
										</table>
									</div>
								</div>
								<div class="pyramidPuzzle word">
									<div class="row"> <h3> Pyramid </h3> </div>
									<div class="pyramid">
										<?php
											// Prints blank pyramid puzzle
											// Cells must be printed with correct styling
											// Top cell, then final top right cell, left cells, inside cells, right cells,
											// bottom cells, then final right cell
											$wordCount = count($wordList);

											for($i = 0; $i < $wordCount; $i++){
												$word = $wordList[$i];
												$charList = splitWord($word);
												$length = getWordLength($word);

												echo'<div class="row">';

												for($j = 0; $j < $length; $j++){
													if($i == 0){
														if($j < $length - 1){
															echo'<div class="top">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
														else{
															echo'<div class="topRight">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
													}
													else if($i < $wordCount - 1){
														if($j == 0){
															echo'<div class="left">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
														else if($j < ($length - 1)){
															echo'<div class="inside">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
														else{
															echo'<div class="right">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
													}
													else{
														if($j < ($length - 1)){
															echo'<div class="bottom">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
														else{
															echo'<div class="bottomRight">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
														}
													}
												}
												echo'</div>';
											}
										?>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Dabble Options</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12" align="center">
															<?php // adding word options here **************************?>
															<div class="col-sm-4">
																	<div class="row">
																			<div class="col-sm-8">
																					<h3>Letters</h3>
																			</div>
																			<div align="left" >
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Letter Square Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterSquareColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Letter Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Line Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='lineColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                    </div>
																		</div>
																	</div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>Puzzle Options</h3>
                                        </div>
                                    </div>

																		<div align="left">
                                        <div class="row">
                                            <div class="col-sm-12" >
                                                <input type="checkbox" class="showSolutionCheckbox" onchange="solutionCheckboxChange()" checked> Show Solution
                                            </div>
                                        </div>
										<?php //addition of letters options ************************* ?>
										<br>
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" id="puzzlelettertype" name="puzzlelettertype" onchange="lettersChange()">
													<option value="rectangle">Rectangle</option>
													<option value="pyramid" >Pyramid</option>
													<option value="stepup" >Step Up</option>
													<option value="stepdown" >Step Down</option>
												</select>
											</div>
										<h4>Letters</h4>
										</div>

										<br>
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" id="puzzletype" name="puzzletype" onchange="puzzleChange()">
													<option value="pyramid" <?php if($puzzleType == "pyramid"){echo('selected="selected"');} ?>>Pyramid</option>
													<option value="stepup" <?php if($puzzleType == "stepup"){echo('selected="selected"');} ?>>Step Up</option>
													<option value="stepdown" <?php if($puzzleType == "stepdown"){echo('selected="selected"');} ?>>Step Down</option>
												</select>
											</div>
										<h4>Words</h4>
										</div>
                                    </div>
                                </div>

																<?php // Words OPTIONS ********************************** ?>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>Words</h3>
                                        </div>
                                    </div>
                                    <div align="left" >
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Word Square Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterSquareColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Word Letter Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Line Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='lineColor'/>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
					<div class="panel panel-primary solutionSection">
						<div class="panel-heading ">
							<div class="row">
								<div class="col-sm-12">
									<div align="center"><h2>Dabble Solution</h2></div>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div align="center">
								<h3><?php echo($title);?></h3>
							</div>
							<div align="center">
								<h4><?php echo($subtitle);?></h4>
							</div>
							<div align="center">
								<div class="col-sm-6">
									<div class="row letters rectangleLettersPuzzle"> <h3>Letters</h3>
										<table class="puzzle">
																		<?php
											// Prints a grid with 5 square width with puzzle letters
											foreach($letterList as $row){
												echo'<tr>';
												foreach($row as $letter){
													if($letter != "0"){
														echo'<td class="filled">'.$letter.'</td>
														';
													}
													else{
														echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
														';
													}
												}
												echo'</tr>';
											}
										?>
										</table>
									</div>
										<div class="letters stepupLettersPuzzle" style="display: none;">
											<div class="row"> <h3>Step Up Letters</h3></div>
											<div class="row">
												<table class="puzzle">
													<?php
														// Prints blank step up puzzle
														foreach($stepUpLetterPuzzle as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>';
																}
																else{
																	echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																	';
																}
															}
															echo'</tr>';
														}
													?>
												</table>
											</div>
										</div>
										<div class="letters stepdownLettersPuzzle" style="display: none;">
											<div class="row"> <h3>Step Down Letters</h3> </div>
											<div class="row">
												<table class="puzzle">
													<?php
														// Prints blank step down puzzle

														foreach($stepDownLetterPuzzle as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>
																	';
																}
																else{
																	echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																	';
																}
															}
															echo'</tr>';
														}
													?>
												</table>
											</div>
										</div>
										<div class="letters pyramidLettersPuzzle" style="display: none;">
											<div class="row"> <h3>Pyramid Letters</h3> </div>
											<div class="pyramid">
												<?php
													// Prints blank pyramid puzzle
													// Cells must be printed with correct styling
													// Top cell, then final top right cell, left cells, inside cells, right cells,
													// bottom cells, then final right cell
													$wordCount = count($wordList);
													$count = 0;

													for($i = 0; $i < $wordCount; $i++){
														$word = $wordList[$i];
														$charList = splitWord($word);
														$length = getWordLength($word);

														echo'<div class="row">';

														for($j = 0; $j < $length; $j++){
															if($i == 0){
																if($j < $length - 1){
																	echo'<div class="top">'.$characterList[$count++].'</div>';
																}
																else{
																	echo'<div class="topRight">'.$characterList[$count++].'</div>';
																}
															}
															else if($i < $wordCount - 1){
																if($j == 0){
																	echo'<div class="left">'.$characterList[$count++].'</div>';
																}
																else if($j < ($length - 1)){
																	echo'<div class="inside">'.$characterList[$count++].'</div>';

																}
																else{
																	echo'<div class="right">'.$characterList[$count++].'</div>';

																}
															}
															else{
																if($j < ($length - 1)){
																	echo'<div class="bottom">'.$characterList[$count++].'</div>';

																}
																else{
																	echo'<div class="bottomRight">'.$characterList[$count++].'</div>';

																}
															}
														}
														echo'</div>';
													}
												?>
											</div>
										</div>
								</div>
								<div class="col-sm-6">
									<div class="stepupSolution word">
										<div class="row"> <h3>Step Up</h3> </div>
										<div class="row">
											<table class="puzzle">
												<?php
													// Prints solution for step up
													foreach($stepUpPuzzle as $row){
														echo'<tr>';
														foreach($row as $letter){
															if($letter != "0"){
																echo'<td class="filled">'.$letter.'</td>
																';
															}
															else{
																echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																';
															}
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
									<div class="stepdownSolution word">
										<div class="row"> <h3> Step Down </h3> </div>
										<div class="row">
											<table class="puzzle">
												<?php
													// Prints solution for step down
													foreach($stepDownPuzzle as $row){
														echo'<tr>';
														foreach($row as $letter){
															if($letter != "0"){
																echo'<td class="filled">'.$letter.'</td>
																';
															}
															else{
																echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																';
															}
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
									<div class="pyramidSolution word">
										<div class="row"> <h3> Pyramid </h3> </div>
										<div class="pyramid">
											<?php
												// Prints solution pyramid
												// Cells must be printed with correct styling
												// Top cell, then final top right cell, left cells, inside cells, right cells,
												// bottom cells, then final right cell
												$wordCount = count($wordList);

												for($i = 0; $i < $wordCount; $i++){
													$word = $wordList[$i];
													$charList = splitWord($word);
													$length = getWordLength($word);

													echo'<div class="row">';

													for($j = 0; $j < $length; $j++){
														if($i == 0){
															if($j < $length - 1){
																echo'<div class="top">'.$charList[$j].'</div>';
															}
															else{
																echo'<div class="topRight">'.$charList[$j].'</div>';
															}
														}
														else if($i < $wordCount - 1){
															if($j == 0){
																echo'<div class="left">'.$charList[$j].'</div>';
															}
															else if($j < ($length - 1)){
																echo'<div class="inside">'.$charList[$j].'</div>';
															}
															else{
																echo'<div class="right">'.$charList[$j].'</div>';
															}
														}
														else{
															if($j < ($length - 1)){
																echo'<div class="bottom">'.$charList[$j].'</div>';
															}
															else{
																echo'<div class="bottomRight">'.$charList[$j].'</div>';
															}
														}

													}

													echo'</div>';
												}
											?>
										</div>
									</div>
                                </div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
	// Set default spectrum elements
	$(".letterSquareColor").spectrum({
		color: "#EEEEEE",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("background-color", color.toHexString());
            $(".word .pyramid .inside").css("background-color", color.toHexString());
			$(".word .pyramid .left").css("background-color", color.toHexString());
			$(".word .pyramid .right").css("background-color", color.toHexString());
			$(".word .pyramid .top").css("background-color", color.toHexString());
			$(".word .pyramid .topRight").css("background-color", color.toHexString());
			$(".word .pyramid .bottom").css("background-color", color.toHexString());
			$(".word .pyramid .bottomRight").css("background-color", color.toHexString());
		}
	});

	$(".letterColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .pyramid .cell").css("color", color.toHexString());

			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .pyramid .inside").css("color", color.toHexString());
			$(".word .pyramid .left").css("color", color.toHexString());
			$(".word .pyramid .right").css("color", color.toHexString());
			$(".word .pyramid .top").css("color", color.toHexString());
			$(".word .pyramid .topRight").css("color", color.toHexString());
			$(".word .pyramid .bottom").css("color", color.toHexString());
			$(".word .pyramid .bottomRight").css("color", color.toHexString());
		}
	});

	$(".lineColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
            $(".word .pyramid .cell").css("border", "2px solid " + color.toHexString());

			$(".word table.puzzle tr td.filled").css("border-color", color.toHexString());
            $(".word .pyramid .inside").css("border-color", color.toHexString());
			$(".word .pyramid .left").css("border-color", color.toHexString());
			$(".word .pyramid .right").css("border-color", color.toHexString());
			$(".word .pyramid .top").css("border-color", color.toHexString());
			$(".word .pyramid .topRight").css("border-color", color.toHexString());
			$(".word .pyramid .bottom").css("border-color", color.toHexString());
			$(".word .pyramid .bottomRight").css("border-color", color.toHexString());
		}
	});

	$(".letterSquareColorLetters").spectrum({
		color: "#EEEEEE",
		change: function(color) {
			$(".letters table.puzzle tr td.filled").css("background-color", color.toHexString());
						$(".letters .pyramid .inside").css("background-color", color.toHexString());
			$(".letters .pyramid .left").css("background-color", color.toHexString());
			$(".letters .pyramid .right").css("background-color", color.toHexString());
			$(".letters .pyramid .top").css("background-color", color.toHexString());
			$(".letters .pyramid .topRight").css("background-color", color.toHexString());
			$(".letters .pyramid .bottom").css("background-color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("background-color", color.toHexString());
		}
	});

	$(".letterColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .pyramid .cell").css("color", color.toHexString());

			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .pyramid .inside").css("color", color.toHexString());
			$(".letters .pyramid .left").css("color", color.toHexString());
			$(".letters .pyramid .right").css("color", color.toHexString());
			$(".letters .pyramid .top").css("color", color.toHexString());
			$(".letters .pyramid .topRight").css("color", color.toHexString());
			$(".letters .pyramid .bottom").css("color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("color", color.toHexString());
		}
	});

	$(".lineColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".letters table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
						$(".letters .pyramid .cell").css("border", "2px solid " + color.toHexString());

			$(".letters table.puzzle tr td.filled").css("border-color", color.toHexString());
						$(".letters .pyramid .inside").css("border-color", color.toHexString());
			$(".letters .pyramid .left").css("border-color", color.toHexString());
			$(".letters .pyramid .right").css("border-color", color.toHexString());
			$(".letters .pyramid .top").css("border-color", color.toHexString());
			$(".letters .pyramid .topRight").css("border-color", color.toHexString());
			$(".letters .pyramid .bottom").css("border-color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("border-color", color.toHexString());
		}
	});

	<?php
		// Hide/Show starting puzzles/solutions based off input from Index page
		if($puzzleType == "stepup"){
			echo('$(".pyramidPuzzle").hide();');
			echo('$(".stepupPuzzle").show();');
			echo('$(".stepdownPuzzle").hide();');

			echo('$(".pyramidSolution").hide();');
			echo('$(".stepupSolution").show();');
			echo('$(".stepdownSolution").hide();');
		}
		else if($puzzleType == "stepdown"){
			echo('$(".pyramidPuzzle").hide();');
			echo('$(".stepupPuzzle").hide();');
			echo('$(".stepdownPuzzle").show();');

			echo('$(".pyramidSolution").hide();');
			echo('$(".stepupSolution").hide();');
			echo('$(".stepdownSolution").show();');
		}
		else{
			echo('$(".pyramidPuzzle").show();');
			echo('$(".stepupPuzzle").hide();');
			echo('$(".stepdownPuzzle").hide();');

			echo('$(".pyramidSolution").show();');
			echo('$(".stepupSolution").hide();');
			echo('$(".stepdownSolution").hide();');
		}
	?>

	// Updates the solution section to hidden/visable on check box update
	function solutionCheckboxChange(){
		if($('.showSolutionCheckbox').is(":checked")){
			$(".solutionSection").show();
		}
		else{
			$(".solutionSection").hide();
		}
	}

	// Shows/hides puzzles and solutions when puzzle type is changed
	function puzzleChange(){
		if($('#puzzletype').val() == "pyramid"){
			$(".pyramidPuzzle").show();
			$(".stepupPuzzle").hide();
			$(".stepdownPuzzle").hide();

			$(".pyramidSolution").show();
			$(".stepupSolution").hide();
			$(".stepdownSolution").hide();
		}
		else if($('#puzzletype').val() == "stepup"){
			$(".pyramidPuzzle").hide();
			$(".stepupPuzzle").show();
			$(".stepdownPuzzle").hide();

			$(".pyramidSolution").hide();
			$(".stepupSolution").show();
			$(".stepdownSolution").hide();
		}
		else{
			$(".pyramidPuzzle").hide();
			$(".stepupPuzzle").hide();
			$(".stepdownPuzzle").show();

			$(".pyramidSolution").hide();
			$(".stepupSolution").hide();
			$(".stepdownSolution").show();
		}
	}

	// 	Shows/hides letters when puzzle type is changed
	function lettersChange(){
			if($('#puzzlelettertype').val() == "rectangle"){
				$(".rectangleLettersPuzzle").show();
				$(".pyramidLettersPuzzle").hide();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").hide();

			}
			else if($('#puzzlelettertype').val() == "pyramid"){
				$(".rectangleLettersPuzzle").hide();
				$(".pyramidLettersPuzzle").show();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").hide();

			}
			else if($('#puzzlelettertype').val() == "stepup"){
				$(".rectangleLettersPuzzle").hide();
				$(".pyramidLettersPuzzle").hide();
				$(".stepupLettersPuzzle").show();
				$(".stepdownLettersPuzzle").hide();

			}
			else{
				$(".rectangleLettersPuzzle").hide();
				$(".pyramidLettersPuzzle").hide();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").show();

			}
		}
</script>
</html>
