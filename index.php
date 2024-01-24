<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Kakuro Combinations</title>
</head>
<body>
<h1>Kakuro Combinations Finder</h1>

<form method="post" action="">
    <div class="number-row"></div>

    <div>
        <label for="sum">Enter the sum:</label>
        <input type="number" id="sum" name="sum" required min="1">
    </div>

    <div>
        <label for="numDigits">Enter the number of digits (1-5):</label>
        <input type="number" id="numDigits" name="numDigits" required min="1" max="5" oninput="toggleSumInputs()">
    </div>

    <div class="otherSums hidden">
        <label for="sumRight">Enter the sum 3 right:</label>
        <input type="number" id="sumRight" name="sumRight" min="1">
    </div>

    <div class="otherSums hidden">
        <label for="sumLeft">Enter the sum 3 left:</label>
        <input type="number" id="sumLeft" name="sumLeft" min="1">
    </div>

    <div class="otherSums hidden">
        <label for="sumCenter">Enter the sum 3 center:</label>
        <input type="number" id="sumCenter" name="sumCenter" min="1">
    </div>

    <div>
        <label for="fixed">Enter fixed digits:</label>
        <input type="number" id="fixed" name="fixed[]" min="0" max="9">
        <input type="number" id="fixed" name="fixed[]" min="0" max="9">
        <input type="number" id="fixed" name="fixed[]" min="0" max="9">
        <input type="number" id="fixed" name="fixed[]" min="0" max="9">
        <input type="number" id="fixed" name="fixed[]" min="0" max="9">
    </div>

    <div>
        <label for="noPair">Check if no pairs:</label>
        <input type="checkbox" id="noPair" name="noPair">
    </div>

    <button type="submit">Find Combinations</button>
</form>

<div class="result hidden"></div>
</body>
</html>
