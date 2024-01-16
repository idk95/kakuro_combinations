<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kakuro Combinations</title>
</head>
<body>
<h1>Kakuro Combinations Finder</h1>

<form method="post" action="">
    <label for="sum">Enter the sum:</label>
    <input type="number" id="sum" name="sum" required min="1">

    <label for="numDigits">Enter the number of digits (1-5):</label>
    <input type="number" id="numDigits" name="numDigits" required min="1" max="5">

    <label for="fixedPositions">Enter positions of fixed digits (comma-separated):</label>
    <input type="text" id="fixedPositions" name="fixedPositions" placeholder="e.g., 1,3,5">

    <label for="fixedValues">Enter values of fixed digits (comma-separated):</label>
    <input type="text" id="fixedValues" name="fixedValues" placeholder="e.g., 3,1,2">

    <button type="submit">Find Combinations</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission
    $sum = intval($_POST['sum']);
    $numDigits = intval($_POST['numDigits']);
    $fixedPositions = isset($_POST['fixedPositions']) ? explode(',', $_POST['fixedPositions']) : [];
    $fixedValues = isset($_POST['fixedValues']) ? explode(',', $_POST['fixedValues']) : [];

    // Validate the input
    if (count($fixedPositions) !== count($fixedValues)) {
        echo "<p>Number of fixed positions must match the number of fixed values.</p>";
    } else {
        // Call the function to find combinations
        $result = findCombinations($sum, $numDigits, $fixedPositions, $fixedValues);

        echo "<h2>Combinations for sum $sum in $numDigits digits:</h2>";
        if (empty($result)) {
            echo "<p>No combinations found.</p>";
        } else {
            foreach ($result as $combination) {
                echo implode(', ', $combination) . "<br>";
            }
        }
    }
}

/**
 * @param $sum
 * @param $numDigits
 * @param $fixedPositions
 * @param $fixedValues
 * @return array
 */
function findCombinations($sum, $numDigits, $fixedPositions, $fixedValues): array
{
    $combinations = [];
    $maxDigit = 9;

    $maxRange = pow($maxDigit + 1, $numDigits);

    for ($i = 0; $i < $maxRange; $i++) {
        $current = $i;
        $currentCombination = [];
        $validCombination = true;

        for ($j = $numDigits - 1; $j >= 0; $j--) {
            $digit = $current % 10;

            // Check if the current position is fixed
            if (in_array($j + 1, $fixedPositions)) {
                // If fixed, check if the value matches
                if ($digit !== intval($fixedValues[array_search($j + 1, $fixedPositions)])) {
                    $validCombination = false;
                    break;
                }
            }

            array_unshift($currentCombination, $digit);
            $current = intdiv($current, 10);
        }

        if ($validCombination) {

            // Check if the sum matches and the number of duplicates is within the limit
            if (array_sum($currentCombination) === $sum && countDuplicates($currentCombination) <= 2) {
                $combinations[] = $currentCombination;
            }
        }
    }

    return $combinations;
}

/**
 * @param $array
 * @return int
 */
function countDuplicates($array): int
{
    $count = array_count_values($array);
    $duplicates = 0;

    foreach ($count as $value) {
        if ($value > 1) {
            $duplicates += $value - 1;
        }
    }

    return $duplicates;
}

?>
</body>
</html>
